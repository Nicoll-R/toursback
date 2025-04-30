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
        .tour-list {
            margin-top: 20px;
        }
        .tour-item {
            background-color: #fff;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .tour-item button {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 3px;
        }
        .tour-item button:hover {
            background-color: #c0392b;
        }
        .tour-item .details {
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <h1>Gestión de Tours</h1>

    <div id="tour-list" class="tour-list">
        <!-- Los tours se cargarán aquí dinámicamente -->
    </div>

    <script>
        // URL de la API
        const apiUrl = 'http://localhost:8000/api/tours'; // Cambiar según sea necesario

        // Función para obtener y mostrar los tours
        async function fetchTours() {
            try {
                const response = await fetch(apiUrl);
                const tours = await response.json();

                if (Array.isArray(tours) && tours.length > 0) {
                    displayTours(tours);
                } else {
                    document.getElementById('tour-list').innerHTML = '<p>No hay tours disponibles.</p>';
                }
            } catch (error) {
                console.error('Error al obtener tours:', error);
                document.getElementById('tour-list').innerHTML = '<p>Hubo un error al cargar los tours.</p>';
            }
        }

        // Función para mostrar los tours en la página
        function displayTours(tours) {
            const tourListElement = document.getElementById('tour-list');
            tourListElement.innerHTML = ''; // Limpiar la lista existente

            tours.forEach(tour => {
                const tourItem = document.createElement('div');
                tourItem.classList.add('tour-item');

                tourItem.innerHTML = `
                    <h3>${tour.nombreto}</h3>
                    <p><strong>Descripción:</strong> ${tour.descripcion}</p>
                    <p><strong>Precio:</strong> $${tour.precio}</p>
                    <p><strong>Duración:</strong> ${tour.duracion} días</p>
                    <p><strong>Fecha de inicio:</strong> ${tour.fecha_inicio}</p>
                    <p><strong>Destino:</strong> ${tour.destino}</p>
                    <div class="details">
                        <button onclick="deleteTour(${tour.id_tour})">Eliminar</button>
                    </div>
                `;

                tourListElement.appendChild(tourItem);
            });
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

        // Cargar los tours cuando se cargue la página
        window.onload = fetchTours;
    </script>

</body>
</html>
