<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table = 'usuarios';
    protected $primaryKey = 'id';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $allowedFields = [
        'usuario', 'correo', 'pass', 'nombre', 'apellido', 'rol',  // Removí pass_confirm
        'id_membresia', 'fecha_nacimiento', 'fecha_inicio_membresia',
        'fecha_fin_membresia', 'created_at', 'updated_at'
    ];

    // Reglas específicas para INSERT (sin {id} para is_unique)
    protected $insertValidationRules = [
        'usuario' => 'required|alpha_numeric_space|min_length[3]|max_length[30]|is_unique[usuarios.usuario]',
        'pass' => 'required|min_length[6]',
        'pass_confirm' => 'required|matches[pass]',
        'correo' => 'required|valid_email|is_unique[usuarios.correo]',
        'nombre' => 'required|min_length[3]|max_length[50]',
        'apellido' => 'required|min_length[3]|max_length[50]',
        'rol' => 'required|in_list[admin,usuario]',
        'id_membresia' => 'required|integer',
        'fecha_inicio_membresia' => 'required|valid_date[Y-m-d]',
        'fecha_fin_membresia' => 'required|valid_date[Y-m-d]',
        'fecha_nacimiento' => 'permit_empty|valid_date[Y-m-d]'
    ];

    // Reglas específicas para UPDATE (con {id} para is_unique)
    protected $updateValidationRules = [
        'usuario' => 'required|alpha_numeric_space|min_length[3]|max_length[30]|is_unique[usuarios.usuario,id,{id}]',
        'pass' => 'permit_empty|min_length[6]',
        'pass_confirm' => 'permit_empty|matches[pass]',
        'correo' => 'required|valid_email|is_unique[usuarios.correo,id,{id}]',
        'nombre' => 'required|min_length[3]|max_length[50]',
        'apellido' => 'required|min_length[3]|max_length[50]',
        'rol' => 'required|in_list[admin,usuario]',
        'id_membresia' => 'required|integer',
        'fecha_nacimiento' => 'permit_empty|valid_date[Y-m-d]',
        'fecha_inicio_membresia' => 'required|valid_date[Y-m-d]',
        'fecha_fin_membresia' => 'required|valid_date[Y-m-d]'
    ];

    protected $validationMessages = [
        'usuario' => [
            'required' => 'El usuario es requerido.',
            'is_unique' => 'El usuario ya existe.',
        ],
        'pass' => [
            'required' => 'La contraseña es requerida.',
            'min_length' => 'La contraseña debe tener al menos 6 caracteres.',
        ],
        'pass_confirm' => [
            'matches' => 'Las contraseñas no coinciden.',
        ],
        'correo' => [
            'required' => 'El correo es requerido.',
            'valid_email' => 'Formato de correo inválido.',
            'is_unique' => 'El correo ya está registrado.',
        ],
        'nombre' => [
            'required' => 'El nombre es requerido.',
        ],
        'rol' => [
            'required' => 'El rol es requerido.',
        ],
    ];
    protected $skipValidation = false;

    protected function beforeInsert(array $data)
    {
        if (!empty($data['pass'])) {
            $data['pass'] = md5($data['pass']);
            log_message('debug', 'beforeInsert: Pass hasheado para nuevo usuario a ' . $data['pass']);
        }
        return $data;
    }

    protected function beforeUpdate(array $data)
    {
        if (isset($data['pass']) && $data['pass'] !== '') {
            $data['pass'] = md5($data['pass']);
            log_message('debug', 'beforeUpdate: Pass hasheado para update a ' . $data['pass']);
        } else {
            unset($data['pass']);
        }
        return $data;
    }

    public function getAllUsuariosWithMembresia()
    {
        return $this->select('usuarios.id, usuarios.usuario, usuarios.nombre, usuarios.apellido, usuarios.correo, usuarios.rol, tipos_membresia.nombre AS nombre_membresia')
                    ->join('tipos_membresia', 'tipos_membresia.id = usuarios.id_membresia', 'left')
                    ->findAll();
    }
}