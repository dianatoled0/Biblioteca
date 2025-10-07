<?php

namespace App\Controllers;

use App\Models\UsuarioModel;

class Login extends BaseController
{
    public function index()
    {
        $session = session();
        if ($session->get('logged_in')) {
            return $session->get('rol') === 'admin' 
                ? redirect()->to('/admin') 
                : redirect()->to('/usuario');
        }

        return view('login');
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
            
            // CONCATENAR 'nombre' y 'apellido' de la base de datos
            $nombreCompleto = trim($usuario['nombre'] . ' ' . $usuario['apellido']);
            
            // Usar el username como último recurso
            $nombreCompleto = !empty($nombreCompleto) ? $nombreCompleto : $usuario['usuario']; 
            
            // Establecer la sesión
            $session->set([
                'id'                => $usuario['id'],
                'usuario'           => $usuario['usuario'], 
                'rol'               => $usuario['rol'],
                'logged_in'         => true,
                'nombre_completo'   => $nombreCompleto, // CLAVE: Nombre completo para el saludo
            ]);

            return $usuario['rol'] === 'admin' 
                ? redirect()->to('/admin') 
                : redirect()->to('/usuario');
        } else {
            $session->setFlashdata('error', 'Usuario o contraseña incorrectos');
            return redirect()->to('/');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }
}