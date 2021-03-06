<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Divisao;
use DataTables;

class DivisaoController extends Controller
{
    private $divisao;
    private $regPerPage = 3;

    public function __construct(Divisao $divisao)
    {
        $this->divisao = $divisao;
    }

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $divisaos = $this->divisao->all(); // retorna tudo sem paginação
        //dd($divisaos);
        return DataTables::of($divisaos)->make(true);
        return DataTables::collection($divisaos)->make(true);
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
        
        $validate = validator($data, $this->divisao->rules());

        if ( $validate->fails() ) {
            $messages = $validate->messages();

            return response()->json(['validate_error' => $messages], 422);
        }
        if (!$insert = $this->divisao->create($data) ) {
            return response()->json(['error' => 'error_insert'], 500);
        }
            
        return response()->json(['divisaos' => $insert], 201);
    }

    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function show($id)// Divisao $divisao
    {
        //model bind - esse comando abaixo para pegar os dados do produto por id 
        //pode ser substituido pela injecao no metodo show
        //$divisao = $this->divisao->findOrFail($id); 

        //a forma abaixo e mais correta pois data para validar e atribuir um mensagem de erro customizada

        if( !$divisao = $this->divisao->find($id)) {
            return response()->json(['error' => 'not_found'], 404);
        }

        return response()->json($divisao, 200);
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

        $validate = validator($data, $this->divisao->rules($id));

        if ( $validate->fails() ) {
            $messages = $validate->messages();

            return response()->json(['validate_error' => $messages], 422);
        }

        if( !$divisao = $this->divisao->find($id)) {
            //dd("sadsadasd");
            return response()->json(['error' => 'divisao_not_found'], 404);
        }

        if ( !$update = $divisao->update($data) ) {
            return response()->json(['error' => 'divisao_not_update', 500]);
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
        if( !$divisao = $this->divisao->find($id)) {
            return response()->json(['error' => 'divisao_not_found'], 404);
        }

        if ( !$delete = $divisao->delete() ) {
            return response()->json(['error' => 'divisao_not_delete', 500]);
        }

        return response()->json(['response' => $delete], 200);
    }

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function listarTudo()
    {
        return response()->json(['divisaos' => $this->divisao->all()], 200);
    }
}
