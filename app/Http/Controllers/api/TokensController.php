<?php

namespace App\Http\Controllers\api;

use Throwable;
use App\Http\Controllers\Controller;
use App\Models\Token;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class TokensController extends Controller
{
    public static function verifyTokenIsValid(Request $request)
    {
        $decoded = self::decryptToken($request);

        if (!$decoded || (isset($decoded->original) && !$decoded->original['success'])) {
            return response()->json([
                'error' => 'Token de autenticação inválido ou expirado.',
                'status' => false
            ], 401);
        }

        return response()->json(["user" => $decoded, "message" => "Usuário está logado",  "success" => true]);
    }

    public static function createToken($data)
    {
        $payload = [
            "exp" => time() + 1800,
            "iat" => time(),
            "data" => $data
        ];

        $token = JWT::encode($payload, env("LOGIN_KEY_JWT"), env("LOGIN_KEY_METHOD"));

        $token_registered = new Token();
        $token_registered->token = $token;
        $token_registered->save();

        return $token;
    }

    public static function renoveToken(Request $request)
    {
        $token = $request->header('Authorization');

        $token = str_replace("Bearer ", "", $token);

        $key = new Key(env("LOGIN_KEY_JWT"), env("LOGIN_KEY_METHOD"));

        try {
            $data = self::decryptToken($request);

            $payload = [
                "exp" => time() + 1800,
                "iat" => time(),
                "data" => $data,
            ];

            $new_token = JWT::encode($payload, env("LOGIN_KEY_JWT"), env("LOGIN_KEY_METHOD"));

            $old_token = Token::Where("token", $token)->first();

            if ($old_token) {
                $old_token->token = $new_token;
                $old_token->save();
            } else {
                http_response_code(404);
                echo response()->json(["error" => "Token não encontrado", "success" => false]);
                exit;
            }

            echo $token;
        } catch (Throwable $e) {
            http_response_code(401);
            echo response()->json(["error" => "Token expirado, favor realizar login novamente", "success" => false]);
            exit;
        }
    }

    public static function deleteToken(Request $request)
    {
        $token = $request->header('Authorization');

        $token = str_replace("Bearer ", "", $token);

        $token = Token::where("token", $token)->first();
        if ($token) {
            $token->delete();
        } else {
            http_response_code(404);
            echo response()->json(["error" => "Token não encontrado", "success" => false]);
            exit;
        }
    }

    public static function decryptToken(Request $request)
    {

        $token = $request->header('Authorization');

        if (!$token) {
            return null;
        }

        $token = str_replace("Bearer ", "", $token);

        $key = new Key(env("LOGIN_KEY_JWT"), env("LOGIN_KEY_METHOD"));

        try {
            $decoded = JWT::decode($token, $key);
            return $decoded->data;
        } catch (Throwable $e) {
            return null;
        }
    }
}
