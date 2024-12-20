# Laravel

## Descripción
Este proyecto es un sistema de gestión de propiedades, reservas, incidencias y tareas desarrollado con Laravel. Proporciona una interfaz de usuario basada en Blade templates para administrar propiedades, realizar reservas, gestionar incidencias y asignar tareas asociadas.

---

## Instalación

### Requisitos Previos
Asegúrate de tener instalados los siguientes programas:

- [PHP](https://www.php.net/) (v8.1 o superior)
- [Composer](https://getcomposer.org/)
- [MySQL](https://www.mysql.com/) u otro sistema de bases de datos compatible
- [Node.js](https://nodejs.org/) (v16 o superior, para compilar assets con Vite)
- Servidor web como Apache o Nginx

### Pasos de Instalación

1. **Clonar el Repositorio**
   ```bash
   git clone https://github.com/DieGomezR/test-homeclub
   cd test-homeclub
   ```

2. **Instalar Dependencias de PHP**
   ```bash
   composer install
   ```

3. **Instalar Dependencias de Node.js**
   ```bash
   npm install
   ```

4. **Configurar el Archivo `.env`**
   Crea un archivo `.env` basado en el ejemplo incluido:
   ```bash
   cp .env.example .env
   ```
   Edita el archivo `.env` para configurar los detalles de conexión a la base de datos:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nombre_de_la_base_de_datos
   DB_USERNAME=usuario
   DB_PASSWORD=contraseña
   ```

5. **Generar la Clave de la Aplicación**
   ```bash
   php artisan key:generate
   ```

6. **Ejecutar las Migraciones**
   Este paso creará las tablas necesarias en tu base de datos:
   ```bash
   php artisan migrate
   ```

7. **Compilar los Assets**
   Para compilar los archivos CSS y JavaScript:
   ```bash
   npm run dev
   ```
   Si estás preparando el proyecto para producción, usa:
   ```bash
   npm run build
   ```

8. **Iniciar el Servidor de Desarrollo**
   ```bash
   php artisan serve
   ```
   La aplicación estará disponible en: [http://127.0.0.1:8000](http://127.0.0.1:8000).

---

## Uso

### Navegación por Módulos

- **Propiedades:** [http://127.0.0.1:8000/properties](http://127.0.0.1:8000/properties)
- **Reservas:** [http://127.0.0.1:8000/bookings](http://127.0.0.1:8000/bookings)
- **Incidencias:** [http://127.0.0.1:8000/incidences](http://127.0.0.1:8000/incidences)

Cada módulo permite crear, ver y administrar los elementos correspondientes.

---

## Comandos Útiles

- **Limpiar cachés:**
  ```bash
  php artisan cache:clear
  php artisan config:clear
  php artisan route:clear
  ```

---

## Contribuciones

Si deseas contribuir al proyecto:

1. Haz un fork del repositorio.
2. Crea una rama para tu feature:
   ```bash
   git checkout -b feature/nueva-funcionalidad
   ```
3. Realiza tus cambios y haz commit:
   ```bash
   git commit -m "Descripción de los cambios"
   ```
4. Envía un pull request.

---

## Licencia

Este proyecto está licenciado bajo la [MIT License](LICENSE).
