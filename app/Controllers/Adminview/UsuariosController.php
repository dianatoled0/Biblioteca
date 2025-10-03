<?php namespace App\Controllers\Adminview;

use App\Controllers\BaseController; // Asegura que esta línea sea correcta
use App\Models\UsuarioModel;

class UsuariosController extends BaseController
{
    protected $usuarioModel;

    public function __construct()
    {
        // Inicializa el modelo de usuarios
        $this->usuarioModel = new UsuarioModel();
    }

    /**
     * Muestra la lista de todos los usuarios.
     */
    public function index()
    {
        // Obtiene todos los usuarios
        $data['usuarios'] = $this->usuarioModel->findAll();
        
        // Carga la vista de índice.
        return view('admin/usuarios/index', $data);
    }

    /**
     * Muestra el formulario de creación o procesa la creación.
     */
    public function crear()
    {
        if ($this->request->getMethod() === 'post') {
            
            // Recolección de datos del formulario.
            $data = [
                'usuario'                => $this->request->getPost('usuario'),
                'pass'                   => $this->request->getPost('pass') ?: null,
                'rol'                    => $this->request->getPost('rol'),
                'nombre'                 => $this->request->getPost('nombre'),
                'apellido'               => $this->request->getPost('apellido'),
                'correo'                 => $this->request->getPost('correo'),
                'id_membresia'           => $this->request->getPost('id_membresia') ?? 1, 
                'fecha_nacimiento'       => $this->request->getPost('fecha_nacimiento') ?: null,
                'fecha_inicio_membresia' => $this->request->getPost('fecha_inicio_membresia'),
                'fecha_fin_membresia'    => $this->request->getPost('fecha_fin_membresia'),
            ];

            // 1. FORZAR LA VALIDACIÓN CON EL CONTEXTO 'insert' (creación)
            $rules = $this->usuarioModel->getValidationRules(['except' => ['pass']]);
            $rules['pass'] = $this->usuarioModel->getValidationRules(['only' => ['pass_create']])['pass_create'];

            if (!$this->validate($rules, $this->usuarioModel->getValidationMessages())) {
                // Si la validación falla (incluyendo la regla 'pass_create')
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            // 2. Si la validación pasa, usamos insert()
            if ($this->usuarioModel->insert($data)) {
                return redirect()->to('/admin/usuarios')->with('success', 'Usuario creado correctamente.');
            } else {
                // Fallo de la base de datos (Ej. FK, conexión)
                return redirect()->back()->withInput()->with('errors', ['error_db' => 'Error al intentar guardar el usuario en la base de datos.']);
            }
        }
        
        // Muestra el formulario
        return view('admin/usuarios/form');
    }

    /**
     * Muestra el formulario de edición o procesa la actualización.
     */
    public function editar($id)
    {
        $usuario = $this->usuarioModel->find($id);

        if (!$usuario) {
            return redirect()->to('/admin/usuarios')->with('error', 'Usuario no encontrado.');
        }

        if ($this->request->getMethod() === 'post') {
            $data = [
                'id'                     => $id, 
                'usuario'                => $this->request->getPost('usuario'),
                'rol'                    => $this->request->getPost('rol'),
                'nombre'                 => $this->request->getPost('nombre'),
                'apellido'               => $this->request->getPost('apellido'),
                'correo'                 => $this->request->getPost('correo'),
                'id_membresia'           => $this->request->getPost('id_membresia'),
                'fecha_nacimiento'       => $this->request->getPost('fecha_nacimiento'),
                'fecha_inicio_membresia' => $this->request->getPost('fecha_inicio_membresia'),
                'fecha_fin_membresia'    => $this->request->getPost('fecha_fin_membresia'),
            ];
            
            // Solo actualizamos la contraseña si se proporcionó una nueva.
            $newPass = $this->request->getPost('pass');
            if (!empty($newPass)) {
                $data['pass'] = $newPass; 
            }
            
            // Usamos save() para la actualización
            if ($this->usuarioModel->save($data)) {
                return redirect()->to('/admin/usuarios')->with('success', 'Usuario actualizado correctamente.');
            } else {
                 return redirect()->back()->withInput()->with('errors', $this->usuarioModel->errors());
            }
        }

        return view('admin/usuarios/form', ['usuario' => $usuario]);
    }

    /**
     * Elimina un usuario.
     */
    public function eliminar($id)
    {
        $this->usuarioModel->delete($id);
        return redirect()->to('/admin/usuarios')->with('success', 'Usuario eliminado correctamente.');
    }
}