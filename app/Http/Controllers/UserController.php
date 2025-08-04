<?php
/**
 * Class UserController
 *
 * Controlador para gestionar usuarios en la aplicación.
 * Proporciona funcionalidades para listar, crear, actualizar, cambiar estado,
 * asignar roles y exportar usuarios a PDF.
 *
 * Métodos:
 * - index(): Muestra una lista de usuarios y roles.
 * - store(Request $request): Almacena un nuevo usuario y le asigna el rol de 'Paciente'.
 * - update(Request $request, string $id): Actualiza la información de un usuario existente.
 * - cambiarEstado($id): Cambia el estado activo/inactivo de un usuario.
 * - asignarRol(Request $request, $id): Asigna uno o más roles a un usuario.
 * - exportarPDF(): Exporta la lista de usuarios a un archivo PDF.
 *
 * @package App\Http\Controllers
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; 
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rule;
use App\Rules\UserRules;


use PDF; 

class UserController extends Controller
{

    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usuarios = User::all(); 
        $roles = Role::all(); 
        return view('usuarios.index', compact('usuarios', 'roles')); 
    }

   

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $validated = $request->validate(
    UserRules::store(),
    UserRules::messages()
);

    $usuario = new User();
    $usuario->nombreCompleto = mb_strtoupper($validated['nombreCompleto'], 'UTF-8');
    $usuario->email = strtolower($validated['email']);
    $usuario->identidad = strtoupper($validated['identidad']);
    $usuario->fechaNacimiento = $validated['fechaNacimiento'];
    $usuario->telefono = strtoupper($validated['telefono']);
    $usuario->password = bcrypt($validated['password']);
    $usuario->save();

     $usuario->assignRole('Paciente');

    
    \App\Models\Paciente::create([
        'codigoUsuario' => $usuario->codigoUsuario,
    ]);

    return redirect('/usuarios')->with('success', 'Usuario creado correctamente.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $validated = $request->validate(
    UserRules::update($id),
    UserRules::messages()
);

    $validated['nombreCompleto'] = mb_strtoupper($validated['nombreCompleto'], 'UTF-8');
    $validated['identidad'] = strtoupper($validated['identidad']);
    $validated['telefono'] = strtoupper($validated['telefono']);
    $validated['email'] = strtolower($validated['email']);

    User::where('codigoUsuario', $id)->update($validated);


    return redirect()->back()->withInput(); 
    }

   

    public function cambiarEstado($id)
{
    $usuarios = User::findOrFail($id);
    $usuarios->is_active = !$usuarios->is_active;
    $usuarios->save();

    return redirect()->route('usuarios.index')->with('success', 'Estado del usuario actualizado correctamente.');
}

    public function asignarRol(Request $request, $id)
    {
         $request->validate([
        'roles' => 'required|array|min:1',
    ], [
        'roles.required' => 'Debes seleccionar al menos un rol.',
        'roles.min' => 'Debes seleccionar al menos un rol.',
    ]);

         $usuario = User::find($id);
         $usuario->syncRoles($request->roles); 
        
        
        return redirect('/usuarios');
    }   

   

public function exportarPDF()
{
    $usuarios = User::all();
    $pdf = PDF::loadView('reportes.usuariosreportes', compact('usuarios'));
    return $pdf->download('reporte_usuarios.pdf');


}
} 