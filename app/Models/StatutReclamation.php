<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatutReclamation extends Model
{
    use HasFactory;

    protected $table = 'statuts_reclamation';

    protected $fillable = ['lib_stat', 'des_stat'];

    public function reclamations()
    {
        return $this->hasMany(Reclamation::class, 'id_stat');
    }
}