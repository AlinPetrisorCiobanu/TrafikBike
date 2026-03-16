# 🏍️ TrafikBike

### Plataforma Web para Concesionario, Tienda y Taller de Motocicletas

![PHP](https://img.shields.io/badge/PHP-Backend-blue)
![JavaScript](https://img.shields.io/badge/JavaScript-Frontend-yellow)
![HTML5](https://img.shields.io/badge/HTML5-Markup-orange)
![CSS3](https://img.shields.io/badge/CSS3-Styles-blue)
![MariaDB](https://img.shields.io/badge/MariaDB-Database-brown)
![Docker](https://img.shields.io/badge/Docker-Container-blue)
![Render](https://img.shields.io/badge/Deploy-Render-purple)
![Railway](https://img.shields.io/badge/Database-Railway-black)

Proyecto final del certificado profesional\
**IFCD0210 --- Desarrollo de aplicaciones con tecnologías web**\
**Certificado de Profesionalidad Nivel 3**

------------------------------------------------------------------------

# 📌 Descripción del proyecto

**TrafikBike** es una aplicación web que simula el funcionamiento de un
**concesionario digital de motocicletas**, integrando en una misma
plataforma:

-   🏍️ **Venta de motocicletas nuevas y usadas**
-   🛒 **Tienda online de equipamiento para motoristas**
-   🔧 **Sistema de gestión de citas para taller mecánico**

La aplicación permite la **gestión completa del sistema mediante
operaciones CRUD**, incluyendo:

-   usuarios
-   vehículos
-   productos
-   citas de taller

El proyecto implementa una **arquitectura cliente-servidor**, donde el
**frontend interactúa con el backend mediante llamadas a API**, mientras
que las diferentes vistas se gestionan mediante **rutas web**.

La plataforma está diseñada principalmente para **aficionados al
motociclismo**, aunque también resulta útil para **usuarios interesados
en adquirir equipamiento o contratar servicios mecánicos**.

------------------------------------------------------------------------

# 🧰 Tecnologías utilizadas

## Backend

-   PHP
-   Arquitectura MVC
-   Programación Orientada a Objetos (POO)

## Frontend

-   HTML5
-   CSS3
-   JavaScript
-   JQuery

## Base de datos

-   MariaDB

## Infraestructura y despliegue

-   Docker
-   Apache (XAMPP en entorno local)
-   Render (servidor web)
-   Railway (base de datos)

------------------------------------------------------------------------

# ⚙️ Funcionalidades principales

## 👤 Gestión de usuarios

El sistema permite administrar cuentas de usuario con autenticación y
gestión de perfiles.

Funcionalidades:

-   Registro de nuevos usuarios
-   Inicio y cierre de sesión
-   Edición de perfil
-   Eliminación de cuenta
-   Gestión de usuarios desde panel de administración

------------------------------------------------------------------------

## 🏍️ Gestión de motocicletas

Sistema de administración del catálogo de motos del concesionario.

Funcionalidades:

-   Crear motos nuevas o usadas
-   Visualización del catálogo
-   Edición de información
-   Eliminación de motos
-   Sistema de filtros por:
    -   marca
    -   precio
    -   categoría (nuevo / usado)

------------------------------------------------------------------------

## 🛒 Tienda de equipamiento

Tienda online orientada a equipamiento para motoristas.

Funcionalidades:

-   Catálogo de productos
-   Gestión de inventario
-   Panel de administración de productos
-   Integración con pasarela de pago

------------------------------------------------------------------------

## 🔧 Taller mecánico

Sistema de gestión de citas para el taller.

Funcionalidades:

-   Solicitud de citas
-   Edición de citas
-   Cancelación de citas
-   Gestión de servicios del taller

------------------------------------------------------------------------

# 🎨 Experiencia de usuario

La interfaz está diseñada para ofrecer una **experiencia moderna y
accesible**, incluyendo:

-   Diseño **responsive**
-   Compatibilidad con **móvil, tablet y escritorio**
-   Validaciones en **frontend y backend**
-   Interactividad mediante **JavaScript**
-   Navegación optimizada

------------------------------------------------------------------------

# 🌐 Estructura de navegación

## Usuarios

-   Home
-   Catálogo de motos
-   Tienda
-   Taller
-   Mis datos
-   Contacto
-   Login / Logout
-   Registro

------------------------------------------------------------------------

# 🔐 Sistema de roles

La aplicación implementa **control de acceso basado en roles**.

## 👑 Administrador

Control completo del sistema:

-   Gestión de usuarios
-   Gestión de motos
-   Gestión de productos
-   Gestión de citas

## 🏍️ Vendedor

Gestión del catálogo comercial:

-   Crear motos
-   Editar motos
-   Gestionar productos de tienda

## 🔧 Mecánico

Gestión de servicios del taller:

-   Crear citas
-   Editar citas
-   Gestionar servicios mecánicos

------------------------------------------------------------------------

# 📂 Estructura del proyecto

    trafikbike/
    │
    ├── app/
    │   ├── controllers/
    │   ├── models/
    │   ├── core/
    │   └── config/
    │
    ├── public/
    │   ├── index
    │   ├── css/
    │   ├── js/
    │   ├── assets/
    │
    ├── views/
    │   ├── auth/
    │   ├── control_panel/
    │   │     ├── admin/
    │   │     ├── vendedor/
    │   │     └── mecanico/
    │   ├── errors/
    │   ├── layouts/
    │   └── usuario/
    │
    ├── database/
    │   trafikbike.sql
    │
    ├── dockerfile
    │
    └── README.md

------------------------------------------------------------------------

# 🚀 Instalación y ejecución

## 1️⃣ Clonar el repositorio

``` bash
git clone https://github.com/tuusuario/trafikbike.git
```

## 2️⃣ Acceder al proyecto

``` bash
cd trafikbike
```

## 3️⃣ Iniciar contenedores Docker

``` bash
docker-compose up -d
```

## 4️⃣ Acceder a la aplicación

    http://localhost

------------------------------------------------------------------------

# 🖼️ Capturas de pantalla

## Página principal

![home](screenshots/home.png)

## Catálogo de motos

![motos](screenshots/motos.png)

## Panel de administración

![admin](screenshots/admin.png)

------------------------------------------------------------------------

# 📦 Recursos utilizados

-   Imágenes generadas con IA o libres de derechos
-   Iconos personalizados
-   Tipografías web
-   Carrusel de imágenes
-   Diseño responsive
-   Logo personalizado

------------------------------------------------------------------------

# 🎯 Objetivo del proyecto

El objetivo de **TrafikBike** es desarrollar una aplicación web completa
que integre:

-   Gestión de usuarios
-   Gestión de vehículos
-   Gestión de productos
-   Gestión de citas de taller
-   Arquitectura cliente-servidor
-   Base de datos relacional
-   API para comunicación entre frontend y backend

Aplicando **buenas prácticas de desarrollo web**, arquitectura **MVC**,
y principios de **programación orientada a objetos**.

------------------------------------------------------------------------

# 📚 Posibles mejoras futuras

-   Sistema de carrito de compra completo
-   Historial de pedidos
-   Notificaciones por correo electrónico
-   Panel de estadísticas para administradores
-   API REST pública
-   Sistema de valoraciones de productos
