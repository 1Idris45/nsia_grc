<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationReclamation extends Model
{
    use HasFactory;

    protected $table = 'notifications_reclamation';
    protected $primaryKey = 'cod_notif';

    protected $fillable = [
        'cod_reclam', 'mess_notif', 'dat_notif', 'canal_notif', 'statut_notif',
    ];

    protected function casts(): array
    {
        return ['dat_notif' => 'date'];
    }

    public function reclamation()
    {
        return $this->belongsTo(Reclamation::class, 'cod_reclam', 'cod_reclam');
    }
}