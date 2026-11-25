# Checklist de evidencia — GA7-220501102-AA1-EV02

**Proyecto:** Tema WordPress “LA PUBLICIDAD” (Mocoa, Putumayo)  
**Autor:** Ramiro Solarte Pérez (YiyeSP)  
**Repositorio:** https://github.com/YiyeSP/lapublicidad  
**Rama principal:** `main` (local `master`)

## Datos del entorno

| Ítem | Valor | Estado |
|---|---|---|
| Tema local | `C:\Users\YIYE-PC\Pictures\PAGINA WEB - LA PUBLICIDAD\lapublicidad` | Hecho |
| WordPress local | `C:\xampp\htdocs\lapublicidad` | Hecho |
| Admin local | `http://localhost/lapublicidad/wp-admin/` | Hecho |
| Tema activo | `lapublicidad` | Hecho |
| WP-CLI | `C:\xampp\php\wp.bat --path="C:\xampp\htdocs\lapublicidad"` | Hecho |

## Checklist por fase

| Fase | Evidencia / ruta | Estado |
|---|---|---|
| A1 | Inventario del tema en Pictures | Hecho |
| A2 | Copia XAMPP→Pictures, respaldos `.bak-*`, junction en `wp-content/themes/lapublicidad` | Hecho |
| A3 | `wp-config.php` con WP_DEBUG activo; respaldo `wp-config.php.bak-a3-20261124` | Hecho |
| A4 | Demo importada, página Inicio ID 19 como front page, plantilla `elementor-templates/2-inicio-completo-con-widgets.json` | Hecho |
| A5 | Capturas `home-1440.png` y `home-390.png` (temp); `CONTENIDO_EXTERNO_LA_PUBLICIDAD.png` no encontrado | Parcial |
| A6 | 6 widgets LA PUBLICIDAD en Elementor; hero `assets/img/hero.webp`; botón descuento abre `#lap-discount` | Hecho |
| A7 | Cotizaciones prueba en `wp_lp_cotizaciones`; descuento `LP-EIFON` en `wp_lp_descuentos` | Hecho |
| B1 | `.gitignore` ajustado; fusiones a `master/main`; push y etiqueta `v1.0.0` | Hecho |
| B2 | `tests/precios.test.js` y `tests/precios.test.php` — 5 OK, 0 FAIL | Hecho |
| B3 | GitHub Project: https://github.com/users/YiyeSP/projects/1 con HU-01 a HU-10 | Hecho |
| B4 | Propuesta Bootstrap: `docs/PROPUESTA-BOOTSTRAP.md` (rama `feature/propuesta-bootstrap`, commit `cddd345`) | Hecho |
| B5 | Este checklist de evidencia | Hecho |

## Evidencias técnicas

| Evidencia | Ruta / referencia | Estado |
|---|---|---|
| Repositorio GitHub | https://github.com/YiyeSP/lapublicidad | Hecho |
| Etiqueta versión | `v1.0.0` | Hecho |
| Pruebas de precios | `tests/datos-precios.json`, `tests/precios.test.js`, `tests/precios.test.php` | Hecho |
| Backlog HU | `docs/backlog.md` | Hecho |
| Pruebas manuales | `docs/PRUEBAS.md` | Hecho |
| Código reutilizable | `docs/CODIGO-REUTILIZABLE.md` | Hecho |
| Propuesta Bootstrap | `docs/PROPUESTA-BOOTSTRAP.md` | Hecho |
| Widgets Elementor | `inc/elementor.php`, `inc/elementor-widgets.php` | Hecho |
| Cotizador/descuentos | `inc/quote.php`, `assets/js/main.js` | Hecho |

## Pendientes / observaciones

- [ ] A5: conseguir `CONTENIDO_EXTERNO_LA_PUBLICIDAD.png` y completar comparación visual.
- [x] B3: renombrar el título del GitHub Project en GitHub. *Hecho el 2026-11-25: renombrado manualmente a “LA PUBLICIDAD — Backlog”.*
- [ ] B4.2: aplicar prueba acotada de Bootstrap en `landing.php` solo si se aprueba.
- [x] Limpiar datos de prueba en BD local (`wp_lp_cotizaciones`, `wp_lp_descuentos`) antes de entrega final. *Hecho el 2026-11-25: se eliminaron 2 cotizaciones y 1 descuento de prueba.*
- [x] Verificar número WhatsApp del negocio. *Hecho: `inc/setup.php` actualizado a `573105784288` (valor por defecto y ayuda del Customizer).*
