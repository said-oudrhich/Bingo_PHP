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
 * Modifica un cartón para añadir dos espacios vacíos (null) en cada fila.
 * La función modifica el cartón directamente (paso por referencia).
 *
 * @param array &$carton El cartón a modificar.
 */
function borrarEspacios(&$carton)
{
    // Recorre cada fila del cartón por su índice.
    for ($i = 0; $i < count($carton); $i++) {
        // Genera dos índices de columna aleatorios y distintos entre 0 y 6.
        $numAl1 = random_int(0, 6);
        $numAl2 = random_int(0, 6);
        while ($numAl1 == $numAl2) {
            $numAl2 = random_int(0, 6);
        }
        // Recorre cada columna de la fila actual.
        for ($j = 0; $j < count($carton[$i]); $j++) {
            // Si el índice de la columna coincide con uno de los números aleatorios,
            // se establece el valor de esa celda a null para crear un espacio vacío.
            if ($j == $numAl1 || $j == $numAl2) {
                $carton[$i][$j] = null;
            }
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
