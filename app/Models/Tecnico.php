<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tecnico extends Model
{
    //Tecnico = tecnicos // nome da tabela no banco ele bota o S automaticamente pra ficar no plural

    protected $table = 'tecnico'; //caso eu quizer especificar o nome da tabela do banco a mao
    protected $primaryKey  = 'codigo_tecnico'; //caso eu quizer especificar o nome da primary key da tabela do banco a mao

    protected $fillable = ['nome']; // campos que são permitidos gravar

    public function rules($codigo_tecnico = '')
    {
    	return [
    		'nome' 		  => "required|min:3|max:30|unique:tecnico,nome,{$codigo_tecnico},codigo_tecnico"
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
