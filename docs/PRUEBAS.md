# Pruebas manuales — Tema "LA PUBLICIDAD"

**Proyecto:** Tema WordPress LA PUBLICIDAD  
**URL local:** https://localhost/lapublicidad/  
**Admin:** https://localhost/lapublicidad/wp-admin/  
**Autor:** Ramiro Solarte Pérez (YiyeSP)  
**Evidencia:** GA7-220501102-AA1-EV02 — SENA

> Lista paso a paso para probar el tema en WordPress. Marca cada caso como OK / Falló / N/A.

## Preparación
- [ ] WordPress instalado y funcionando en `C:\xampp\htdocs\lapublicidad`.
- [ ] Tema copiado en `wp-content/themes/lapublicidad`.
- [ ] PHP 7.4+ y WordPress 6.0+.
- [ ] Elementor gratuito instalado y activo (para pruebas de widgets/edición).

---

## 1. Activar el tema
1. Ir a **Apariencia > Temas**.
2. Activar **LA PUBLICIDAD**.
3. **Esperado:** el sitio no muestra errores fatales; se ven estilos del tema.
4. **Verificar:** menú principal, logo/identidad, footer.

## 2. Importar contenido demo
1. Ir al panel **LA PUBLICIDAD** en el admin.
2. Usar la opción de importar/crear contenido demo (según `inc/demo.php`).
3. **Esperado:** se crean páginas, productos, servicios, portafolio y menús necesarios.
4. **Verificar:** la página de inicio muestra secciones (hero, servicios, portafolio, cotizador, contacto).

## 3. Ver la landing / inicio
1. Abrir `https://localhost/lapublicidad/`.
2. **Esperado:** carga la landing con estilo oscuro, verde #A0F900, azul #070836, tipografías Outfit/Inter.
3. **Verificar:** header fijo, menú móvil en pantalla pequeña, anclas/scroll a secciones, botones visibles.

## 4. Carrusel 3D / productos
1. En la sección de productos/carrusel, navegar con flechas, puntos y swipe.
2. Hacer clic en “Cotizar ahora” de un producto.
3. **Esperado:** el carrusel cambia de producto; el botón lleva al cotizador y preselecciona el producto cuando aplica.
4. **Verificar:** no hay errores en consola del navegador.

## 5. Cotización rápida
1. Ir a la sección **Cotizador**.
2. Seleccionar producto, medidas, material, acabados y cantidad.
3. **Esperado:** el total se actualiza en vivo.
4. **Casos de control:**
   - Pendón 80×200 cm = **56.000**
   - Pendón 30×30 = **35.000** (mínimo)
   - Vinilo 100×100 ×4 con laminado = **244.000**
   - Pasacalle 500×100 con refuerzo = **187.000**
   - Stickers ×2 = **90.000**
5. Completar nombre y WhatsApp válido (10 dígitos, inicia por 3), enviar.
6. **Esperado:** se guarda la cotización, aparece resumen, número de cotización y botón para WhatsApp.

## 6. Cotización formal
1. En el cotizador, cambiar a pestaña/modo **Formal**.
2. Completar datos: nombre, WhatsApp, correo, documento, ciudad, consentimiento.
3. Enviar.
4. **Esperado:** validación de campos; si es válido, se guarda como cotización formal.
5. **Verificar:** errores visibles junto a campos inválidos (correo malo, WhatsApp inválido, falta consentimiento).

## 7. Descuento web
1. Abrir el modal/botón de descuento web.
2. Ingresar nombre y WhatsApp válido, aceptar consentimiento.
3. **Esperado:** se genera código, se muestra vigencia y enlace de WhatsApp.
4. Usar el código en una cotización formal.
5. **Esperado:** aplica 10 % de descuento; el código queda marcado como usado/no reutilizable según lógica.

## 8. Contacto
1. Ir a la sección de contacto.
2. Probar envío con datos inválidos.
3. **Esperado:** mensajes de error por campo.
4. Enviar con datos válidos.
5. **Esperado:** se abre WhatsApp con el mensaje prellenado.

## 9. Servicios y portafolio
1. Ver sección de servicios en inicio.
2. Entrar a un servicio con “Ver →”.
3. **Esperado:** carga `single-lp_servicio.php`, muestra hero, items/trabajos y CTA.
4. Ver sección de portafolio.
5. **Esperado:** muestra trabajos publicados; si no hay, no se rompe.

## 10. Panel de administración LA PUBLICIDAD
1. Ir al menú **LA PUBLICIDAD** en wp-admin.
2. Revisar pestañas/paneles disponibles (precios, cotizaciones, descuentos, configuración según implementación).
3. **Esperado:** las tablas cargan; se ven cotizaciones y descuentos guardados.
4. Cambiar un precio (si se prueba), guardar y verificar en cotizador.
5. **Esperado:** el cálculo del frontend y el del servidor coinciden.

## 11. Elementor (widgets)
1. Editar una página con Elementor.
2. Buscar widgets de “LA PUBLICIDAD”.
3. Insertar: Carrusel 3D, Servicios, Portafolio, Cotizador, Botón descuento, Contacto.
4. **Esperado:** los widgets renderizan en el editor y en el frontend.
5. **Verificar:** no depende de Elementor Pro.

## 12. Responsive y consola
1. Probar en 360px, 768px, 1024px y escritorio.
2. **Esperado:** sin desbordes horizontales, menú móvil funcional, cotizador usable.
3. Abrir consola del navegador.
4. **Esperado:** sin errores JS críticos en carga y uso normal.

---

## Registro de errores
Cuando aparezca un error de WordPress:
1. Copiar el mensaje exacto y la ruta/pantalla donde ocurrió.
2. Revisar `wp-content/debug.log` si `WP_DEBUG_LOG` está activo.
3. Identificar archivo, línea y causa.
4. Aplicar el cambio mínimo, probar de nuevo y registrar el resultado.
