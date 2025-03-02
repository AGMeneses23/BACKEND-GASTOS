<?php

namespace App\Http\Controllers;

use OpenApi\Annotations as OA;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CategoriasController extends Controller
{
    /**
     * @OA\Get(
     *     path="/categorias/lista",
     *     summary="Obtener lista de categorías",
     *     tags={"Categorias"},
     *     security={{"sanctum":{}}},  
     *     @OA\Response(
     *         response=200,
     *         description="Lista de categorías obtenida con éxito",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Listado de categorías obtenido exitosamente"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="nombre", type="string", example="Tecnología"),
     *                     @OA\Property(property="user_id", type="integer", example=5)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="No autorizado")
     *         )
     *     )
     * )
     */

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

    /**
     * @OA\Post(
     *     path="/categorias/guardar",
     *     summary="Crear una nueva categoría",
     *     tags={"Categorias"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre"},
     *             @OA\Property(property="nombre", type="string", example="Alimentos")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Categoría creada exitosamente"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Datos inválidos"
     *     )
     * )
     */
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


    /**
     * @OA\Put(
     *     path="/categorias/editar/{id}",
     *     summary="Actualizar una categoría",
     *     tags={"Categorias"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la categoría a actualizar",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre"},
     *             @OA\Property(property="nombre", type="string", example="Bebidas")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Categoría actualizada correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Categoría no encontrada"
     *     )
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/categorias/eliminar/{id}",
     *     summary="Eliminar una categoría",
     *     tags={"Categorias"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la categoría a eliminar",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Categoría eliminada exitosamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Categoría no encontrada"
     *     )
     * )
     */
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
