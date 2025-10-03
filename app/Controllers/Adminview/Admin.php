<?php

namespace App\Controllers\Adminview;

use App\Models\CategoriaModel;
use App\Models\DiscoModel;
use App\Models\UsuarioModel;
use App\Models\MembresiaModel;
use App\Models\PagoModel;
use App\Models\IngresoModel;
use App\Models\ReciboModel;
use CodeIgniter\Controller;

class Admin extends \App\Controllers\BaseController 
{
    public function index()
    {
        return view('admin/index'); // dashboard del admin
    }

    /* =========================
     * CRUD CATEGORÍAS
     * ========================= */
    public function categorias()
    {
        $model = new CategoriaModel();
        $data['categorias'] = $model->findAll();
        return view('admin/categorias/index', $data);
    }

    public function crearCategoria()
    {
        $model = new CategoriaModel();
        if ($this->request->getMethod() === 'post') {
            $model->save([
                'nombre' => $this->request->getPost('nombre'),
                'descripcion' => $this->request->getPost('descripcion')
            ]);
            return redirect()->to('/admin/categorias');
        }
        return view('admin/categorias/crear');
    }

    public function editarCategoria($id)
    {
        $model = new CategoriaModel();
        if ($this->request->getMethod() === 'post') {
            $model->update($id, [
                'nombre' => $this->request->getPost('nombre'),
                'descripcion' => $this->request->getPost('descripcion')
            ]);
            return redirect()->to('/admin/categorias');
        }
        $data['categoria'] = $model->find($id);
        return view('admin/categorias/editar', $data);
    }

    public function eliminarCategoria($id)
    {
        $model = new CategoriaModel();
        $model->delete($id);
        return redirect()->to('/admin/categorias');
    }

    /* =========================
     * CRUD DISCOS
     * ========================= */
    public function discos()
    {
        $model = new DiscoModel();
        $data['discos'] = $model->findAll();
        return view('admin/discos/index', $data);
    }

    public function crearDisco()
    {
        $model = new DiscoModel();
        if ($this->request->getMethod() === 'post') {
            $model->save([
                'titulo'       => $this->request->getPost('titulo'),
                'artista'      => $this->request->getPost('artista'),
                'precio'       => $this->request->getPost('precio'),
                'id_categoria' => $this->request->getPost('id_categoria')
            ]);
            return redirect()->to('/admin/discos');
        }
        return view('admin/discos/crear');
    }

    public function editarDisco($id)
    {
        $model = new DiscoModel();
        if ($this->request->getMethod() === 'post') {
            $model->update($id, [
                'titulo'       => $this->request->getPost('titulo'),
                'artista'      => $this->request->getPost('artista'),
                'precio'       => $this->request->getPost('precio'),
                'id_categoria' => $this->request->getPost('id_categoria')
            ]);
            return redirect()->to('/admin/discos');
        }
        $data['disco'] = $model->find($id);
        return view('admin/discos/editar', $data);
    }

    public function eliminarDisco($id)
    {
        $model = new DiscoModel();
        $model->delete($id);
        return redirect()->to('/admin/discos');
    }

    /* =========================
     * CRUD USUARIOS
     * ========================= */
    public function usuarios()
    {
        $model = new UsuarioModel();
        $data['usuarios'] = $model->findAll();
        return view('admin/usuarios/index', $data);
    }

    public function crearUsuario()
    {
        $model = new UsuarioModel();
        if ($this->request->getMethod() === 'post') {
            $model->save([
                'nombre'   => $this->request->getPost('nombre'),
                'email'    => $this->request->getPost('email'),
                'password' => md5($this->request->getPost('password')),
                'rol'      => $this->request->getPost('rol')
            ]);
            return redirect()->to('/admin/usuarios');
        }
        return view('admin/usuarios/crear');
    }

    public function editarUsuario($id)
    {
        $model = new UsuarioModel();
        if ($this->request->getMethod() === 'post') {
            $updateData = [
                'nombre' => $this->request->getPost('nombre'),
                'email'  => $this->request->getPost('email'),
                'rol'    => $this->request->getPost('rol')
            ];

            if ($this->request->getPost('password')) {
                $updateData['password'] = md5($this->request->getPost('password'));
            }

            $model->update($id, $updateData);
            return redirect()->to('/admin/usuarios');
        }
        $data['usuario'] = $model->find($id);
        return view('admin/usuarios/editar', $data);
    }

    public function eliminarUsuario($id)
    {
        $model = new UsuarioModel();
        $model->delete($id);
        return redirect()->to('/admin/usuarios');
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
            $model->save([
                'nombre'          => $this->request->getPost('nombre'),
                'precio'          => $this->request->getPost('precio'),
                'duracion_meses'  => $this->request->getPost('duracion_meses')
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
                'nombre'          => $this->request->getPost('nombre'),
                'precio'          => $this->request->getPost('precio'),
                'duracion_meses'  => $this->request->getPost('duracion_meses')
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
                'id_disco'      => $this->request->getPost('id_disco'),
                'cantidad'      => $this->request->getPost('cantidad'),
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

