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
        // Importante: Solo hashea si la contraseña se ha proporcionado y no está vacía
        if (isset($data['data']['pass']) && !empty($data['data']['pass'])) {
            // Utilizamos MD5 (32 caracteres)
            $data['data']['pass'] = md5($data['data']['pass']);
        }
        return $data;
    }

    // Reglas de validación
    protected $validationRules = [
        // Validación de unicidad. La sintaxis 'is_unique[tabla.campo,id,{id}]'
        // permite la edición sin error al excluir el ID actual.
        'usuario'       => 'required|min_length[3]|max_length[50]|is_unique[usuarios.usuario,id,{id}]',
        'correo'        => 'required|valid_email|max_length[150]|is_unique[usuarios.correo,id,{id}]',
        
        // CORRECCIÓN CLAVE: Reglas condicionales para 'pass'.
        // Asegura que sea 'required' SOLO al insertar, y 'permit_empty' en la actualización.
        'pass' => [ 
            'label' => 'Contraseña',
            'rules' => 'min_length[5]',
            'on'    => [
                'insert' => 'required|min_length[5]', 
                'update' => 'permit_empty|min_length[5]' 
            ],
        ],

        // Campos No Nulo restantes
        'rol'                   => 'required|in_list[admin,usuario]',
        // Validamos que el ID exista en la tabla tipos_membresia
        'id_membresia'          => 'required|integer|is_not_unique[tipos_membresia.id]',
        'nombre'                => 'required|max_length[150]',
        'apellido'              => 'required|max_length[150]',
        
        // Validación de fechas (NOT NULL en la BD)
        'fecha_nacimiento'      => 'permit_empty|valid_date',
        'fecha_inicio_membresia' => 'required|valid_date',
        'fecha_fin_membresia'    => 'required|valid_date', 
    ];

    protected $validationMessages = [
        'usuario' => [
            'required'  => 'El campo **Usuario** es obligatorio.',
            'is_unique' => 'El nombre de usuario ya está registrado.',
        ],
        'correo' => [
            'required'      => 'El campo **Correo Electrónico** es obligatorio.',
            'valid_email'   => 'Por favor, ingresa un correo electrónico válido.',
            'is_unique'     => 'El correo electrónico ya está registrado.',
        ],
        'pass' => [ 
            'required'   => 'La **Contraseña** es obligatoria.',
            'min_length' => 'La contraseña debe tener al menos 5 caracteres.',
        ],
        'rol' => [
            'required' => 'El campo **Rol** es obligatorio.',
            'in_list'  => 'El valor del Rol no es válido (debe ser admin o usuario).',
        ],
        'id_membresia' => [
            'required'      => 'El campo **Membresía ID** es obligatorio.',
            'is_not_unique' => 'El ID de la membresía seleccionada no existe.',
        ],
        'nombre' => [
            'required' => 'El campo **Nombre** es obligatorio.',
        ],
        'apellido' => [
            'required' => 'El campo **Apellido** es obligatorio.',
        ],
        'fecha_inicio_membresia' => [
            'required'   => 'La **Fecha de Inicio de Membresía** es obligatoria.',
            'valid_date' => 'Formato de fecha de inicio inválido.',
        ],
        'fecha_fin_membresia' => [
            'required'   => 'La **Fecha Fin Membresía** es obligatoria.',
            'valid_date' => 'Formato de fecha fin inválido.',
        ],
    ];
}