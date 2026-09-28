<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeReclamation extends Model
{
    use HasFactory;

    protected $table = 'types_reclamation';

    protected $fillable = ['lib_typ_rec', 'des_typ_rec', 'id_serv'];

    public function service()
    {
        return $this->belongsTo(Service::class, 'id_serv');
    }

    public function reclamations()
    {
        return $this->hasMany(Reclamation::class, 'id_typ_rec');
    }
}