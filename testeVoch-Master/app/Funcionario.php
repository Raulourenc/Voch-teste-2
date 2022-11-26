<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Funcionario extends Model
{
    protected $fillable = [
        'gec_id', 'ban_id', 'uni_id', 'fun_nome', 'fun_documento', 'fun_celular', 'fun_ativo'
    ];

    protected $table = 'funcionario';

    public function grupoEconomico(){
        return $this->belongsTo('App\GrupoEconomico', 'gec_id');
    }

    public function banderia(){
        return $this->belongsTo('App\Bandeira', 'ban_id');
    }

    public function unidade(){
        return $this->belongsTo('App\Unidade', 'uni_id');
    }


  
}
