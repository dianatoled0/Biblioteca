<?php

/**
 * Helper para funciones específicas de la vista (como manejo de clases 'active').
 * Adaptada para manejar la navegación del área de usuario y de administrador.
 */

if (! function_exists('is_active')) {
    /**
     * Compara un nombre de segmento con el segmento actual de la URL para determinar la clase 'active' en el menú.
     * * @param string $segment_name Nombre del segmento de la ruta a verificar (ej. 'home', 'membresias', 'usuarios').
     * @return string La clase 'active' si coincide, o una cadena vacía.
     */
    function is_active(string $segment_name): string
    {
        $uri = service('uri');
        
        // 1. Determinar el segmento base (usuario o admin)
        $base_segment = $uri->getSegment(1); // 'usuario' o 'admin'
        
        // 2. Determinar qué segmento usar para la comparación
        // Si estamos en /admin/, el controlador es el segmento 2.
        // Si estamos en /usuario/, el controlador es el segmento 2.
        $current_segment = $uri->getSegment(2);
        
        // Lógica de Activación:
        
        // Caso A: Raíz del área de Usuario o Admin
        // Esto cubre: /usuario (segmento 2 vacío) y /admin (segmento 2 vacío)
        if (($segment_name === 'home' && $base_segment === 'usuario' && empty($current_segment)) ||
            ($segment_name === 'dashboard' && $base_segment === 'admin' && empty($current_segment))) {
            return 'active';
        }
        
        // Caso B: Otros enlaces (controladores: /usuario/membresias o /admin/usuarios)
        return ($segment_name === $current_segment) ? 'active' : '';
    }
}