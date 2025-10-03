<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class Auth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // Verifica si el usuario NO está logueado
        if (! $session->get('logged_in')) {
            return redirect()->to('/')->with('error', 'Debes iniciar sesión para acceder.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No se requiere lógica adicional
    }
}
