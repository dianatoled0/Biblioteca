<?php
// model/UsuarioModel.php
namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table = 'usuarios';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'usuario', 'pass', 'id_membresia',
        'nombre', 'apellido', 'fecha_nacimiento',
        'correo', 'fecha_inicio_membresia', 'fecha_fin_membresia'
    ];

    public function getUsuarioByUsername($usuario)
    {
        return $this->where('usuario', $usuario)->first();
    }
}
