<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Moviment;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StockController extends Controller
{

    public function index()
    {
        $stocks = Stock::with(['address','category', 'create', 'item'])->get();

        if ($stocks->isEmpty()) {
            return response()->json([
                "success" => false,
                "error" => "Não foi encontrado nada no estoque ou ocorreu um erro."
            ]);
        }

        return response()->json([
            "success" => true,
            "stocks" => $stocks
        ]);
    }

    public function show(Request $request)
    {
        $id_stock = $request->id;

        $stock = Stock::with(['address','category', 'create', 'item' ])->find($id_stock);

        if (!$stock) {
            return response()->json([
                "success" => false,
                "error" => "Erro ao buscar dados no estoque ou nenhum dado encontrado."
            ]);
        }

        return response()->json([
            "success" => true,
            "stock" => $stock
        ]);
    }

    public function create(Request $request)
    {
        $validateRules = [
            "quantity" => "required|min:1|max:100",
            "id_item" => "required|exists:itens,id|min:1",
            "id_category" => "required|exists:category,id|min:1",
            "id_address" => "required|exists:address,id|min:1",
        ];

        $messageValidateStock = [
            "quantity.required" => "A quantidade é obrigatório",
            "quantity.min" => "A categoria precisa ter no mínimo :min caracteres.",
            "quantity.max" => "A categoria precisa ter no máximo :max caracteres.",

            "id_item.required" => "É preciso ter um item para cadastrar no estoque",
            "id_item.min" => "Não existe este item cadastrado.",
            "id_item.exists" => "O item informado não existe.",

            "id_category.required" => "É preciso ter uma categoria para cadastrar no estoque",
            "id_category.min" => "Não existe está categoria cadastrado.",
            "id_category.exists" => "A categoria informado não existe.",

            "id_address.required" => "É preciso ter um endereço para cadastrar no estoque",
            "id_address.min" => "Não existe este endereço cadastrado.",
            "id_address.exists" => "O endereço informado não existe.",
        ];

        $validatedStock = Validator::make($request->all(), $validateRules, $messageValidateStock);

        if ($validatedStock->fails()) {
            return response()->json([
                "success" => false,
                "error" => $validatedStock->errors()->first()
            ], 401);
        }

        $stock = new Stock();

        $id_user = TokensController::decryptToken($request);

        if (!$id_user) {
            return response()->json([
                "success" => false,
                "error" => "É preciso fazer login para fazer está ação"
            ], 401);
        }

        $stock->quantity = $request->quantity;
        $stock->id_item = $request->id_item;
        $stock->id_category = $request->id_category;
        $stock->id_address = $request->id_address;
        $stock->create_by = $id_user->id;
        $stock->save();

        $moviment = new Moviment();
        $moviment->quantity = $request->quantity;
        $moviment->type_moviment = "Entrada";
        $moviment->id_entrie = $stock->id;
        $moviment->save();

        if (!$moviment) {
            return response()->json([
                "success" => false,
                "message" => "Ocorreu algum problema."
            ]);
        }

        return response()->json([
            "success" => true,
            "message" => "Sucesso ao criar cadastro no estoque."
        ]);
    }

    public function update(Request $request)
    {

        $id_stock = $request->id;

        $validateRules = [
            "quantity" => "min:1|max:100",
            "id_item" => "exists:itens,id|min:1",
            "id_category" => "exists:category,id|min:1",
            "id_address" => "exists:address,id|min:1",
        ];

        $messageValidateStock = [
            "quantity.min" => "A categoria precisa ter no mínimo :min caracteres.",
            "quantity.max" => "A categoria precisa ter no máximo :max caracteres.",

            "id_item.min" => "Não existe este item cadastrado.",
            "id_item.exists" => "O item informado não existe.",

            "id_category.min" => "Não existe está categoria cadastrado.",
            "id_category.exists" => "A categoria informado não existe.",

            "id_address.min" => "Não existe este endereço cadastrado.",
            "id_address.exists" => "O endereço informado não existe.",
        ];

        $validatedStock = Validator::make($request->all(), $validateRules, $messageValidateStock);

        if ($validatedStock->fails()) {
            return response()->json([
                "success" => false,
                "error" => $validatedStock->errors()->first()
            ], 401);
        }

        $stock = Stock::with(['item', 'category', 'address', 'user'])->find($id_stock);

        if (!$stock) {
            return response()->json([
                "success" => false,
                "error" => "Erro ao buscar cadastro ou cadastro não encontrado."
            ]);
        }

        if (!$request->hasAny(['quantity', 'id_item', 'id_category', 'id_address'])) {
            return response()->json([
                "success" => false,
                "error" => "Nenhuma alteração foi enviada."
            ]);
        }

        $id_user = TokensController::decryptToken($request);

        if (!$id_user) {
            return response()->json([
                "success" => false,
                "error" => "É preciso fazer login para fazer está ação"
            ], 401);
        }

        $stock->quantity = $request->quantity ?? $stock->quantity;
        $stock->id_item = $request->id_item ?? $stock->id_item;
        $stock->id_category = $request->id_category ?? $stock->id_category;
        $stock->id_address = $request->id_address ?? $stock->id_address;
        $stock->create_by = $id_user->id;

        $moviment = new Moviment();

        $moviment->quantity = $request->quantiy ?? $stock->quantity;
        $moviment->id_entrie = $request->id_stock ?? $stock->id_stock;
        $moviment->save();

        if (!$moviment) {
            return response()->json([
                "success" => false,
                "message" => "Ocorreu algum problema."
            ]);
        }

        return response()->json([
            "success" => true,
            "message" => "Cadastro do estoque alterado com sucesso."
        ]);
    }

    public function destroy(Request $request)
    {
        $id_stock = $request->id;

        $stock = Stock::find($id_stock);

        if (!$stock) {
            return response()->json([
                "success" => false,
                "error" => "Erro ao deletar ou não foi encontrado cadastrado."
            ]);
        }

        $stock->delete();

        return response()->json([
            "success" => true,
            "error" => "Sucesso ao deletar."
        ]);
    }

}
