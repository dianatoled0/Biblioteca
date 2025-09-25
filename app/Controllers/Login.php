<?php
// controller/Login.php
namespace App\Controllers;

use App\Models\UsuarioModel;
use CodeIgniter\Controller;

class Login extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function autenticar()
    {
        $usuario = $this->request->getPost('usuario');  
        $password = $this->request->getPost('pass');

        $usuarioModel = new UsuarioModel();
        $datosUsuario = $usuarioModel->getUsuarioByUsername($usuario);

        if ($datosUsuario) {
            // Comparación usando MD5
            if (md5($password) === $datosUsuario['pass']) {
                session()->set([
                    'usuario'   => $datosUsuario['usuario'],
                    'nombre'    => $datosUsuario['nombre'],
                    'apellido'  => $datosUsuario['apellido'],
                    'logged_in' => true
                ]);
                return redirect()->to('/panel');
            } else {
                return redirect()->back()->with('error', 'Contraseña incorrecta');
            }
        } else {
            return redirect()->back()->with('error', 'Usuario no encontrado');
        }
    }

    public function registro()
    {
        return view('registro');
    }

    public function crear()
    {
        $usuario = $this->request->getPost('usuario');
        $password = $this->request->getPost('pass');
        $nombre   = $this->request->getPost('nombre');
        $apellido = $this->request->getPost('apellido');
        $correo   = $this->request->getPost('correo');
        $fecha_nacimiento = $this->request->getPost('fecha_nacimiento'); // opcional

        $usuarioModel = new UsuarioModel();

        if ($usuarioModel->getUsuarioByUsername($usuario)) {
            return redirect()->back()->with('error', 'El nombre de usuario ya existe.');
        }

        $data = [
            'usuario' => $usuario,
            'pass' => md5($password), // guardado como MD5
            'nombre' => $nombre,
            'apellido' => $apellido,
            'correo' => $correo,
            'fecha_nacimiento' => $fecha_nacimiento,
            'id_membresia' => 1,
            'fecha_inicio_membresia' => date('Y-m-d'),
            'fecha_fin_membresia'    => date('Y-m-d', strtotime('+3 months')),
        ];

        if ($usuarioModel->insert($data)) {
            return redirect()->to('/')->with('success', 'Registro exitoso. Ahora puedes iniciar sesión.');
        } else {
            return redirect()->back()->with('error', 'No se pudo crear el usuario. Inténtalo de nuevo.');
        }
    }

    public function panel()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }
        return view('panel');
    }

    public function salir()
    {
        session()->destroy();
        return redirect()->to('/');
    }
}
