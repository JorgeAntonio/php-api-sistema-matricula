# php api sistema de matricula

Este proyecto es un sistema de matrícula de estudiantes desarrollado con PHP y MySQL. Incluye una interfaz de usuario para la gestión de matrículas y una API REST para acceder y modificar datos.

## Tabla de Contenidos

- [php api sistema de matricula](#php-api-sistema-de-matricula)
  - [Tabla de Contenidos](#tabla-de-contenidos)
  - [Demo](#demo)
  - [Características](#características)
  - [Instalación](#instalación)
    - [Requisitos Previos](#requisitos-previos)
    - [Pasos de Instalación](#pasos-de-instalación)
  - [Uso](#uso)
    - [Interfaz de Usuario](#interfaz-de-usuario)
    - [API REST](#api-rest)
  - [Contribución](#contribución)
  - [License](#license)

## Demo

Proporciona un enlace a una demostración en vivo del proyecto, si está disponible. Por ejemplo:

- [Demostración en Vivo](https://example.com)

## Características

Describe brevemente las principales características y funcionalidades del proyecto. Por ejemplo:

- Gestión de matrículas de estudiantes.
- API REST para acceder y modificar datos.
- Interfaz de usuario intuitiva.

## Instalación

Proporciona instrucciones claras sobre cómo instalar y configurar el proyecto en un entorno local.

### Requisitos Previos

- PHP (versión 8.1)
- Servidor web (Apache o Nginx)
- MySQL (u otro sistema de gestión de bases de datos compatible)

### Pasos de Instalación

1. Clona el repositorio:

   ```bash
   git clone https://github.com/JorgeAntonio/php-api-sistema-matricula.git

2. Configura la base de datos:

    Importa el archivo SQL proporcionado (data.sql) en tu servidor de base de datos.

3. Configura la aplicación:

    Copia el archivo de configuración de ejemplo (config.example.php) y renómbralo a config.php.
Edita config.php y configura las variables de conexión a la base de datos.

## Uso


### Interfaz de Usuario

- Accede a la aplicación en tu navegador web.
- Inicia sesión con las credenciales de administrador.
- Explora las opciones de matrícula y gestión de estudiantes.

### API REST

Accede a la API utilizando las rutas proporcionadas en api/.
Usa métodos HTTP (GET, POST, PUT, DELETE) para interactuar con los recursos (por ejemplo, estudiantes, cursos).

## Contribución


1. **Fork** el repositorio.
2. Crea una nueva rama (`git checkout -b feature-nueva-funcionalidad`).
3. Realiza tus cambios y haz commit (`git commit -am 'Agregar nueva funcionalidad'`).
4. Sube tus cambios a la rama (`git push origin feature-nueva-funcionalidad`).
5. Abre un **Pull Request**.


## License

[MIT](https://choosealicense.com/licenses/mit/)