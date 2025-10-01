<?php
namespace App\Controllers;

use App\Models\UsuarioModel;
use App\Controllers\BaseController;

class Login extends BaseController
{
    protected $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
        helper(['url', 'form', 'text']);
    }

    // Formulario de login
    public function index()
    {
        // Si ya está logueado, redirige según rol
        $ses = session();
        if ($ses->get('logged_in')) {
            if ($ses->get('rol') === 'admin') {
                return redirect()->to('/admin');
            }
            return redirect()->to('/panel');
        }

        return view('login');
    }

    public function autenticar()
    {
        $usuario = $this->request->getPost('usuario');  
        $password = $this->request->getPost('pass');

        $datosUsuario = $this->usuarioModel->getUsuarioByUsername($usuario);

        if ($datosUsuario) {
            // Comparación usando MD5 (según tu BD)
            if (md5($password) === $datosUsuario['pass']) {
                // Guardar en sesión
                session()->set([
                    'usuario'   => $datosUsuario['usuario'],
                    'nombre'    => $datosUsuario['nombre'],
                    'apellido'  => $datosUsuario['apellido'],
                    'rol'       => $datosUsuario['rol'],
                    'logged_in' => true
                ]);

                if ($datosUsuario['rol'] === 'admin') {
                    return redirect()->to('/admin');
                } else {
                    return redirect()->to('/panel');
                }
            } else {
                return redirect()->back()->with('error', 'Contraseña incorrecta')->withInput();
            }
        } else {
            return redirect()->back()->with('error', 'Usuario no encontrado')->withInput();
        }
    }

    // Panel para usuarios comunes (solo accesible si está logueado)
    public function panel()
    {
        // Protección por filtro o por chequeo adicional
        if (! session()->get('logged_in')) {
            return redirect()->to('/');
        }

        return view('panel');
    }

    // Panel admin (ruta protegida por filtro admin)
    public function admin()
    {
        // La protección principal la hace el filtro Admin.
        // Aquí hacemos una verificación por si accede directamente.
        if (! session()->get('logged_in') || session()->get('rol') !== 'admin') {
            return redirect()->to('/')->with('error', 'Acceso denegado.');
        }

        // Pasar datos de sesión a la vista si quieres
        $data = [
            'nombre'   => session()->get('nombre'),
            'apellido' => session()->get('apellido'),
            'usuario'  => session()->get('usuario')
        ];

        return view('admin/dashboard', $data);
    }

    public function salir()
    {
        session()->destroy();
        return redirect()->to('/')->with('success', 'Sesión cerrada.');
    }
}

