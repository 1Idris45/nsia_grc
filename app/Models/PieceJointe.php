<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PieceJointe extends Model
{
    use HasFactory;

    protected $table = 'pieces_jointes';
    protected $primaryKey = 'id_piec';

    protected $fillable = [
        'cod_reclam', 'nom_fich_piec', 'typ_fich_piec',
        'chem_fichpiec', 'taille_piec', 'dat_ajout_piec',
    ];

    public function reclamation()
    {
        return $this->belongsTo(Reclamation::class, 'cod_reclam', 'cod_reclam');
    }
}