<?php namespace App\Controllers\Adminview;

use App\Controllers\BaseController;
use App\Models\MembresiaModel;
use App\Models\PagoModel;
use App\Models\IngresoModel;
use App\Models\ReciboModel;

// NOTA IMPORTANTE: Hemos quitado CategoriaModel, DiscoModel, y UsuarioModel
// porque sus CRUDs ya están en controladores dedicados.

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
     * CRUD MEMBRESÍAS
     * ========================= */
    public function membresias()
    {
        $model = new MembresiaModel();
        $data['membresias'] = $model->findAll();
        return view('admin/membresias/index', $data);
    }

    public function crearMembresia()
    {
        $model = new MembresiaModel();
        if ($this->request->getMethod() === 'post') {
            // Asegúrate de usar $model->save() con la data de la DB
            $model->save([
                'nombre' => $this->request->getPost('nombre'),
                'precio' => $this->request->getPost('precio'),
                'duracion_meses' => $this->request->getPost('duracion_meses')
            ]);
            return redirect()->to('/admin/membresias');
        }
        return view('admin/membresias/crear');
    }

    public function editarMembresia($id)
    {
        $model = new MembresiaModel();
        if ($this->request->getMethod() === 'post') {
            $model->update($id, [
                'nombre' => $this->request->getPost('nombre'),
                'precio' => $this->request->getPost('precio'),
                'duracion_meses' => $this->request->getPost('duracion_meses')
            ]);
            return redirect()->to('/admin/membresias');
        }
        $data['membresia'] = $model->find($id);
        return view('admin/membresias/editar', $data);
    }

    public function eliminarMembresia($id)
    {
        $model = new MembresiaModel();
        $model->delete($id);
        return redirect()->to('/admin/membresias');
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
