<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Historique extends Model
{
    use HasFactory;

    protected $table = 'historiques';
    protected $primaryKey = 'id_histor';

    protected $fillable = [
        'cod_reclam', 'id_util', 'act_ehistor',
        'dat_act_histor', 'heure_act_histor',
    ];

    public function reclamation()
    {
        return $this->belongsTo(Reclamation::class, 'cod_reclam', 'cod_reclam');
    }

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_util');
    }
}