<?php
// model/UsuarioModel.php
namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    // Define la tabla de la base de datos
    protected $table = 'usuarios';
    // Define la clave primaria de la tabla
    protected $primaryKey = 'id';
    // Define los campos que se pueden modificar
    protected $allowedFields = ['usuario', 'pass'];

    /**
     * Busca un usuario por su nombre de usuario.
     * @param string $usuario El nombre de usuario a buscar.
     * @return array|null Retorna los datos del usuario o null si no se encuentra.
     */
    public function getUsuarioByUsername($usuario)
    {
        return $this->where('usuario', $usuario)->first();
    }

    /**
     * Crea un nuevo usuario en la base de datos.
     * @param array $data Un array con los datos del usuario (usuario y pass).
     * @return bool Retorna true si el usuario se crea con éxito, false en caso contrario.
     */
    public function crearUsuario($data)
    {
        // Se asegura de que la contraseña esté hasheada antes de guardar
        $data['pass'] = password_hash($data['pass'], PASSWORD_DEFAULT);
        return $this->insert($data);
    }
}