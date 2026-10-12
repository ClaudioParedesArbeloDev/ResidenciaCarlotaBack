<h1 align="center">Residencia Carlota · API</h1>

<p align="center">
  API REST para la gestión clínica y operativa de residencias geriátricas · REST API for clinical and operational management of elderly care homes
</p>

<p align="center">
  <img src="https://img.shields.io/badge/PHP_8.3-777BB4?style=for-the-badge&logo=php&logoColor=white" />
  <img src="https://img.shields.io/badge/Laravel_13-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" />
  <img src="https://img.shields.io/badge/Sanctum-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" />
  <img src="https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white" />
</p>

<p align="center">
  <a href="#-español">Español</a> · <a href="#-english">English</a>
</p>

> Frontend Flutter: **[ResidenciaCarlotaFront](https://github.com/ClaudioParedesArbeloDev/ResidenciaCarlotaFront)**

---

## 🇪🇸 Español

### Qué es

Backend en Laravel que expone los endpoints que consumen las apps de **Residencia Carlota**: historia clínica, indicaciones médicas, signos vitales, camas y personal. Está diseñado como **multi-institución**: un superadmin crea proyectos e instituciones, y cada usuario trabaja dentro de la institución a la que pertenece, con permisos según su rol.

### Módulos

| Área | Recursos |
|---|---|
| **Autenticación** | Login con token (Sanctum), Google (Socialite), recuperación de contraseña |
| **Administración** | Proyectos, instituciones, empleados, roles, especialidades |
| **Infraestructura** | Habitaciones, camas, asignación de camas a pacientes |
| **Pacientes** | Datos personales, contactos familiares (con contacto principal), alergias |
| **Clínica** | Diagnósticos (codificación **CIE-10**), evoluciones, estudios médicos, signos vitales |
| **Medicación** | Vademécum, prescripciones (suspender / reactivar) y horarios de administración |

### Roles y permisos

Las rutas se protegen con middleware propio (`super_admin`, `institution`, `role:…`):

- **super_admin** — gestiona proyectos e instituciones.
- **admin** — personal, especialidades, habitaciones y camas.
- **doctor** — pacientes, diagnósticos, prescripciones, estudios.
- **doctor / nurse** — signos vitales.
- **doctor, nurse, kinesiologist, nutritionist, social_worker** — evoluciones y consulta de historia clínica.

### Instalación

```bash
git clone https://github.com/ClaudioParedesArbeloDev/ResidenciaCarlotaBack.git
cd ResidenciaCarlotaBack
# crear .env con la configuración estándar de Laravel (MySQL, Google OAuth)
composer setup        # instala dependencias, genera key, migra y compila assets
php artisan serve
```

Variables clave del `.env`: conexión MySQL (`DB_*`) y Google OAuth (`GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET`, `GOOGLE_REDIRECT_URI`).

Tests:

```bash
php artisan test
```

---

## 🇬🇧 English

### What it is

Laravel backend serving the **Residencia Carlota** apps: medical records, prescriptions, vital signs, beds and staff. It is **multi-institution**: a super admin creates projects and institutions, and every user works inside their own institution with role-based permissions.

### Modules

- **Auth:** Sanctum tokens, Google sign-in (Socialite), password reset.
- **Administration:** projects, institutions, employees, roles, specialties.
- **Facilities:** rooms, beds, patient-bed assignments.
- **Patients:** personal data, family contacts, allergies.
- **Clinical:** diagnoses with **ICD-10** codes, progress notes, medical studies, vital signs.
- **Medication:** drug catalogue, prescriptions (suspend / reactivate) and administration schedules.

### Roles

Custom middleware (`super_admin`, `institution`, `role:…`) restricts each group of endpoints to super admins, admins, doctors, nurses and allied health staff (physiotherapists, nutritionists, social workers).

### Setup

Create a `.env` with your MySQL connection (`DB_*`) and Google OAuth credentials (`GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET`, `GOOGLE_REDIRECT_URI`), then:

```bash
composer setup
php artisan serve
```

---

<p align="center">
  Hecho por / Built by <a href="https://github.com/ClaudioParedesArbeloDev"><b>Claudio Paredes Arbelo</b></a> · <a href="https://www.claudioparedes.site">Code & Lens Solutions</a>
</p>
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
