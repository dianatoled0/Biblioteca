<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Verifica si el usuario NO está logueado
        if (! session()->get('logged_in')) {
            return redirect()->to('/'); // redirige al login
        }

        // Verifica si el usuario no es admin
        if (session()->get('rol') !== 'admin') {
            return redirect()->to('/panel'); // redirige al panel de usuario
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No se necesita lógica después en este caso
    }
}
