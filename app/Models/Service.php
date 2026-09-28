<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $table = 'services';

    protected $fillable = ['lib_serv', 'des_serv'];

    public function agents()
    {
        return $this->hasMany(Agent::class, 'id_serv');
    }

    public function typesReclamation()
    {
        return $this->hasMany(TypeReclamation::class, 'id_serv');
    }
}