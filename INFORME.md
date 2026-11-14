# Informe del Trabajo Práctico Final

**Proyecto:** Agencia D.M - Agencia de representación de futuros jugadores
**Alumno/a:** Matias De Moliner - **Docente:** Prof. Alan Acosta

## 1. Cómo funciona el sistema

Cualquier persona puede crear una cuenta, iniciar sesión y ver sus datos en un panel personal. Los administradores, además, pueden registrar usuarios, buscarlos, editarlos, archivarlos y reactivarlos.

El camino de los datos es siempre el mismo:
**formulario HTML → PHP valida → PDO (consulta preparada) → MySQL → PHP muestra el resultado.**

- **Registro** (`registro.php`): valida los datos, cifra la contraseña con `password_hash()` y guarda con un `INSERT` preparado. El rol siempre es `usuario`.
- **Login** (`login.php`): busca al usuario por correo, compara con `password_verify()` y guarda en `$_SESSION` el id, el nombre y el rol. Al admin lo manda a `admin_usuarios.php` y al usuario común a `panel.php`.
- **Panel** (`panel.php`): si no hay sesión, redirige al login. Muestra los datos del usuario, nunca la contraseña.
- **Panel admin**: todas las páginas verifican la sesión y el rol `admin`; si no, responden 403.
- **Archivar**: no borra el registro, guarda la fecha en `archivado_en`. Los activos son los que tienen `archivado_en IS NULL`. **Reactivar** vuelve a poner `NULL`.

## 2. Base de datos

Base `agencia_dm`, tabla `usuarios`: `id`, `nombre`, `apellido`, `dni` (UNIQUE), `email` (UNIQUE), `password_hash`, `rol`, `creado_en`, `archivado_en`.

## 3. Medidas de seguridad

| Medida | Dónde |
|---|---|
| Contraseñas cifradas con `password_hash()` / `password_verify()` | registro, login, alta admin |
| Consultas preparadas con PDO (`prepare` + `execute`) | todos los archivos |
| `escapar()` con `htmlspecialchars` al mostrar datos (evita XSS) | todas las páginas |
| Validación en el servidor (campos vacíos, DNI, correo, contraseña) | formularios |
| DNI y correo únicos: restricción `UNIQUE` y captura del error `23000` | registro, alta, edición |
| Páginas privadas verifican la sesión | panel y panel admin |
| Páginas admin verifican el rol `admin` (error 403) | panel admin |
| `session_regenerate_id(true)` al iniciar sesión | login |
| Token CSRF en los formularios del panel admin | alta, edición, archivar, reactivar |
| Archivar y reactivar solo aceptan POST (error 405 si no) | archivar, reactivar |
| El id recibido se valida con `filter_input` (400 si es inválido, 404 si no existe) | editar, archivar, reactivar |
| No se muestran contraseñas ni hashes en ninguna pantalla | todas |
| El admin no puede archivarse a sí mismo | archivar |


## 4. Capturas 

(En carpeta `capturas/`):

| 1 | `01_inicio.png` | Página de inicio con el banner | 
| 2 | `02_registro_errores.png` | Formulario de registro con errores de validación (campos vacíos, DNI inválido, contraseñas distintas) |
| 3 | `03_registro_ok.png` | Mensaje "Cuenta creada correctamente" |
| 4 | `04_registro_duplicado.png` | Intento de registrar un DNI o correo ya existente |
| 5 | `05_login_error.png` | Login con datos incorrectos |
| 6 | `06_panel_usuario.png` | Panel personal de un usuario común, con sus datos |
| 7 | `07_admin_listado.png` | Listado de usuarios activos del administrador |
| 8 | `08_admin_busqueda.png` | Búsqueda con resultados (por ejemplo, un apellido) |
| 9 | `09_phpmyadmin_tabla.png` | phpMyAdmin → tabla `usuarios`, mostrando `password_hash` cifrado y la columna `archivado_en` |

### 5 Dificultades encontradas: MySQL de XAMPP no iniciaba

Al empezar a probar el sistema, MySQL no arrancaba en el panel de XAMPP. El archivo `mysql_error.log` mostraba un cierre y, en cada arranque, el mensaje "Starting crash recovery": la carpeta de datos se había dañado por un apagado incorrecto.

Lo pude solucionar con ayuda de Claude, que me dio la siguiente solucion: con XAMPP cerrado, se renombró `C:\xampp\mysql\data` a `data_vieja`, se copió la carpeta `backup` y se la renombró `data`. Al iniciar de nuevo, MySQL arrancó (en verde).

### 5.2 Problemas habituales de este tipo de sistema y cómo los previene el proyecto

| Problema habitual | Cómo lo resuelve el proyecto |
|---|---|
| Que se registren dos personas con el mismo DNI o correo | La tabla tiene `UNIQUE` en `dni` y `email`, y PHP captura el error `23000` para mostrar un mensaje claro |
| Que alguien escriba código (`<script>`) en un formulario y se ejecute en la página | Todo dato se muestra con `escapar()` (`htmlspecialchars`) |
| Que alguien manipule una consulta escribiendo SQL en un formulario | Todas las consultas usan `prepare()` y `execute()` de PDO |
| Que el buscador no encuentre "Joaquín Benítez" al escribir nombre y apellido juntos | La consulta agrega `CONCAT(nombre, ' ', apellido) LIKE ...` |
| Que se repita el mismo parámetro en una consulta preparada | Cada parámetro tiene nombre distinto (`:b1`, `:b2`, ...) |
| Que alguien se registre como administrador cambiando el formulario | El rol no viene del formulario: `registro.php` siempre guarda `usuario` |
| Que archivar o reactivar se ejecute con solo abrir un enlace | Esas acciones aceptan solo POST (error 405 si no) y llevan token CSRF |
| Que alguien cambie el `id` de la URL por letras o por números que no existen | `filter_input` valida el id: error 400 si es inválido y 404 si no existe |
| Que el código siga ejecutándose después de redirigir con `header()` | Cada redirección termina con `exit` |
| Que un administrador se archive a sí mismo y quede sin acceso | `archivar_usuario.php` lo rechaza |
| Que una cuenta archivada siga usando el sistema con la sesión abierta | `panel.php` consulta la base en cada carga y cierra la sesión si la cuenta ya no está activa |
| Que se pueda ingresar a una página privada escribiendo la dirección | Cada página privada revisa la sesión; las de administración, además, el rol |

## 6. Herramientas utilizadas

Para informe, readme, y algunas archivos mas que no entendia he usado la IA Claude, que explica muy bien todo lo que hace.