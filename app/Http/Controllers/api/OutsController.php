<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\api\TokensController;
use App\Models\Moviment;
use App\Models\Out;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OutsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $outs = Out::with(['user', 'receiver', 'stock', 'stock.item', 'stock.category', 'stock.address'])->get();

        if (!$outs) {
            return response()->json([
                "success" => false,
                "error" => "Erro ao buscar saídas ou nenhum saída encontrada"
            ]);
        }

        return response()->json([
            "success" => true,
            "outs" => $outs
        ]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $validateRules = [
            "quantity" => "required|min:1|max:100",
            "id_stock" => "required|exists:stocks,id|min:1",
            "id_sender" => "required|exists:users,id|min:1",
        ];

        $messageValidateStock = [
            "quantity.required" => "A quantidade é obrigatório",
            "quantity.min" => "A categoria precisa ter no mínimo :min caracteres.",
            "quantity.max" => "A categoria precisa ter no máximo :max caracteres.",

            "id_stock.required" => "É preciso o id do cadastro para criar uma saída.",
            "id_stock.min" => "Não existe este cadastro.",
            "id_stock.exists" => "O cadastro não existe.",

            "id_sender.required" => "É preciso o id do usuário que recebeu para criar uma saída.",
            "id_sender.min" => "Não existe este cadastro.",
            "id_sender.exists" => "O cadastro não existe.",
        ];

        $validatedStock = Validator::make($request->all(), $validateRules, $messageValidateStock);

        if ($validatedStock->fails()) {
            return response()->json([
                "success" => false,
                "error" => $validatedStock->errors()->first()
            ], 401);
        }

        $out = new Out();

        $user = TokensController::decryptToken($request);

        $stock = Stock::find($request->id_stock);

        if (!$stock) {
            return response()->json([
                "success" => false,
                "error" => "Erro ao encontrar cadastro no estoque."
            ], 401);
        }

        $totalQuantity = $stock->quantity - $request->quantity;

        if ($totalQuantity <= 0) {
            return response()->json([
                "success" => false,
                "error" => "O produto cadastrado não possuí a quantidade a ser subtrída."
            ], 401);
        }

        $stock->quantity = $totalQuantity;
        $stock->save();
        $out->quantity = $request->quantity;
        $out->id_stock = $request->id_stock;
        $out->recipient = $request->id_sender;
        $out->id_user = $user->id;
        $out->save();

        $moviment = new Moviment();

        $moviment->quantity = $request->quantity;
        $moviment->type_moviment = "Saída";
        $moviment->id_out = $out->id;
        $moviment->save();

        if (!$moviment) {
            return response()->json([
                "success" => false,
                "message" => "Ocorreu algum problema."
            ]);
        }

        return response()->json([
            "success" => true,
            "message" => "Saída cadastrada com sucesso."
        ]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {

        $id_out = $request->id;

        $validateRules = [
            "quantity" => "min:1|max:100",
            "id_stock" => "exists:stocks,id|min:1",
            "id_sender" => "exists:users,id|min:1",
        ];

        $messageValidateStock = [
            "quantity.required" => "A quantidade é obrigatório",
            "quantity.min" => "A categoria precisa ter no mínimo :min caracteres.",
            "quantity.max" => "A categoria precisa ter no máximo :max caracteres.",

            "id_stock.min" => "Não existe este cadastro.",
            "id_stock.exists" => "O cadastro não existe.",

            "id_sender.min" => "Não existe este cadastro.",
            "id_sender.exists" => "O cadastro não existe.",
        ];

        $validatedStock = Validator::make($request->all(), $validateRules, $messageValidateStock);

        if ($validatedStock->fails()) {
            return response()->json([
                "success" => false,
                "error" => $validatedStock->errors()->first()
            ], 401);
        }

        $id_user = TokensController::decryptToken($request);

        $stock = Stock::find($request->id_stock);

        $out =  Out::find($id_out);

        if (!$id_user) {
            return response()->json([
                "success" => false,
                "error" => "É preciso fazer login para fazer está ação"
            ], 401);
        }
        if (!$stock) {
            return response()->json([
                "success" => false,
                "error" => "Erro ao encontrar cadastro no estoque."
            ], 401);
        }

        if (!$out) {
            return response()->json([
                "success" => false,
                "error" => "Erro ao encontrar saída do estoque."
            ], 401);
        }

        if ($request->quantity) {
            $quantityStockOld = $stock->quantity + $out->quantity;
            $totalQuantity = $quantityStockOld - $request->quantity;
            $stock->quantity = $totalQuantity;
            $stock->save();
            if ($totalQuantity < 0) {
                return response()->json([
                    "success" => false,
                    "error" => "O produto cadastrado não possuí a quantidade a ser subtrída."
                ], 401);
            }
        }


        $out->quantity = $request->quantity ?? $out->quantity;
        $out->id_stock = $request->id_stock ?? $out->id_stock;
        $out->recipient = $request->recipient ?? $out->recipient;
        $out->id_user = $id_user->id;
        $out->save();

        $moviment = new Moviment();

        $moviment->quantity = $request->quantity;
        $moviment->id_out = $request->id_stock ?? $out->id_stock;
        $moviment->type_moviment = "Saída";
        $moviment->save();

        if (!$moviment) {
            return response()->json([
                "success" => false,
                "message" => "Ocorreu algum problema."
            ]);
        }

        return response()->json([
            "success" => true,
            "message" => "Saída atualizada com sucesso."
        ]);
    }
    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $id_out = $request->id;

        $out = Out::with(['user', 'receiver', 'stock', 'stock.item', 'stock.category', 'stock.address',])->find($id_out);

        if (!$out) {
            return response()->json([
                "success" => false,
                "error" => "Erro ao buscar saída ou nenhum saída encontrada."
            ]);
        }

        return response()->json([
            "success" => true,
            "out" => $out
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id_out = $request->id;

        $out = Out::find($id_out);

        if (!$out) {
            return response()->json([
                "success" => false,
                "error" => "Erro ao tentar deletar saída ou nenhum saída encontrada."
            ]);
        }

        $stock = Stock::find($out->id_stock);
        $quantityStockOld = $stock->quantity + $out->quantity;
        $stock->quantity = $quantityStockOld;
        $stock->save();


        $out->delete();

        return response()->json([
            "success" => true,
            "out" => "Sucesso ao deletar saída."
        ]);
    }
}
