<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reclamation extends Model
{
    use HasFactory;

    protected $table = 'reclamations';
    protected $primaryKey = 'cod_reclam';

    protected $fillable = [
        'obj_reclam', 'des_reclam', 'dat_reclam', 'date_echeance',
        'date_traitement', 'courrier_depassement_envoye',
        'id_clit', 'id_agt', 'id_stat', 'id_typ_rec', 'id_serv',
    ];

    protected function casts(): array
    {
        return [
            'dat_reclam' => 'date',
            'date_echeance' => 'date',
            'date_traitement' => 'date',
            'courrier_depassement_envoye' => 'boolean',
        ];
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'id_clit');
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class, 'id_agt');
    }

    public function statut()
    {
        return $this->belongsTo(StatutReclamation::class, 'id_stat');
    }

    public function typeReclamation()
    {
        return $this->belongsTo(TypeReclamation::class, 'id_typ_rec');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'id_serv');
    }

    public function piecesJointes()
    {
        return $this->hasMany(PieceJointe::class, 'cod_reclam', 'cod_reclam');
    }

    public function historiques()
    {
        return $this->hasMany(Historique::class, 'cod_reclam', 'cod_reclam');
    }

    public function notifications()
    {
        return $this->hasMany(NotificationReclamation::class, 'cod_reclam', 'cod_reclam');
    }

    public function estHorsDelai(): bool
    {
        return $this->date_traitement === null && now()->greaterThan($this->date_echeance);
    }
}