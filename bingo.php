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

function crearCarton()
{
    $carton = array();
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
    borrarEspacios($carton);
    return $carton;
}

function crearTabla($carton)
{
    $html = "<table>";
    foreach ($carton as $fila) {
        $html .= "<tr>";
        foreach ($fila as $valor) {
            if (is_null($valor)) {
                $html .= "<td class='vacio'>–</td>";
            } else {
                $html .= "<td>$valor</td>";
            }
        }
        $html .= "</tr>";
    }
    $html .= "</table>";
    return $html;
}
