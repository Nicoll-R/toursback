<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Tours</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        h1 {
            color: #333;
        }
        .search-bar {
            margin-bottom: 20px;
            display: flex;
            justify-content: flex-end;
        }
        .search-bar input {
            padding: 8px;
            width: 200px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #3498db;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .action-btns {
            display: flex;
            justify-content: space-between;
        }
        .action-btns button {
            background-color: #e74c3c;
            color: white;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
            border-radius: 3px;
        }
        .action-btns button:hover {
            background-color: #c0392b;
        }
        .no-tours {
            text-align: center;
            font-size: 18px;
            color: #e74c3c;
        }
    </style>
</head>
<body>

    <h1>Gestión de Tours</h1>

    <!-- Barra de búsqueda -->
    <div class="search-bar">
        <input type="text" id="search-input" placeholder="Buscar tours..." oninput="filterTours()">
    </div>

    <!-- Tabla para mostrar los tours -->
    <div id="tour-list">
        <!-- La tabla de tours se cargará aquí dinámicamente -->
    </div>

    <script>
        // URL de la API
        const apiUrl = 'http://localhost:8000/api/tours'; // Cambiar según sea necesario
        let tours = []; // Almacenaremos los tours en esta variable

        // Función para obtener y mostrar los tours
        async function fetchTours() {
            try {
                const response = await fetch(apiUrl);
                tours = await response.json(); // Guardamos los tours recibidos
                displayTours(tours);
            } catch (error) {
                console.error('Error al obtener tours:', error);
                document.getElementById('tour-list').innerHTML = '<p class="no-tours">Hubo un error al cargar los tours.</p>';
            }
        }

        // Función para mostrar los tours en una tabla
        function displayTours(tours) {
            const tourListElement = document.getElementById('tour-list');
            if (tours.length === 0) {
                tourListElement.innerHTML = '<p class="no-tours">No hay tours disponibles.</p>';
                return;
            }

            let tableHTML = `
                <table>
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Precio</th>
                            <th>Duración</th>
                            <th>Fecha de Inicio</th>
                            <th>Destino</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            tours.forEach(tour => {
                tableHTML += `
                    <tr>
                        <td>${tour.nombreto}</td>
                        <td>${tour.descripcion}</td>
                        <td>$${tour.precio}</td>
                        <td>${tour.duracion} días</td>
                        <td>${tour.fecha_inicio}</td>
                        <td>${tour.destino}</td>
                        <td class="action-btns">
                            <button onclick="deleteTour(${tour.id_tour})">Eliminar</button>
                        </td>
                    </tr>
                `;
            });

            tableHTML += '</tbody></table>';
            tourListElement.innerHTML = tableHTML;
        }

        // Función para eliminar un tour
        async function deleteTour(id_tour) {
            const confirmDelete = confirm('¿Estás seguro de que deseas eliminar este tour?');

            if (confirmDelete) {
                try {
                    const response = await fetch(`${apiUrl}/${id_tour}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                        }
                    });

                    const result = await response.json();

                    if (response.ok) {
                        alert(result.message);
                        fetchTours(); // Recargar la lista de tours después de eliminar
                    } else {
                        alert(result.message || 'Hubo un error al eliminar el tour.');
                    }
                } catch (error) {
                    console.error('Error al eliminar el tour:', error);
                    alert('Hubo un error al eliminar el tour.');
                }
            }
        }

        // Función para filtrar tours por nombre o destino
        function filterTours() {
            const query = document.getElementById('search-input').value.toLowerCase();
            const filteredTours = tours.filter(tour => 
                tour.nombreto.toLowerCase().includes(query) || 
                tour.destino.toLowerCase().includes(query)
            );
            displayTours(filteredTours);
        }

        // Cargar los tours cuando se cargue la página
        window.onload = fetchTours;
    </script>

</body>
</html>
