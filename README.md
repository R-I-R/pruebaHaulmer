# API REST con laravel 8 y mockApi

## Instalacion
1. Clonar repositorio en su carpeta de proyecto
2. Ejecutar **composer install** en la carpeta raiz del proyecto
3. Ejecutar **php artisan serve**

## Uso
La api por defecto se inicia en http://localhost:8000, esta cuenta con 3 endpoints detallados a continuacion:
| Metodo | Url        | Codigo | Respuesta | Descripcion                                   |
|--------|------------|--------|-----------|-----------------------------------------------|
| POS    | /api/new   | 200    | usuario   | Crea un nuevo usuario y devuelve su vista     |
| POST   | /api/login | 200    | token     | Inicia sesion del usuario y devuelve un token |
| DELETE | /api/login | 200    | mensaje   | Cierra la sesion del usuario                  |
| GET    | /api/me    | 200    | usuario   | Devuelve la informacion del usuario           |
| PUT    | /api/me    | 200    | usuario   | Modifica la informacion del usuario           |
| DELETE | /api/me    | 200    | mensaje   | Elimina al usuario                            |

---
# Capturas de uso
El usuario se crea haciendo POST a /api/new entregando un JSON con el email, nombre y contraseña
![crear usuario](./images/new_user.png)
La sesion se inica haciendo POST a /api/login entregando al body un JSON con el email y contraseña
![iniciar sesion](./images/login.png)
La consulta de datos se realiza haciendo un GET a /api/me pasando el token de sesion por la cabecera
![consultar datos](./images/consulta_datos.png)
Los datos se editan haciendo PUT a /api/me pasando porel body los datos a editar y con el token en el header
![editar datos](./images/update_usuario.png)
La sesion se cierra haciendo DELETE a /api/login
![cerrar sesion](./images/logout.png)
Como los datos fueron editados anteriormente el login debe hacerse con las nuevas credenciales
![inicio de sesion datos nuevos](./images/new_login.png)
El usuario se borra haciendo DELETE a /api/me
![borrar usuario](./images/delete_user.png)

---
[Postman documentation](https://documenter.getpostman.com/view/18671160/UVJihZtz)
