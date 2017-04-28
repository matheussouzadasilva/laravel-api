<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//versao 1 da API
$this->group(['prefix' => 'v1'], function() {
    $this->post('cadusuario', 'Auth\RegisterController@criar');
    $this->post('auth', 'Auth\AuthApiController@authenticate');
    $this->post('auth-refresh', 'Auth\AuthApiController@refreshToken');
    $this->post('auth', 'Auth\AuthApiController@authenticate');
    $this->put('esquecisenha', 'Auth\AuthApiController@esqueciSenha');
    $this->put('altsenha', 'Auth\AuthApiController@alterarSenha');
    
    $this->group(['middleware' => 'jwt.auth'], function() {
        //$only = ['only' => ['index', 'store', 'show', 'update', 'destroy']];  //quais metodos vão ser utilizados
        $except = ['except' => ['create', 'edit']];  //quais metodos não vão ser utilizados

        $this->get('logado', 'Auth\AuthApiController@logado');
        $this->get('logout', 'Auth\AuthApiController@logout');
        $this->put('altsenhalog', 'Auth\AuthApiController@alterarSenhaLogado');
        $this->get('products/search', 'API\V1\ProductController@search');
        $this->resource('products', 'API\V1\ProductController', $except);

        $this->get('categorias/listar-tudo', 'API\V1\CategoriaController@listarTudo');
        $this->resource('categorias', 'API\V1\CategoriaController', $except);

        $this->get('divisoes/listar-tudo', 'API\V1\DivisaoController@listarTudo');
        $this->resource('divisoes', 'API\V1\DivisaoController', $except);

        $this->get('tecnicos/listar-tudo', 'API\V1\TecnicoController@listarTudo');
        $this->resource('tecnicos', 'API\V1\TecnicoController', $except);

        $this->post('times/{id}', 'API\V1\TimeController@update'); //tive que usar POST no lugar do PUT no metodo update pois o PUT não suporta envio de arquivo
        $this->resource('times', 'API\V1\TimeController', $except);

        Route::middleware('auth:api')->get('/user', function (Request $request) {
            return $request->user();
        });		
    });
});
