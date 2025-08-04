@extends('adminlte::page')

@section('title', 'Medicamentos Asignados')

@section('content_header')
    <h1>Medicamentos Asignados a la Consulta #{{ $codigoConsulta }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <a href="{{ route('consultas.index') }}" class="btn btn-secondary">
            ← Volver a Consultas
            </a>
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
                                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalEditar{{$item->codigoEntrega}}">
                                        Editar
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        @foreach($medicamentosAsignados as $item)
                    <!-- Modal para editar medicamento -->
                    <div class="modal fade" id="modalEditar{{ $item->codigoEntrega }}" tabindex="-1" role="dialog" aria-labelledby="modalEditarLabel{{ $item->codigoEntrega }}" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <form action="{{ route('consultasmedicamentos.update', ['codigoConsulta' => $codigoConsulta]) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="codigoEntrega" value="{{ $item->codigoEntrega }}">
                            <input type="hidden" name="codigoConsulta" value="{{ $codigoConsulta }}">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h5 class="modal-title" id="modalEditarLabel{{ $item->codigoEntrega }}">Editar Medicamento Asignado</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                <div class="form-group">
                                    <label>Medicamento</label>
                                    <input type="text" class="form-control" value="{{ ucfirst(strtolower($item->medicamento->nombre)) }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="cantidadEntregada{{ $item->codigoEntrega }}">Cantidad Entregada</label>
                                    <input type="number" name="cantidadEntregada" id="cantidadEntregada{{ $item->codigoEntrega }}" class="form-control" value="{{ $item->cantidadEntregada }}" min="1" required>
                                </div>
                        </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
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
                            <select name="codigoMedicamento" class="form-control" id="codigoMedicamento" required>
                                <option value="">Seleccione...</option>
                                @foreach($todosMedicamentos as $med)
                                    <option value="{{ $med->codigoMedicamento }}" data-stock="{{ $med->stock }}">
                                        {{ ucfirst(strtolower($med->nombre)) }}| restante:{{$med->stock}}
                                    </option>
                                @endforeach
                            </select>
                            <small id="stockInfo" class="form-text text-muted"></small>
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

@section('js')
<script>
    $(document).ready(function () {
        $('#codigoMedicamento').on('change', function () {
            let stock = $(this).find('option:selected').data('stock');
            let text = stock ? `Stock disponible: ${stock}` : '';
            $('#stockInfo').text(text);
        });
    });
</script>
@stop
