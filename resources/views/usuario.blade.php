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
            background-color: #e67e22;
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            font-size: 13px;
            cursor: pointer;
        }

        .action-btns button:hover {
            opacity: 0.8;
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

        /* MODAL */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.6);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 999;
        }

        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 8px;
            width: 400px;
            max-width: 90%;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .modal-content input,
        .modal-content textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        .modal-buttons {
            display: flex;
            justify-content: space-between;
        }

        .modal-buttons button {
            padding: 8px 14px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .modal-buttons button:first-child {
            background-color: #2ecc71;
            color: white;
        }

        .modal-buttons button:last-child {
            background-color: #e74c3c;
            color: white;
        }
    </style>
</head>
<body>

    <div class="top-bar">
        <h1>Gestión de Tours 🌍</h1>
        <button class="add-btn">➕ Agregar Tour</button>
    </div>

    <div class="search-bar">
        <input type="text" id="search-input" placeholder="🔍 Buscar por nombre o destino..." oninput="filterTours()">
    </div>

    <div id="message" class="message"></div>
    <p class="tour-count" id="tour-count"></p>
    <div id="tour-list"></div>

    <div class="pagination">
        <button onclick="prevPage()">⏮ Anterior</button>
        <button onclick="nextPage()">Siguiente ⏭</button>
    </div>

    <!-- MODAL -->
    <div id="tour-modal" style="display: none;" class="modal">
        <div class="modal-content">
            <h2 id="modal-title">Agregar Tour</h2>
            <form id="tour-form">
                <input type="hidden" id="tour-id">
                <input type="text" id="nombreto" placeholder="Nombre del Tour" required>
                <textarea id="descripcion" placeholder="Descripción" required></textarea>
                <input type="number" id="precio" placeholder="Precio" required>
                <input type="number" id="duracion" placeholder="Duración (días)" required>
                <input type="date" id="fecha_inicio" required>
                <input type="text" id="destino" placeholder="Destino" required>
                <input type="text" id="categoria" placeholder="Categoría (opcional)">
                <div class="modal-buttons">
                    <button type="submit">💾 Guardar</button>
                    <button type="button" onclick="closeModal()">❌ Cancelar</button>
                </div>
            </form>
        </div>
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
                            <button onclick='openModal(${JSON.stringify(tour)})'>✏️ Editar</button>
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

        document.querySelector('.add-btn').addEventListener('click', () => {
            openModal();
        });

        function openModal(tour = null) {
            document.getElementById('tour-form').reset();
            document.getElementById('tour-id').value = tour?.id_tour || '';
            document.getElementById('modal-title').textContent = tour ? 'Editar Tour' : 'Agregar Tour';
            if (tour) {
                document.getElementById('nombreto').value = tour.nombreto;
                document.getElementById('descripcion').value = tour.descripcion;
                document.getElementById('precio').value = tour.precio;
                document.getElementById('duracion').value = tour.duracion;
                document.getElementById('fecha_inicio').value = tour.fecha_inicio;
                document.getElementById('destino').value = tour.destino;
                document.getElementById('categoria').value = tour.categoria || '';
            }
            document.getElementById('tour-modal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('tour-modal').style.display = 'none';
        }

        document.getElementById('tour-form').addEventListener('submit', async function (e) {
            e.preventDefault();

            const id = document.getElementById('tour-id').value;
            const data = {
                nombreto: document.getElementById('nombreto').value,
                descripcion: document.getElementById('descripcion').value,
                precio: parseFloat(document.getElementById('precio').value),
                duracion: parseInt(document.getElementById('duracion').value),
                fecha_inicio: document.getElementById('fecha_inicio').value,
                destino: document.getElementById('destino').value,
                categoria: document.getElementById('categoria').value
            };

            try {
                const url = id ? `${apiUrl}/${id}` : apiUrl;
                const method = id ? 'PUT' : 'POST';

                const response = await fetch(url, {
                    method,
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (response.ok) {
                    showMessage(result.message || (id ? 'Tour actualizado' : 'Tour agregado'));
                    closeModal();
                    fetchTours();
                } else {
                    alert(result.message || 'Error en el formulario');
                }
            } catch (error) {
                console.error('Error al guardar:', error);
                alert('No se pudo guardar el tour.');
            }
        });

        window.onload = fetchTours;
    </script>

</body>
</html>
