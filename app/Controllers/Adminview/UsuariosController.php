<?php

namespace App\Controllers\Adminview;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;
use App\Models\TipoMembresiaModel;

class UsuariosController extends BaseController
{
    protected $usuarioModel;
    protected $membresiaModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
        $this->membresiaModel = new TipoMembresiaModel();
    }

    public function index()
    {
        // 🚨 CAMBIO CLAVE: Capturar el filtro de rol de la URL
        $filtroRol = $this->request->getGet('filtro_rol');
        
        // Cargar los usuarios usando el nuevo método con filtro
        $data['usuarios'] = $this->usuarioModel->getUsuariosConFiltro($filtroRol);
        
        // Pasar el filtro a la vista para que el selector permanezca seleccionado
        $data['filtroRol'] = $filtroRol; 

        return view('admin/usuarios/index', $data);
    }

    public function crear()
    {
        $data['membresias'] = $this->membresiaModel->findAll();
        return view('admin/usuarios/form', $data);
    }

    public function guardar()
    {
        // 1. Preparar datos del POST
        $data = [
            'usuario' => $this->request->getPost('usuario'),
            'correo' => $this->request->getPost('correo'),
            'pass' => $this->request->getPost('pass'),
            'nombre' => $this->request->getPost('nombre'),
            'apellido' => $this->request->getPost('apellido'),
            'rol' => $this->request->getPost('rol'),
            'id_membresia' => $this->request->getPost('id_membresia'),
            'fecha_nacimiento' => $this->request->getPost('fecha_nacimiento'),
            'fecha_inicio_membresia' => $this->request->getPost('fecha_inicio_membresia'),
            'fecha_fin_membresia' => $this->request->getPost('fecha_fin_membresia'),
        ];

        // 2. Aplicar Hash MD5 manualmente (según tu implementación)
        $pass = $this->request->getPost('pass');
        if (!empty($pass)) {
            // Asegúrate de que el campo 'pass_confirm' coincida si tienes validación
            $data['pass'] = md5($pass); 
        }

        // 3. Intentar insertar y redireccionar
        if ($this->usuarioModel->insert($data)) {
            // Redirección exitosa a la lista de usuarios (index)
            return redirect()->to(base_url('admin/usuarios'))->with('success', 'Usuario creado exitosamente. ID: ' . $this->usuarioModel->insertID());
        } else {
            // Manejo de errores (ej: usuario o correo duplicado)
            // Redirige de vuelta al formulario con los errores y los datos anteriores
            return redirect()->back()->withInput()->with('errors', $this->usuarioModel->errors());
        }
    }

    public function editar($id)
    {
        $data['usuario'] = $this->usuarioModel->find($id);
        if (empty($data['usuario'])) {
            return redirect()->to(base_url('admin/usuarios'))->with('error', 'Usuario no encontrado');
        }
        $data['membresias'] = $this->membresiaModel->findAll();
        return view('admin/usuarios/form', $data);
    }

    public function actualizar($id)
    {
        $data = [
            'usuario' => $this->request->getPost('usuario'),
            'correo' => $this->request->getPost('correo'),
            'nombre' => $this->request->getPost('nombre'),
            'apellido' => $this->request->getPost('apellido'),
            'rol' => $this->request->getPost('rol'),
            'id_membresia' => $this->request->getPost('id_membresia'),
            'fecha_nacimiento' => $this->request->getPost('fecha_nacimiento'),
            'fecha_inicio_membresia' => $this->request->getPost('fecha_inicio_membresia'),
            'fecha_fin_membresia' => $this->request->getPost('fecha_fin_membresia'),
        ];

        $pass = $this->request->getPost('pass');
        if (!empty($pass)) {
            $data['pass'] = md5($pass); // Hash manual
        }

        if ($this->usuarioModel->update($id, $data)) {
            return redirect()->to(base_url('admin/usuarios'))->with('success', 'Usuario actualizado correctamente');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->usuarioModel->errors());
        }
    }

    public function eliminar($id)
    {
        $usuario = $this->usuarioModel->find($id);
        if (empty($usuario) || $usuario['usuario'] === 'dtoledo') {
            return redirect()->to(base_url('admin/usuarios'))->with('error', 'No se puede eliminar este usuario.');
        }
        $this->usuarioModel->delete($id);
        return redirect()->to(base_url('admin/usuarios'))->with('success', 'Usuario eliminado correctamente');
    }
}