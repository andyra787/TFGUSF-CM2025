# USF-CM2025
System

Introducción
Este manual te guiará paso a paso para instalar y configurar el Sistema USF en tu entorno de desarrollo local. El sistema está construido con Laravel y requiere tanto PHP como Node.js para su funcionamiento completo.
Requisitos del Sistema
Antes de comenzar la instalación, asegúrate de tener instalados los siguientes componentes:
Requisitos Obligatorios
Componente	Versión Mínima	Enlace de Descarga
PHP	        8.2             -->	https://www.php.net/downloads.php

Composer	2.5 o superior	--> https://getcomposer.org/download/

Node.js	    14	            --> https://nodejs.org/en/download/prebuilt-installer/current


Opción Recomendada para PHP
Para una instalación más sencilla de PHP, se recomienda utilizar:

-	XAMPP v3.3.0 que incluye PHP 8.2+ y otros componentes necesarios
  
Verificar Instalación de Requisitos
Para verificar que tienes los componentes instalados correctamente, ejecuta los siguientes comandos en tu terminal:

# Verificar versión de PHP

php --version

# Verificar versión de Composer

composer --version

# Verificar versión de Node.js

node --version

# Verificar versión de NPM

npm --version

Instalación de Dependencias
Paso 1: Clonar o Descargar el Proyecto
Asegúrate de tener el proyecto del Sistema USF en tu máquina local y navega hasta la carpeta principal del proyecto.

cd ruta/hacia/sistema-usf
Paso 2: Instalar Dependencias de PHP
Ejecuta el siguiente comando para instalar todas las dependencias de PHP definidas en composer.json:

composer install

Nota: Este proceso puede tomar varios minutos dependiendo de tu conexión a internet.

Paso 3: Instalar Dependencias de Node.js
Una vez completada la instalación de Composer, instala las dependencias de Node.js:

npm install

Importante: Asegúrate de tener Node.js 14.x instalado antes de ejecutar este comando.

Configuración del Proyecto
Paso 4: Configurar Variables de Entorno
1.	Localiza el archivo .env.example en la carpeta raíz del proyecto
2.	Copia el archivo y renómbralo a .env
3.	Edita el archivo .env según tus necesidades (configuración de base de datos, etc.)

# En Windows

copy .env.example .env

# En macOS/Linux

cp .env.example .env

Paso 5: Generar Clave de Aplicación
Ejecuta el siguiente comando para generar una clave única para tu aplicación:

php artisan key:generate

Este comando actualizará automáticamente el archivo .env con la nueva clave.

Configuración de Base de Datos
Paso 6: Configurar Base de Datos
Antes de ejecutar las migraciones, asegúrate de:

1.	Crear una base de datos en tu servidor de base de datos
2.	Configurar las credenciales en el archivo .env:

DB_CONNECTION=mysql

DB_HOST=127.0.0.1

DB_PORT=3306

DB_DATABASE=nombre_de_tu_base_de_datos

DB_USERNAME=tu_usuario

DB_PASSWORD=tu_contraseña

Paso 7: Ejecutar Migraciones
Ejecuta las migraciones para crear las tablas necesarias:

php artisan migrate
Paso 8: Ejecutar Seeders
Carga los datos iniciales en la base de datos:

php artisan db:seed

Inicialización del Servidor
Para iniciar el sistema, necesitarás dos terminales abiertas simultáneamente:
Terminal 1: Servidor Laravel
php artisan serve
Terminal 2: Servidor de Desarrollo de Assets
npm run dev

Verificación de la Instalación
Una vez que ambos servidores estén ejecutándose:

1.	Abre tu navegador web
2.	Navega a: http://127.0.0.1:8000/
3.	Verifica que el sistema cargue correctamente

Nota: Si el puerto 8000 está ocupado, Laravel automáticamente asignará otro puerto. Revisa la salida en tu terminal para ver la URL exacta.
Solución de Problemas
Problemas Comunes y Soluciones
Error: "composer command not found"
# Solución: Reinstala Composer y asegúrate de que esté en tu PATH

# Windows: Reinicia tu terminal/PowerShell después de la instalación

# macOS/Linux: Verifica que Composer esté en /usr/local/bin
Error: "php artisan command not found"
# Solución: Asegúrate de estar en la carpeta correcta del proyecto

cd ruta/hacia/sistema-usf

php artisan --version
Error: "npm command not found"
# Solución: Reinstala Node.js y reinicia tu terminal

node --version

npm --version
Error de permisos en archivos
# En macOS/Linux, si tienes problemas de permisos:

sudo chmod -R 755 storage/

sudo chmod -R 755 bootstrap/cache/
Puerto ocupado
# Si el puerto 8000 está ocupado, especifica otro:

php artisan serve --port=8001
Logs y Depuración
Los logs del sistema se encuentran en:

storage/logs/laravel.log

Para más información de depuración, puedes activar el modo debug en tu archivo .env:

APP_DEBUG=true

________________________________________
Contacto y Soporte
Si encuentras problemas durante la instalación que no se resuelven con este manual, por favor:

1.	Revisa los logs de error
2.	Verifica que todos los requisitos estén instalados correctamente
3.	Consulta la documentación oficial de Laravel

________________________________________

¡Instalación Completada! 🎉

Tu Sistema USF debería estar funcionando correctamente en http://127.0.0.1:8000/

