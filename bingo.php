<?php

/**
 * Comprueba si un número ya existe en el cartón.
 *
 * @param int $num El número a comprobar.
 * @param array $carton El cartón de bingo (matriz 2D).
 * @return bool Devuelve true si el número NO está en el cartón, false si ya existe.
 */
function comprobar($num, $carton)
{
    // Recorre cada fila del cartón.
    foreach ($carton as $fila) {
        // Recorre cada celda (valor) de la fila.
        foreach ($fila as $valor) {
            // Si el valor de la celda es igual al número buscado, el número ya existe.
            if ($valor == $num) return false;
        }
    }
    // Si termina de recorrer todo el cartón sin encontrar el número, devuelve true.
    return true;
}

/**
 * Modifica un cartón para dejar exactamente 5 números por fila.
 * La función modifica el cartón directamente (paso por referencia).
 *
 * @param array &$carton El cartón a modificar.
 */
function borrarEspacios(&$carton)
{
    foreach ($carton as &$fila) {
        // Contamos cuántos números hay en la fila (sin contar nulls)
        $numerosEnFila = count(array_filter($fila, function ($val) {
            return $val !== null;
        }));

        // Calculamos cuántos espacios debemos borrar para dejar exactamente 5
        $espaciosABorrar = $numerosEnFila - 5;

        if ($espaciosABorrar <= 0) {
            continue; // Ya tiene 5 o menos números
        }

        // Columnas candidatas a ser borradas
        $indicesDisponibles = [];
        for ($col = 0; $col < count($fila); $col++) {
            // Solo podemos borrar si hay un número (no es null)
            if ($fila[$col] !== null) {
                // Si es la columna 6 (última) y tiene el 60, NO se puede borrar
                if ($col === 6 && $fila[$col] === 60) {
                    continue;
                }
                $indicesDisponibles[] = $col;
            }
        }

        // Elegimos aleatoriamente los espacios a borrar
        shuffle($indicesDisponibles);
        $indicesBorrar = array_slice($indicesDisponibles, 0, $espaciosABorrar);

        // Borramos las celdas seleccionadas
        foreach ($indicesBorrar as $col) {
            $fila[$col] = null;
        }
    }
}

/**
 * Crea un cartón de bingo de 3x7.
 *
 * @return array El cartón generado como una matriz 2D.
 */
function crearCarton()
{
    $carton = [];

    // Elegimos aleatoriamente en qué fila irá el 60
    $fila60 = random_int(0, 2);

    for ($i = 0; $i < 3; $i++) {
        $carton[$i] = [];

        for ($j = 0; $j < 7; $j++) {

            // Última columna
            if ($j == 6) {
                $carton[$i][$j] = ($i == $fila60) ? 60 : null;
                continue;
            }

            // Generar números aleatorios para las columnas 0-5
            while (empty($carton[$i][$j])) {
                $min = ($j * 9) + 1;
                $max = ($j + 1) * 9;
                $num = random_int($min, $max);
                if (comprobar($num, $carton)) $carton[$i][$j] = $num;
            }
        }
    }

    borrarEspacios($carton);
    return $carton;
}

/**
 * Convierte una matriz de cartón en una tabla HTML.
 *
 * @param array $carton El cartón de bingo (matriz 2D).
 * @return string El código HTML de la tabla.
 */
function crearTabla($carton)
{
    $html = "<table>";
    foreach ($carton as $fila) {
        $html .= "<tr>";
        foreach ($fila as $valor) {
            // Si el valor es nulo, crea una celda vacía con una clase especial.
            if (is_null($valor)) {
                $html .= "<td class='vacio'>–</td>";
            } else {
                // Si no, crea una celda con el número.
                $html .= "<td>$valor</td>";
            }
        }
        $html .= "</tr>";
    }
    $html .= "</table>";
    return $html;
}
