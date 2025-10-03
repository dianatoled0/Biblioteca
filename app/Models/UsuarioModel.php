<?php namespace App\Models;

use CodeIgniter\Model;

/**
 * Modelo para la tabla 'usuarios'.
 */
class UsuarioModel extends Model
{
    // Configuración principal de la tabla
    protected $table          = 'usuarios';
    protected $primaryKey     = 'id';
    protected $returnType     = 'array';
    protected $useSoftDeletes = false; 
    protected $useTimestamps  = false; 

    // Campos permitidos para inserción y actualización
    protected $allowedFields = [
        'usuario',
        'pass',
        'rol',
        'id_membresia',
        'nombre',
        'apellido',
        'fecha_nacimiento', 
        'correo',
        'fecha_inicio_membresia',
        'fecha_fin_membresia',
    ];

    // Eventos del modelo (Hooks) para el hash de contraseña
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    /**
     * Aplica la función MD5 a la contraseña antes de guardarla.
     */
    protected function hashPassword(array $data)
    {
        if (isset($data['data']['pass']) && !empty($data['data']['pass'])) {
            $data['data']['pass'] = md5($data['data']['pass']);
        }
        return $data;
    }

    // Reglas de validación (Asegura que los campos No Nulo de la BD sean 'required')
    protected $validationRules = [
        // Validación de campos clave
        'usuario'    => 'required|min_length[3]|max_length[50]|is_unique[usuarios.usuario,id,{id}]',
        'correo'     => 'required|valid_email|max_length[150]|is_unique[usuarios.correo,id,{id}]',
        
        // Regla para la edición (update): si se provee, valida longitud.
        'pass'       => 'if_exist|min_length[5]', 
        
        // Regla para la creación (insert): debe ser required.
        'pass_create' => [
            'label' => 'Contraseña',
            'rules' => 'required|min_length[5]',
            'on'    => 'insert', 
        ],
        
        // Campos No Nulo restantes
        'rol'          => 'required|in_list[admin,usuario]',
        'id_membresia' => 'required|integer',
        'nombre'       => 'required|max_length[150]',
        'apellido'     => 'required|max_length[150]',
        
        // Validación de fechas
        'fecha_nacimiento'       => 'permit_empty|valid_date',
        'fecha_inicio_membresia' => 'required|valid_date',
        'fecha_fin_membresia'    => 'required|valid_date',
    ];

    protected $validationMessages = [
        'usuario' => [
            'required'  => 'El campo Usuario es obligatorio.',
            'is_unique' => 'El nombre de usuario ya está registrado.',
        ],
        'correo' => [
            'required'    => 'El campo Correo Electrónico es obligatorio.',
            'valid_email' => 'Por favor, ingresa un correo electrónico válido.',
            'is_unique'   => 'El correo electrónico ya está registrado.',
        ],
        // Mensaje para la regla 'pass_create' (al crear)
        'pass_create' => [
            'required'   => 'La Contraseña es obligatoria.',
            'min_length' => 'La contraseña debe tener al menos 5 caracteres.',
        ],
        'rol' => [
            'required' => 'El campo Rol es obligatorio.',
            'in_list'  => 'El valor del Rol no es válido.',
        ],
        'id_membresia' => [
            'required' => 'El campo Membresía ID es obligatorio.',
        ],
        'nombre' => [
            'required' => 'El campo Nombre es obligatorio.',
        ],
        'apellido' => [
            'required' => 'El campo Apellido es obligatorio.',
        ],
        'fecha_inicio_membresia' => [
            'required'   => 'La Fecha de Inicio de Membresía es obligatoria.',
            'valid_date' => 'Formato de fecha inválido.',
        ],
        'fecha_fin_membresia' => [
            'required'   => 'La Fecha Fin Membresía es obligatoria.',
            'valid_date' => 'Formato de fecha inválido.',
        ],
    ];
}