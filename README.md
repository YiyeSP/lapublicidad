# LA PUBLICIDAD — Tema para WordPress + Elementor

Tema de WordPress para **LA PUBLICIDAD**, negocio de impresión digital y publicidad en gran formato en Putumayo, Colombia. Se edita arrastrando y soltando con Elementor (versión gratuita).

> Proyecto académico — Tecnólogo en Desarrollo Multimedia y Web, SENA.
> Autor: **Ramiro Solarte Pérez** · Ficha 3336532

## Qué incluye
- Carrusel 3D de productos (Coverflow) conectado al tipo de contenido *Productos*.
- Cotizador con calculadora de precios en COP, modo rápido y formal.
- Base de datos propia para cotizaciones y códigos de descuento web (10 %, 5 días, uno por mes por número de WhatsApp).
- Tipos de contenido: Productos, Servicios y Portafolio.
- Panel de administración "LA PUBLICIDAD" (cotizaciones, descuentos, precios).
- Widgets propios para Elementor y dos plantillas importables (`elementor-templates/`).
- Menú móvil off-canvas y diseño adaptable (celular, tablet y escritorio).

## Requisitos
WordPress 6.0+ · PHP 7.4+ · Elementor (gratuito).

## Instalación
1. Comprime la carpeta `lapublicidad` en un `.zip` (o descarga el release).
2. WordPress → Apariencia → Temas → Añadir nuevo → Subir tema → Activar.
3. LA PUBLICIDAD → Contenido demo → Importar contenido de demostración.
4. Más detalles en `LEEME.txt`.

## Estructura
```
lapublicidad/
├── functions.php          # carga los módulos de /inc
├── header.php / footer.php
├── front-page.php         # inicio (Elementor o landing por defecto)
├── single-lp_servicio.php # página de cada servicio
├── template-parts/        # landing por defecto
├── inc/                   # setup, cpt, quote, shortcodes, admin, demo, elementor
├── assets/                # css, js, imágenes
└── elementor-templates/   # plantillas .json importables
```

## Metodología de trabajo
Kanban personal en GitHub Projects (Por hacer → En progreso → Pruebas → Hecho), con un máximo de 2 tareas en progreso.

**Ramas:** `main` (versión estable) y `feature/nombre-de-la-tarea` para cada tarea; se fusionan a `main` con *pull request*.

**Mensajes de commit** (`tipo: descripción`):
- `feat:` nueva funcionalidad · `fix:` corrección · `style:` diseño/CSS · `docs:` documentación · `refactor:` mejora interna sin cambiar el resultado.

## Licencia
Proyecto académico. Las imágenes y marcas pertenecen a LA PUBLICIDAD.
