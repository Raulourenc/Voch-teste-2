<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bandeira extends Model
{
    protected $fillable = [
        'gec_id', 'ban_nome', 'ban_documento', 'ban_ativo'
    ];

    protected $table = 'bandeira';

    public function grupoEconomico(){
        return $this->belongsTo('App\GrupoEconomico', 'gec_id');
    }

}
