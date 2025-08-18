# 🚀 Autenticación de usuarios e integración con Shopify

Este proyecto implementa una **aplicación Laravel 12** que permite la autenticación de usuarios y la integración con Shopify para visualizar productos, pedidos y exportarlos en formato Excel.

---

## 📌 Funcionalidades principales

1. **Autenticación de usuarios** (registro/login simple con Laravel Breeze).
2. **Integración con Shopify** vía **OAuth** y **REST Admin API**.
3. **Visualización de productos** de la tienda conectada:
   - Nombre
   - SKU
   - Precio
   - Imagen
4. **Visualización de pedidos recientes** (últimos 30 días):
   - Cliente
   - Fecha
   - Productos comprados
   - Estado
5. **Exportación** de productos y pedidos a archivo Excel.

---

## 🛠️ Tecnologías utilizadas

- **[Laravel 12](https://laravel.com/docs/12.x)** – Framework PHP.
- **[PostgreSQL](https://www.postgresql.org/)** – Base de datos relacional.
- **[Nginx](https://www.nginx.com/)** – Servidor web y proxy inverso.
- **[Ngrok](https://ngrok.com/)** – Exposición del entorno local a Internet.
- **[Docker](https://www.docker.com/)** – Contenerización del entorno.
- **[TailwindCSS](https://tailwindcss.com/)** – Estilos y maquetado.
- **[Maatwebsite/Excel](https://laravel-excel.com/)** – Exportación de datos en formato Excel/CSV.

---

## 📋 Requisitos previos

- Docker (última versión)
- Docker Compose (última versión)
- Token de **Ngrok** para exponer la aplicación local
- Credenciales de Shopify (**API Key** y **API Secret**) y URL de la tienda

---

## ⚙️ Instalación y configuración en Docker

### 1. Clonar el repositorio
```bash
git clone https://github.com/jeancarlos110295/prueba-tecnica-amplifica-int-shopify.git
cd prueba-tecnica-amplifica-int-shopify
```

### 2. Configurar variables de entorno (Docker)

Copia el archivo `.env.example` y renómbralo:

```bash
cp .env.example .env
```

Edita las variables según tu entorno:

#### Variables para autenticacion con ngrok

Se debe obtener el token, ingresando al siguiente link: https://dashboard.ngrok.com/get-started/your-authtoken.

Despues de obtener el token configurarlo en la siguiente variable de entorno:

```
NGROK_AUTHTOKEN=""
```

---

### 3. Construir y levantar contenedores

```bash
docker-compose up -d --build
```

Despues de que se termine de hacer el build, verificar que los contenedores esten corriendo.

---

### 4. Instalar dependencias de Laravel

Accede al contenedor de la aplicación:

```bash
docker exec -it container_ecommerce_amplifica_app bash
```

### Pasos a seguir en el contenedor

```bash
composer install
```

---

### Configurar Laravel

Copia el archivo `.env.example` y renómbralo:

```bash
cp .env.example .env
```

Ingresar a la siguiente url: http://localhost:4040

Para obtener la URL HTTPS entregada por Ngrok

```
APP_URL=<URL_NGROK>
SHOPIFY_API_KEY=<tu_api_key>
SHOPIFY_API_SECRET=<tu_api_secret>
SHOPIFY_URL_SHOP=<tu-tienda.myshopify.com>
```

```bash
php artisan key:generate &&
php artisan optimize &&
php artisan migrate
```

---

### Ejecutar Vite (Tailwind + JS)

```bash
npm install &&
npm run dev -- --host
```
> Esto iniciará el servidor de Vite y permitirá la recarga en caliente de CSS/JS.

Si todo se ejecuta bien sin errores en el contenedor, al ingresar a la url entregada por Ngrok, no deberian de existir errores.

---

## ▶️ Flujo de uso (explicado)

1) **Autenticación en Laravel**
   - El usuario inicia sesión y llega al *Dashboard*.

2) **Conectar Shopify (inicio de OAuth)**
   - En el *Dashboard* hay un botón **“Conectar Shopify”** que llama:
     - `GET /shopify/install?shop=<tu-tienda>.myshopify.com`
   - El backend construye la URL de autorización de Shopify y **redirige** al panel de permisos.

3) **Permisos en Shopify**
   - Shopify muestra los permisos solicitados (ej. `read_products`, `read_orders`).
   - El usuario **acepta**.

4) **Callback a la app**
   - Shopify redirige a:
     - `GET /shopify/callback?code=...&shop=<tu-tienda>.myshopify.com`
   - El backend **intercambia** `code` por `access_token` (Admin REST API).

5) **Persistencia de credenciales**
   - Se guarda en BD (tabla `connected_shops`) el `shop` y el `access_token`, asociados al usuario autenticado.

6) **Uso de la integración**
   - **Productos:** `GET /shopify/products` → lista paginada (cards con Tailwind: nombre, SKU, precio, imagen).
   - **Pedidos (últimos 30 días):** `GET /shopify/pedidos` → cliente, fecha, items, estado.
   - **Exportación:** 
     - Productos a Excel: `GET /export/productos.xlsx`

### Notas importantes
- Asegura que **APP_URL** y las **Redirect URLs** en Shopify coincidan al 100% (mismo host/esquema/puerto).

1. Una vez conectada la tienda:
   - Ir a **/shopify/products** para ver productos.
     - Usar botón de **Descargar Excel**.
   - Ir a **/shopify/pedidos** para ver pedidos recientes.

---

## 📂 Estructura de carpetas relevante

```
app/
  DTOs/              # Objetos de transferencia de datos
  Interfaces/        # Interfaces (principio DIP de SOLID)
  Services/Shopify/  # Lógica de integración con Shopify
```

---

## 📜 Licencia
Este proyecto es parte de una prueba técnica y su uso es exclusivamente educativo.
