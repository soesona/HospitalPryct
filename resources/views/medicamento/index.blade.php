@extends('adminlte::page')

@section('title', 'Medicamentos')

@section('content_header')
    <h1 class="mb-3">Listado de Medicamentos</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <button class="btn btn-primary" data-toggle="modal" data-target="#modalCrear">
                <i class="fas fa-plus"></i> Registrar Nuevo Medicamento
            </button>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-hover table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Stock</th>
                        <th>Fecha de Vencimiento</th>
                        <th>Editar</th>
                        <th>Descontinuar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($listaMedicamentos as $medicamento)
                        <tr>
                            <td>{{ $medicamento->codigoMedicamento }}</td>
                            <td>{{ $medicamento->nombre }}</td>
                            <td>{{ $medicamento->descripcion }}</td>
                            <td>{{ $medicamento->stock }}</td>
                            <td>{{ $medicamento->fechaVencimiento }}</td>
                            <td>
                                <button class="btn btn-warning btn-sm ejecutar"
                                    data-toggle="modal" data-target="#mEditarMedicamento"
                                    data-codigomed="{{ $medicamento->codigoMedicamento }}"
                                    data-nombre="{{ $medicamento->nombre }}"
                                    data-descripcion="{{ $medicamento->descripcion }}"
                                    data-stock="{{ $medicamento->stock }}"
                                    data-fechav="{{ $medicamento->fechaVencimiento }}">
                                    <i class="fas fa-edit"></i> Editar
                                </button>
                            </td>
                            <td>
                                <form id="estado-form-{{ $medicamento->codigoMedicamento }}" action="{{ route('medicamento.cambiarEstado', $medicamento->codigoMedicamento) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <button type="button" 
                                    class="btn btn-sm {{ $medicamento->activo ? 'btn-danger' : 'btn-success' }}" 
                                    onclick="confirmarCambioEstado({{ $medicamento->codigoMedicamento }}, '{{ $medicamento->activo ? 'desactivar' : 'activar' }}')">
                                    <i class="fas {{ $medicamento->activo ? 'fa-user-slash' : 'fa-user-check' }}"></i>
                                    {{ $medicamento->activo ? 'Descontinuar' : 'Reactivar' }}
                                </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Crear -->
    <div class="modal fade" id="modalCrear" tabindex="-1">
        <div class="modal-dialog">
            <form action="/admin/medicamentos" method="POST" class="modal-content">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Registrar Medicamento</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Descripción</label>
                        <textarea name="descripcion" class="form-control" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Stock</label>
                        <input type="number" name="stock" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Fecha de Vencimiento</label>
                        <input type="date" name="fechaVencimiento" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success">Registrar</button>
                    <button class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Editar -->
    <div class="modal fade" id="mEditarMedicamento" tabindex="-1">
        <div class="modal-dialog">
            <form action="/admin/medicamentos" method="POST" class="modal-content" id="miFormU">
                @csrf
                @method('PUT')
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Editar Medicamento</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Código</label>
                        <input type="text" id="codigoMedicamentou" name="codigoMedicamentou" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" id="nombreu" name="nombreu" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Descripción</label>
                        <input type="text" id="descripcionu" name="descripcionu" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Stock</label>
                        <input type="number" id="stocku" name="stocku" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Fecha de Vencimiento</label>
                        <input type="date" id="fechaVencimientou" name="fechaVencimientou" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" type="submit">Guardar</button>
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    

@stop

@section('js')
<script>
    document.querySelectorAll('.ejecutar').forEach(btn => {
        btn.addEventListener('click', function () {
            document.getElementById('codigoMedicamentou').value = this.dataset.codigomed;
            document.getElementById('nombreu').value = this.dataset.nombre;
            document.getElementById('descripcionu').value = this.dataset.descripcion;
            document.getElementById('stocku').value = this.dataset.stock;
            document.getElementById('fechaVencimientou').value = this.dataset.fechav;
        });
    });
</script>

<script>
    function confirmarCambioEstado(codigo, accion) {
        if (confirm(`¿Estás seguro que deseas ${accion} este medicamento?`)) {
            document.getElementById(`estado-form-${codigo}`).submit();
        }
    }
</script>

@stop
