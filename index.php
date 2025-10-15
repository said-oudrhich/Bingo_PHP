<?php
session_start();
require 'funciones_carton.php';
require 'controlador_partida.php';

// Configuración
$jugadores = 4;
$cartones_por_jugador = 3;

// Procesar juego
inicializar_partida($jugadores, $cartones_por_jugador);
procesar_acciones();
$datos = obtener_datos_juego();

// Variables para la vista
$partida_terminada = $datos['ganador'] !== null;
$texto_boton = $partida_terminada ? "Partida terminada" : "Sacar número nuevo";
$disabled = $partida_terminada ? " disabled style=\"opacity:0.6\"" : "";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Juego de Bingo</title>
    <style>
        .ganador { border:2px solid green; background:#f5fff7; }
        .cartones { display:flex; gap:10px; }
    </style>
</head>
<body>
    <h1>Juego de Bingo</h1>

    <div>
        <form method="post">
            <input type="submit" name="sacar_numero" value="<?= $texto_boton ?>"<?= $disabled ?>>
        </form>
        
        <form method="post">
            <input type="submit" name="reiniciar" value="Reiniciar partida">
        </form>

        <?php if ($partida_terminada): ?>
            <span style="color:green;font-weight:bold;">🎉 ¡El Jugador <?= $datos['ganador'] ?> ha ganado el Bingo! 🎉</span>
        <?php endif; ?>
    </div>

    <div>
        <?php if ($datos['ultimo_numero'] !== null): ?>
            <div>
                Último número: <strong style="color:red"><?= $datos['ultimo_numero'] ?></strong> 
                · Total: <?= $datos['total_sacados'] ?>
            </div>
        <?php else: ?>
            <div>Aún no hay números</div>
        <?php endif; ?>
        
        <?php foreach ($datos['numeros_sacados'] as $num): ?>
            <img src="img/<?= $num ?>.PNG" alt="<?= $num ?>" width="36" height="36" />
        <?php endforeach; ?>
    </div>

    <?php for ($i = 1; $i <= $jugadores; $i++): ?>
        <?php 
        $es_ganador = $partida_terminada && $datos['ganador'] === $i;
        $clase_jugador = $es_ganador ? "jugador ganador" : "jugador";
        $titulo = $es_ganador ? "Jugador $i (Ganador)" : "Jugador $i";
        $color_titulo = $es_ganador ? "#0a8f2e" : "#000";
        ?>
        
        <div class="<?= $es_ganador ? 'ganador' : '' ?>">
            <h2 style="color:<?= $color_titulo ?>"><?= $titulo ?></h2>
            <div class="cartones">
                <?php foreach ($datos['lista_jugadores'][$i] as $carton): ?>
                    <?= crearTabla($carton, $datos['numeros_sacados']) ?>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endfor; ?>

</body>
</html>