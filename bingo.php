<?php

/**
 * Comprueba si un número ya existe en el cartón.
 *
 * Parámetros:
 * - $num (int): número candidato a insertar.
 * - $carton (array<int,array<int,int|null>>): cartón de 3x7 filas/columnas con enteros o null.
 *
 * Retorno:
 * - bool: true si NO existe en el cartón (se puede usar), false si ya está repetido.
 */
function comprobar($num, $carton)
{
    // Recorremos todas las celdas del cartón y verificamos si el número ya está presente
    foreach ($carton as $fila) {
        foreach ($fila as $valor) {
            if ($valor == $num) return false;
        }
    }
    return true;
}

/**
 * Elimina aleatoriamente 2 posiciones por fila para dejar 5 números visibles.
 *
 * Notas:
 * - La columna 6 puede contener el 60, que no debe borrarse si está presente.
 * - Se modifica el cartón por referencia.
 *
 * Parámetros:
 * - &$carton (array<int,array<int,int|null>>): cartón a modificar in-place.
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

        // Construimos el conjunto de columnas candidatas a borrar (que no sean null)
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

        // Elegimos aleatoriamente las columnas a borrar
        shuffle($indicesDisponibles);
        $indicesBorrar = array_slice($indicesDisponibles, 0, $espaciosABorrar);

        // Marcamos como null las celdas seleccionadas
        foreach ($indicesBorrar as $col) {
            $fila[$col] = null;
        }
    }
}

/**
 * Crea un cartón de bingo de 3x7 siguiendo estas reglas:
 * - Columnas 0..5: números en rangos de 1..9, 10..18, ..., 46..54 (bloques de 9).
 * - Columna 6: puede contener el número 60 en una sola fila, el resto null.
 * - No hay números repetidos dentro del mismo cartón.
 * - Tras generar, se borran espacios para dejar 5 números por fila.
 *
 * Retorno:
 * - array<int,array<int,int|null>>: cartón listo para mostrarse.
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

            // Generar números aleatorios para las columnas 0-5 (en bloques de 9)
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
 * Convierte un cartón en HTML y marca en rojo las celdas cuyos números ya salieron.
 *
 * Parámetros:
 * - $carton (array<int,array<int,int|null>>): cartón a representar.
 * - $numerosSacados (int[]): lista de números ya sorteados.
 *
 * Retorno:
 * - string: tabla HTML lista para imprimirse.
 */
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

/**
 * Indica si todas las celdas numéricas del cartón están marcadas (Bingo completo).
 *
 * Parámetros:
 * - $carton (array<int,array<int,int|null>>)
 * - $numerosSacados (int[])
 *
 * Retorno:
 * - bool: true si todas las celdas con número han sido sorteadas.
 */
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
