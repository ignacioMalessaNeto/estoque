<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Moviment;
use App\Models\Out;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MovimentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $moviments = Moviment::with(['out', 'entrie', 'entrie.item', 'entrie.category', 'entrie.address', 'entrie.create', 'out.receiver', 'out.user', 'out.stock.category', 'out.stock.item', 'out.stock.address'])->get();

        if ($moviments->isEmpty()) {
            return response()->json([
                'success' => false,
                'error' => "Nenhum movimento encontrado."
            ]);
        }

        $movimentsFormatted = $moviments->map(function ($moviment) {
            $data = [
                'id' => $moviment->id,
                'quantity' => $moviment->quantity,
                'type_moviment' => $moviment->type_moviment,
                'created_at' => $moviment->created_at,
                'updated_at' => $moviment->updated_at,
            ];

            if ($moviment->type_moviment === "Entrada") {
                $data['stock'] = $moviment->entrie;
            } else {
                $data['stock'] = $moviment->out;
            }

            return $data;
        });

        return response()->json([
            'success' => true,
            'moviments' => $movimentsFormatted
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $validateRules = [
            "quantity" => "required|min:1|max:100",
            "type_moviment" => "string|required|min:1|max:7",
            "id_entrie" => "required|exists:stock,id|min:1",
            "id_out" => "exists:outs,id|min:1",
        ];

        $messageValidateStock = [
            "quantity.required" => "A quantidade é obrigatório",
            "quantity.min" => "A categoria precisa ter no mínimo :min caracteres.",
            "quantity.max" => "A categoria precisa ter no máximo :max caracteres.",

            "type_moviment.string" => "É preciso que o tipo seja uma string.",
            "type_moviment.required" => "É preciso ter um item para cadastrar no estoque",
            "type_moviment.min" => "O tipo precisa ter no mínimo min:.",
            "type_moviment.max" => "O tipo pode ter no máximo max:.",

            "id_entrie.min" => "Não existe cadastro no estoque.",
            "id_entrie.exists" => "O cadastro no estoque não existe.",

            "id_out.min" => "Não existe está saída cadastrado.",
            "id_out.exists" => "A saída informada não existe.",
        ];

        $validatedStock = Validator::make($request->all(), $validateRules, $messageValidateStock);

        if ($validatedStock->fails()) {
            return response()->json([
                "success" => false,
                "error" => $validatedStock->errors()->first()
            ], 401);
        }

        if ($request->type_moviment === "Entrada") {
            $stock = Stock::find($request->entrie);

            if (!$stock) {
                return response()->json([
                    "success" => false,
                    "error" => "Cadastro no estoque não encontrado."
                ]);
            }

            $moviment = new Moviment();

            $moviment->quantity = $request->quantity;
            $moviment->type_moviment = "Entrada";
            $moviment->id_entrie = $stock->id;
            $moviment->save();
        } else {
            $out = Stock::find($request->id_out);

            if (!$out) {
                return response()->json([
                    "success" => false,
                    "error" => "Saída no estoque não encontrada."
                ]);
            }

            $moviment = new Moviment();

            $moviment->quantity = $request->quantity;
            $moviment->type_moviment = $request->type_moviment;
            $moviment->id_out = $out->id;
            $moviment->updated = 0;
            $moviment->save();
        }

        return response()->json([
            "success" => true,
            "message" => "Sucesso ao cadastrar movimento."
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $id_moviment = $request->id;

        $moviment = Moviment::with(['out', 'entrie', 'entrie.item', 'entrie.category', 'entrie.address'])->find($id_moviment);

        if (!$moviment) {
            return response()->json([
                'success' => false,
                'error' => "Nenhum movimento encontrado."
            ]);
        }

        // Verifica se é entrada ou saída
        $data = [
            'id' => $moviment->id,
            'quantity' => $moviment->quantity,
            'type_moviment' => $moviment->type_moviment,
            'created_at' => $moviment->created_at,
            'updated_at' => $moviment->updated_at,
        ];

        if ($moviment->type_moviment === "Entrada") {
            $data['entrie'] = $moviment->entrie;
        } else {
            $data['out'] = $moviment->out;
        }

        return response()->json([
            'success' => true,
            'moviment' => $data
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $id_moviment = $request->id;

        $validateRules = [
            "quantity" => "required|min:1|max:100",
        ];

        $messageValidateStock = [
            "quantity.required" => "A quantidade é obrigatório",
            "quantity.min" => "A categoria precisa ter no mínimo :min caracteres.",
            "quantity.max" => "A categoria precisa ter no máximo :max caracteres.",
        ];

        $validatedStock = Validator::make($request->all(), $validateRules, $messageValidateStock);

        if ($validatedStock->fails()) {
            return response()->json([
                "success" => false,
                "error" => $validatedStock->errors()->first()
            ], 401);
        }

        $user = TokensController::decryptToken($request);
        $moviment = Moviment::find($id_moviment);

        if ($moviment->type_moviment === "Entrada") {

            // with([])->find($request->id_entrie);

            $stock = Stock::find($request->id_entrie);

            if (!$stock) {
                return response()->json([
                    "success" => false,
                    "error" => "Cadastro no estoque não encontrado."
                ]);
            }

            $moviment = Moviment::find($id_moviment);
            $moviment->quantity =  $request->quantity ?? $stock->quantity;
            $moviment->updated = 1;
            $moviment->save();

            if ($moviment->type_moviment === "Entrada" && $request->quantity) {
                $stock->quantity = $moviment->quantity;
                $stock->create_by = $user->id;
                $stock->save();
            }
        } else {
            $out = Out::with(['stock'])->find($request->id_out);

            if (!$out) {
                return response()->json([
                    "success" => false,
                    "error" => "Saída no estoque não encontrada."
                ]);
            }


            $moviment->quantity =  $request->quantity ?? $out->quantity;
            $moviment->updated = 1;
            $moviment->save();

            $stock = Stock::find($out->stock['id']);

            
            if ($request->quantity) {
                $quantityStockOld = $stock->quantity + $out->quantity;
                $totalQuantity = $quantityStockOld - $request->quantity;
                if ($totalQuantity < 0) {
                    return response()->json([
                        "success" => false,
                        "error" => "O produto cadastrado não possuí a quantidade a ser subtrída."
                    ], 401);
                }
                $stock->quantity = $totalQuantity;
                $stock->save();
                $out->quantity = $moviment->quantity;
                $out->id_user = $user->id;
                $out->save();
            }
        }

        return response()->json([
            "success" => true,
            "message" => "Sucesso ao atualziar movimento."
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id_moviment = $request->id;

        $moviment = Moviment::find($id_moviment);

        if (!$moviment) {
            return response()->json([
                'success' => false,
                'error' => "Nenhum movimento encontrado."
            ]);
        }

        $moviment->delete();

        return response()->json([
            'success' => true,
            'message' => "Sucesso ao deletar movimento"
        ]);
    }
}
