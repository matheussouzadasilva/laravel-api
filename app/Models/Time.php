<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Time extends Model
{
    //Time = times // nome da tabela no banco ele bota o S automaticamente pra ficar no plural

    protected $table = 'time'; //caso eu quizer especificar o nome da tabela do banco a mao
    protected $primaryKey  = 'codigo_time'; //caso eu quizer especificar o nome da primary key da tabela do banco a mao

    protected $fillable = ['nome', 'codigo_tecnico', 'codigo_categoria', 'codigo_divisao', 'desempenho_time', 'comprar_novo_jogador', 'capa']; // campos que são permitidos gravar

    public function rulesStore()
    {
    	return [
    		'nome' 		  			=> "required|min:2|max:35|unique:time",
            'codigo_tecnico'  		=> "required|min:1|max:10",
            'codigo_categoria'  	=> "required|min:1|max:10",
            'codigo_divisao'  		=> "required|min:1|max:10",
            'desempenho_time'  		=> "required|min:1|max:1",
            'comprar_novo_jogador'  => "required|min:1|max:1",
            'capa'  				=> "required|min:5|max:100"
    	];
    }

    public function rulesUpdate($codigo_time = '')
    {
        return [
            'nome'                  => "required|min:2|max:35|unique:time,nome,{$codigo_time},codigo_time",
            'codigo_tecnico'        => "required|min:1|max:10",
            'codigo_categoria'      => "required|min:1|max:10",
            'codigo_divisao'        => "required|min:1|max:10",
            'desempenho_time'       => "min:1|max:1",
            'comprar_novo_jogador'  => "min:1|max:1",
            'capa'                  => "min:5|max:100"
        ];
    }


    public function rulesUpload()
	{
	    return [
	      'name'        => 'required',
	      'sku'         => 'required|unique:products,sku,' . $this->get('id'),
	      'image'       => 'required|mimes:png'
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
