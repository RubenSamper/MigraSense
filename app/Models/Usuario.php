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
        'google_id',
        'latitud',
        'longitud',
        'nombre',
        'apellidos',
        'correo',
        'rol_id',
        'medico_id',
        'password',
        'created_at',
        'updated_at'
    ];

    protected $hidden = ['password'];

    // Roles conocidos (tabla `roles`): 1 = Administrador, 2 = Usuario (paciente), 3 = Médico.
    public function esAdmin(): bool
    {
        return (int) $this->rol_id === 1;
    }

    public function esPaciente(): bool
    {
        return (int) $this->rol_id === 2;
    }

    public function esMedico(): bool
    {
        return (int) $this->rol_id === 3;
    }

    // El médico asignado a este paciente (solo tiene sentido si esPaciente()).
    public function medico()
    {
        return $this->belongsTo(Usuario::class, 'medico_id');
    }

    // Los pacientes asignados a este médico (solo tiene sentido si esMedico()).
    public function pacientes()
    {
        return $this->hasMany(Usuario::class, 'medico_id');
    }

    // Episodios de migraña registrados por este usuario (solo tiene sentido
    // si esPaciente()). Sirve para que el médico pueda ordenar y contar.
    public function migranas()
    {
        return $this->hasMany(TipoMigrana::class, 'usuario_id');
    }
}