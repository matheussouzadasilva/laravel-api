<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Time;
use DataTables;
use Illuminate\Support\Facades\Storage;

class TimeController extends Controller
{
    private $time;
    private $regPerPage = 3;

    public function __construct(Time $time)
    {
        $this->time = $time;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $times = $this->time->all(); // retorna tudo sem paginação
        //dd($times);
        return DataTables::of($times)->make(true);
        return DataTables::collection($times)->make(true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        //$data["capa"] = Storage::disk('uploads')->put('images', $request->capa);
        //$data["capa"] = Storage::put('file.png', $request->capa, 'public');
        //$data["capa"] = $request->file('capa')->store('public');

        $validate = validator($data, $this->time->rulesStore());

        if ( $validate->fails() ) {
            $messages = $validate->messages();

            return response()->json(['validate_error' => $messages], 422);
        }

        $data["capa"] = Storage::disk('uploads')->put('images', $request->capa);

        if (!$insert = $this->time->create($data) ) {
            return response()->json(['error' => 'error_insert'], 500);
        }
            
        return response()->json(['times' => $insert], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)// time $time
    {
        //model bind - esse comando abaixo para pegar os dados do produto por id 
        //pode ser substituido pela injecao no metodo show
        //$time = $this->time->findOrFail($id); 

        //a forma abaixo e mais correta pois data para validar e atribuir um mensagem de erro customizada

        if( !$time = $this->time->find($id)) {
            return response()->json(['error' => 'not_found'], 404);
        }

        return response()->json($time);
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
        $data = $request->all();

        $validate = validator($data, $this->time->rulesUpdate($id));

        if ( $validate->fails() ) {
            $messages = $validate->messages();

            return response()->json(['validate_error' => $messages], 422);
        }

        if( !$time = $this->time->find($id)) {
            //dd("sadsadasd");
            return response()->json(['error' => 'time_not_found'], 404);
        }

        if ($request->capa !== 'undefined') {
            $data["capa"] = Storage::disk('uploads')->put('images', $request->capa);
        } else {
            unset($data["capa"]); //exclui key 'capa' do array associativo para não apagar o nome da imagem quando não selecinar uma imagem
        }

        if ( !$update = $time->update($data) ) {
            return response()->json(['error' => 'time_not_update', 500]);
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
        if( !$time = $this->time->find($id)) {
            return response()->json(['error' => 'time_not_found'], 404);
        }

        if ( !$delete = $time->delete() ) {
            return response()->json(['error' => 'time_not_delete', 500]);
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

        $validate = validator($data, $this->time->rulesSearch());

        if ( $validate->fails() ) {
            $messages = $validate->messages();

            return response()->json(['validate_error' => $messages], 422);
        }

        //->where('name', 'LIKE', $data['key-search'])

        $times = $this->time->search($data, $this->regPerPage);

        return response()->json(['times' => $times]);
    }
}
