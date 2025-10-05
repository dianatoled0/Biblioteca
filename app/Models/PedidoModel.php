<?php

namespace App\Models;

use CodeIgniter\Model;

class ReciboModel extends Model
{
    protected $table            = 'recibos';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'id_usuario',
        'fecha',
        'total'
    ];

    protected $validationRules = [
        'id_usuario' => 'required|integer',
        'fecha'      => 'required|valid_date',
        'total'      => 'required|decimal'
    ];

    protected $validationMessages = [
        'id_usuario' => [
            'required' => 'Debe seleccionar un usuario.',
            'integer'  => 'El usuario no es válido.'
        ],
        'fecha' => [
            'required'   => 'Debe ingresar la fecha del recibo.',
            'valid_date' => 'La fecha no es válida.'
        ],
        'total' => [
            'required' => 'Debe ingresar el total del recibo.',
            'decimal'  => 'El total debe ser un número decimal válido.'
        ]
    ];

    protected $skipValidation = false;

    /**
     * Obtener recibos junto con información del usuario
     */
    public function getRecibosConUsuarios()
    {
        return $this->select('recibos.*, usuarios.username, usuarios.email')
                    ->join('usuarios', 'usuarios.id = recibos.id_usuario')
                    ->orderBy('recibos.fecha', 'DESC')
                    ->findAll();
    }

    /**
     * Obtener un recibo con su detalle
     */
    public function getReciboConDetalle($id)
    {
        $db = \Config\Database::connect();

        // Recibo principal
        $recibo = $this->select('recibos.*, usuarios.username, usuarios.email')
                       ->join('usuarios', 'usuarios.id = recibos.id_usuario')
                       ->where('recibos.id', $id)
                       ->first();

        if (!$recibo) {
            return null;
        }

        // Detalles del recibo
        $builder = $db->table('detalle_recibos');
        $builder->select('detalle_recibos.*, discos.titulo, discos.artista, discos.precio');
        $builder->join('discos', 'discos.id = detalle_recibos.id_disco');
        $builder->where('detalle_recibos.id_recibo', $id);

        $recibo['detalles'] = $builder->get()->getResultArray();

        return $recibo;
    }
}

