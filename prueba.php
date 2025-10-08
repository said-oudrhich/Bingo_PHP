<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bingo</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1>Juego de Bingo</h1>
    <?php
    function comprobar($num, $carton)
    {
        foreach ($carton as $fila) {
            foreach ($fila as $valor) {
                if ($valor == $num) return false;
            }
        }
        return true;
    }

    function borrarEspacios(&$carton)
    {
        for ($i = 0; $i < count($carton); $i++) {
            $numAl1 = random_int(0, 6);
            $numAl2 = random_int(0, 6);
            while ($numAl1 == $numAl2) {
                $numAl2 = random_int(0, 6);
            }
            for ($j = 0; $j < count($carton[$i]); $j++) {
                if ($j == $numAl1 || $j == $numAl2) {
                    $carton[$i][$j] = null;
                }
            }
        }
    }

    function crearCarton(&$carton)
    {
        for ($i = 0; $i < 3; $i++) {
            $veces = 1;
            $carton[$i] = array();
            for ($j = 0; $j < 7; $j++) {
                while (empty($carton[$i][$j])) {
                    $num = random_int($veces, (($j + 1) * 10) - 1);
                    if (comprobar($num, $carton)) {
                        $carton[$i][$j] = $num;
                    }
                }
                $veces += 10;
            }
        }
    }

    function crearTabla($carton)
    {
        echo "<table>";
        foreach ($carton as $fila) {
            echo "<tr>";
            foreach ($fila as $valor) {
                if (is_null($valor))
                    echo "<td class='vacio'>–</td>";
                else
                    echo "<td>$valor</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    }

    $jugadores = 4;
    $cartonesPorJugador = 3;

    for ($i = 1; $i <= $jugadores; $i++) {
        echo "<div class='jugador'>";
        echo "<h2>Jugador $i</h2>";
        echo "<div class='cartones'>";
        for ($j = 1; $j <= $cartonesPorJugador; $j++) {
            $carton = array();
            crearCarton($carton);
            borrarEspacios($carton);
            crearTabla($carton);
        }
        echo "</div></div>";
    }
    ?>
</body>

</html>