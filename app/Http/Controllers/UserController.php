<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; 
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rule;

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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $validated = $request->validate(
        $this->reglasValidacion(), 
        $this->mensajesValidacion()
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

    return redirect('/usuarios');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

         $validated = $request->validate(
        $this->reglasValidacion($id),
        $this->mensajesValidacion()
    );

    $validated['nombreCompleto'] = mb_strtoupper($validated['nombreCompleto'], 'UTF-8');
    $validated['identidad'] = strtoupper($validated['identidad']);
    $validated['telefono'] = strtoupper($validated['telefono']);
    $validated['email'] = strtolower($validated['email']);

    User::where('codigoUsuario', $id)->update($validated);


    return redirect()->back()->withInput(); 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
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

   private function reglasValidacion($codigoUsuario = null)
{
    return [
   
        'password' => ['nullable', 'string', 'min:6'],
        'nombreCompleto' => ['required', 'string', 'max:60', 'regex:/^[\pL\s]+$/u'],
        'email' => [
            'required', 'email', 'max:255', 'not_regex:/\s/',
            Rule::unique('users', 'email')->ignore($codigoUsuario, 'codigoUsuario')
        ],
        'identidad' => [
            'required', 'digits:13',
            Rule::unique('users', 'identidad')->ignore($codigoUsuario, 'codigoUsuario')
        ],
        'fechaNacimiento' => ['required', 'date'],
        'telefono' => ['required', 'digits:8'],
    ];
}

private function mensajesValidacion()
{
    return [
        'nombreCompleto.regex' => 'El nombre solo debe contener letras y espacios.',
        'email.not_regex' => 'El email no puede contener espacios.',
        'identidad.digits' => 'La identidad debe tener exactamente 13 números.',
        'telefono.digits' => 'El teléfono debe tener exactamente 8 números.',
        'identidad.unique' => 'Esta identidad ya está en uso.',
        'email.unique' => 'Este email ya está registrado.',
        'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
    ];
}

public function exportarPDF()
{
    $usuarios = User::all();
    $pdf = PDF::loadView('reportes.usuariosreportes', compact('usuarios'));
    return $pdf->download('reporte_usuarios.pdf');


}
} 