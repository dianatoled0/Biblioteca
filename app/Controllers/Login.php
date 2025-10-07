<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use CodeIgniter\Controller;

class Login extends BaseController
{
    /**
     * Muestra la vista de login o redirige si el usuario ya está autenticado.
     */
    public function index()
    {
        $session = session();
        
        // Si ya está logueado, redirigir según rol
        if ($session->get('logged_in')) {
            return $session->get('rol') === 'admin' 
                ? redirect()->to(base_url('admin')) 
                : redirect()->to(base_url('usuario'));
        }

        // Si no está logueado, muestra la vista de login
        return view('login'); // archivo: app/Views/login.php
    }

    /**
     * Procesa los datos del formulario de login.
     */
    public function autenticar()
    {
        $session = session();
        $usuarioModel = new UsuarioModel();

        // 1. Obtener credenciales
        $username = $this->request->getPost('usuario');
        $password = $this->request->getPost('pass');
        // Tu código usa MD5, mantenemos la consistencia:
        $passwordHash = md5($password); 

        // 2. Buscar usuario
        $usuario = $usuarioModel->where('usuario', $username)
                                ->where('pass', $passwordHash)
                                ->first();

        if ($usuario) {
            
            // 3. Crear el Nombre Completo
            // Se usa trim para evitar espacios extra si el nombre o apellido están vacíos.
            $nombreCompleto = trim(($usuario['nombre'] ?? '') . ' ' . ($usuario['apellido'] ?? ''));
            
            // Fallback: Si el nombre completo queda vacío, usar el username
            if (empty($nombreCompleto)) {
                $nombreCompleto = $usuario['usuario'];
            }
            
            // 4. Guardar datos en sesión
            $session->set([
                'id'                => $usuario['id'],
                'usuario'           => $usuario['usuario'],
                'rol'               => $usuario['rol'],
                'logged_in'         => true,
                'nombre_completo'   => $nombreCompleto, 
            ]);

            // 5. Redirigir según rol
            return $usuario['rol'] === 'admin' 
                ? redirect()->to(base_url('admin')) 
                : redirect()->to(base_url('usuario'));
        } else {
            // 6. Manejar error
            $session->setFlashdata('error', 'Usuario o contraseña incorrectos');
            return redirect()->to(base_url('/')); // Redirige de vuelta al formulario de login
        }
    }

    /**
     * Cierra la sesión y redirige a la página de login.
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('/')); // Redirige a la raíz (login)
    }
}