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
        $data['usuarios'] = $this->usuarioModel->getAllUsuariosWithMembresia();
        return view('admin/usuarios/index', $data);
    }

    public function crear()
    {
        $data['membresias'] = $this->membresiaModel->findAll();
        return view('admin/usuarios/form', $data);
    }

    public function guardar()
    {
        // DEBUG TEMPORAL: Siempre imprime POST (no se redirige antes)
        $post_data = $this->request->getPost();
        echo "<h3 style='color: red; background: yellow; padding: 10px;'>DEBUG: POST Recibido</h3>";
        echo "<pre style='background: #f0f0f0; padding: 10px; border: 1px solid #ccc;'>";
        print_r($post_data);
        echo "</pre>";
        echo "<h3 style='color: red;'>DEBUG Pass: '" . $this->request->getPost('pass') . "' | Pass Confirm: '" . $this->request->getPost('pass_confirm') . "'</h3>";
        // FIN DEBUG – NO BORRES AÚN

        // Preparar datos básicos (sin validación estricta para test)
        $data = [
            'usuario' => $this->request->getPost('usuario') ?: 'test_default',
            'correo' => $this->request->getPost('correo') ?: 'test@example.com',
            'pass' => $this->request->getPost('pass') ?: 'defaultpass', // Default si vacío
            'nombre' => $this->request->getPost('nombre') ?: 'Test',
            'apellido' => $this->request->getPost('apellido') ?: 'User',
            'rol' => $this->request->getPost('rol') ?: 'usuario',
            'id_membresia' => $this->request->getPost('id_membresia') ?: 1,
            'fecha_nacimiento' => $this->request->getPost('fecha_nacimiento') ?: null,
            'fecha_inicio_membresia' => $this->request->getPost('fecha_inicio_membresia') ?: date('Y-m-d'),
            'fecha_fin_membresia' => $this->request->getPost('fecha_fin_membresia') ?: date('Y-m-d', strtotime('+3 months')),
        ];

        // TEMPORAL: Hash MD5 manual (bypassa hooks y validación de pass_confirm)
        if (!empty($data['pass'])) {
            $data['pass'] = md5($data['pass']);
            echo "<h3 style='color: green;'>DEBUG: Pass hasheado manual a: " . $data['pass'] . "</h3>";
        }

        // TEMPORAL: Salta validación completa – inserta directo
        echo "<h3 style='color: blue;'>DEBUG: Intentando insert con datos:</h3>";
        echo "<pre>";
        print_r($data);
        echo "</pre>";

        if ($this->usuarioModel->insert($data)) {
            echo "<h1 style='color: green;'>¡USUARIO CREADO EXITOSAMENTE! ID: " . $this->usuarioModel->insertID() . "</h1>";
            echo "<p>Ve a phpMyAdmin y chequea la tabla usuarios (pass debe ser MD5).</p>";
            echo "<a href='" . base_url('admin/usuarios') . "' style='background: green; color: white; padding: 10px;'>Ir a Lista de Usuarios</a>";
            // Remueve el redirect para ver esto – después lo ponemos
        } else {
            echo "<h1 style='color: red;'>ERROR en INSERT: " . print_r($this->usuarioModel->errors(), true) . "</h1>";
        }

        // TEMPORAL: No redirige – muestra todo en pantalla para debug
        // Después, volvemos a redirect()->to('/admin/usuarios')->with('success', 'OK');
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
        // TEMPORAL: Usa versión simple para update también
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