document.addEventListener('DOMContentLoaded', function () {
    const inputBuscar = document.getElementById('buscarIdentidad');
    const tabla = document.getElementById('tablaCitas').getElementsByTagName('tbody')[0];
    const filas = tabla.getElementsByTagName('tr');

    inputBuscar.addEventListener('keyup', function () {
        const filtro = inputBuscar.value.toLowerCase();

        for (let i = 0; i < filas.length; i++) {
            const identidad = filas[i].getElementsByTagName('td')[0]?.textContent.toLowerCase();
            const estado = filas[i].getElementsByTagName('td')[6]?.textContent.toLowerCase();

            // Si no hay filtro, mostrar todo
            if (filtro === '') {
                filas[i].style.display = '';
            }
            // Si hay filtro, mostrar solo las citas pendientes que coincidan con identidad
            else if (identidad.includes(filtro) && estado.includes('pendiente')) {
                filas[i].style.display = '';
            } else {
                filas[i].style.display = 'none';
            }
        }
    });
});