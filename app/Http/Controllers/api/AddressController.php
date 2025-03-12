<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = Address::all()->toArray();

        if (count($addresses) <= 0 || !$addresses) {
            return response()->json([
                "success" => false,
                "error" => "Endereços não encontrados ou não cadastrados."
            ]);
        }

        return response()->json([
            "success" => true,
            "message" => $addresses
        ]);
    }

    public function show(Request $request)
    {
        $id_address = $request->id;

        $addres = Address::find($id_address)->toArray();

        if (count($addres) <= 0 || !$addres) {
            return response()->json([
                "success" => false,
                "error" => "Endereço não encontrado ou não cadastrado."
            ]);
        }

        return response()->json([
            "success" => true,
            "message" => $addres
        ]);
    }

    public function create(Request $request)
    {
        $validateRules = [
            "name" => "required|unique:address|min:1|max:100"
        ];

        $messageValidateItens  = [
            "name.required" => "O nome é requirido",
            "name.unique" => "Este endereco já está cadastrado",
            "name.min" => "O nome precisa ter no mínimo :min caracteres.",
            "name.max" => "O nome precisa ter no máximo :max caracteres.",
        ];

        $validatedItens = Validator::make($request->all(), $validateRules, $messageValidateItens);

        if ($validatedItens->fails()) {
            return response()->json([
                "success" => false,
                "error" => $validatedItens->errors()->first()
            ], 401);
        }

        $address = new Address();

        $address->name = $request->name;
        $address->save();

        return response()->json([
            "success" => true,
            "message" => "Sucesso ao criar endereço."
        ]);
    }

    public function update(Request $request)
    {
        $id_address = $request->id;

        $validateRules = [
            "name" => "required|unique:address|min:1|max:100"
        ];

        $messageValidateItens  = [
            "name.required" => "O nome é requirido",
            "name.unique" => "Este endereco já está cadastrado",
            "name.min" => "O nome precisa ter no mínimo :min caracteres.",
            "name.max" => "O nome precisa ter no máximo :max caracteres.",
        ];

        $validatedItens = Validator::make($request->all(), $validateRules, $messageValidateItens);

        if ($validatedItens->fails()) {
            return response()->json([
                "success" => false,
                "error" => $validatedItens->errors()->first()
            ], 401);
        }

        $address = Address::find($id_address);

        if (!$address) {
            return response()->json([
                "success" => false,
                'error' => "Endereço não encontrado.",
            ]);
        }

        $address->name = $request->name ?? $address->name;
        $address->save();

        return response()->json([
            "success" => true,
            "message" => "Sucesso ao criar endereço."
        ]);
    }

    public function destroy(Request $request)
    {
        $id_address = $request->id;

        $address = Address::find($id_address);

        if (!$address) {
            return response()->json([
                "success" => false,
                "error" => "Endereço não encontrado ou não cadastrado."
            ]);
        }

        $address->delete();

        return response()->json([
            "success" => true,
            "message" => "Endereço deletado com sucesso!"
        ]);
    }
}
