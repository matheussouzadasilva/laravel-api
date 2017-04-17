<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //Product = products // nome da tabela no banco ele bota o S automaticamente pra ficar no plural

    //protected $table = 'produtos'; //caso eu quizer especificar o nome da tabela do banco a mao

    protected $fillable = ['name', 'description']; // campos que são permitidos gravar

    public function rules($id = '')
    {
    	return [
    		'name' 		  => "required|min:3|max:100|unique:products,name,{$id},id",
    		'description' => 'required|min:3|max:1500'
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
    	return $this->where('name', $data['key-search'])
        ->orWhere('description', 'LIKE', "%{$data['key-search']}%")
        ->paginate($regPerPage); // retorna pesquisa com paginação
    }
}
