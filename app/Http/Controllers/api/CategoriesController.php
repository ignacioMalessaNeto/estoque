<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoriesController extends Controller
{
    public function index()
    {
        $categories = Category::all()->toArray();

        if (!$categories || count($categories) <= 0) {
            return response()->json([
                "success" => false,
                "error" => "Nenhuma categoria encontrada ou erro ao buscar."
            ]);
        }

        return response()->json([
            "success" => true,
            "categories" => $categories
        ]);
    }

    public function show(Request $request)
    {
        $id_category = $request->id;


        $category = Category::find($id_category);

        if (!$category) {
            return response()->json([
                "success" => false,
                "error" => "Nenhuma categoria encontrada ou erro ao buscar."
            ]);
        }

        return response()->json([
            "success" => true,
            "category" => $category
        ]);
    }

    public function create(Request $request)
    {
        $validateRules = [
            "name" => "required|unique:category|min:1|max:100"
        ];

        $messageValidateCategory = [
            "name.required" => "O nome é obrigatório",
            "name.unique" => "Esta categoria já está cadastrado",
            "name.min" => "A categoria precisa ter no mínimo :min caracteres.",
            "name.max" => "A categoria precisa ter no máximo :max caracteres.",
        ];

        $validatedCategory = Validator::make($request->all(), $validateRules, $messageValidateCategory);

        if ($validatedCategory->fails()) {
            return response()->json([
                "success" => false,
                "error" => $validatedCategory->errors()->first()
            ], 401);
        }

        $category = new Category();

        $category->name = $request->name;
        $category->save();

        return response()->json([
            "success" => true,
            "message" => "Sucesso ao criar categoria."
        ]);
    }

    public function update(Request $request)
    {
        $id_category = $request->id;

        $validateRules = [
            "name" => "required|unique:category|min:1|max:100"
        ];

        $messageValidateCategory = [
            "name.required" => "O nome é obrigatório",
            "name.unique" => "Esta categoria já está cadastrado",
            "name.min" => "A categoria precisa ter no mínimo :min caracteres.",
            "name.max" => "A categoria precisa ter no máximo :max caracteres.",
        ];

        $validatedCategory = Validator::make($request->all(), $validateRules, $messageValidateCategory);

        if ($validatedCategory->fails()) {
            return response()->json([
                "success" => false,
                "error" => $validatedCategory->errors()->first()
            ], 401);
        }

        $category = Category::find($id_category);

        if (!$category) {
            return response()->json([
                "success" => false,
                "error" => "Erro ao encontrar categoria"
            ]);
        }

        $category->name = $request->name ?? $category->name;
        $category->save();

        return response()->json([
            "success" => true,
            "message" => "Sucesso ao atualizar categoria."
        ]);
    }

    public function destroy(Request $request)
    {
        $id_category = $request->id;

        $category = Category::find($id_category);

        if (!$category) {
            return response()->json([
                "success" => false,
                "error" => "Categoria não encontrado ou não cadastrado."
            ]);
        }
        $category->delete();

        return response()->json([
            "success" => true,
            "message" => "Categoria deletada com sucesso!"
        ]);
    }
}
