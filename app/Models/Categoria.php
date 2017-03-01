<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    //Categoria = categorias // nome da tabela no banco ele bota o S automaticamente pra ficar no plural

    protected $table = 'categoria'; //caso eu quizer especificar o nome da tabela do banco a mao
    protected $primaryKey  = 'codigo_categoria'; //caso eu quizer especificar o nome da primary key da tabela do banco a mao

    protected $fillable = ['nome']; // campos que são permitidos gravar

    public function rules($codigo_categoria = '')
    {
    	return [
    		'nome' 		  => "required|min:3|max:30|unique:categoria,nome,{$codigo_categoria},codigo_categoria"
    	];
    }


    public function rulesSearch()
    {
    	return [
    		'key-search' => "required"
    	];
    }

    public function search($data, $regPerPage)
    {
    	return $this->where('nome', 'LIKE', "%{$data['key-search']}%")
        ->paginate($regPerPage); // retorna pesquisa com paginação
    }
}
