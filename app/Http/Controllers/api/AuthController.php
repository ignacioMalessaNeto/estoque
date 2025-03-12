<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\api\TokensController;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validation rules
        $validateRules = [
            'email' => 'required|email|min:5|max:50',
            'password' => 'required|string|min:6|max:260'
        ];

        // Message validations
        $messageValidate = [
            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'O e-mail precisa ser um e-mail válido.',
            'email.min' => 'O e-mail precisa ter no mínimo :min caracteres.',
            'email.max' => 'O e-mail precisa ter no máximo :max caracteres.',
            'password.required' => 'A senha é obrigatória.',
            'password.string' => 'A senha precisa ser um texto.',
            'password.min' => 'A senha precisa ter no mínimo :min caracteres.',
            'password.max' => 'A senha precisa ter no máximo :max caracteres.',
        ];

        // Data validation
        $validateUser = Validator::make($request->all(), $validateRules, $messageValidate);

        if ($validateUser->fails()) {
            return response()->json([
                'success' => false,
                'error' => $validateUser->errors()->first(),
            ], 401);
        }

        // Data valids
        $email = $request->email;
        $password = $request->password;

        $user = User::where('email', $email)->first();

        if (empty($user)) {
            return response()->json([
                'success' => false,
                'error' => "Erro ao efetuar login, senha ou e-mail incorretos!",
            ], 401);
        }

        if (!password_verify($password,  $user->password)) {
            return response()->json([
                'success' => false,
                'error' => "Erro ao efetuar login, senha ou e-mail incorretos!",
            ], 401);
        }
        
        // Create token after login
        $encode = TokensController::createToken($user);

        // Return message of success
        return response()->json([
            "token" => $encode,
            "message" => "Login realizado com sucesso.",
            "status" => true
        ], 200);
    }
    public function signUp(Request $request)
    {
        $validateRules = [
            "name" => "required|string|min:5|max:100",
            "email" => "required|email|unique:users|min:5|max:50",
            "password" => "required|string|min:6|max:260",
            "level_access" => "required|integer",
        ];  

        $messageValidateSignUp = [
            'name.required' => "O nome é obrigatório",
            'name.string' => 'O nome precisa ser um texto válido.',
            'name.min' => 'O nome precisa ter no mínimo :min caracteres.',
            'name.max' => 'O nome precisa ter no máximo :max caracteres.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.unique' => 'Já existe uma conta cadastrada com esse e-mail.',
            'email.email' => 'O e-mail precisa ser um e-mail válido.',
            'email.min' => 'O e-mail precisa ter no mínimo :min caracteres.',
            'email.max' => 'O e-mail precisa ter no máximo :max caracteres.',
            'password.required' => 'A senha é obrigatória.',
            'password.string' => 'A senha precisa ser um texto.',
            'password.min' => 'A senha precisa ter no mínimo :min caracteres.',
            'password.max' => 'A senha precisa ter no máximo :max caracteres.',
            'level_access.required' => 'O nível de acesso é requirido',
            'level_access.integer' => 'O nível de acesso precisar ser um número',
        ];

        // Creating validation with rules validation and messages validation
        $validateUserSignUp = Validator::make($request->all(), $validateRules, $messageValidateSignUp);

        // Validation if exists erro
        if($validateUserSignUp->fails()){
            return response()->json(
                [
                    "success" => false,
                    "error" => $validateUserSignUp->errors()->first(),
                ]
            );
        }

        // Creating new user and defining yours values from request
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->level_access = $request->level_access;
        $user->password = bcrypt($request->password);
        $user->save();

        // Create return of success
        return response()->json([
            "message" => "Sucesso ao criar usuário",
            "success" => true
        ]);
    }
    public function logout(Request $request) {
        TokensController::deleteToken($request);
    }
}
