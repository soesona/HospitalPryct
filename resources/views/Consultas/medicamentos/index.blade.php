@extends('adminlte::page')

@section('title', 'Medicamentos Asignados')

@section('content_header')
    <h1>Medicamentos Asignados a la Consulta #{{ $codigoConsulta }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Listado de Medicamentos</span>
            <button class="btn btn-success" data-toggle="modal" data-target="#modalAsignar">
                Asignar Medicamento
            </button>
        </div>

        <div class="card-body">
            @if($medicamentosAsignados->isEmpty())
                <p>No hay medicamentos asignados.</p>
            @else
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Cantidad Entregada</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($medicamentosAsignados as $item)
                            <tr>
                                <td>{{ucfirst(strtolower($item->medicamento->nombre))  }}</td>
                                <td>{{ $item->cantidadEntregada }}</td>
                                <td>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <!-- Modal para asignar medicamento -->
    <div class="modal fade" id="modalAsignar" tabindex="-1" role="dialog" aria-labelledby="modalAsignarLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('consultasmedicamentos.store', ['codigoConsulta' => $codigoConsulta]) }}" method="POST">
                @csrf
                <input type="hidden" name="codigoConsulta" value="{{ $codigoConsulta }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalAsignarLabel">Asignar Medicamento</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="codigoMedicamento">Medicamento</label>
                            <select name="codigoMedicamento" class="form-control" required>
                                <option value="">Seleccione...</option>
                                @foreach($todosMedicamentos as $med)
                                    <option value="{{ $med->codigoMedicamento }}">{{ ucfirst(strtolower($med->nombre))  }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="cantidadEntregada">Cantidad Entregada</label>
                            <input type="number" name="cantidadEntregada" class="form-control" min="1" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop
