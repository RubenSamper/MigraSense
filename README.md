<div align="center">

<img src="public/images/migrasense-logo.svg" alt="MigraSense" width="120" />

# MigraSense

**Anticípate a tu migraña antes de que aparezca.**

MigraSense es una aplicación web que ayuda a personas con migraña a registrar sus episodios y a anticipar días de riesgo a partir de la variación de presión atmosférica de su zona, obtenida de la API pública de AEMET OpenData.

[![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?logo=php&logoColor=white)](https://www.php.net/)
[![Laravel](https://img.shields.io/badge/Laravel-FF2D20?logo=laravel&logoColor=white)](https://laravel.com/)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?logo=mysql&logoColor=white)](https://dev.mysql.com/)
[![Docker](https://img.shields.io/badge/Docker-Compose-2496ED?logo=docker&logoColor=white)](https://docs.docker.com/)
[![Deployed on Railway](https://img.shields.io/badge/Deploy-Railway-0B0D0E?logo=railway&logoColor=white)](https://railway.com/)

</div>

---

## 📋 Índice

- [Descripción](#-descripción)
- [Funcionalidades](#-funcionalidades)
- [Roles de usuario](#-roles-de-usuario)
- [Tecnologías](#-tecnologías)
- [Puesta en marcha](#-puesta-en-marcha)
- [Variables de entorno](#-variables-de-entorno)
- [Estructura del proyecto](#-estructura-del-proyecto)
- [Autor](#-autor)

---

## 📝 Descripción

Muchas personas con migraña ya llevan un registro mental o en papel de sus episodios y de las condiciones del día. MigraSense automatiza y sistematiza ese seguimiento: compara los datos meteorológicos oficiales de la estación AEMET más cercana con la sensibilidad a la presión configurada por cada usuario, y muestra un semáforo de riesgo (🟢 verde / 🟡 amarillo / 🔴 rojo) cada vez que se accede al panel.

Proyecto Final de Ciclo Formativo de Grado Superior en **Desarrollo de Aplicaciones Web**, desplegado en producción y accesible públicamente.

## ✨ Funcionalidades

- 🔐 Registro e inicio de sesión, propio o con cuenta de Google (OAuth 2.0)
- 🌦️ Semáforo de riesgo diario calculado a partir de datos reales de AEMET
- 📝 Registro rápido de episodios (tipo, intensidad, síntomas, horario)
- 📊 Seguimiento histórico con gráficas de evolución (Chart.js)
- 📄 Exportación del seguimiento a PDF
- 🩺 Panel para médicos: revisión de pacientes asignados y diagnósticos
- 🛠️ Panel de administración: gestión de pacientes y altas de cuentas de médico
- 📱 Diseño responsive (escritorio, tablet y móvil)

## 👥 Roles de usuario

| Rol | Qué puede hacer |
| --- | --- |
| **Paciente** | Registrar episodios, consultar su semáforo de riesgo y su historial, configurar su estación meteorológica |
| **Médico** | Consultar los episodios de sus pacientes asignados y dejar un diagnóstico |
| **Administrador** | Gestionar el listado de pacientes y dar de alta nuevas cuentas de médico |

## 🛠️ Tecnologías

- **Backend:** [Laravel](https://laravel.com/docs) (PHP)
- **Base de datos:** [MySQL 8.0](https://dev.mysql.com/doc)
- **Frontend:** Blade, jQuery, [Chart.js](https://www.chartjs.org)
- **Autenticación:** Laravel [Socialite](https://laravel.com/docs/socialite) (Google OAuth 2.0)
- **Datos meteorológicos:** [AEMET OpenData](https://opendata.aemet.es)
- **Entorno de desarrollo:** Docker Compose (PHP, MySQL, phpMyAdmin)
- **Despliegue:** [Railway](https://docs.railway.com) (despliegue continuo desde GitHub)

## 🚀 Puesta en marcha

### Requisitos

- [Docker](https://docs.docker.com) y Docker Compose
- Una clave de API de [AEMET OpenData](https://opendata.aemet.es) (gratuita)
- Credenciales de OAuth de Google (si se quiere usar el inicio de sesión con Google)

### Pasos

```bash
# 1. Clonar el repositorio
git clone https://github.com/RubenSamper/MigraSense.git
cd MigraSense

# 2. Copiar el archivo de entorno y completar las variables
cp .env.example .env

# 3. Levantar los contenedores
docker compose up -d

# 4. Instalar dependencias de PHP
docker exec laravel_app composer install

# 5. Generar la clave de aplicación
docker exec laravel_app php artisan key:generate

# 6. Ejecutar las migraciones
docker exec laravel_app php artisan migrate
```

La aplicación quedará disponible en **http://localhost:8000** y phpMyAdmin en **http://localhost:8080**.

## 🔑 Variables de entorno

| Variable | Descripción |
| --- | --- |
| `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` | Credenciales de conexión a MySQL |
| `AEMET_API_KEY` | Clave de la API de AEMET OpenData |
| `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET`, `GOOGLE_REDIRECT_URL` | Credenciales de OAuth 2.0 de Google |
| `APP_KEY` | Clave de cifrado de Laravel (se genera con `artisan key:generate`) |

> ⚠️ El archivo `.env` nunca se sube al repositorio (está en `.gitignore`). En producción, estas variables se configuran directamente en el panel de Railway.

## 📁 Estructura del proyecto

```
app/
├── Http/Controllers/    # Controladores (usuarios, migrañas)
├── Models/              # Modelos Eloquent
├── Services/            # Integración con la API de AEMET
└── Support/             # Lógica auxiliar (cálculo visual del riesgo)
resources/views/         # Vistas Blade (paciente, médico, administrador)
routes/web.php           # Definición de rutas
```

## 👤 Autor

**Rubén Samper Cruz**
Proyecto Final de Ciclo Formativo de Grado Superior — Desarrollo de Aplicaciones Web
Curso académico 2025/2026

---

<div align="center">
<sub>Hecho con ❤️ para ayudar a quienes conviven con migraña.</sub>
</div>
