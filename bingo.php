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
    $carton = array();
    // Bucle para las 3 filas del cartón.
    for ($i = 0; $i < 3; $i++) {
        $carton[$i] = array();
        // Bucle para las 7 columnas del cartón.
        for ($j = 0; $j < 7; $j++) {
            // Bucle para asegurar que se genere un número único para la celda.
            while (empty($carton[$i][$j])) {
                // Genera un número aleatorio basado en la columna.
                // Col 0: 1-9, Col 1: 10-19, Col 2: 20-29, etc.
                $min = ($j * 10) + 1;
                $max = ($j + 1) * 10;
                // Ajustado para que el máximo sea 60, según las reglas.
                $min = ($j * 9) + 1;  // Distribuimos los 60 números en 7 columnas
                $max = ($j + 1) * 9;
                if ($j == 6) { // La última columna llega hasta 60
                    $max = 60;
                }
                $num = random_int($min, $max);
                // Comprueba que el número no esté ya en el cartón.
                // Comprueba que el número no esté ya en el cartón y sea <= 60.
                if (comprobar($num, $carton)) {
                    // Si es único, lo asigna a la celda.
                    $carton[$i][$j] = $num;
                }
            }
        }
    }
    // Añade los espacios vacíos al cartón recién creado.
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
