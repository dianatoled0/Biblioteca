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
        $usuario = $this->request->getPost('username');
        $password = $this->request->getPost('pass');

        $usuarioModel = new UsuarioModel();
        $datosUsuario = $usuarioModel->getUsuarioByUsername($usuario);

        // Verifica si el usuario existe y si la contraseña es correcta
        if ($datosUsuario && password_verify($password, $datosUsuario['pass'])) {
            session()->set([
                'usuario' => $datosUsuario['usuario'],
                'logged_in' => true
            ]);
            return redirect()->to('/panel');
        } else {
            // Muestra un mensaje de error si la autenticación falla
            return redirect()->back()->with('error', 'Usuario o contraseña incorrectos');
        }
    }

    /**
     * Muestra la vista del formulario de registro.
     * @return \CodeIgniter\View\View
     */
    public function registro()
    {
        return view('registro');
    }

    /**
     * Procesa los datos del formulario de registro y crea un nuevo usuario.
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function crear()
    {
        $usuario = $this->request->getPost('username');
        $password = $this->request->getPost('pass');

        $usuarioModel = new UsuarioModel();

        // Verifica si el nombre de usuario ya existe
        if ($usuarioModel->getUsuarioByUsername($usuario)) {
            return redirect()->back()->with('error', 'El nombre de usuario ya existe.');
        }

        $data = [
            'usuario' => $usuario,
            'pass' => $password,
        ];

        if ($usuarioModel->crearUsuario($data)) {
            // Registro exitoso, redirige al login con un mensaje
            return redirect()->to('/')->with('success', 'Registro exitoso. Ahora puedes iniciar sesión.');
        } else {
            // Si el registro falla por algún motivo
            return redirect()->back()->with('error', 'No se pudo crear el usuario. Inténtalo de nuevo.');
        }
    }


    public function panel()
    {
        // Redirige al inicio de sesión si el usuario no está autenticado
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }
        return view('panel');
    }

    public function salir()
    {
        // Destruye la sesión y redirige al inicio de sesión
        session()->destroy();
        return redirect()->to('/');
    }
}