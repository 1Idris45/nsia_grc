<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $table = 'clients';

    protected $fillable = ['id_util', 'num_client'];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_util');
    }

    public function reclamations()
    {
        return $this->hasMany(Reclamation::class, 'id_clit');
    }
}