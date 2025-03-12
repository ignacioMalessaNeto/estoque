<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ItensController extends Controller
{
  public function index()
  {
    $itens = Item::all()->toArray();

    if (count($itens) <= 0) {
      return response()->json([
        "success" => false,
        "error" => "Nenhum item cadastrado ou encontrado."
      ]);
    }

    return response()->json([
      "itens" => $itens
    ]);
  }

  public function show(Request $request)
  {
    $id_item = $request->id;

    $item = Item::find($id_item)->toArray();

    if (!$item || count($item) <= 0) {
      return response()->json([
        "success" => false,
        "error" => "Erro ao buscar item ou item não encontrado."
      ]);
    }

    return response()->json([
      "success" => true,
      "message" => $item
    ]);
  }
  public function create(Request $request)
  {

    $validateRules = [
      "name" => "required|unique:itens|min:3|max:250"
    ];

    $messageValidateItens = [
      "name.required" => "O nome é requirido",
      "name.unique" => "Este item já está cadastrado",
      "name.min" => "O nome precisa ter no mínimo :min caracteres.",
      "name.max" => "O nome precisa ter no máximo :max caracteres.",
    ];

    $validateItens = Validator::make($request->all(), $validateRules, $messageValidateItens);

    if ($validateItens->fails()) {
      return response()->json([
        "success" => false,
        'error' => $validateItens->errors()->first(),
      ]);
    }

    $item = new Item();
    $item->name = $request->name;
    $item->save();

    return response()->json([
      "success" => true,
      "message" => "Item criado com sucesso!"
    ]);
  }

  public function update(Request $request)
  {

    $id_item = $request->id;

    $validateRules = [
      "name" => "required|unique:itens|min:3|max:250"
    ];

    $messageValidateItens = [
      "name.required" => "O nome é requirido",
      "name.unique" => "Este item já está cadastrado",
      "name.min" => "O nome precisa ter no mínimo :min caracteres.",
      "name.max" => "O nome precisa ter no máximo :max caracteres.",
    ];

    $validateItens = Validator::make($request->all(), $validateRules, $messageValidateItens);

    if ($validateItens->fails()) {
      return response()->json([
        "success" => false,
        'error' => $validateItens->errors()->first(),
      ]);
    }


    $item = Item::find($id_item);
    if (!$item) {
      return response()->json([
        "success" => false,
        'error' => "Erro ao encotrar item.",
      ]);
    }
    $item->name = $request->name ?? $item->name;
    $item->save();

    return response()->json([
      "success" => true,
      "message" => "Item atualizado com sucesso!"
    ]);
  }

  public function destroy(Request $request)
  {
    $id_item = $request->id;

    $item = Item::find($id_item);

    if (!$item) {
      return response()->json([
        "success" => false,
        "error" => "Endereço não encontrado ou não cadastrado."
      ]);
    }
    $item->delete();

    return response()->json([
      "success" => true,
      "message" => "Item deletado com sucesso!"
    ]);
  }
}
