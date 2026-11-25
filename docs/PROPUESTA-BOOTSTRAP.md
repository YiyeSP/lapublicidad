# Propuesta Bootstrap — Tema "LA PUBLICIDAD"

**Proyecto:** Tema WordPress LA PUBLICIDAD (Mocoa, Putumayo)  
**Autor:** Ramiro Solarte Pérez (YiyeSP)  
**Evidencia:** GA7-220501102-AA1-EV02 — SENA  
**Rama:** `feature/propuesta-bootstrap`

## 1. Objetivo

Definir cómo usar **Bootstrap 5.3.3** en el tema de forma **acotada y segura**, sin romper el diseño actual (oscuro, minimalista, colores/fuentes de marca) y sin cambios grandes de una sola vez.

## 2. Estado actual (verificado)

- Archivo presente: `assets/vendor/bootstrap-5.3.3/bootstrap-grid.min.css` (~51 KB).
- Carga actual: solo en páginas individuales de servicio, desde `inc/setup.php`:
  - `is_singular('lp_servicio')` → `wp_enqueue_style('lp-bootstrap-grid', ..., '5.3.3')`.
- Uso actual: `single-lp_servicio.php` reemplaza la rejilla propia por clases Bootstrap:
  - contenedor: `row g-4`
  - columnas: `col-12 col-md-6 col-lg-4`
- La rejilla propia del tema sigue existiendo en `assets/css/main.css`: `.lap-grid`, `.lap-grid--3`, `.lap-grid--4`.
- `.gitignore` permite `assets/vendor/` (excepción sobre `/vendor/`).

## 3. Reglas de la propuesta

- Usar **solo Bootstrap Grid** (contenedores, filas, columnas, gutters/utilidades de grid necesarias). No cargar Bootstrap completo.
- No cambiar colores, tipografías ni identidad visual.
- No borrar ni inventar secciones.
- Aplicar cambios pequeños, por plantilla/componente, con prueba visual y rollback fácil.
- Mantener compatibilidad con Elementor gratuito y con los shortcodes existentes.

## 4. Candidatos a migrar

| Prioridad | Archivo / zona | Estado actual | Propuesta Bootstrap | Riesgo |
|---|---|---|---|---|
| Alta | `template-parts/landing.php` — “Por qué elegirnos” | `lap-grid lap-grid--4` | `row g-4` + `col-12 col-sm-6 col-xl-3` | Bajo, sección aislada |
| Alta | `template-parts/landing.php` — “Aliados” | `lap-grid lap-grid--3` | `row g-4` + `col-12 col-md-6 col-lg-4` | Bajo, sección aislada |
| Media | `inc/shortcodes.php` — grids de servicios/portafolio | `lap-grid lap-grid--N` | Evaluar wrapper Bootstrap o mantener `lap-grid` y usar Bootstrap solo en plantillas nuevas | Medio: afecta shortcodes usados en Elementor |
| Media | Plantillas/archivos con listados propios | Depende del archivo | Migrar solo si el markup es simple y no depende de CSS de `lap-grid` | Medio |
| Baja | Cotizador/contacto con layouts complejos | Layout propio con CSS específico | No migrar por ahora; requiere prueba cuidadosa | Alto |

## 5. Plan por fases

### Fase B4.1 — Documentación (esta tarea)
- Crear esta propuesta.
- No tocar PHP/CSS de plantillas.

### Fase B4.2 — Prueba acotada en landing (requiere confirmación)
- Encolar `lp-bootstrap-grid` también cuando se use la landing por defecto (`is_front_page()` y plantilla sin Elementor, o condición equivalente).
- Migrar solo 2 bloques de `template-parts/landing.php`: “Por qué elegirnos” y “Aliados”.
- Mantener clases `lap-*` de tarjetas/contenido; usar Bootstrap solo para la rejilla.
- Verificar escritorio 1440px y móvil 390px.

### Fase B4.3 — Evaluación de shortcodes (requiere confirmación)
- Revisar `lp_servicios` y `lp_portafolio` en `inc/shortcodes.php`.
- Decidir si conviene: A) mantener `lap-grid` por compatibilidad, B) agregar clases Bootstrap en paralelo, o C) migrar con condición.
- Probar en landing, en página editada con Elementor y en móvil.

## 6. Criterios de aceptación

- El diseño visual se mantiene (colores, tipografía, espaciado general).
- No hay desbordes horizontales en 360px, 390px, 768px, 1024px y 1440px.
- Los shortcodes/widgets siguen funcionando en Elementor.
- No hay errores PHP (`php -l`) ni JS (`node --check`) en archivos tocados.
- La carga de Bootstrap Grid solo ocurre donde se necesita.

## 7. Riesgos y mitigación

- **Conflicto CSS:** `.row`/`.col-*` de Bootstrap pueden chocar con estilos del tema. *Mitigación:* cargar solo grid; migrar por bloques; revisar márgenes/gutters.
- **Impacto en shortcodes:** cambiar markup afecta landing y Elementor. *Mitigación:* no migrar shortcodes sin prueba en editor y frontend.
- **Peso extra:** 51 KB de CSS. *Mitigación:* mantener enqueue condicional.
- **Regresión visual:** *Mitigación:* capturas antes/después y pruebas responsive.

## 8. Plan de prueba

1. `php -l` en archivos PHP modificados.
2. `node --check` en JS modificados (si aplica).
3. Verificar en navegador: `http://localhost/lapublicidad/`.
4. Capturas responsive: 1440px y 390px.
5. Comprobar en Elementor que los widgets/shortcodes se vean bien.
6. Revisar consola del navegador: sin errores críticos.

## 9. Recomendación

Empezar por **B4.1 (este documento)** y, si se aprueba, seguir con **B4.2** como prueba pequeña en `landing.php`. Dejar shortcodes para una fase aparte porque tienen mayor impacto.
