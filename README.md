# Bingo PHP

## ¿En qué consiste?
Juego simple de Bingo en PHP. Genera cartones para varios jugadores, va sacando números al azar (1..60) sin repetir, marca en rojo los números que ya salieron y detecta automáticamente cuándo un jugador completa su cartón (Bingo).

## Estructura
- `index.php`: Punto de entrada y flujo de la partida. Gestiona la sesión, el sorteo, el reinicio y el renderizado de la interfaz.
- `bingo.php`: Funciones puras de lógica y visualización de cartones:
  - `crearCarton()`
  - `borrarEspacios(&$carton)`
  - `comprobar($num, $carton)`
  - `crearTabla($carton, $numerosSacados)`
  - `cartonCompleto($carton, $numerosSacados)`
- `img/`: Imágenes 1..60 en formato `.PNG` para mostrar la tira de números salidos.

## Cómo funciona
1. Al cargar `index.php`, se inicializan:
   - `$_SESSION['listaJugadores']`: Matriz de jugadores → cartones.
   - `$_SESSION['numerosSacados']`: Lista de números ya sorteados.
2. Cada petición con el botón “Sacar número nuevo” añade un número aleatorio (1..60) no repetido a `$_SESSION['numerosSacados']`.
3. Se recalcula si algún cartón está completo con `cartonCompleto(...)`. Si hay ganador:
   - Se desactiva el botón de sorteo y se muestra el ganador.
4. Los cartones se dibujan con `crearTabla(...)`, marcando en rojo los números que ya salieron.

## Reglas del cartón
- Tamaño: 3 filas x 7 columnas.
- Columnas 0..5: números en bloques de 9 (1..9, 10..18, ..., 46..54), sin repetidos en el cartón.
- Columna 6: una sola celda contiene el número 60; las demás celdas de esa columna pueden quedar vacías (null).
- `borrarEspacios(...)` elimina 2 celdas por fila (o hasta dejar 5 números en total), sin borrar el 60 si está presente.

## Flujo de sesión (estado)
- `$_SESSION['listaJugadores']`: Se crea una vez y persiste hasta “Reiniciar partida”.
- `$_SESSION['numerosSacados']`: Se actualiza cada vez que se sortea un número y se usa para pintar los cartones.
- “Reiniciar partida” limpia toda la sesión y recarga `index.php`.

## Cómo ejecutar
1. Coloca el proyecto en tu servidor local (por ejemplo, `wamp/www/Bingo_PHP`).
2. Accede a `http://localhost/Bingo_PHP/index.php`.
3. Usa los botones superiores para sortear números o reiniciar.

## Mini análisis y decisiones
- Se priorizó claridad del flujo sobre estilos: HTML sencillo con estilos inline mínimos.
- La lógica de sorteo se detiene en cuanto hay un ganador (botón deshabilitado) para evitar estados inconsistentes.
- Los cartones se generan con rangos por columna para repartir la distribución y facilitar la lectura.
- La columna final fuerza el 60 en una única fila, replicando la restricción vista en el ejercicio original.
- La sesión se usa solo para lo necesario: lista de cartones y números salidos; todo lo demás se recalcula por petición.
