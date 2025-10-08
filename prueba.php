<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<?php

    function comprobar($num, $carton)
    {
        $devolver = true;
        foreach ($carton as $fila) 
        {
            foreach ($fila as $valor) 
            {
                if ($valor == $num) 
                {
                    return false;
                }
            }
        }

        return $devolver;
    }
    function borrarEspacios(&$carton)
    {
        for($i = 0; $i < count($carton); $i++)
        {
            $numAl1 = random_int(0,6);
            $numAl2 = random_int(0,6);
            while($numAl1 == $numAl2)
            {
                $numAl2 = random_int(0,6);
            }
            for($j = 0; $j < count($carton[$i]); $j++)
            {
                if ( $j == $numAl1 || $j == $numAl2)
                {
                    $carton[$i][$j] = null;
                }
            }
            
        }
    }

  
    function crearCarton(&$carton)
    {
        for($i = 0; $i < 3; $i++)
        {
            $veces = 1;
            $carton[$i] = array();
            for($j = 0; $j < 7; $j++)
            {
                while (empty($carton[$i][$j]))
                {         
                    $num = random_int($veces, (($j+1)*10)-1);
                    if(comprobar($num,$carton))
                    {
                        $carton[$i][$j] = $num;
                    }
                }
            $veces += 10;
            }
            echo "<br>";
        }

    }

    function crearTabla($carton)
    {

    
        echo "<table border='1' cellpadding='5' cellspacing='0'>";

        foreach ($carton as $indice)
        {
            echo "<tr>";
            foreach ($indice as $valor)
            {
                echo "<td style='text-align:center;'>";
                if(is_null($valor))
                {
                    echo " - ";
                }
                else{
                    echo " " . $valor . " ";
                }
                echo"</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    }
    
    $jugadores = 4;
    $cartonesPorJugador = 3;

    $listaJugadores = array();

    for($i = 1; $i <= $jugadores; $i++)
    {
        $listaJugadores[$i] = array(); // Array de cartones del jugador $j

        echo "<h2>Jugador $i</h2>";
        echo "<div style='display:flex; flex-direction:row;'>";

        for($j = 1; $j <= $cartonesPorJugador; $j++)
        {
            $carton = array();
            crearCarton($carton);
            borrarEspacios($carton);
            crearTabla($carton);

          
            $listaJugadores[$i][] = $carton;
        }
         echo "</div>";
    }
   

    
?>    

</body>
</html>