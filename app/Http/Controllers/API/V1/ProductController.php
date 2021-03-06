<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    private $product;
    private $regPerPage = 3;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$products = $this->product->all(); // retorna tudo sem paginação
        $products = $this->product->paginate($this->regPerPage); // retorna tudo com paginação

        return response()->json(['data' => $products], 200);
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
        
        $validate = validator($data, $this->product->rules());

        if ( $validate->fails() ) {
            $messages = $validate->messages();

            return response()->json(['validate_error' => $messages], 422);
        }
        if (!$insert = $this->product->create($data) ) {
            return response()->json(['error' => 'error_insert'], 500);
        }
            
        return response()->json(['data' => $insert], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)// Product $product
    {
        //model bind - esse comando abaixo para pegar os dados do produto por id 
        //pode ser substituido pela injecao no metodo show
        //$product = $this->product->findOrFail($id); 

        //a forma abaixo e mais correta pois data para validar e atribuir um mensagem de erro customizada

        if( !$product = $this->product->find($id)) {
            return response()->json(['error' => 'not_found'], 404);
        }

        return response()->json(['data' => $product]);
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

        $validate = validator($data, $this->product->rules($id));

        if ( $validate->fails() ) {
            $messages = $validate->messages();

            return response()->json(['validate_error' => $messages], 422);
        }

        if( !$product = $this->product->find($id)) {
            return response()->json(['error' => 'product_not_found'], 404);
        }

        if ( !$update = $product->update($data) ) {
            return response()->json(['error' => 'product_not_update', 500]);
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
        if( !$product = $this->product->find($id)) {
            return response()->json(['error' => 'product_not_found'], 404);
        }

        if ( !$delete = $product->delete() ) {
            return response()->json(['error' => 'product_not_delete', 500]);
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

        $validate = validator($data, $this->product->rulesSearch());

        if ( $validate->fails() ) {
            $messages = $validate->messages();

            return response()->json(['validate_error' => $messages], 422);
        }

        //->where('name', 'LIKE', $data['key-search'])

        $products = $this->product->search($data, $this->regPerPage);

        return response()->json(['data' => $products]);
    }
}
