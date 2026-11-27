<?php
/**
 * Shortcodes: se pueden pegar en cualquier página o usar con los widgets de Elementor.
 *   [lp_coverflow cantidad="12"]   [lp_servicios columnas="3"]   [lp_portafolio columnas="4" cantidad="8"]
 *   [lp_cotizador]   [lp_boton_descuento texto="QUIERO MI DESCUENTO" estilo="green"]   [lp_contacto]
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ---------- Utilidades ---------- */
function lp_empty_notice( $msg ) {
	if ( current_user_can( 'edit_posts' ) ) {
		return '<p class="lap-empty">' . esc_html( $msg ) . '</p>';
	}
	return '';
}

/** Convierte "Nombre | imagen.webp" (una por línea) en una lista de ítems. */
function lp_parse_items( $text ) {
	$items = array();
	foreach ( preg_split( '/\r\n|\r|\n/', (string) $text ) as $line ) {
		$line = trim( $line );
		if ( '' === $line ) {
			continue;
		}
		$parts = array_map( 'trim', explode( '|', $line, 2 ) );
		$img   = '';
		if ( ! empty( $parts[1] ) ) {
			if ( preg_match( '#^https?://#i', $parts[1] ) ) {
				$img = $parts[1];
			} else {
				$file = basename( $parts[1] );
				if ( file_exists( LP_DIR . '/assets/img/catalogo/' . $file ) ) {
					$img = lp_asset( 'img/catalogo/' . $file );
				}
			}
		}
		$items[] = array( 'name' => $parts[0], 'img' => $img );
	}
	return $items;
}

/* ---------- [lp_coverflow] ---------- */
function lp_sc_coverflow( $atts ) {
	$a = shortcode_atts( array( 'cantidad' => 12 ), $atts, 'lp_coverflow' );
	$q = new WP_Query(
		array(
			'post_type'      => 'lp_producto',
			'posts_per_page' => max( 1, (int) $a['cantidad'] ),
			'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'ASC' ),
			'no_found_rows'  => true,
		)
	);
	if ( ! $q->have_posts() ) {
		return lp_empty_notice( 'Aún no hay productos. Ve a Productos → Añadir nuevo, o importa el contenido de demostración en LA PUBLICIDAD → Contenido demo.' );
	}
	ob_start();
	?>
	<div class="lap-cf" data-lap-coverflow>
		<div class="lap-cf__stage" role="group" aria-roledescription="carrusel" aria-label="Productos destacados" tabindex="0">
			<?php
			while ( $q->have_posts() ) {
				$q->the_post();
				$pid    = get_the_ID();
				$precio = (int) get_post_meta( $pid, 'lp_precio_desde', true );
				$badge  = (string) get_post_meta( $pid, 'lp_etiqueta', true );
				$key    = (string) get_post_meta( $pid, 'lp_cotizador', true );
				$desc   = has_excerpt() ? get_the_excerpt() : wp_trim_words( wp_strip_all_tags( get_the_content() ), 22 );
				$price  = $precio > 0 ? 'Desde ' . lp_money( $precio ) . ' COP' : 'Precio a cotizar';
				?>
				<article class="lap-cf__item" data-key="<?php echo esc_attr( $key ); ?>" data-name="<?php echo esc_attr( get_the_title() ); ?>" data-desc="<?php echo esc_attr( $desc ); ?>" data-price="<?php echo esc_attr( $price ); ?>" data-badge="<?php echo esc_attr( $badge ); ?>">
					<div class="lap-cf__card">
						<?php
						if ( has_post_thumbnail() ) {
							echo get_the_post_thumbnail( $pid, 'large', array( 'class' => 'lap-cf__img', 'alt' => get_the_title(), 'loading' => 'lazy' ) ); // phpcs:ignore WordPress.Security.EscapeOutput
						} else {
							echo '<span class="lap-cf__noimg" aria-hidden="true"></span>';
						}
						if ( '' !== $badge ) {
							echo '<span class="lap-cf__tag">' . esc_html( $badge ) . '</span>';
						}
						?>
						<div class="lap-cf__label">
							<strong><?php echo esc_html( get_the_title() ); ?></strong>
							<small><?php echo esc_html( $price ); ?></small>
						</div>
					</div>
				</article>
				<?php
			}
			wp_reset_postdata();
			?>
		</div>
		<div class="lap-cf__controls">
			<button type="button" class="lap-cf__arrow" data-cf="prev" aria-label="Producto anterior"><svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="15 18 9 12 15 6"/></svg></button>
			<div class="lap-cf__dots" role="tablist" aria-label="Elegir producto"></div>
			<button type="button" class="lap-cf__arrow" data-cf="next" aria-label="Producto siguiente"><svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="9 18 15 12 9 6"/></svg></button>
		</div>
		<div class="lap-cf__info" aria-live="polite">
			<span class="lap-cf__badge" hidden></span>
			<h3 class="lap-cf__name"></h3>
			<p class="lap-cf__desc"></p>
			<p class="lap-cf__price"></p>
			<a class="lap-btn lap-btn--green lap-cf__cta" href="#cotizador">COTIZAR AHORA →</a>
		</div>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'lp_coverflow', 'lp_sc_coverflow' );

/* ---------- [lp_servicios] ---------- */
function lp_sc_servicios( $atts ) {
	$a    = shortcode_atts( array( 'columnas' => 3 ), $atts, 'lp_servicios' );
	$cols = in_array( (int) $a['columnas'], array( 2, 3, 4 ), true ) ? (int) $a['columnas'] : 3;
	$q    = new WP_Query(
		array(
			'post_type'      => 'lp_servicio',
			'posts_per_page' => 12,
			'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'ASC' ),
			'no_found_rows'  => true,
		)
	);
	if ( ! $q->have_posts() ) {
		return lp_empty_notice( 'Aún no hay servicios. Ve a Servicios → Añadir nuevo, o importa el contenido de demostración.' );
	}
	ob_start();
	echo '<div class="lap-grid lap-grid--' . (int) $cols . '">';
	while ( $q->have_posts() ) {
		$q->the_post();
		$pid    = get_the_ID();
		$icon   = (string) get_post_meta( $pid, 'lp_icono', true );
		$icon   = '' !== $icon ? $icon : 'admin-tools';
		$aliado = '1' === (string) get_post_meta( $pid, 'lp_aliado', true );
		$desc   = has_excerpt() ? get_the_excerpt() : wp_trim_words( wp_strip_all_tags( get_the_content() ), 20 );
		?>
		<a class="lap-card" href="<?php echo esc_url( get_permalink() ); ?>">
			<span class="lap-card__media">
				<?php
				if ( has_post_thumbnail() ) {
					echo get_the_post_thumbnail( $pid, 'lap-card', array( 'alt' => get_the_title(), 'loading' => 'lazy' ) ); // phpcs:ignore WordPress.Security.EscapeOutput
				}
				?>
				<span class="lap-card__icon dashicons dashicons-<?php echo esc_attr( sanitize_html_class( $icon ) ); ?>" aria-hidden="true"></span>
				<?php if ( $aliado ) : ?>
					<span class="lap-card__tag">Aliado</span>
				<?php endif; ?>
			</span>
			<span class="lap-card__body">
				<h3><?php echo esc_html( get_the_title() ); ?></h3>
				<span class="lap-card__text"><?php echo esc_html( $desc ); ?></span>
				<span class="lap-card__more">Ver →</span>
			</span>
		</a>
		<?php
	}
	echo '</div>';
	wp_reset_postdata();
	return ob_get_clean();
}
add_shortcode( 'lp_servicios', 'lp_sc_servicios' );

/* ---------- [lp_portafolio] ---------- */
function lp_sc_portafolio( $atts ) {
	$a    = shortcode_atts( array( 'columnas' => 4, 'cantidad' => 8 ), $atts, 'lp_portafolio' );
	$cols = in_array( (int) $a['columnas'], array( 2, 3, 4 ), true ) ? (int) $a['columnas'] : 4;
	$q    = new WP_Query(
		array(
			'post_type'      => 'lp_trabajo',
			'posts_per_page' => max( 1, (int) $a['cantidad'] ),
			'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'ASC' ),
			'no_found_rows'  => true,
		)
	);
	if ( ! $q->have_posts() ) {
		return lp_empty_notice( 'Aún no hay trabajos. Ve a Portafolio → Añadir nuevo, o importa el contenido de demostración.' );
	}
	ob_start();
	echo '<div class="lap-grid lap-grid--' . (int) $cols . '">';
	while ( $q->have_posts() ) {
		$q->the_post();
		$pid = get_the_ID();
		?>
		<article class="lap-work">
			<div class="lap-work__media">
				<?php
				if ( has_post_thumbnail() ) {
					echo get_the_post_thumbnail( $pid, 'lap-card', array( 'alt' => get_the_title(), 'loading' => 'lazy' ) ); // phpcs:ignore WordPress.Security.EscapeOutput
				}
				?>
			</div>
			<div class="lap-work__body">
				<h3><?php echo esc_html( get_the_title() ); ?></h3>
				<p><span>MAT</span> <?php echo esc_html( get_post_meta( $pid, 'lp_material', true ) ); ?></p>
				<p><span>SVC</span> <?php echo esc_html( get_post_meta( $pid, 'lp_servicio_txt', true ) ); ?></p>
			</div>
		</article>
		<?php
	}
	echo '</div>';
	wp_reset_postdata();
	return ob_get_clean();
}
add_shortcode( 'lp_portafolio', 'lp_sc_portafolio' );

/* ---------- [lp_cotizador] (la interfaz la construye assets/js/main.js con la tabla de precios) ---------- */
function lp_sc_cotizador() {
	ob_start();
	?>
	<div class="lap-cot" data-lap-cotizador>
		<div class="lap-cot__main">
			<section class="lap-panel" data-step="producto"><h3><span>1.</span> Producto</h3><div class="lap-opts" data-role="productos"></div><button type="button" class="lap-more-products" data-role="mas-productos" hidden>Ver más productos</button></section>
			<section class="lap-panel" data-step="medidas"><h3><span>2.</span> Medidas</h3>
				<div class="lap-opts" data-role="medidas"></div>
				<div class="lap-custom" data-role="custom" hidden>
					<div class="lap-field"><label for="lap-ancho">Ancho (cm)</label><input id="lap-ancho" type="number" inputmode="decimal" min="1" step="any" data-role="ancho"></div>
					<div class="lap-field"><label for="lap-alto">Alto (cm)</label><input id="lap-alto" type="number" inputmode="decimal" min="1" step="any" data-role="alto"></div>
					<p class="lap-hint" data-role="limites"></p>
				</div>
			</section>
			<section class="lap-panel" data-step="material"><h3><span>3.</span> Material</h3><div class="lap-opts" data-role="materiales"></div></section>
			<section class="lap-panel" data-step="diseno"><h3><span>4.</span> Diseño gráfico</h3>
				<label class="lap-switch"><input type="checkbox" data-role="diseno"><span class="lap-switch__ui" aria-hidden="true"></span><span data-role="diseno-texto">Incluir diseño gráfico</span></label>
			</section>
			<section class="lap-panel" data-step="acabados"><h3><span>5.</span> Acabados</h3><div class="lap-checks" data-role="acabados"></div></section>
			<section class="lap-panel" data-step="cantidad"><h3><span>6.</span> Cantidad</h3>
				<div class="lap-qty"><button type="button" data-role="menos" aria-label="Disminuir cantidad">−</button><input type="number" min="1" max="1000" value="1" data-role="cantidad" aria-label="Cantidad"><button type="button" data-role="mas" aria-label="Aumentar cantidad">+</button></div>
			</section>
		</div>

		<aside class="lap-cot__summary">
			<div class="lap-panel lap-panel--sticky">
				<h3>Tu cotización</h3>
				<dl class="lap-sum" data-role="resumen"></dl>
				<p class="lap-error" data-role="error" role="alert" hidden></p>
				<div class="lap-total"><span>TOTAL</span><strong data-role="total">$0</strong><small data-role="total-ref"></small></div>
				<p class="lap-note" data-role="nota"></p>

				<div class="lap-tabs" role="tablist" aria-label="Tipo de cotización">
					<button type="button" role="tab" aria-selected="true" data-tab="rapida">Rápida</button>
					<button type="button" role="tab" aria-selected="false" data-tab="formal">Formal</button>
				</div>

				<div class="lap-help-cta">
					<p class="lap-help-cta__title">¿No sabes qué material o medida elegir?</p>
					<p class="lap-help-cta__text">No te preocupes. Cuéntanos qué necesitas y te ayudamos a encontrar la mejor opción.</p>
					<a class="lap-btn lap-btn--outline lap-btn--block" href="<?php echo esc_url( lp_wa_link( 'Hola, necesito asesoría para cotizar un producto de LA PUBLICIDAD. ¿Me pueden orientar?' ) ); ?>" target="_blank" rel="noopener">Recibir asesoría por WhatsApp</a>
				</div>

				<form class="lap-form" data-role="form" novalidate>
					<h4>Datos para solicitar</h4>
					<div class="lap-field" data-field="nombre"><label for="lap-nombre">Nombre</label><input id="lap-nombre" name="nombre" type="text" autocomplete="name" placeholder="Tu nombre completo"><span class="lap-error"></span></div>
					<div class="lap-field" data-field="whatsapp"><label for="lap-wa">WhatsApp</label><input id="lap-wa" name="whatsapp" type="tel" inputmode="numeric" autocomplete="tel-national" placeholder="3001234567"><span class="lap-error"></span></div>
					<div class="lap-formal" data-role="formal" hidden>
						<div class="lap-field" data-field="correo"><label for="lap-correo">Correo electrónico</label><input id="lap-correo" name="correo" type="email" autocomplete="email"><span class="lap-error"></span></div>
						<div class="lap-field" data-field="documento"><label for="lap-doc">Cédula o NIT</label><input id="lap-doc" name="documento" type="text" inputmode="numeric"><span class="lap-error"></span></div>
						<div class="lap-field" data-field="ciudad"><label for="lap-ciudad">Ciudad</label><input id="lap-ciudad" name="ciudad" type="text" autocomplete="address-level2"><span class="lap-error"></span></div>
					</div>
					<div class="lap-field" data-field="codigo"><label for="lap-codigo">Código de descuento web (opcional)</label><input id="lap-codigo" name="codigo" type="text" autocomplete="off" placeholder="LP-XXXXX"><span class="lap-error"></span></div>
					<div class="lap-trap" aria-hidden="true"><label>No llenar este campo<input type="text" name="web" tabindex="-1" autocomplete="off"></label></div>
					<div class="lap-field lap-field--check" data-field="consent"><label><input type="checkbox" name="consent" value="1"> <span>Autorizo el tratamiento de mis datos personales (Ley 1581 de 2012). <a href="<?php echo esc_url( function_exists( 'get_privacy_policy_url' ) && get_privacy_policy_url() ? get_privacy_policy_url() : '#' ); ?>">Política de datos</a></span></label><span class="lap-error"></span></div>
					<button type="submit" class="lap-btn lap-btn--green lap-btn--block" data-role="enviar">Solicitar cotización por WhatsApp</button>
					<button type="button" class="lap-btn lap-btn--outline lap-btn--block" data-role="pdf">Descargar cotización (PDF)</button>
				</form>

				<div class="lap-result" data-role="resultado" hidden></div>
			</div>
		</aside>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'lp_cotizador', 'lp_sc_cotizador' );

/* ---------- [lp_boton_descuento] ---------- */
function lp_sc_boton_descuento( $atts ) {
	$a     = shortcode_atts( array( 'texto' => 'QUIERO MI DESCUENTO', 'estilo' => 'green' ), $atts, 'lp_boton_descuento' );
	$style = 'outline' === $a['estilo'] ? 'lap-btn--outline' : 'lap-btn--green';
	return '<button type="button" class="lap-btn ' . esc_attr( $style ) . '" data-lap-open-discount>' . esc_html( $a['texto'] ) . '</button>';
}
add_shortcode( 'lp_boton_descuento', 'lp_sc_boton_descuento' );

/* ---------- [lp_contacto] ---------- */
function lp_sc_contacto() {
	ob_start();
	?>
	<div class="lap-contact">
		<div class="lap-contact__info">
			<ul class="lap-contact__list">
				<li><span class="dashicons dashicons-format-chat" aria-hidden="true"></span><div><small>WhatsApp</small><a href="<?php echo esc_url( lp_wa_link() ); ?>" target="_blank" rel="noopener"><?php echo esc_html( lp_wa_pretty() ); ?></a></div></li>
				<li><span class="dashicons dashicons-phone" aria-hidden="true"></span><div><small>Teléfono</small><a href="tel:<?php echo esc_attr( preg_replace( '/[^\d+]/', '', lp_opt( 'telefono' ) ) ); ?>"><?php echo esc_html( lp_opt( 'telefono' ) ); ?></a></div></li>
				<li><span class="dashicons dashicons-email" aria-hidden="true"></span><div><small>Correo</small><a href="mailto:<?php echo esc_attr( lp_opt( 'correo' ) ); ?>"><?php echo esc_html( lp_opt( 'correo' ) ); ?></a></div></li>
				<li><span class="dashicons dashicons-location" aria-hidden="true"></span><div><small>Ubicación</small><span><?php echo esc_html( lp_opt( 'direccion' ) ); ?></span></div></li>
			</ul>
			<a class="lap-btn lap-btn--green" href="<?php echo esc_url( lp_wa_link( 'Hola, quiero información sobre sus servicios.' ) ); ?>" target="_blank" rel="noopener">Chatear por WhatsApp</a>
		</div>
		<form class="lap-form lap-panel" data-lap-contact novalidate>
			<h3>Formulario de contacto</h3>
			<div class="lap-field" data-field="nombre"><label for="lapc-nombre">Nombre</label><input id="lapc-nombre" name="nombre" type="text" autocomplete="name" placeholder="Tu nombre completo"><span class="lap-error"></span></div>
			<div class="lap-field" data-field="correo"><label for="lapc-correo">Correo electrónico</label><input id="lapc-correo" name="correo" type="email" autocomplete="email" placeholder="tucorreo@ejemplo.com"><span class="lap-error"></span></div>
			<div class="lap-field" data-field="telefono"><label for="lapc-tel">Teléfono o WhatsApp</label><input id="lapc-tel" name="telefono" type="tel" autocomplete="tel" placeholder="3001234567"><span class="lap-error"></span></div>
			<div class="lap-field" data-field="mensaje"><label for="lapc-msg">Tu mensaje</label><textarea id="lapc-msg" name="mensaje" rows="4" placeholder="Describe tu proyecto o lo que necesitas…"></textarea><span class="lap-error"></span></div>
			<button type="submit" class="lap-btn lap-btn--green lap-btn--block">Enviar por WhatsApp</button>
		</form>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'lp_contacto', 'lp_sc_contacto' );

/* ---------- Elementos globales: ventana del descuento y botón flotante de WhatsApp ---------- */
function lp_footer_extras() {
	?>
	<dialog class="lap-dialog" id="lap-discount" aria-labelledby="lap-discount-title">
		<form method="dialog"><button class="lap-dialog__close" aria-label="Cerrar">×</button></form>
		<h2 id="lap-discount-title">Tu 10 % de descuento</h2>
		<div data-role="paso1">
			<p>Cotiza tu trabajo desde la web y recibe un código de descuento. Es personal, vence en 5 días y se permite uno por mes por número de WhatsApp. <strong>Solo aplica para compras por la página web, de ninguna manera presencial.</strong></p>
			<form class="lap-form" data-lap-discount-form novalidate>
				<div class="lap-field" data-field="nombre"><label for="lapd-nombre">Nombre</label><input id="lapd-nombre" name="nombre" type="text" autocomplete="name"></div>
				<div class="lap-field" data-field="whatsapp"><label for="lapd-wa">WhatsApp (10 dígitos)</label><input id="lapd-wa" name="whatsapp" type="tel" inputmode="numeric" autocomplete="tel-national" placeholder="3001234567"></div>
				<div class="lap-trap" aria-hidden="true"><label>No llenar este campo<input type="text" name="web" tabindex="-1" autocomplete="off"></label></div>
				<div class="lap-field lap-field--check"><label><input type="checkbox" name="consent" value="1"> <span>Autorizo el tratamiento de mis datos personales (Ley 1581 de 2012).</span></label></div>
				<p class="lap-error" data-role="error" role="alert" hidden></p>
				<button type="submit" class="lap-btn lap-btn--green lap-btn--block">Generar mi código</button>
			</form>
		</div>
		<div data-role="paso2" hidden>
			<p>¡Listo! Este es tu código:</p>
			<p class="lap-code" data-role="codigo"></p>
			<p class="lap-note">Vence el <strong data-role="vence"></strong>. Escríbelo en el cotizador para aplicar el 10 %.</p>
			<p class="lap-note" data-role="sent-msg" hidden></p>
			<a class="lap-btn lap-btn--green lap-btn--block" data-role="wa" href="#" target="_blank" rel="noopener">Recibir mi código por WhatsApp</a>
			<a class="lap-btn lap-btn--outline lap-btn--block" href="<?php echo esc_url( home_url( '/#cotizador' ) ); ?>" data-role="ir">Ir al cotizador</a>
		</div>
	</dialog>
	<a class="lap-wa-float" href="<?php echo esc_url( lp_wa_link( 'Hola, quiero cotizar un trabajo.' ) ); ?>" target="_blank" rel="noopener" aria-label="Escribir por WhatsApp"><span class="dashicons dashicons-format-chat" aria-hidden="true"></span></a>
	<?php
}
add_action( 'wp_footer', 'lp_footer_extras' );
