<?php

namespace App\Controllers;

use App\Models\UsuarioModel;

class Login extends BaseController
{
    public function index()
    {
        $session = session();
        // Si ya está logueado, redirigir según rol
        if ($session->get('logged_in')) {
            return $session->get('rol') === 'admin' 
                ? redirect()->to('/admin') 
                : redirect()->to('/usuario');
        }

        return view('login'); // archivo: app/Views/login.php
    }

    public function autenticar()
    {
        $session = session();
        $usuarioModel = new UsuarioModel();

        $username = $this->request->getPost('usuario');
        $password = $this->request->getPost('pass');
        $passwordHash = md5($password);

        $usuario = $usuarioModel->where('usuario', $username)
                                ->where('pass', $passwordHash)
                                ->first();

        if ($usuario) {
            // Guardar datos en sesión
            $session->set([
                'id'        => $usuario['id'],
                'usuario'   => $usuario['usuario'],
                'rol'       => $usuario['rol'],
                'logged_in' => true,
            ]);

            // Redirigir según rol
            return $usuario['rol'] === 'admin' 
                ? redirect()->to('/admin') 
                : redirect()->to('/usuario');
        } else {
            $session->setFlashdata('error', 'Usuario o contraseña incorrectos');
            return redirect()->to('/'); // login en la raíz
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/'); // login en la raíz
    }
}