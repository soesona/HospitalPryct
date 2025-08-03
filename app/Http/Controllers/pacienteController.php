<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\User;
use Illuminate\Http\Request;
use PDF;

class pacienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $datosPacientes = Paciente::with('usuario')->get();
        return view('pacientes.index')->with('listaPacientes', $datosPacientes);
    }

    /**
     * Show the form for creating a new resource.
     */
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
    }
 public function exportarPDF()
    {
        $pacientes = Paciente::with('usuario')->get();
        $pdf = \PDF::loadView('reportes.pacientesreportes', compact('pacientes'));
        return $pdf->download('reporte_pacientes.pdf');
    }

   
  
}
