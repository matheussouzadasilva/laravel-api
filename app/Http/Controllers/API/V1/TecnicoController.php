<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tecnico;

class TecnicoController extends Controller
{
    private $tecnico;
    private $regPerPage = 3;

    public function __construct(Tecnico $tecnico)
    {
        $this->tecnico = $tecnico;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$tecnicos = $this->tecnico->all(); // retorna tudo sem paginação
        $tecnicos = $this->tecnico->paginate($this->regPerPage); // retorna tudo com paginação

        return response()->json(['tecnicos' => $tecnicos], 200);
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
        
        $validate = validator($data, $this->tecnico->rules());

        if ( $validate->fails() ) {
            $messages = $validate->messages();

            return response()->json(['validate_error' => $messages], 422);
        }
        if (!$insert = $this->tecnico->create($data) ) {
            return response()->json(['error' => 'error_insert'], 500);
        }
            
        return response()->json(['tecnicos' => $insert], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)// Tecnico $tecnico
    {
        //model bind - esse comando abaixo para pegar os dados do produto por id 
        //pode ser substituido pela injecao no metodo show
        //$tecnico = $this->tecnico->findOrFail($id); 

        //a forma abaixo e mais correta pois data para validar e atribuir um mensagem de erro customizada

        if( !$tecnico = $this->tecnico->find($id)) {
            return response()->json(['error' => 'not_found'], 404);
        }

        return response()->json($tecnico);
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

        $validate = validator($data, $this->tecnico->rules($id));

        if ( $validate->fails() ) {
            $messages = $validate->messages();

            return response()->json(['validate_error' => $messages], 422);
        }

        if( !$tecnico = $this->tecnico->find($id)) {
            //dd("sadsadasd");
            return response()->json(['error' => 'tecnico_not_found'], 404);
        }

        if ( !$update = $tecnico->update($data) ) {
            return response()->json(['error' => 'tecnico_not_update', 500]);
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
        if( !$tecnico = $this->tecnico->find($id)) {
            return response()->json(['error' => 'tecnico_not_found'], 404);
        }

        if ( !$delete = $tecnico->delete() ) {
            return response()->json(['error' => 'tecnico_not_delete', 500]);
        }

        return response()->json(['response' => $delete]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $data = $request->all();

        $validate = validator($data, $this->tecnico->rulesSearch());

        if ( $validate->fails() ) {
            $messages = $validate->messages();

            return response()->json(['validate_error' => $messages], 422);
        }

        //->where('name', 'LIKE', $data['key-search'])

        $tecnicos = $this->tecnico->search($data, $this->regPerPage);

        return response()->json(['tecnicos' => $tecnicos]);
    }
}
