<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    protected $table = 'user';
    protected $fillable = ['id_level', 'name', 'email', 'password'];

    // Relasi ke Level
    public function level()
    {
        return $this->belongsTo(Level::class, 'id_level');
    }
}