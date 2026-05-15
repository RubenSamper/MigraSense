<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Usuario extends Authenticatable
{
    use HasUuids;
    protected $table = "usuarios";

    protected $primaryKey = 'id';

    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'nombre',
        'apellidos',
        'correo',
        'rol_id',
        'password',
        'created_at',
        'updated_at'
    ];

    protected $hidden = ['password'];
}
