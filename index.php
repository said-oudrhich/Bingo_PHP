<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bingo</title>
</head>

<body>
    <h1>Juego de Bingo</h1>

    <?php
    // Iniciamos sesión para conservar estado entre peticiones (cartones y números)
    session_start();
    // Cargamos funciones de generación/visualización del bingo
    require 'bingo.php';

    // Reiniciar partida: limpia toda la sesión y recarga la página
    if (isset($_POST['reiniciar'])) {
        $_SESSION = [];
        session_unset();
        session_regenerate_id(true);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    // Parámetros de la partida (se pueden ajustar)
    $jugadores = 4;              // número de jugadores
    $cartonesPorJugador = 3;     // cartones por jugador

    // Inicializar cartones por jugador en la primera carga
    if (!isset($_SESSION['listaJugadores'])) {
        $listaJugadores = array();
        for ($i = 1; $i <= $jugadores; $i++) {
            $listaJugadores[$i] = array();
            for ($j = 1; $j <= $cartonesPorJugador; $j++) {
                $listaJugadores[$i][] = crearCarton();
            }
        }
        $_SESSION['listaJugadores'] = $listaJugadores;
    }
    $listaJugadores = $_SESSION['listaJugadores'];

    // Inicializar lista de números ya sorteados
    if (!isset($_SESSION['numerosSacados'])) {
        $_SESSION['numerosSacados'] = [];
    }

    // Calcular ganador actual antes de permitir más sorteos
    $ganador = null;
    for ($i = 1; $i <= $jugadores; $i++) {
        foreach ($listaJugadores[$i] as $cartonTmp) {
            if (cartonCompleto($cartonTmp, $_SESSION['numerosSacados'])) {
                $ganador = $i;
                break 2;
            }
        }
    }

    // Sacar nuevo número aleatorio (1..60) solo si no hay ganador y evitando repetidos
    if (isset($_POST['sacar_numero']) && $ganador === null) {
        do {
            $nuevo = rand(1, 60);
        } while (in_array($nuevo, $_SESSION['numerosSacados']));
        $_SESSION['numerosSacados'][] = $nuevo;

        // Recalcular ganador tras el nuevo número
        for ($i = 1; $i <= $jugadores; $i++) {
            foreach ($listaJugadores[$i] as $cartonTmp) {
                if (cartonCompleto($cartonTmp, $_SESSION['numerosSacados'])) {
                    $ganador = $i;
                    break 2;
                }
            }
        }
    }

    // Preparar datos resumen para cabecera informativa
    $totalSacados = count($_SESSION['numerosSacados']);
    $ultimoNumero = ($totalSacados > 0) ? $_SESSION['numerosSacados'][$totalSacados - 1] : null;

    // Controles simples arriba (estáticos)
    echo "<div style='padding:10px 0;display:flex;gap:10px;align-items:center;justify-content:flex-start;'>";
    $disabled = ($ganador !== null) ? " disabled style=\"opacity:0.6\"" : "";
    $drawLabel = ($ganador !== null) ? "Partida terminada" : "Sacar número nuevo";
    echo "<form method=\"post\" style=\"display:inline-block;\"><input type=\"submit\" name=\"sacar_numero\" value=\"$drawLabel\"$disabled></form>";
    echo "<form method=\"post\" style=\"display:inline-block;\"><input type=\"submit\" name=\"reiniciar\" value=\"Reiniciar partida\"></form>";
    if ($ganador !== null) {
        echo "<span style='margin-left:10px;color:#0a8f2e;font-weight:bold;'>🎉 ¡El Jugador $ganador ha ganado el Bingo! 🎉</span>";
    }
    echo "</div>";

    // Bloque estático para números salidos (tira de imágenes)
    echo "<div style='padding:8px 0;border-top:1px solid #ddd;border-bottom:1px solid #ddd;margin-bottom:10px;'>";
    if ($ultimoNumero !== null) {
        echo "<div style='margin-bottom:6px;'>Último número: <strong style=\"color:#d00000\">$ultimoNumero</strong> · Total: $totalSacados</div>";
    } else {
        echo "<div style='margin-bottom:6px;color:#555;'>Aún no hay números</div>";
    }
    foreach ($_SESSION['numerosSacados'] as $num) {
        echo "<img src='img/" . $num . ".PNG' alt='" . $num . "' style='width:36px;height:36px;margin-right:6px;' />";
    }
    echo "</div>";

    // Render de cartones por jugador y (opcional) resalte de ganador
    for ($i = 1; $i <= $jugadores; $i++) {
        $isWinner = ($ganador !== null && $ganador === $i);
        $wrapperStyle = $isWinner
            ? "margin:20px 0;padding:10px;border:2px solid #0a8f2e;border-radius:8px;background:#f5fff7;"
            : "margin:20px 0;";
        echo "<div style='" . $wrapperStyle . "'>";
        $title = $isWinner ? "Jugador $i (Ganador)" : "Jugador $i";
        $titleColor = $isWinner ? "#0a8f2e" : "#000";
        echo "<h2 style='margin:10px 0;color:" . $titleColor . "'>" . $title . "</h2>";
        echo "<div style='display:flex;gap:10px;flex-wrap:wrap;justify-content:center;'>";
        foreach ($listaJugadores[$i] as $carton) {
            echo crearTabla($carton, $_SESSION['numerosSacados']);
        }
        echo "</div></div>";
    }

    ?>

</body>

</html>