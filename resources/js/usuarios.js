import Swal from 'sweetalert2';

window.confirmarCambioEstado = function(id, accion) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: `¿Deseas ${accion} este usuario?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, confirmar',
        cancelButtonText: 'Cancelar',
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(`estado-form-${id}`).submit();
        }
    });
};