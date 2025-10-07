<?php 

namespace App\Controllers\Adminview;

use App\Controllers\BaseController;
use App\Models\PagoModel;
use App\Models\IngresoModel;
use App\Models\ReciboModel;

// NOTA: Se ha quitado el modelo de Membresias, ya que su lógica
// y CRUD completo están ahora en el controlador dedicado 'MembresiaController.php'.

class Admin extends BaseController 
{
    /**
     * Muestra el Dashboard Principal.
     */
    public function index()
    {
        return view('admin/index'); // dashboard del admin
    }

    /* =========================
     * CRUD TIPOS DE PAGO
     * ========================= */
    public function pagos()
    {
        $model = new PagoModel();
        $data['pagos'] = $model->findAll();
        return view('admin/pagos/index', $data);
    }

    public function crearPago()
    {
        $model = new PagoModel();
        if ($this->request->getMethod() === 'post') {
            $model->save([
                'nom_tipo' => $this->request->getPost('nom_tipo')
            ]);
            return redirect()->to('/admin/pagos');
        }
        return view('admin/pagos/crear');
    }

    public function editarPago($id)
    {
        $model = new PagoModel();
        if ($this->request->getMethod() === 'post') {
            $model->update($id, [
                'nom_tipo' => $this->request->getPost('nom_tipo')
            ]);
            return redirect()->to('/admin/pagos');
        }
        $data['pago'] = $model->find($id);
        return view('admin/pagos/editar', $data);
    }

    public function eliminarPago($id)
    {
        $model = new PagoModel();
        $model->delete($id);
        return redirect()->to('/admin/pagos');
    }

    /* =========================
     * INGRESOS
     * ========================= */
    public function ingresos()
    {
        $model = new IngresoModel();
        $data['ingresos'] = $model->findAll();
        return view('admin/ingresos/index', $data);
    }

    public function crearIngreso()
    {
        $model = new IngresoModel();
        if ($this->request->getMethod() === 'post') {
            $model->save([
                'id_disco' => $this->request->getPost('id_disco'),
                'cantidad' => $this->request->getPost('cantidad'),
                'fecha_ingreso' => $this->request->getPost('fecha_ingreso')
            ]);
            return redirect()->to('/admin/ingresos');
        }
        return view('admin/ingresos/crear');
    }

    /* =========================
     * RECIBOS
     * ========================= */
    public function recibos()
    {
        $model = new ReciboModel();
        $data['recibos'] = $model->findAll();
        return view('admin/recibos/index', $data);
    }

    public function detalleRecibo($id)
    {
        $model = new ReciboModel();
        $data['recibo'] = $model->find($id);
        return view('admin/recibos/detalle', $data);
    }
}