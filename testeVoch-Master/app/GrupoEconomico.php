<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GrupoEconomico extends Model
{
    protected $fillable = [
        'gec_razao_social', 'gec_nome_fantasia', 'gec_documento', 'gec_ativo'
    ];

    protected $table = 'grupo_economico';

    public function unidades(){
        return $this->hasMany('App\Unidade', 'gec_id', 'id');
    }

    public function bandeiras(){
        return $this->hasMany('App\Bandeira', 'gec_id', 'id');
    }

    public function funcionarios(){
        return $this->hasMany('App\Funcionario', 'gec_id', 'id');
    }


}
