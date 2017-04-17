<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Yajra\Datatables\Facades\Datatables;

class CategoriaController extends Controller
{
    private $categoria;
    private $regPerPage = 3;

    public function __construct(Categoria $categoria)
    {
        $this->categoria = $categoria;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categorias = $this->categoria->all(); // retorna tudo sem paginação
        //dd($categorias);
        return Datatables::of($categorias)->make(true);
        return Datatables::collection($categorias)->make(true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->json()->all();
        
        $validate = validator($data, $this->categoria->rules());

        if ( $validate->fails() ) {
            $messages = $validate->messages();

            return response()->json(['validate_error' => $messages], 422);
        }
        if (!$insert = $this->categoria->create($data) ) {
            return response()->json(['error' => 'error_insert'], 500);
        }
            
        return response()->json(['categorias' => $insert], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)// Categoria $categoria
    {
        //model bind - esse comando abaixo para pegar os dados do produto por id 
        //pode ser substituido pela injecao no metodo show
        //$categoria = $this->categoria->findOrFail($id); 

        //a forma abaixo e mais correta pois data para validar e atribuir um mensagem de erro customizada

        if( !$categoria = $this->categoria->find($id)) {
            return response()->json(['error' => 'not_found'], 404);
        }

        return response()->json($categoria);
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->json()->all();

        $validate = validator($data, $this->categoria->rules($id));

        if ( $validate->fails() ) {
            $messages = $validate->messages();

            return response()->json(['validate_error' => $messages], 422);
        }

        if( !$categoria = $this->categoria->find($id)) {
            //dd("sadsadasd");
            return response()->json(['error' => 'categoria_not_found'], 404);
        }

        if ( !$update = $categoria->update($data) ) {
            return response()->json(['error' => 'categoria_not_update', 500]);
        }

        return response()->json(['response' => $update], 200);
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if( !$categoria = $this->categoria->find($id)) {
            return response()->json(['error' => 'categoria_not_found'], 404);
        }

        if ( !$delete = $categoria->delete() ) {
            return response()->json(['error' => 'categoria_not_delete', 500]);
        }

        return response()->json(['response' => $delete]);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listarTudo()
    {
        return response()->json(['categorias' => $this->categoria->all()], 200);
    }
}
