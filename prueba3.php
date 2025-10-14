<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    set_time_limit(10);
    session_start();

    if(isset($_POST['reiniciar']))
    {
       $_SESSION = [];
        session_unset();
        session_regenerate_id(true); // evita reusar la cookie vieja
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

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
            if($carton[$i][6] == 60)
            {
                $numAl1 = random_int(0,6);
            }
            else{
                $numAl1 = 6;
            }
            
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
        for ($i = 0; $i < 3; $i++) {
            $carton[$i] = array();
            for ($j = 0; $j < 7; $j++) {
                // Definir rangos por columna
                $min = $j * 10 + 1;
                $max = ($j + 1) * 10 - 1;

                // Ajuste especial para la última columna (60–69)
                if ($j == 6) {
                    $min = 60;
                    $max = 69;
                }

                // Asegurar que no se repitan números
                do {
                    $num = random_int($min, $max);
                } while (!comprobar($num, $carton));

                $carton[$i][$j] = $num;
            }
        }
    }



    function crearTabla($carton)
    {

    
        echo "<table border='1' cellpadding='5' cellspacing='0' style='margin:10px'>";

        foreach ($carton as $indice)
        {
            echo "<tr>";
            foreach ($indice as $valor)
            {
                echo "<td style='text-align:center; width:30px'>";
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

   if(!isset($_SESSION['listaJugadores']))
   {
        $listaJugadores = array();
        for($i= 1; $i <= $jugadores; $i++)
        {
            $listaJugadores[$i] = array();
            for($j = 1; $j <= $cartonesPorJugador;$j++)
            {
                $carton = array();
                crearCarton($carton);
                borrarEspacios($carton);
                $listaJugadores[$i][] = $carton;
            }
        }
        $_SESSION['listaJugadores'] = $listaJugadores;
   }

   $listaJugadores = $_SESSION['listaJugadores'];

   
        foreach ($listaJugadores as $idJugador => $cartones) 
        {
            echo "<h2>Jugador $idJugador</h2>";
            echo "<div style='display:flex; flex-direction:row;'>";
            foreach ($cartones as $carton) 
            {
                crearTabla($carton);
            }
            echo "</div>";
        }
  
   

    
   if (!isset($_SESSION['numerosSacados'])) {
    $_SESSION['numerosSacados'] = [];
}

// Si se ha pedido un nuevo número
if (isset($_POST['sacar_numero'])) {
    $nuevo = rand(1, 60);
    while (in_array($nuevo, $_SESSION['numerosSacados'])) {
        $nuevo = rand(1, 60); // evitar repetidos
    }
    $_SESSION['numerosSacados'][] = $nuevo;
}

// Mostrar números sacados
echo "<h3>Números ya sacados:</h3>";
foreach ($_SESSION['numerosSacados'] as $num) {
    echo "<img src='images/$num.png' alt='$num' style='width:40px; height:40px; margin:2px;' />";
}


// Botón para sacar número nuevo
?>
<form method="post">
    <input type="submit" name="sacar_numero" value="Sacar número nuevo">
</form>

<!-- Botón opcional para reiniciar partida -->
<form method="post">
    <input type="submit" name="reiniciar" value="Reiniciar partida">
</form>

    

</body>
</html>