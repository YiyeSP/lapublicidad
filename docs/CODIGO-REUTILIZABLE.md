# Código reutilizable — Tema "LA PUBLICIDAD"

**Proyecto:** Tema WordPress LA PUBLICIDAD  
**Autor:** Ramiro Solarte Pérez (YiyeSP)  
**Evidencia:** GA7-220501102-AA1-EV02 — SENA

Este documento muestra **4 ejemplos reales** del proyecto donde se reutiliza código o se evita repetir lógica. En cada ejemplo se ve el problema (“antes”, forma repetida) y la solución aplicada en el proyecto (“después”).

---

## 1) Generador de campos en `inc/cpt.php`

**Idea reutilizable:** definir los campos en una sola estructura de datos (`lp_meta_fields`) y usar esa misma definición para **renderizar** el metabox y para **guardar** los datos. Así, si agrego un campo, no tengo que copiar HTML y lógica de guardado en varios sitios.

### Antes (repetir HTML y guardado por cada campo)
```php
// Había que escribir un input por cada campo...
echo '<label>Precio desde</label>';
echo '<input type="number" name="lp_precio_desde" value="' . esc_attr( get_post_meta( $post->ID, 'lp_precio_desde', true ) ) . '">';

echo '<label>Etiqueta</label>';
echo '<input type="text" name="lp_etiqueta" value="' . esc_attr( get_post_meta( $post->ID, 'lp_etiqueta', true ) ) . '">';

// ...y luego repetir el guardado campo por campo:
if ( isset( $_POST['lp_precio_desde'] ) ) {
    update_post_meta( $post_id, 'lp_precio_desde', intval( $_POST['lp_precio_desde'] ) );
}
if ( isset( $_POST['lp_etiqueta'] ) ) {
    update_post_meta( $post_id, 'lp_etiqueta', sanitize_text_field( wp_unslash( $_POST['lp_etiqueta'] ) ) );
}
```

### Después (lo que hace el proyecto)
En `inc/cpt.php`, los campos se declaran una sola vez:

```php
function lp_meta_fields() {
    return array(
        'lp_producto' => array(
            array( 'key' => 'lp_precio_desde', 'label' => 'Precio desde...', 'type' => 'number' ),
            array( 'key' => 'lp_etiqueta', 'label' => 'Etiqueta...', 'type' => 'text' ),
            array( 'key' => 'lp_cotizador', 'label' => 'Producto del cotizador...', 'type' => 'select', 'options' => $cotizador ),
        ),
        // lp_servicio, lp_trabajo...
    );
}
```

La misma definición la usa `lp_render_meta_box()` para generar el formulario y `lp_save_meta()` para validar/sanitizar y guardar. Agregar un campo nuevo es agregar un arreglo, no duplicar lógica.

---

## 2) Clase base `LP_Widget_Base` en `inc/elementor-widgets.php`

**Idea reutilizable:** los widgets de Elementor comparten comportamiento (categoría, registro de controles, render con shortcode). En vez de repetir ese código en cada widget, se crea una clase base.

### Antes (repetir la estructura en cada widget)
```php
class LP_Widget_Cotizador extends \Elementor\Widget_Base {
    public function get_categories() { return array( 'lapublicidad' ); }
    protected function register_controls() { /* controles... */ }
    protected function render() {
        $s = $this->get_settings_for_display();
        echo do_shortcode( '[lp_cotizador]' );
    }
}

class LP_Widget_Servicios extends \Elementor\Widget_Base {
    public function get_categories() { return array( 'lapublicidad' ); }
    protected function register_controls() { /* controles... */ }
    protected function render() {
        $s = $this->get_settings_for_display();
        echo do_shortcode( '[lp_servicios cantidad="' . (int) $s['cantidad'] . '"]' );
    }
}
// ...y lo mismo en cada widget.
```

### Después (lo que hace el proyecto)
`LP_Widget_Base` concentra lo común y obliga a cada widget a definir solo lo propio:

```php
abstract class LP_Widget_Base extends \Elementor\Widget_Base {
    public function get_categories() { return array( 'lapublicidad' ); }

    protected function lp_controls() {}
    protected function register_controls() { $this->lp_controls(); }

    abstract protected function lp_shortcode( $s );

    protected function render() {
        $s = $this->get_settings_for_display();
        echo do_shortcode( $this->lp_shortcode( $s ) ); // phpcs:ignore WordPress.Security.EscapeOutput
    }
}
```

Cada widget concreto solo implementa su nombre, título, icono, controles y el shortcode:

```php
class LP_Widget_Coverflow extends LP_Widget_Base {
    public function get_name() { return 'lp_coverflow'; }
    public function get_title() { return 'Carrusel de productos 3D'; }
    public function get_icon() { return 'eicon-slider-3d'; }
    protected function lp_controls() { /* solo sus controles */ }
    protected function lp_shortcode( $s ) {
        return '[lp_coverflow cantidad="' . (int) $s['cantidad'] . '"]';
    }
}
```

---

## 3) Variables y clases reutilizables en `assets/css/main.css`

**Idea reutilizable:** definir colores, fuentes y medidas como variables CSS en `:root`, y crear clases de botón/base que se reutilizan en toda la página.

### Antes (valores repetidos en muchos selectores)
```css
.header { background: #070836; font-family: 'Outfit', sans-serif; }
.btn-principal { background: #A0F900; color: #03041F; border-radius: 10px; }
.btn-otro { background: #A0F900; color: #03041F; border-radius: 10px; }
.footer { background: #03041F; color: #B8C1C8; }
```

Si el verde o la fuente cambian, hay que buscar y reemplazar en muchos lugares, con riesgo de olvidar alguno.

### Después (lo que hace el proyecto)
Se definen tokens en `:root` y se reutilizan:

```css
:root {
  --lp-green: #A0F900;
  --lp-navy: #070836;
  --lp-surface: #03041F;
  --lp-white: #FFFFFF;
  --lp-soft: #B8C1C8;
  --lp-display: 'Outfit', sans-serif;
  --lp-body: 'Inter', sans-serif;
  --lap-max: 1280px;
  --lap-header: 64px;
}
```

Luego las clases base reutilizan esos valores:

```css
.lap-btn {
  display: inline-flex; align-items: center; justify-content: center;
  min-height: 48px; padding: 12px 28px; border-radius: 10px;
  font: 800 16px var(--lp-display);
}
.lap-btn--green { background: var(--lp-green); color: var(--lp-surface); }
.lap-btn--outline { background: transparent; color: #fff; border-color: rgba(255,255,255,.5); }
```

Un mismo botón base (`.lap-btn`) se combina con variantes (`--green`, `--outline`, `--sm`) en distintas secciones.

---

## 4) Cálculo duplicado con la misma lógica en JS y PHP

**Idea reutilizable:** la regla de negocio del precio es la misma; se implementa en dos capas por necesidad técnica (JS para respuesta en vivo, PHP para validar/guardar en servidor), pero se mantiene **el mismo orden de operaciones** y los mismos casos de control.

### Antes (riesgo de lógica diferente)
```js
// JS: calcula rápido en el navegador
total = area * tarifa * cantidad; // simplificado
```

```php
// PHP: calcula distinto al guardar
$total = round($area * $tarifa) * $cantidad; // otro orden / otras reglas
```

El resultado podía salir diferente entre lo que ve el usuario y lo que se guarda.

### Después (lo que hace el proyecto)
La función JS `calc(p, s)` en `assets/js/main.js` y la función PHP `lp_calcular($in)` en `inc/quote.php` siguen el mismo flujo:

1. Validar producto/cantidad.
2. Si es paquete, total = precio × cantidad.
3. Validar ancho/alto y límites.
4. Calcular área en m²: `(ancho * alto) / 10000`.
5. Seleccionar material y tarifa (con o sin diseño).
6. Aplicar mínimo facturable.
7. Aplicar descuento por volumen.
8. Sumar acabados (unidad/m²/fijo).
9. Redondear total final.

Ejemplo real alineado:

```js
// assets/js/main.js (calc)
var area = (w * h) / 10000;
var imp = area * tarifa;
var minAp = imp < p.minimo;
var base = Math.max(imp, p.minimo);
var sub = base * cant;
// ... descuento por volumen, acabados ...
return { ok: true, ..., total: Math.round(sub - dv + ac) };
```

```php
// inc/quote.php (lp_calcular)
$area = ( $ancho * $alto ) / 10000;
$impresion = $area * $tarifa;
$min_ap    = $impresion < $p['minimo'];
$base      = max( $impresion, $p['minimo'] );
$sub       = $base * $cant;
// ... descuento por volumen, acabados ...
$total = (int) round( $sub - $desc_vol + $ac_total );
```

Para evitar diferencias, el proyecto tiene pruebas en `tests/` que verifican los mismos casos de control en JS y PHP (`tests/datos-precios.json`).
