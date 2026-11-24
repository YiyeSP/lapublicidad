# Backlog — Tema WordPress "LA PUBLICIDAD"

**Proyecto:** Tema WordPress para LA PUBLICIDAD (impresión digital y publicidad en gran formato, Mocoa, Putumayo).  
**Autor:** Ramiro Solarte Pérez (GitHub: YiyeSP)  
**Repositorio:** https://github.com/YiyeSP/lapublicidad  
**Evidencia:** GA7-220501102-AA1-EV02 — SENA

> Formato: historia de usuario + criterios de aceptación.  
> Estado sugerido: Pendiente / En curso / Hecho.

---

## HU-01 — Precio en vivo en el cotizador
**Como** cliente potencial  
**quiero** ver el precio de mi producto al instante cuando cambio medidas, material o acabados  
**para** decidir rápido si solicito la cotización.

**Criterios de aceptación**
- [ ] Al cambiar producto, ancho, alto, material, diseño, acabados o cantidad, el total se actualiza sin recargar la página.
- [ ] El total mostrado coincide con la lógica de cálculo del servidor (`lp_calcular`) para los casos de control.
- [ ] Si los datos son inválidos (medidas fuera de rango), se muestra un mensaje claro y no un total engañoso.

---

## HU-02 — Cotización rápida por WhatsApp
**Como** cliente  
**quiero** enviar mi cotización rápida por WhatsApp con el resumen del producto y el total  
**para** recibir atención de un asesor sin llenar un formulario largo.

**Criterios de aceptación**
- [ ] El formulario rápido pide solo los datos mínimos necesarios (nombre y WhatsApp).
- [ ] Al enviar, el sistema guarda la cotización y abre WhatsApp con el resumen y el total.
- [ ] El mensaje incluye el número de cotización y la aclaración de que el precio es sujeto a verificación.

---

## HU-03 — Cotización formal con datos completos
**Como** cliente empresarial  
**quiero** registrar una cotización formal con mis datos (nombre, WhatsApp, correo, documento, ciudad)  
**para** recibir una cotización más completa y trazable.

**Criterios de aceptación**
- [ ] La pestaña/formulario formal solicita nombre, WhatsApp, correo, documento y ciudad.
- [ ] Los campos se validan en el servidor (formato de correo, WhatsApp colombiano, campos obligatorios).
- [ ] La cotización queda guardada en la tabla `lp_cotizaciones` con tipo "formal".

---

## HU-04 — Código de descuento web
**Como** visitante del sitio  
**quiero** generar un código de descuento del 10 % desde la página  
**para** usarlo en mi cotización formal y ahorrar.

**Criterios de aceptación**
- [ ] El usuario puede solicitar el código con nombre y WhatsApp válido.
- [ ] El sistema genera un código único, muestra la vigencia y lo guarda en la base de datos.
- [ ] Al usar el código en una cotización formal válida, se aplica el 10 % y el código queda marcado como usado.

---

## HU-05 — Ver servicios con detalle
**Como** cliente  
**quiero** ver la lista de servicios y entrar al detalle de cada uno  
**para** entender qué ofrece LA PUBLICIDAD antes de cotizar.

**Criterios de aceptación**
- [ ] La sección/listado de servicios muestra los servicios publicados (CPT `lp_servicio`).
- [ ] Cada servicio tiene una página de detalle con imagen, descripción y llamada a la acción.
- [ ] Los enlaces y botones funcionan en escritorio y móvil.

---

## HU-06 — Ver portafolio de trabajos
**Como** cliente  
**quiero** ver ejemplos de trabajos realizados  
**para** confiar en la calidad antes de pedir una cotización.

**Criterios de aceptación**
- [ ] La sección de portafolio muestra elementos del CPT `lp_portafolio`.
- [ ] Cada elemento muestra imagen, título y categoría/dato relevante.
- [ ] Si no hay elementos, la sección no se rompe y muestra un estado vacío adecuado.

---

## HU-07 — Edición de contenido con Elementor gratuito
**Como** administrador del sitio  
**quiero** editar la página de inicio y secciones con Elementor gratuito  
**para** actualizar el contenido sin tocar código.

**Criterios de aceptación**
- [ ] La plantilla de inicio es compatible con Elementor (se puede editar con el constructor).
- [ ] Los widgets/shortcodes propios (`lp_coverflow`, `lp_servicios`, `lp_portafolio`, `lp_cotizador`, `lp_boton_descuento`, `lp_contacto`) se pueden usar en Elementor.
- [ ] No se requiere Elementor Pro para la funcionalidad principal.

---

## HU-08 — Actualizar tabla de precios desde el panel
**Como** administrador  
**quiero** ajustar la tabla de precios desde el panel de WordPress  
**para** mantener las cotizaciones actualizadas sin modificar código.

**Criterios de aceptación**
- [ ] El panel "LA PUBLICIDAD" permite ver/editar la configuración de precios.
- [ ] Los cambios se validan antes de guardarse (estructura, campos numéricos, tipos de acabado).
- [ ] Tras guardar, el cotizador usa los nuevos precios y el cálculo del servidor coincide con el del frontend.

---

## HU-09 — Contacto directo desde el sitio
**Como** visitante  
**quiero** enviar un mensaje de contacto desde la web  
**para** resolver dudas antes de cotizar.

**Criterios de aceptación**
- [ ] El formulario de contacto valida nombre, correo, teléfono y mensaje.
- [ ] Al enviar, se genera un mensaje claro para WhatsApp con los datos del usuario.
- [ ] Los errores de validación se muestran junto al campo correspondiente.

---

## HU-10 — Experiencia responsive y accesible básica
**Como** usuario en celular  
**quiero** navegar la landing y usar el cotizador desde mi teléfono  
**para** cotizar cómodamente desde cualquier lugar.

**Criterios de aceptación**
- [ ] La cabecera, menú móvil, carrusel, cotizador y formularios se ven bien en pantallas de 360 px en adelante.
- [ ] Los botones y campos son tocables y legibles en móvil.
- [ ] El menú móvil se puede abrir/cerrar, cierra con Escape y mantiene el foco de forma básica.
