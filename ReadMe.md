# Aplicación PHP: Registro de Usuarios

Esta es una aplicación PHP desarrollada siguiendo los principios de **Domain-Driven Design (DDD)** y **Ports & Adapters**. La aplicación permite registrar usuarios, validar sus datos y persistirlos en una base de datos MySQL utilizando **Doctrine** como ORM. Además, se incluyen pruebas unitarias y de integración con **PHPUnit**, y el entorno se puede desplegar fácilmente usando **Docker**.

## Requisitos

- Docker
- Docker Compose
- PHP 8.1 o superior
- Composer

## Instalación

1. Clona el repositorio

    ```sh
    git clone https://github.com/Jorge-Ivan/user-manager-with-ddd.git
    cd user-manager-with-ddd
    ```

2. Iniciar el Entorno con Docker

    ```sh
    make setup
    ```
    Este comando hará lo siguiente:

    - Levantar los servicios de PHP y MySQL.
    - Instalar las dependencias de Composer.

3. Acceder al Contenedor de PHP

    ```sh
    make bash
    ```

4. Ejecutar las Migraciones (Opcional)

    ```sh
    make migrate
    ```

5. Ejecutar las Pruebas

    ```sh
    make test
    ```

6. Acceder a la Aplicación

    La aplicación estará disponible en **http://localhost:9000** (si el contenedor se compilo y ejecuto correctamente).

    - **Registro de usuarios**

        ```POST /api/register```

        Body ```application/json```:
        ```json copy
        {
            "name": "John Doe",
            "email": "john@example.com",
            "password": "Str0ngP@ss!"
        }
        ```

7. Apagar contenedor

    ```sh
    make down
    ```

## Configuración de Docker
El archivo ```docker-compose.yml``` define dos servicios:

1. php: Contenedor con PHP, Composer y Doctrine.
2. mysql: Contenedor con MySQL en el puerto (3307) y adminer para gestionar la base de datos en **http://localhost:8080**.

## Dependencias
Las dependencias principales incluyen:

- Doctrine: Para la gestión de la base de datos.
- PHPUnit: Para pruebas automatizadas.
- Symfony HTTP Foundation: Para manejo de solicitudes y respuestas HTTP.
