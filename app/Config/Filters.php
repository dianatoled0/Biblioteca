<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Filters extends BaseConfig
{
    /**
     * Configuración de alias para los filtros.
     *
     * @var array<string, class-string>
     */
    public $aliases = [
        'csrf'   => \CodeIgniter\Filters\CSRF::class,
        'honeypot' => \CodeIgniter\Filters\Honeypot::class,

        // Tus filtros personalizados
        'auth'  => \App\Filters\Auth::class,
        'admin' => \App\Filters\AdminFilter::class,
    ];

    /**
     * Filtros globales que se ejecutan antes/después de cada petición.
     *
     * Nota: No estoy forzando 'auth' globalmente para evitar bloquear rutas públicas (login, assets, etc.).
     *
     * @var array<string, array<string>>
     */
    public $globals = [
        'before' => [
            // 'honeypot',
            // 'csrf', // Habilítalo si quieres protección CSRF en formularios
        ],
        'after'  => [
            //'toolbar',
            // 'honeypot',
        ],
    ];

    /**
     * Filtros que se aplican por método HTTP (opcional).
     *
     * @var array<string, array<string>>
     */
    public $methods = [
        // 'post' => ['csrf'],
    ];

    /**
     * Filtros asignados por ruta.
     *
     * Aquí puedes definir filtros para grupos de rutas o rutas individuales.
     *
     * @var array<string, array<string>>
     */
    public $filters = [
        // Ejemplo: aplicar 'auth' a todas las rutas que empiecen con 'panel' o 'admin'
        'auth' => [
            'before' => [
                'panel/*',
                'panel',
            ],
        ],
        'admin' => [
            'before' => [
                'admin/*',
                'admin',
            ],
        ],
    ];
}

