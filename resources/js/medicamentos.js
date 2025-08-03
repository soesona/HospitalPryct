if (document.getElementById("tablaMedicamentos") && typeof simpleDatatables.DataTable !== 'undefined') {
    new simpleDatatables.DataTable("#tablaMedicamentos", {
        paging: true,
        perPage: 5,
        perPageSelect: [5, 10, 15, 20, 25],
        sortable: false,
        searchable: true,
        labels: {
            placeholder: "Buscar medicamento...",
            perPage: "Registros por página",
            noRows: "No se encontraron medicamentos",
            info: "Mostrando {start} a {end} de {rows} medicamentos",
            noResults: "No hay resultados que coincidan con su búsqueda"
        }
    });
}
