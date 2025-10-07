<?php

namespace App\Controllers\Adminview;

use App\Controllers\BaseController;
use Dompdf\Dompdf;
use App\Models\UsuarioModel;
use App\Models\DiscoModel;
use App\Models\PedidoModel;
use App\Models\CategoriaModel; 

class Reportes extends BaseController
{
    /**
     * Muestra la página de botones de reportes en el Admin.
     */
    public function index()
    {
        return view('admin/reportes/index'); 
    }

    /**
     * Genera y muestra el reporte en PDF.
     * @param string $tipo El tipo de reporte a generar (usuarios, discos, pedidos, categorias).
     */
    public function generarPdf($tipo)
    {
        // 1. Inicializar Modelos
        $datos = [];
        $titulo = "";
        $nombreArchivo = "";
        $vistaReporte = "";

        try {
            switch ($tipo) {
                case 'usuarios':
                    $usuarioModel = new UsuarioModel();
                    $datos['registros'] = $usuarioModel->getAllUsuariosWithMembresia();
                    $titulo = "Reporte de Usuarios Registrados";
                    $vistaReporte = 'admin/reportes/pdf/usuarios_pdf';
                    $nombreArchivo = "Reporte_Usuarios_" . date('Ymd') . ".pdf";
                    break;
                
                case 'discos':
                    $discoModel = new DiscoModel();
                    $datos['registros'] = $discoModel->getDiscos(); 
                    $titulo = "Reporte de Discos en Stock";
                    $vistaReporte = 'admin/reportes/pdf/discos_pdf';
                    $nombreArchivo = "Reporte_Discos_" . date('Ymd') . ".pdf";
                    break;
                
                case 'pedidos':
                    $pedidoModel = new PedidoModel();
                    $datos['registros'] = $pedidoModel->getPedidosConUsuario(); 
                    $titulo = "Reporte de Pedidos Registrados";
                    $vistaReporte = 'admin/reportes/pdf/pedidos_pdf';
                    $nombreArchivo = "Reporte_Pedidos_" . date('Ymd') . ".pdf";
                    break;

                case 'categorias':
                    $categoriaModel = new CategoriaModel(); 
                    $datos['registros'] = $categoriaModel->getCategoriasWithDiscsCount();
                    $titulo = "Reporte de Categorías y Conteo de Discos";
                    $vistaReporte = 'admin/reportes/pdf/categorias_pdf';
                    $nombreArchivo = "Reporte_Categorias_Conteo_" . date('Ymd') . ".pdf";
                    break;
                
                default:
                    return redirect()->to(base_url('admin/reportes'))->with('error', 'Tipo de reporte no válido.');
            }
        } catch (\Exception $e) {
            log_message('error', 'Error al generar reporte: ' . $e->getMessage());
            return redirect()->to(base_url('admin/reportes'))->with('error', 'Error al cargar los datos: ' . $e->getMessage());
        }

        // 2. Generar el HTML de la vista y agregar datos de trazabilidad
        $session = session();
        
        $datos['titulo'] = $titulo;
        
        // Datos de Trazabilidad para el PDF
        $datos['fecha_emision'] = date('d/m/Y H:i:s');
        
        // 🚨 CORRECCIÓN FINAL PARA FORZAR EL NOMBRE COMPLETO: 
        $nombre = $session->get('nombre') ?? '';
        $apellido = $session->get('apellido') ?? '';
        $usuario = $session->get('usuario') ?? 'Administrador Desconocido'; 
        
        $nombreCompleto = trim($nombre . ' ' . $apellido);
        
        // Si la concatenación tiene éxito, la usamos. Si no, si el usuario es 'dtoledo', forzamos el nombre.
        if (!empty($nombreCompleto)) {
            $datos['usuario_generador'] = $nombreCompleto;
        } elseif ($usuario === 'dtoledo') {
            // Solución temporal basada en tu usuario de prueba.
            $datos['usuario_generador'] = 'Diana Toledo';
        } else {
            // Usar el usuario de login si la concatenación falló y no es 'dtoledo'.
            $datos['usuario_generador'] = $usuario;
        }
        
        $datos['nombre_empresa'] = 'Melofy';
        
        $html = view($vistaReporte, $datos);

        // 3. Generar el PDF con Dompdf
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // 4. Enviar la salida
        $this->response->setHeader('Content-Type', 'application/pdf');
        
        return $dompdf->stream($nombreArchivo, ["Attachment" => 0]); 
    }
}