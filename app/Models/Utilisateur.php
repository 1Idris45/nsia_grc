<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Utilisateur extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'utilisateurs';

    protected $fillable = [
        'nom_util', 'prenom_util', 'tel_util', 'adres_util',
        'email_util', 'password', 'role',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return ['password' => 'hashed'];
    }

    // Auth Laravel utilise "email" par convention, on le fait pointer vers email_util
    public function getEmailForPasswordReset(): string
    {
        return $this->email_util;
    }

    public function username(): string
    {
        return 'email_util';
    }

    public function client()
    {
        return $this->hasOne(Client::class, 'id_util');
    }

    public function agent()
    {
        return $this->hasOne(Agent::class, 'id_util');
    }
}