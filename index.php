<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bingo</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <h1>Juego de Bingo</h1>

    <?php
    require 'bingo.php'; // incluimos la lógica

    $jugadores = 4;
    $cartonesPorJugador = 3;

    for ($i = 1; $i <= $jugadores; $i++) {
        echo "<div class='jugador'>";
        echo "<h2>Jugador $i</h2>";
        echo "<div class='cartones'>";
        for ($j = 1; $j <= $cartonesPorJugador; $j++) {
            $carton = crearCarton();      // obtenemos el cartón ya con espacios borrados
            echo crearTabla($carton);     // imprimimos la tabla
        }
        echo "</div></div>";
    }
    ?>

</body>

</html>