<?php

namespace App\Controllers\Userview;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;
use App\Models\TipoMembresiaModel;

class MembresiaController extends BaseController
{
    protected $usuarioModel;
    protected $membresiaModel;
    protected $session;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
        $this->membresiaModel = new TipoMembresiaModel();
        $this->session = \Config\Services::session();
    }

    /**
     * Muestra la página de tipos de membresía.
     */
    public function index()
    {
        // ASUMIMOS que el ID del usuario está guardado en la sesión como 'id_usuario'
        $idUsuario = $this->session->get('id_usuario'); 
        
        if (!$idUsuario) {
            return redirect()->to(base_url('login'))->with('error', 'Debes iniciar sesión para ver tus membresías.');
        }

        // Obtener datos del usuario, incluyendo el nombre de la membresía actual
        $usuario = $this->usuarioModel
                        ->select('usuarios.*, tipos_membresia.nombre AS nombre_membresia')
                        ->join('tipos_membresia', 'tipos_membresia.id = usuarios.id_membresia', 'left')
                        ->find($idUsuario);

        // Obtener todos los tipos de membresía
        $tiposMembresia = $this->membresiaModel->getTiposMembresiaParaVista();

        $data = [
            'usuario' => $usuario,
            'tipos_membresia' => $tiposMembresia,
            'title' => 'Tipos de Membresía - Melofy',
            // Pasa los beneficios procesados a la vista para JavaScript
            'beneficios' => $this->obtenerBeneficiosArray($tiposMembresia) 
        ];

        return view('user/membresias_view', $data);
    }

    /**
     * Procesa la compra de una nueva membresía (Lógica AJAX).
     */
    public function comprar()
    {
        if ($this->request->getMethod() !== 'post') {
            return $this->response->setStatusCode(405)->setJSON(['success' => false, 'message' => 'Método no permitido.']);
        }
        
        $idUsuario = $this->session->get('id_usuario'); 
        $idMembresiaNueva = $this->request->getPost('id_membresia');

        if (!$idUsuario || !$idMembresiaNueva) {
            return $this->response->setJSON(['success' => false, 'message' => 'Datos de usuario o membresía inválidos.']);
        }

        $membresiaNueva = $this->membresiaModel->find($idMembresiaNueva);

        if (!$membresiaNueva) {
            return $this->response->setJSON(['success' => false, 'message' => 'Tipo de membresía no encontrada.']);
        }

        // SIMULACIÓN DE PAGO (INTEGRACIÓN REAL AQUÍ)
        $pagoExitoso = true; 

        if ($pagoExitoso) {
            // Calcular nuevas fechas
            $fechaInicio = date('Y-m-d');
            // Calcula la fecha de fin sumando la duración en meses
            $fechaFin = date('Y-m-d', strtotime("+$membresiaNueva[duracion_meses] months", strtotime($fechaInicio)));

            $dataUpdate = [
                'id_membresia' => $idMembresiaNueva,
                'fecha_inicio_membresia' => $fechaInicio,
                'fecha_fin_membresia' => $fechaFin,
            ];

            // Actualizar la base de datos
            if ($this->usuarioModel->update($idUsuario, $dataUpdate)) {
                
                return $this->response->setJSON([
                    'success' => true, 
                    'message' => "¡Felicidades! Tu membresía **$membresiaNueva[nombre]** ha sido activada.",
                ]);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Error al actualizar la membresía en la base de datos.']);
            }

        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'El pago fue rechazado. Por favor, intenta de nuevo.']);
        }
    }
    
    /**
     * Función auxiliar para generar el array de beneficios en HTML,
     * incluyendo todas las características principales de la tabla.
     * Esta versión está actualizada con el texto fijo de envío y tiempos de entrega.
     */
    private function obtenerBeneficiosArray(array $membresias): array
    {
        $beneficios = [];
        foreach ($membresias as $m) {
            
            // Determinar si es envío completamente gratis (costo fijo 0.00 y monto mínimo 0.00)
            // Esto aplica a Estándar y Premium en la tabla de características.
            $envioGratisAbsoluto = ($m['costo_envio_fijo'] == 0.00 && $m['envio_gratis_monto_minimo'] == 0.00);
            
            $beneficiosHtml = "<ul>";
            
            // 1. Descuento
            $descuentoPorcentaje = number_format($m['descuento_porcentaje'] * 100);
            $beneficiosHtml .= "<li>Descuento: $descuentoPorcentaje% en toda la tienda.</li>";

            // 2. Duración (Ya estaba, pero la incluimos para completitud)
            $beneficiosHtml .= "<li>Duración de $m[duracion_meses] mes(es).</li>";

            // 3. Envío y costo (Dinámico)
            if ($envioGratisAbsoluto) {
                // Aplica a Estándar y Premium
                $beneficiosHtml .= "<li>Envío gratuito en todos tus pedidos.</li>";
            } else {
                // Aplica a Básica
                $montoMinimo = number_format($m['envio_gratis_monto_minimo'], 2);
                $costoFijo = number_format($m['costo_envio_fijo'], 2);
                
                $beneficiosHtml .= "<li>Costo de envío fijo: Q $costoFijo.</li>";
                $beneficiosHtml .= "<li>Envío gratuito en compras mayores a Q $montoMinimo.</li>";
            }
            
            // 4. Beneficios Específicos/Adicionales (Fijos según ID)
            
            // BÁSICA (ID 1)
            if ($m['id'] == 1) {
                 $beneficiosHtml .= "<li>Tiempo de entrega: hasta 1 mes.</li>";
            } 
            // ESTÁNDAR (ID 2)
            elseif ($m['id'] == 2) {
                 $beneficiosHtml .= "<li>Tiempo de entrega: entre 15 y 20 días.</li>";
            } 
            // PREMIUM (ID 3)
            elseif ($m['id'] == 3) {
                 // Nota: Incluimos el tiempo de entrega de Estándar/Premium si es necesario, pero 
                 // nos enfocamos en los beneficios especiales de Premium según la tabla.
                 $beneficiosHtml .= "<li>Tiempo de entrega: entre 15 y 20 días.</li>"; // Si aplica
                 $beneficiosHtml .= "<li>Beneficios adicionales: Acceso prioritario a preventas.</li>";
                 $beneficiosHtml .= "<li>Entrega prioritaria de productos.</li>";
            }

            $beneficiosHtml .= "</ul>";

            $beneficios[$m['id']] = [
                'nombre' => $m['nombre'],
                'precio' => number_format($m['precio'], 2),
                'duracion' => $m['duracion_meses'],
                'html' => $beneficiosHtml
            ];
        }
        return $beneficios;
    }
}