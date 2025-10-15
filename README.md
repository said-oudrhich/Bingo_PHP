# Juego de Bingo en PHP

Un juego de bingo completo desarrollado en PHP que simula una partida con múltiples jugadores y cartones.

## Descripción

Este proyecto implementa un juego de bingo tradicional donde 4 jugadores compiten con 3 cartones cada uno. El sistema sortea números del 1 al 60 y marca automáticamente los cartones hasta que un jugador complete todos los números de al menos uno de sus cartones.

## Estructura del Proyecto

```text
bingo/
├── index.php              # Interfaz principal del juego
├── funciones_carton.php   # Lógica de creación y manejo de cartones
├── controlador_partida.php # Control del flujo del juego
├── img/                   # Imágenes de números (1.PNG a 60.PNG)
└── README.md             # Este archivo
```

## Archivos Principales

### 1. index.php

**Propósito**: Interfaz de usuario y punto de entrada principal.

**Funcionalidades**:

- Inicia la sesión PHP para mantener el estado del juego
- Configura los parámetros del juego (4 jugadores, 3 cartones cada uno)
- Procesa las acciones del usuario (sacar número, reiniciar)
- Muestra la interfaz HTML con los cartones y controles

**Flujo de ejecución**:

1. `session_start()` - Inicia/continúa la sesión
2. Incluye los archivos de funciones
3. Inicializa la partida si es necesaria
4. Procesa acciones POST del usuario
5. Obtiene datos actualizados del juego
6. Renderiza la interfaz HTML

### 2. funciones_carton.php

**Propósito**: Manejo de cartones de bingo.

**Funciones principales**:

- `crearCarton()`: Genera un cartón de 3x7 con 15 números
  - Columnas 1-6: números del 1-10, 11-20, ..., 51-60
  - Columna 7: solo el número 60
  - Elimina aleatoriamente números hasta dejar exactamente 15

- `crearTabla($carton, $numerosSacados)`: Convierte cartón en HTML
  - Marca en rojo los números ya sorteados
  - Muestra "-" en casillas vacías

- `cartonCompleto($carton, $numerosSacados)`: Verifica si hay bingo
  - Comprueba que todos los números del cartón estén sorteados

- `comprobar($num, $carton)`: Evita números duplicados
- `borrarEspacios(&$carton)`: Elimina números para dejar 15

### 3. controlador_partida.php

**Propósito**: Lógica del flujo del juego.

**Funciones principales**:

- `inicializar_partida($jugadores, $cartones_por_jugador)`:
  - Crea cartones para todos los jugadores si no existen
  - Inicializa array de números sorteados

- `procesar_acciones()`: Maneja formularios POST
  - Botón "Sacar número nuevo" → `sacar_numero()`
  - Botón "Reiniciar partida" → `reiniciar_partida()`

- `sacar_numero()`: Sortea números del 1-60
  - Evita repetir números ya sorteados
  - No permite sortear si ya hay ganador

- `obtener_ganador()`: Busca jugadores con bingo completo
  - Recorre todos los cartones de todos los jugadores
  - Retorna el número del primer ganador encontrado

- `obtener_datos_juego()`: Recopila estado actual
  - Lista de jugadores y cartones
  - Números sorteados y último número
  - Información del ganador

## Conceptos PHP Utilizados

### Sessions (Sesiones)

```php
session_start();
$_SESSION['listaJugadores'] = $datos;
```

- **Propósito**: Mantener el estado del juego entre peticiones HTTP
- **Datos almacenados**: Cartones de jugadores, números sorteados
- **Ventaja**: El juego persiste al recargar la página

### Includes/Requires

```php
require 'funciones_carton.php';
require 'controlador_partida.php';
```

- **Propósito**: Modularizar el código en archivos separados
- **Diferencia**: `require` detiene ejecución si falla, `include` solo advierte

### Formularios POST

```php
if (isset($_POST['sacar_numero'])) {
    // Procesar acción
}
```

- **Propósito**: Capturar acciones del usuario (botones)
- **Método POST**: Adecuado para acciones que modifican estado

### Arrays Multidimensionales

```php
$lista_jugadores[1][0] = $carton; // Jugador 1, Cartón 0
```

- **Estructura**: `$jugadores[número_jugador][índice_cartón]`
- **Uso**: Organizar cartones por jugador

## Flujo del Juego

1. **Inicio**: Usuario accede a `index.php`
2. **Inicialización**: Se crean cartones si no existen en sesión
3. **Interfaz**: Se muestra estado actual (cartones, números sorteados)
4. **Acción**: Usuario hace clic en "Sacar número nuevo"
5. **Procesamiento**: Se sortea número y actualiza estado
6. **Verificación**: Se comprueba si hay ganador
7. **Actualización**: Se recarga página con nuevo estado
8. **Fin**: Cuando hay ganador, se deshabilita el sorteo

## Requisitos

- **PHP 7.0+**: Para sintaxis moderna (`<?= ?>`, `random_int()`)
- **Servidor web**: Apache, Nginx, o servidor de desarrollo PHP
- **Imágenes**: Archivos `1.PNG` a `60.PNG` en carpeta `img/`

## Instalación

1. Clonar/descargar archivos en directorio del servidor web
2. Crear carpeta `img/` con imágenes de números (1.PNG a 60.PNG)
3. Acceder a `index.php` desde navegador
4. ¡Jugar!