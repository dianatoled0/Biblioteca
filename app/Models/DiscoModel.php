<?php

namespace App\Models;

use CodeIgniter\Model;

class DiscoModel extends Model
{
    protected $table = 'discos';          // Nombre de la tabla
    protected $primaryKey = 'id';         // Llave primaria

    // Campos permitidos para inserción/actualización
    protected $allowedFields = [
        'id_categoria',
        'titulo',
        'artista',
        'precio_venta',
        'stock'
    ];

    // Opcional: validaciones internas
    protected $validationRules = [
        'id_categoria'  => 'required|integer',
        'titulo'        => 'required|min_length[2]|max_length[100]',
        'artista'       => 'required|min_length[2]|max_length[100]',
        'precio_venta'  => 'required|decimal',
        'stock'         => 'required|integer'
    ];

    protected $validationMessages = [
        'titulo' => [
            'required' => 'El título es obligatorio',
            'min_length' => 'El título debe tener al menos 2 caracteres'
        ],
        'artista' => [
            'required' => 'El artista es obligatorio',
        ],
        'precio_venta' => [
            'required' => 'El precio es obligatorio',
            'decimal'  => 'El precio debe ser un número válido'
        ],
        'stock' => [
            'required' => 'El stock es obligatorio',
            'integer'  => 'El stock debe ser un número entero'
        ]
    ];
}
