<?php

namespace App\Controllers;

use App\Models\UsuarioModel;

class Login extends BaseController
{
    public function index()
    {
        return view('login');
    }

    public function autenticar()
    {
        $session = session();
        $usuarioModel = new UsuarioModel();

        // Obtener datos del formulario
        $username = $this->request->getPost('usuario');
        $password = $this->request->getPost('pass');

        // Encriptar con MD5
        $passwordHash = md5($password);

        // Buscar usuario en la tabla usuarios
        $usuario = $usuarioModel->where('usuario', $username)
                                ->where('pass', $passwordHash)
                                ->first();

        if ($usuario) {
            // Guardar datos en sesión
            $sessionData = [
                'id'       => $usuario['id'],
                'usuario'  => $usuario['usuario'],
                'rol'      => $usuario['rol'],
                'isLoggedIn' => true,
            ];
            $session->set($sessionData);

            // Redirigir según rol
            if ($usuario['rol'] === 'admin') {
                return redirect()->to('/admin');
            } else {
                return redirect()->to('/usuario');
            }
        } else {
            // Credenciales inválidas
            $session->setFlashdata('error', 'Usuario o contraseña incorrectos');
            return redirect()->to('/login');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}

