<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasFactory;

    protected $table = 'agents';

    protected $fillable = ['id_util', 'mat_agt', 'type_agent', 'id_serv'];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_util');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'id_serv');
    }

    public function reclamations()
    {
        return $this->hasMany(Reclamation::class, 'id_agt');
    }

    public function estGeneral(): bool
    {
        return $this->type_agent === 'general';
    }
}