<?php
// Funciones para crear y manejar cartones de bingo

// Comprueba si un número ya existe en el cartón
function comprobar($num, $carton)
{
    foreach ($carton as $fila) {
        foreach ($fila as $valor) {
            if ($valor == $num) return false;
        }
    }
    return true;
}

// Elimina números aleatoriamente para dejar exactamente 15
function borrarEspacios(&$carton)
{
    $totalNumeros = 0;
    $posicionesConNumeros = [];
    
    for ($fila = 0; $fila < count($carton); $fila++) {
        for ($col = 0; $col < count($carton[$fila]); $col++) {
            if ($carton[$fila][$col] !== null) {
                $totalNumeros++;
                $posicionesConNumeros[] = [$fila, $col];
            }
        }
    }
    
    $numerosBorrar = $totalNumeros - 15;
    
    if ($numerosBorrar > 0) {
        shuffle($posicionesConNumeros);
        $posicionesBorrar = array_slice($posicionesConNumeros, 0, $numerosBorrar);
        
        foreach ($posicionesBorrar as $posicion) {
            $carton[$posicion[0]][$posicion[1]] = null;
        }
    }
}

// Crea un cartón de bingo con 15 números
function crearCarton()
{
    $carton = [];
    
    // Inicializar cartón vacío
    for ($i = 0; $i < 3; $i++) {
        $carton[$i] = array_fill(0, 7, null);
    }

    // Llenar cada columna con números de su rango
    for ($col = 0; $col < 7; $col++) {
        
        if ($col == 6) {
            // Última columna: solo el 60 en una fila aleatoria
            $filaAleatoria = random_int(0, 2);
            $carton[$filaAleatoria][$col] = 60;
        } else {
            // Columnas 0-5: rangos de 10 números cada una
            $min = ($col * 10) + 1;
            $max = ($col * 10) + 10;
            
            for ($fila = 0; $fila < 3; $fila++) {
                do {
                    $num = random_int($min, $max);
                } while (!comprobar($num, $carton));
                
                $carton[$fila][$col] = $num;
            }
        }
    }

    borrarEspacios($carton);
    return $carton;
}

// Convierte un cartón en tabla HTML
function crearTabla($carton, $numerosSacados = [])
{
    $html = "<table border='1' cellpadding='5' cellspacing='0' style='margin:10px; text-align:center;'>";
    
    foreach ($carton as $fila) {
        $html .= "<tr>";
        
        foreach ($fila as $valor) {
            if (is_null($valor)) {
                $html .= "<td style='width:30px;'> - </td>";
            } else {
                if (!empty($numerosSacados) && in_array($valor, $numerosSacados)) {
                    $html .= "<td style='background-color:red; color:white; font-weight:bold; width:30px;'>$valor</td>";
                } else {
                    $html .= "<td style='width:30px;'>$valor</td>";
                }
            }
        }
        
        $html .= "</tr>";
    }
    
    $html .= "</table>";
    return $html;
}

// Verifica si un cartón tiene bingo completo
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