<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // Verifica si el usuario NO está logueado
        if (! $session->get('logged_in')) {
            return redirect()->to('/')->with('error', 'Debes iniciar sesión.');
        }

        // Verifica si el usuario NO es admin
        if ($session->get('rol') !== 'admin') {
            return redirect()->to('/usuario')->with('error', 'Acceso restringido solo para administradores.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No se requiere lógica adicional
    }
}
