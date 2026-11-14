# Agencia D.M · Sistema de usuarios con PHP y MySQL

Trabajo práctico final: registro, inicio de sesión y administración de usuarios para una agencia de representantes de futuros jugadores.

## Instalación (XAMPP)

1. Copiá la carpeta `agencia_demoliner` dentro de `C:\xampp\htdocs\`.
2. En XAMPP iniciá **Apache** y **MySQL** (los dos tienen que estar en verde).
3. Abrí `http://localhost/phpmyadmin/`, entrá a la pestaña **SQL**, pegá todo el contenido de `sql/database.sql` y apretá **Continuar**.
4. Abrí `http://localhost/agencia_representantes/crear_admin.php` y creá el primer administrador.
5. **Borrá el archivo `crear_admin.php`** del proyecto.
6. Entrá a `http://localhost/agencia_representantes/` e iniciá sesión.

## Archivos

| `conexion.php` | Conexión a MySQL con PDO |
| `escapar.php` | Función `escapar()` para mostrar datos sin riesgo (XSS) |
| `index.php` | Página de inicio |
| `registro.php` | Registro público (el rol siempre es `usuario`) |
| `login.php` | Inicio de sesión |
| `panel.php` | Panel personal del usuario |
| `cerrar_sesion.php` | Cierra la sesión |
| `admin_usuarios.php` | Listado de usuarios activos y buscador (admin) |
| `crear_usuario.php` | Alta de usuarios desde el panel admin |
| `editar_usuario.php` | Edición de datos de un usuario |
| `archivar_usuario.php` | Archiva una cuenta (borrado lógico) |
| `usuarios_archivados.php` | Listado de cuentas archivadas |
| `reactivar_usuario.php` | Reactiva una cuenta archivada |
| `crear_admin.php` | Crea el primer administrador (borrar después de usar) |
| `css/estilos.css` | Estilos |
| `img/` | Imágenes: `banner.svg` (inicio) y `perfil.svg` (panel). Reemplazalas por tus fotos |
| `sql/` | `database.sql` (base y tabla) y `datos_demo.sql` (datos de prueba) |

