<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bingo</title>
</head>
<body>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
set_time_limit(10);
session_start();

if (isset($_POST['reiniciar'])) {
    $_SESSION = [];
    session_unset();
    session_regenerate_id(true);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// ---------- FUNCIONES ---------- //

function comprobar($num, $carton)
{
    foreach ($carton as $fila) {
        foreach ($fila as $valor) {
            if ($valor == $num) {
                return false;
            }
        }
    }
    return true;
}

function borrarEspacios(&$carton)
{
    for ($i = 0; $i < count($carton); $i++) {
        if ($carton[$i][6] == 60) {
            $numAl1 = random_int(0, 6);
        } else {
            $numAl1 = 6;
        }
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
        $carton[$i] = array();
        for ($j = 0; $j < 7; $j++) {
            $min = $j * 10 + 1;
            $max = ($j + 1) * 10 - 1;

            if ($j == 6) {
                $min = 60;
                $max = 60;
            }

            do {
                $num = random_int($min, $max);
            } while (!comprobar($num, $carton));

            $carton[$i][$j] = $num;
        }
    }
}

// 🟥 CREAR TABLA CON NÚMEROS MARCADOS
function crearTabla($carton, $numerosSacados)
{
    echo "<table border='1' cellpadding='5' cellspacing='0' style='margin:10px; text-align:center;'>";
    foreach ($carton as $fila) {
        echo "<tr>";
        foreach ($fila as $valor) {
            if (is_null($valor)) {
                echo "<td style='width:30px;'> - </td>";
            } else {
                if (in_array($valor, $numerosSacados)) {
                    echo "<td style='background-color:red; color:white; font-weight:bold; width:30px;'>$valor</td>";
                } else {
                    echo "<td style='width:30px;'>$valor</td>";
                }
            }
        }
        echo "</tr>";
    }
    echo "</table>";
}

// 🔍 COMPROBAR SI UN CARTÓN ESTÁ COMPLETO
function cartonCompleto($carton, $numerosSacados)
{
    $totalNumeros = 0;
    $marcados = 0;

    foreach ($carton as $fila) {
        foreach ($fila as $valor) {
            if (!is_null($valor)) {
                $totalNumeros++;
                if (in_array($valor, $numerosSacados)) {
                    $marcados++;
                }
            }
        }
    }

    return $totalNumeros > 0 && $marcados == $totalNumeros;
}

// ---------- INICIALIZAR PARTIDA ---------- //

$jugadores = 4;
$cartonesPorJugador = 3;

if (!isset($_SESSION['listaJugadores'])) {
    $listaJugadores = array();
    for ($i = 1; $i <= $jugadores; $i++) {
        $listaJugadores[$i] = array();
        for ($j = 1; $j <= $cartonesPorJugador; $j++) {
            $carton = array();
            crearCarton($carton);
            borrarEspacios($carton);
            $listaJugadores[$i][] = $carton;
        }
    }
    $_SESSION['listaJugadores'] = $listaJugadores;
}

$listaJugadores = $_SESSION['listaJugadores'];

if (!isset($_SESSION['numerosSacados'])) {
    $_SESSION['numerosSacados'] = [];
}

// ---------- SACAR NUEVO NÚMERO ---------- //

if (isset($_POST['sacar_numero'])) {
    do {
        $nuevo = rand(1, 60);
    } while (in_array($nuevo, $_SESSION['numerosSacados']));
    $_SESSION['numerosSacados'][] = $nuevo;
}

// ---------- MOSTRAR CARTONES ---------- //

$ganador = null;

foreach ($listaJugadores as $idJugador => $cartones) {
    echo "<h2>Jugador $idJugador</h2>";
    echo "<div style='display:flex; flex-direction:row;'>";
    foreach ($cartones as $carton) {
        crearTabla($carton, $_SESSION['numerosSacados']);
        if (cartonCompleto($carton, $_SESSION['numerosSacados'])) {
            $ganador = $idJugador;
        }
    }
    echo "</div>";
}

// ---------- MOSTRAR NÚMEROS SALIDOS ---------- //

echo "<h3>Números ya sacados:</h3>";
foreach ($_SESSION['numerosSacados'] as $num) {
    echo "<img src='images/$num.png' alt='$num' style='width:40px; height:40px; margin:2px;' />";
}

// ---------- BOTONES ---------- //

?>
<form method="post">
    <input type="submit" name="sacar_numero" value="Sacar número nuevo">
</form>

<form method="post">
    <input type="submit" name="reiniciar" value="Reiniciar partida">
</form>

<?php
// ---------- MOSTRAR GANADOR ---------- //
if ($ganador !== null) {
    echo "<h2 style='color:green;'>🎉 ¡El Jugador $ganador ha ganado el Bingo! 🎉</h2>";
}
?>

</body>
</html>
