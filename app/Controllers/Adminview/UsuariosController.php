<?php namespace App\Controllers\Adminview;

use App\Controllers\BaseController; 
use App\Models\UsuarioModel;
use CodeIgniter\I18n\Time; // Se asegura de usar la clase Time para manejo de fechas

class UsuariosController extends BaseController
{
    protected $usuarioModel;

    public function __construct()
    {
        // Inicializa el modelo de usuarios
        $this->usuarioModel = new UsuarioModel();
    }

    public function index()
    {
        $data['usuarios'] = $this->usuarioModel->findAll();
        return view('admin/usuarios/index', $data);
    }

    public function crear()
    {
        // 1. Procesa el formulario (POST)
        if ($this->request->getMethod() === 'post') {
            
            // --- Lógica de cálculo de fechas y valores por defecto ---
            $fechaInicio = $this->request->getPost('fecha_inicio_membresia') ?: Time::now()->toDateString();
            $idMembresia = $this->request->getPost('id_membresia') ?? 1; 
            
            // Calculamos fecha fin (asumiendo 4 meses por defecto si está vacío)
            $fechaFin = $this->request->getPost('fecha_fin_membresia');
            if (empty($fechaFin)) {
                $fechaFin = Time::parse($fechaInicio)->addMonths(4)->toDateString();
            }

            // Recolección de datos
            $data = [
                'usuario' => $this->request->getPost('usuario'),
                'pass' => $this->request->getPost('pass'), 
                'rol' => $this->request->getPost('rol'),
                'nombre' => $this->request->getPost('nombre'),
                'apellido' => $this->request->getPost('apellido'),
                'correo' => $this->request->getPost('correo'),
                'id_membresia' => $idMembresia, 
                'fecha_nacimiento' => $this->request->getPost('fecha_nacimiento') ?: null,
                'fecha_inicio_membresia' => $fechaInicio,
                'fecha_fin_membresia' => $fechaFin,
            ];

            // 2. Intenta insertar los datos con manejo de excepciones (TRY-CATCH)
            try {
                if ($this->usuarioModel->insert($data)) {
                    return redirect()->to('/admin/usuarios')->with('success', 'Usuario creado correctamente.');
                } else {
                    // Fallo de Validación (si el modelo devuelve false)
                    $errors = $this->usuarioModel->errors();
                    return redirect()->back()->withInput()->with('errors', $errors);
                }
            } catch (\CodeIgniter\Database\Exceptions\DatabaseException $e) {
                // CAPTURA DE ERROR CRÍTICO DE BASE DE DATOS (FK, columna faltante, etc.)
                $errors = ['db_error' => 'Error Crítico de Base de Datos: ' . $e->getMessage()];
                return redirect()->back()->withInput()->with('errors', $errors);
            } catch (\Exception $e) {
                // CAPTURA DE CUALQUIER OTRA EXCEPCIÓN
                $errors = ['general_error' => 'Error Inesperado: ' . $e->getMessage()];
                return redirect()->back()->withInput()->with('errors', $errors);
            }
        }
        
        // 3. Muestra el formulario (GET)
        return view('admin/usuarios/form');
    }

    // El resto de métodos (editar, eliminar) se mantienen igual

    public function editar($id)
    {
        $usuario = $this->usuarioModel->find($id);

        if (!$usuario) {
            return redirect()->to('/admin/usuarios')->with('error', 'Usuario no encontrado.');
        }

        if ($this->request->getMethod() === 'post') {
            
            $data = [
                'id' => $id, 
                'usuario' => $this->request->getPost('usuario'),
                'rol' => $this->request->getPost('rol'),
                'nombre' => $this->request->getPost('nombre'),
                'apellido' => $this->request->getPost('apellido'),
                'correo' => $this->request->getPost('correo'),
                'id_membresia' => $this->request->getPost('id_membresia') ?? 1,
                'fecha_nacimiento' => $this->request->getPost('fecha_nacimiento') ?: null,
                'fecha_inicio_membresia' => $this->request->getPost('fecha_inicio_membresia'),
                'fecha_fin_membresia' => $this->request->getPost('fecha_fin_membresia'),
            ];
            
            $newPass = $this->request->getPost('pass');
            if (!empty($newPass)) {
                $data['pass'] = $newPass;
            }
            
            if ($this->usuarioModel->save($data)) {
                return redirect()->to('/admin/usuarios')->with('success', 'Usuario actualizado correctamente.');
            } else {
                return redirect()->back()->withInput()->with('errors', $this->usuarioModel->errors());
            }
        }

        return view('admin/usuarios/form', ['usuario' => $usuario]);
    }

    public function eliminar($id)
    {
        $this->usuarioModel->delete($id);
        return redirect()->to('/admin/usuarios')->with('success', 'Usuario eliminado correctamente.');
    }
}