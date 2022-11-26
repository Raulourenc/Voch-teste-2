<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Unidade extends Model
{
    protected $fillable = [
        'gec_id', 'ban_id', 'uni_razao_social', 'uni_nome_fantasia', 'uni_documento', 'uni_ativo'
    ];

    protected $table = 'unidade';

    public function grupoEconomico(){
        return $this->belongsTo('App\GrupoEconomico', 'gec_id');
    }

    public function banderia(){
        return $this->belongsTo('App\Bandeira', 'ban_id');
    }

}
