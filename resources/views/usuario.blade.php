<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Tours</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #eef2f3;
            margin: 0;
            padding: 20px;
        }

        h1 {
            color: #2c3e50;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .search-bar {
            margin-bottom: 20px;
        }

        .search-bar input {
            padding: 10px;
            width: 250px;
            border-radius: 20px;
            border: 1px solid #ccc;
            font-size: 15px;
            outline: none;
        }

        .add-btn {
            background-color: #2ecc71;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 20px;
            font-size: 14px;
            cursor: pointer;
        }

        .add-btn:hover {
            background-color: #27ae60;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            background-color: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 14px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #3498db;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .action-btns {
            display: flex;
            gap: 10px;
        }

        .action-btns button {
            background-color: #e74c3c;
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            font-size: 13px;
            cursor: pointer;
        }

        .action-btns button:hover {
            background-color: #c0392b;
        }

        .no-tours {
            text-align: center;
            font-size: 18px;
            color: #e74c3c;
            margin-top: 20px;
        }

        .message {
            padding: 10px;
            background-color: #2ecc71;
            color: white;
            border-radius: 5px;
            text-align: center;
            margin-top: 10px;
            display: none;
        }

        .pagination {
            text-align: center;
            margin-top: 20px;
        }

        .pagination button {
            padding: 6px 12px;
            margin: 0 5px;
            background-color: #3498db;
            border: none;
            border-radius: 4px;
            color: white;
            cursor: pointer;
        }

        .pagination button:hover {
            background-color: #2980b9;
        }

        .tour-count {
            font-weight: bold;
            margin-top: 15px;
        }
    </style>
</head>
<body>

    <div class="top-bar">
        <h1>Gestión de Tours 🌍</h1>
        <button class="add-btn">➕ Agregar Tour</button>
    </div>

    <!-- Barra de búsqueda -->
    <div class="search-bar">
        <input type="text" id="search-input" placeholder="🔍 Buscar por nombre o destino..." oninput="filterTours()">
    </div>

    <!-- Mensaje temporal -->
    <div id="message" class="message"></div>

    <!-- Contador -->
    <p class="tour-count" id="tour-count"></p>

    <!-- Tabla de tours -->
    <div id="tour-list"></div>

    <!-- Paginación (simulada) -->
    <div class="pagination">
        <button onclick="prevPage()">⏮ Anterior</button>
        <button onclick="nextPage()">Siguiente ⏭</button>
    </div>

    <script>
        const apiUrl = 'http://localhost:8000/api/tours';
        let tours = [];
        let currentPage = 1;
        const itemsPerPage = 5;

        async function fetchTours() {
            try {
                const response = await fetch(apiUrl);
                tours = await response.json();
                displayTours(tours);
            } catch (error) {
                console.error('Error al obtener tours:', error);
                document.getElementById('tour-list').innerHTML = '<p class="no-tours">Hubo un error al cargar los tours.</p>';
            }
        }

        function displayTours(tourData) {
            const list = document.getElementById('tour-list');
            if (!tourData.length) {
                list.innerHTML = '<p class="no-tours">No hay tours disponibles 😞</p>';
                return;
            }

            const start = (currentPage - 1) * itemsPerPage;
            const end = start + itemsPerPage;
            const paginated = tourData.slice(start, end);

            let tableHTML = `
                <table>
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Precio</th>
                            <th>Duración</th>
                            <th>Fecha Inicio</th>
                            <th>Destino</th>
                            <th>Categoría</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            paginated.forEach(tour => {
                tableHTML += `
                    <tr>
                        <td>${tour.nombreto}</td>
                        <td>${tour.descripcion}</td>
                        <td>$${tour.precio}</td>
                        <td>${tour.duracion} días</td>
                        <td>${tour.fecha_inicio}</td>
                        <td>${tour.destino}</td>
                        <td>${tour.categoria || 'General'}</td>
                        <td class="action-btns">
                            <button onclick="deleteTour(${tour.id_tour})">🗑 Eliminar</button>
                        </td>
                    </tr>
                `;
            });

            tableHTML += '</tbody></table>';
            list.innerHTML = tableHTML;
            updateCount(tourData.length);
        }

        function updateCount(count) {
            document.getElementById('tour-count').textContent = `Total de tours: ${count}`;
        }

        function filterTours() {
            const query = document.getElementById('search-input').value.toLowerCase();
            const filtered = tours.filter(tour =>
                tour.nombreto.toLowerCase().includes(query) ||
                tour.destino.toLowerCase().includes(query)
            );
            currentPage = 1;
            displayTours(filtered);
        }

        async function deleteTour(id) {
            if (!confirm('¿Seguro que quieres eliminar este tour?')) return;

            try {
                const response = await fetch(`${apiUrl}/${id}`, {
                    method: 'DELETE',
                    headers: { 'Content-Type': 'application/json' }
                });

                const result = await response.json();

                if (response.ok) {
                    showMessage(result.message);
                    fetchTours();
                } else {
                    alert(result.message || 'Error al eliminar.');
                }
            } catch (error) {
                console.error('Error al eliminar:', error);
                alert('Error al eliminar el tour.');
            }
        }

        function showMessage(msg) {
            const messageBox = document.getElementById('message');
            messageBox.textContent = msg;
            messageBox.style.display = 'block';
            setTimeout(() => {
                messageBox.style.display = 'none';
            }, 3000);
        }

        function nextPage() {
            const maxPage = Math.ceil(tours.length / itemsPerPage);
            if (currentPage < maxPage) {
                currentPage++;
                filterTours();
            }
        }

        function prevPage() {
            if (currentPage > 1) {
                currentPage--;
                filterTours();
            }
        }

        window.onload = fetchTours;
    </script>

</body>
</html>
