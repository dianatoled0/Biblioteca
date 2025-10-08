<?php

namespace App\Controllers\Adminview;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;
use App\Models\TipoMembresiaModel;
use App\Models\NotificacionModel; 

class UsuariosController extends BaseController
{
    protected $usuarioModel;
    protected $membresiaModel;
    protected $notificacionModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
        $this->membresiaModel = new TipoMembresiaModel();
        $this->notificacionModel = new NotificacionModel();
    }

    public function index()
    {
        $filtroRol = $this->request->getGet('filtro_rol');
        $data['usuarios'] = $this->usuarioModel->getUsuariosConFiltro($filtroRol);
        $data['filtroRol'] = $filtroRol; 

        return view('admin/usuarios/index', $data);
    }

    public function crear()
    {
        $data['membresias'] = $this->membresiaModel->findAll();
        return view('admin/usuarios/form', $data);
    }

    public function guardar()
    {
        $data = [
            'usuario' => $this->request->getPost('usuario'),
            'correo' => $this->request->getPost('correo'),
            'pass' => $this->request->getPost('pass'),
            'nombre' => $this->request->getPost('nombre'),
            'apellido' => $this->request->getPost('apellido'),
            'rol' => $this->request->getPost('rol'),
            'id_membresia' => $this->request->getPost('id_membresia'),
            'fecha_nacimiento' => $this->request->getPost('fecha_nacimiento'),
            'fecha_inicio_membresia' => $this->request->getPost('fecha_inicio_membresia'),
            'fecha_fin_membresia' => $this->request->getPost('fecha_fin_membresia'),
        ];

        $pass = $this->request->getPost('pass');
        if (!empty($pass)) {
            $data['pass'] = md5($pass); 
        }

        if ($this->usuarioModel->insert($data)) {
            
            $new_id = $this->usuarioModel->insertID();
            
            // --- NOTIFICACIÓN: NUEVO USUARIO CREADO ---
            $this->notificacionModel->save([
                'tipo_evento'   => 'nuevo_usuario',
                'mensaje'       => 'Nuevo usuario registrado: ' . esc($data['usuario']) . ' (Rol: ' . esc($data['rol']) . ')',
                'referencia_id' => $new_id,
            ]);
            // --------------------------------------------
            
            // CORRECCIÓN: Usar setFlashdata y redirección simple
            session()->setFlashdata('success', 'Usuario creado exitosamente. ID: ' . $new_id);
            return redirect()->to(base_url('admin/usuarios'));
        } else {
            return redirect()->back()->withInput()->with('errors', $this->usuarioModel->errors());
        }
    }

    public function editar($id)
    {
        $data['usuario'] = $this->usuarioModel->find($id);
        if (empty($data['usuario'])) {
            // CORRECCIÓN: Usar setFlashdata y redirección simple
            session()->setFlashdata('error', 'Usuario no encontrado');
            return redirect()->to(base_url('admin/usuarios'));
        }
        $data['membresias'] = $this->membresiaModel->findAll();
        return view('admin/usuarios/form', $data);
    }

    public function actualizar($id)
    {
        $data_actual = $this->usuarioModel->find($id); 
        if (empty($data_actual)) {
            // CORRECCIÓN: Usar setFlashdata y redirección simple
            session()->setFlashdata('error', 'Usuario no encontrado para actualizar.');
            return redirect()->to(base_url('admin/usuarios'));
        }

        $data = [
            'usuario' => $this->request->getPost('usuario'),
            'correo' => $this->request->getPost('correo'),
            'nombre' => $this->request->getPost('nombre'),
            'apellido' => $this->request->getPost('apellido'),
            'rol' => $this->request->getPost('rol'),
            'id_membresia' => $this->request->getPost('id_membresia'),
            'fecha_nacimiento' => $this->request->getPost('fecha_nacimiento'),
            'fecha_inicio_membresia' => $this->request->getPost('fecha_inicio_membresia'),
            'fecha_fin_membresia' => $this->request->getPost('fecha_fin_membresia'),
        ];

        $pass = $this->request->getPost('pass');
        if (!empty($pass)) {
            $data['pass'] = md5($pass); 
        }
        
        // Verificar si la membresía ha cambiado
        $membresia_cambiada = (isset($data_actual['id_membresia']) && $data_actual['id_membresia'] != $data['id_membresia']);


        if ($this->usuarioModel->update($id, $data)) {
            
            // --- NOTIFICACIÓN: USUARIO ACTUALIZADO (Membresía) ---
            if ($membresia_cambiada) {
                $membresia_actual = $this->membresiaModel->find($data['id_membresia']);
                $nombre_membresia = $membresia_actual['nombre'] ?? 'Desconocida';
                
                $mensaje = 'Membresía del usuario ' . esc($data['usuario']) . ' actualizada a: ' . esc($nombre_membresia);
                
                $this->notificacionModel->save([
                    'tipo_evento'   => 'usuario_membresia_act',
                    'mensaje'       => $mensaje,
                    'referencia_id' => $id,
                ]);
            }
            // --------------------------------------------
            
            // CORRECCIÓN: Usar setFlashdata y redirección simple
            session()->setFlashdata('success', 'Usuario actualizado correctamente');
            return redirect()->to(base_url('admin/usuarios'));
        } else {
            return redirect()->back()->withInput()->with('errors', $this->usuarioModel->errors());
        }
    }

    public function eliminar($id)
    {
        $usuario = $this->usuarioModel->find($id);
        if (empty($usuario) || $usuario['usuario'] === 'dtoledo') {
            // CORRECCIÓN: Usar setFlashdata y redirección simple
            session()->setFlashdata('error', 'No se puede eliminar este usuario.');
            return redirect()->to(base_url('admin/usuarios'));
        }
        $this->usuarioModel->delete($id);
        
        // CORRECCIÓN: Usar setFlashdata y redirección simple
        session()->setFlashdata('success', 'Usuario eliminado correctamente');
        return redirect()->to(base_url('admin/usuarios'));
    }
}
