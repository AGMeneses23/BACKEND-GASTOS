<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CategoriasController extends Controller
{

    /* MÉTODOS REST */

    public function indexApi()
    {
        $categoriasApi = Categoria::where('user_id', Auth::id())->get();
        $usuario = Auth::user()->name;
        Log::channel('activity')->info('Usuario ' . $usuario . ' listó todas las categorías usando el método GET.');
        return response()->json(['message' => 'Listado de categorías obtenido exitosamente', 'data' => $categoriasApi], 200);
    }

    // TRAE SOLO UNA CATEGORÍA EN ESPECÍFICO

    public function catApi($id)
    {
        $categoriaUna = Categoria::where('user_id', Auth::id())->find($id);
        $usuario = Auth::user()->name;
        Log::channel('activity')->info('Usuario ' . $usuario . ' visualizó la categoría con ID ' . $id . ' usando el método GET.');
        return $categoriaUna;
    }

    public function createApi(Request $request)
    {
        $categoriaCreateApi = new Categoria();
        $categoriaCreateApi->nombre = $request->input('nombre');
        $categoriaCreateApi->user_id = Auth::id();
        $categoriaCreateApi->save();
        $usuario = Auth::user()->name;
        Log::channel('activity')->info('Usuario ' . $usuario . ' creó una nueva categoría con el nombre "' . $request->input('nombre') . '" usando el método POST.');
        return response()->json(['message' => 'Categoría creada exitosamente', 'categoría:' => $categoriaCreateApi], 201);
    }

    public function editApi(Request $request, $id)
    {
        $editCategoriaApi = Categoria::where('user_id', Auth::id())->find($id);

        if (!$editCategoriaApi) {
            return response()->json(['message' => 'Categoría no encontrada'], 404);
        }

        $editCategoriaApi->nombre = $request->input('nombre');
        $editCategoriaApi->save();

        $usuario = Auth::user()->name;
        Log::channel('activity')->info('Usuario ' . $usuario . ' actualizó la categoría con ID ' . $id . ' usando el método PUT.');

        return response()->json(['message' => 'Categoría actualizada correctamente', 'categoría: ' => $editCategoriaApi], 200);
    }

    public function eliminarApi($id)
    {
        $categoriaDelete = Categoria::where('user_id', Auth::id())->find($id);

        if (!$categoriaDelete) {
            return response()->json(['message' => 'Categoría no encontrada'], 404);
        }

        $categoriaDelete->delete();

        $usuario = Auth::user()->name;
        Log::channel('activity')->info('Usuario ' . $usuario . ' eliminó la categoría con ID ' . $id . ' usando el método DELETE.');

        return response()->json(['message' => 'Categoría eliminada exitosamente'], 200);
    }

    /* MÉTODOS WEB DE CRUD */

    public function create()
    {
        $categoria = new Categoria();
        return view('categorias.create', compact('categoria'));
    }

    public function edit($id)
    {
        $categoria = Categoria::find($id);
        return view('categorias.create', compact('categoria'));
    }

    public function index()
    {
        $categorias = Categoria::all();
        return view('categorias.index', compact('categorias'));
    }

    public function store(Request $req)
    {

        if ($req->id != 0) {
            $categoria = Categoria::find($req->id);
        } else {
            $categoria = new Categoria();
        }

        $categoria->nombre = $req->nombre;

        $categoria->save();

        return redirect()->route('categoria.lista');
    }

    public function delete($id)
    {
        $categoria = Categoria::find($id);
        $categoria->delete();
        return redirect()->route('categoria.lista');
    }
}
