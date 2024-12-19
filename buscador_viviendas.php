<?php
require('ln.php'); 

$servidor = "localhost";
$usuario = "root";
$password = ""; 
$base_datos = "lindavista";

// Conexión a la base de datos
$conexion = new mysqli($servidor, $usuario, $password, $base_datos);
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}


$conexion -> set_charset("utf8");
$mensaje = '';
$resultado = []; 

// Procesar el formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $consulta = $_POST['consulta'] ?? ''; 

    if (empty($consulta)) {
        $mensaje = "Debe introducir una consulta.";
    } else {
        $sql = ''; // Variable donde se almacenará la sentencia SQL

        // Procesar la consulta usando la función del archivo ln.php
        if (procesa_consulta($consulta, $conexion, $sql)) {
            // Ejecutar la consulta SQL generada
            $query_result = $conexion->query($sql);

            if ($query_result && $query_result->num_rows > 0) {
                // Almacenar los resultados en un arreglo
                $resultado = $query_result->fetch_all(MYSQLI_ASSOC);
            } else {
                $mensaje = "No hay viviendas disponibles.";
            }
        } else {
            $mensaje = "La consulta no es correcta.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Buscador de Viviendas</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Quicksand', sans-serif;
    }
    .hero-bg {
      background: linear-gradient(to right, #ff5722, #ff7043), 
      url('https://images.unsplash.com/photo-1597593772022-28efb86e7b8c?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MnwzNjUyOXwwfDF8c2VhcmNofDl8fGhvbWVzfGVufDB8fHx8MTY4NjY5MTk1Nw&ixlib=rb-4.0.3&q=80&w=1080') center/cover no-repeat;
    }
  </style>
</head>
<body class="min-h-screen bg-gray-200 flex items-center justify-center">

  <!-- Contenedor Principal -->
  <div class="container mx-auto px-6 py-10">
    <!-- Hero Section -->
    <div class="hero-bg rounded-3xl shadow-2xl px-8 py-16 text-white text-center mb-12">
      <div class="flex flex-col items-center">
        <!-- Logotipo de Casa -->
        <img src="https://img.icons8.com/fluency/96/000000/home.png" alt="Logotipo" class="h-20 w-20 mb-6">

        <h1 class="text-5xl font-bold mb-4">
          Dream<span class="text-yellow-300">Homes</span>
        </h1>
        <p class="text-lg font-medium max-w-3xl">
          Encuentra tu hogar perfecto con facilidad. Inicia tu búsqueda ahora mismo.
        </p>
        <form method="POST" action="" class="mt-8 w-full max-w-3xl flex">
          <input 
            type="text" 
            name="consulta" 
            placeholder="Busca casas, departamentos o terrenos..." 
            class="flex-grow px-6 py-3 rounded-l-xl border-none focus:outline-none text-gray-800 shadow-lg">
          <button 
            type="submit" 
            class="bg-orange-500 hover:bg-orange-600 text-white font-bold px-6 py-3 rounded-r-xl shadow-lg flex items-center">
            <!-- Icono de búsqueda -->
            🔍 Buscar
          </button>
        </form>
      </div>
    </div>

    <!-- Resultados -->
    <div class="bg-gray-100 shadow-xl rounded-3xl p-10">
      <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">
        Resultados de búsqueda
      </h2>
      <?php if (!empty($mensaje)): ?>
        <p class="text-red-500 text-center font-medium mb-6">
          <?= htmlspecialchars($mensaje) ?>
        </p>
      <?php endif; ?>

      <?php if (!empty($resultado)): ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
          <?php foreach ($resultado as $vivienda): ?>
            <div class="bg-white shadow-xl rounded-2xl overflow-hidden hover:scale-105 hover:shadow-2xl transition-transform duration-300">
              <?php if (!empty($vivienda['foto'])): ?>
                <img src="./fotos/<?= htmlspecialchars($vivienda['foto']) ?>" 
                     alt="Imagen de la vivienda" 
                     class="w-full h-64 object-cover">
              <?php else: ?>
                <div class="w-full h-64 bg-gray-300 flex items-center justify-center">
                  <span class="text-gray-500">No hay imagen disponible</span>
                </div>
              <?php endif; ?>
              <div class="p-6">
                <h3 class="text-xl font-semibold text-indigo-700 mb-2">
                  <?= htmlspecialchars($vivienda['tipo']) ?>
                </h3>
                <p class="text-gray-700 mb-1">
                  <span class="font-semibold">Zona:</span> <?= htmlspecialchars($vivienda['zona']) ?>
                </p>
                <p class="text-gray-700 mb-1">
                  <span class="font-semibold">Dormitorios:</span> <?= htmlspecialchars($vivienda['ndormitorios']) ?>
                </p>
                <p class="text-gray-700 mb-1">
                  <span class="font-semibold">Metros cuadrados:</span> <?= htmlspecialchars($vivienda['metros_cuadrados']) ?> m²
                </p>
                <p class="text-orange-600 font-bold text-lg">
                  $<?= number_format($vivienda['precio'], 2) ?>
                </p>
                <button 
                  class="mt-4 bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition-all">
                  Ver Detalles
                </button>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <p class="text-center text-gray-600">
          No se encontraron resultados para tu búsqueda.
        </p>
      <?php endif; ?>
    </div>

    <!-- Pie de Página -->
    <footer class="mt-16 text-center text-gray-600">
      <p>© <?= date('Y') ?> <span class="font-bold">DreamHomes</span>. Todos los derechos reservados.</p>
      <p>Desarrollado con ❤️ por nuestro equipo.</p>
    </footer>
  </div>
</body>
</html>

<?php
// Cerrar la conexión a la base de datos
$conexion->close();
?>
