<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uso de fetch con React</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite('resources/js/app.jsx')  <!-- Importa tu archivo JSX aquí -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}"> <!-- Asegúrate de tener un archivo CSS aquí -->
</head>
<body>
    <div id="app" class="body">  <!-- Cambia esto para que React pueda renderizar en este div -->
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Etiquetas</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="data"></tbody>
        </table>
        <div class="button-container">
            <button type="button" class="btn btn-primary" onClick="openCreateForm()">Crear nuevo usuario</button>
        </div>
        <!-- Modales para crear y editar usuarios -->
        @include('partials.modals') <!-- Puedes extraer tus modales a un archivo de vista parcial -->
    </div>

    <!-- No necesitas incluir React y ReactDOM aquí, ya que Vite se encarga de eso -->
    <!-- El script para Babel tampoco es necesario en este caso -->
</body>
</html>
