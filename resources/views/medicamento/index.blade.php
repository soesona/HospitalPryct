<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Medicamentos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Listado de Medicamentos</h2>

        @if (session('mensaje'))
            <div class="alert alert-success">
                {{ session('mensaje') }}
            </div>
        @endif

        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalCrear">
            Registrar Nuevo Medicamento
        </button>

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Stock</th>
                    <th>Fecha de Vencimiento</th>
                    <th>Acciones</th>
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
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditar{{ $medicamento->codigoMedicamento }}">
                            Editar
                        </button>
                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalEliminar{{ $medicamento->codigoMedicamento }}">
                            Eliminar
                        </button>
                    </td>
                </tr>

                <!-- Modal Editar -->
                <div class="modal fade" id="modalEditar{{ $medicamento->codigoMedicamento }}" tabindex="-1">
                    <div class="modal-dialog">
                        <form action="/admin/medicamentos/{{ $medicamento->codigoMedicamento }}" method="POST" class="modal-content">
                            @csrf
                            @method('PUT')
                            <div class="modal-header">
                                <h5 class="modal-title">Editar Medicamento</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label>Nombre</label>
                                    <input type="text" name="nombre" class="form-control" value="{{ $medicamento->nombre }}" required>
                                </div>
                                <div class="mb-3">
                                    <label>Descripción</label>
                                    <textarea name="descripcion" class="form-control" required>{{ $medicamento->descripcion }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label>Stock</label>
                                    <input type="number" name="stock" class="form-control" value="{{ $medicamento->stock }}" required>
                                </div>
                                <div class="mb-3">
                                    <label>Fecha de Vencimiento</label>
                                    <input type="date" name="fechaVencimiento" class="form-control" value="{{ $medicamento->fechaVencimiento }}" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary">Guardar Cambios</button>
                                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Modal Eliminar -->
                <div class="modal fade" id="modalEliminar{{ $medicamento->codigoMedicamento }}" tabindex="-1">
                    <div class="modal-dialog">
                        <form action="/admin/medicamentos/{{ $medicamento->codigoMedicamento }}" method="POST" class="modal-content">
                            @csrf
                            @method('DELETE')
                            <div class="modal-header">
                                <h5 class="modal-title">Confirmar Eliminación</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                ¿Estás seguro que deseas eliminar el medicamento <strong>{{ $medicamento->nombre }}</strong>?
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-danger">Sí, eliminar</button>
                                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            </div>
                        </form>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal Crear -->
    <div class="modal fade" id="modalCrear" tabindex="-1">
        <div class="modal-dialog">
            <form action="/admin/medicamentos" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Registrar Medicamento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nombre</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Descripción</label>
                        <textarea name="descripcion" class="form-control" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Stock</label>
                        <input type="number" name="stock" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Fecha de Vencimiento</label>
                        <input type="date" name="fechaVencimiento" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success">Registrar</button>
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
