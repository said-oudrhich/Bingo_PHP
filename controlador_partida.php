<?php
// Funciones para controlar la partida de bingo

// Inicializa una nueva partida
function inicializar_partida($jugadores, $cartones_por_jugador) {
    
    if (!isset($_SESSION['listaJugadores'])) {
        $lista_jugadores = array();
        
        for ($i = 1; $i <= $jugadores; $i++) {
            $lista_jugadores[$i] = array();
            
            for ($j = 1; $j <= $cartones_por_jugador; $j++) {
                $lista_jugadores[$i][] = crearCarton();
            }
        }
        
        $_SESSION['listaJugadores'] = $lista_jugadores;
    }
    
    if (!isset($_SESSION['numerosSacados'])) {
        $_SESSION['numerosSacados'] = [];
    }
}

// Reinicia la partida
function reiniciar_partida() {
    $_SESSION = [];
    session_unset();
    session_regenerate_id(true);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Sortea un nuevo número
function sacar_numero() {
    $ganador = obtener_ganador();
    if ($ganador !== null) {
        return false;
    }
    
    do {
        $nuevo = rand(1, 60);
    } while (in_array($nuevo, $_SESSION['numerosSacados']));
    
    $_SESSION['numerosSacados'][] = $nuevo;
    return $nuevo;
}

// Verifica si hay un ganador
function obtener_ganador() {
    $lista_jugadores = $_SESSION['listaJugadores'];
    $total_jugadores = count($lista_jugadores);
    
    for ($i = 1; $i <= $total_jugadores; $i++) {
        foreach ($lista_jugadores[$i] as $carton) {
            if (cartonCompleto($carton, $_SESSION['numerosSacados'])) {
                return $i;
            }
        }
    }
    
    return null;
}

// Procesa las acciones del usuario
function procesar_acciones() {
    
    if (isset($_POST['reiniciar'])) {
        reiniciar_partida();
    }
    
    if (isset($_POST['sacar_numero'])) {
        return sacar_numero();
    }
    
    return null;
}

// Obtiene todos los datos del juego
function obtener_datos_juego() {
    $numeros_sacados = $_SESSION['numerosSacados'];
    $total_sacados = count($numeros_sacados);
    
    $ultimo_numero = ($total_sacados > 0) ? 
        $numeros_sacados[$total_sacados - 1] : null;
    
    return [
        'lista_jugadores' => $_SESSION['listaJugadores'],
        'numeros_sacados' => $numeros_sacados,
        'total_sacados' => $total_sacados,
        'ultimo_numero' => $ultimo_numero,
        'ganador' => obtener_ganador()
    ];
}