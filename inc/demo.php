<?php
/**
 * Contenido de demostración: crea productos, servicios y trabajos con sus imágenes (assets/img/catalogo).
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function lp_demo_data() {
	return array(
		'lp_producto' => array(
			array( 'slug' => 'pendon-100x200', 'title' => 'Pendón 100×200 cm', 'excerpt' => 'Lona backlight 440g con ojales metálicos. Impresión full color de alta definición.', 'img' => 'pendon.webp', 'meta' => array( 'lp_precio_desde' => '45000', 'lp_etiqueta' => 'Más vendido', 'lp_cotizador' => 'pendon' ) ),
			array( 'slug' => 'stand-publicitario', 'title' => 'Stand Publicitario', 'excerpt' => 'Mostrador promocional con panel superior, ideal para eventos y activaciones de marca.', 'img' => 'stand.webp', 'meta' => array( 'lp_precio_desde' => '330000', 'lp_etiqueta' => 'Eventos', 'lp_cotizador' => '' ) ),
			array( 'slug' => 'vinilo-adhesivo', 'title' => 'Vinilo adhesivo por m²', 'excerpt' => 'Vinilo impreso y cortado en mate, brillante o transparente para vitrinas, paredes y vehículos.', 'img' => 'corte-troquelado.webp', 'meta' => array( 'lp_precio_desde' => '38000', 'lp_etiqueta' => '', 'lp_cotizador' => 'vinilo' ) ),
			array( 'slug' => 'pasacalle', 'title' => 'Pasacalle', 'excerpt' => 'Lona frontlit con ojales y refuerzo perimetral para calle o fachada.', 'img' => 'pasacalle.webp', 'meta' => array( 'lp_precio_desde' => '90000', 'lp_etiqueta' => '', 'lp_cotizador' => 'pasacalle' ) ),
			array( 'slug' => 'caja-luminosa', 'title' => 'Caja luminosa', 'excerpt' => 'Caja iluminada con impresión en lona, visible de día y de noche.', 'img' => 'caja-luminosa.webp', 'meta' => array( 'lp_precio_desde' => '200000', 'lp_etiqueta' => '', 'lp_cotizador' => 'cajaluminosa' ) ),
			array( 'slug' => 'aviso-led-neon', 'title' => 'Aviso LED neón', 'excerpt' => 'Neón flex LED sobre acrílico: bajo consumo y gran impacto visual.', 'img' => 'neon-flex.webp', 'meta' => array( 'lp_precio_desde' => '150000', 'lp_etiqueta' => 'Tendencia', 'lp_cotizador' => 'neon' ) ),
			array( 'slug' => 'letras-3d', 'title' => 'Letras 3D', 'excerpt' => 'Avisos corpóreos en acrílico 3D con iluminación posterior.', 'img' => 'acrilico-3d.webp', 'meta' => array( 'lp_precio_desde' => '150000', 'lp_etiqueta' => '', 'lp_cotizador' => 'letras3d' ) ),
			array( 'slug' => 'stickers-troquelados', 'title' => 'Stickers troquelados', 'excerpt' => 'Paquete de 500 unidades de 3×3 cm con corte de contorno, ideal para emprendedores.', 'img' => 'stickers.webp', 'meta' => array( 'lp_precio_desde' => '45000', 'lp_etiqueta' => 'Emprendedores', 'lp_cotizador' => 'stickers' ) ),
			array( 'slug' => 'porta-pendon-arana', 'title' => 'Porta pendón araña', 'excerpt' => 'Estructura portátil tipo X con lona impresa, fácil de armar y transportar.', 'img' => 'porta-pendon-arana.webp', 'meta' => array( 'lp_precio_desde' => '0', 'lp_etiqueta' => '', 'lp_cotizador' => '' ) ),
			array( 'slug' => 'porta-pendon-roller', 'title' => 'Porta pendón roller', 'excerpt' => 'Enrollable en aluminio con estuche de transporte, para ferias y puntos de venta.', 'img' => 'porta-pendon-roller.webp', 'meta' => array( 'lp_precio_desde' => '0', 'lp_etiqueta' => '', 'lp_cotizador' => '' ) ),
			array( 'slug' => 'caja-luminosa-acrilico-cuadrada', 'title' => 'Caja luminosa en acrílico — Cuadrada', 'excerpt' => 'Aviso de pared en acrílico con luz LED interior y brazo metálico.', 'img' => 'acrilico-cuadrado.webp', 'meta' => array( 'lp_precio_desde' => '0', 'lp_etiqueta' => '', 'lp_cotizador' => '' ) ),
			array( 'slug' => 'caja-luminosa-acrilico-redonda', 'title' => 'Caja luminosa en acrílico — Redonda', 'excerpt' => 'Aviso circular en acrílico con luz LED interior, ideal para negocios y consultorios.', 'img' => 'acrilico-redondo.webp', 'meta' => array( 'lp_precio_desde' => '0', 'lp_etiqueta' => '', 'lp_cotizador' => '' ) ),
		),
		'lp_servicio' => array(
			array( 'slug' => 'impresion-digital', 'title' => 'Impresión Digital', 'excerpt' => 'Gran formato en lona, lámina y vinilo. Pendones, pasacalles, vallas y avisos con calidad fotográfica.', 'img' => 'impresion-digital.webp', 'meta' => array( 'lp_icono' => 'printer', 'lp_aliado' => '0', 'lp_items' => "Pendones | pendones.webp\nPasacalles | pasacalles.webp\nAvisos con marco metálico | marco-metalico.webp\nValla publicitaria en lona | valla-lona.webp\nValla publicitaria en lámina | valla-lamina.webp" ) ),
			array( 'slug' => 'corte-y-troquelado', 'title' => 'Corte y Troquelado', 'excerpt' => 'Avisos corpóreos en acrílico 2D y 3D, letras de alto relieve y rótulos en neón flex.', 'img' => 'corte-troquelado.webp', 'meta' => array( 'lp_icono' => 'image-crop', 'lp_aliado' => '0', 'lp_items' => "Avisos corpóreos en acrílico 3D | acrilico-3d.webp\nAvisos en acrílico 2D | acrilico-2d.webp\nAvisos neón flex | aviso-neon-flex.webp" ) ),
			array( 'slug' => 'diseno-grafico', 'title' => 'Diseño Gráfico', 'excerpt' => 'Creación de logotipos, imagen corporativa y piezas gráficas listas para producción.', 'img' => 'diseno-grafico.webp', 'meta' => array( 'lp_icono' => 'art', 'lp_aliado' => '0', 'lp_items' => 'Diseño de logotipos e imagen corporativa | diseno-grafico.webp' ) ),
			array( 'slug' => 'instalacion', 'title' => 'Instalación', 'excerpt' => 'Montaje profesional de avisos publicitarios y vinilos adhesivos en fachadas y superficies.', 'img' => 'instalacion.webp', 'meta' => array( 'lp_icono' => 'admin-tools', 'lp_aliado' => '0', 'lp_items' => 'Instalación de avisos y vinilos adhesivos | instalacion.webp' ) ),
			array( 'slug' => 'litografia', 'title' => 'Litografía', 'excerpt' => 'Folletos, revistas, tarjetas y carpetas en offset de alta calidad, coordinado con aliado de confianza.', 'img' => 'litografia.webp', 'meta' => array( 'lp_icono' => 'media-text', 'lp_aliado' => '1', 'lp_items' => "Folletos\nVolantes | volantes.webp\nRevistas | revistas.webp\nCarpetas carta y oficio\nTarjetas de presentación mate con UV parcial | tarjetas-uv.webp\nTarjetas sencillas | tarjetas-sencillas.webp" ) ),
			array( 'slug' => 'corte-laser', 'title' => 'Corte Láser', 'excerpt' => 'Corte de precisión sobre MDF, acrílico y polietileno, coordinado con aliado especializado.', 'img' => 'corte-laser.webp', 'meta' => array( 'lp_icono' => 'lightbulb', 'lp_aliado' => '1', 'lp_items' => "Corte sobre MDF | corte-mdf.webp\nCorte sobre acrílico | corte-acrilico.webp\nCorte sobre polietileno | corte-polietileno.webp" ) ),
		),
		'lp_trabajo'  => array(
			array( 'slug' => 'aviso-3d', 'title' => 'Aviso 3D', 'excerpt' => '', 'img' => 'acrilico-3d.webp', 'meta' => array( 'lp_material' => 'Acrílico iluminado', 'lp_servicio_txt' => 'Diseño, fabricación e instalación' ) ),
			array( 'slug' => 'pendon-publicitario', 'title' => 'Pendón publicitario', 'excerpt' => '', 'img' => 'pendones.webp', 'meta' => array( 'lp_material' => 'Lona impresa en alta resolución', 'lp_servicio_txt' => 'Impresión y entrega' ) ),
			array( 'slug' => 'letrero-neon-flex', 'title' => 'Letrero neón flex', 'excerpt' => '', 'img' => 'aviso-neon-flex.webp', 'meta' => array( 'lp_material' => 'Neón LED flexible', 'lp_servicio_txt' => 'Fabricación e instalación' ) ),
			array( 'slug' => 'valla-publicitaria', 'title' => 'Valla publicitaria', 'excerpt' => '', 'img' => 'valla-lona.webp', 'meta' => array( 'lp_material' => 'Lona en estructura metálica', 'lp_servicio_txt' => 'Impresión e instalación' ) ),
			array( 'slug' => 'stickers-troquelados-trabajo', 'title' => 'Stickers troquelados', 'excerpt' => '', 'img' => 'stickers.webp', 'meta' => array( 'lp_material' => 'Vinilo adhesivo', 'lp_servicio_txt' => 'Diseño e impresión' ) ),
			array( 'slug' => 'letras-corporeas', 'title' => 'Letras corpóreas', 'excerpt' => '', 'img' => 'acrilico-2d.webp', 'meta' => array( 'lp_material' => 'Acrílico', 'lp_servicio_txt' => 'Diseño, corte y montaje' ) ),
			array( 'slug' => 'banner-gran-formato', 'title' => 'Banner de gran formato', 'excerpt' => '', 'img' => 'pasacalles.webp', 'meta' => array( 'lp_material' => 'Lona backlit', 'lp_servicio_txt' => 'Impresión y tensado' ) ),
			array( 'slug' => 'tarjetas-de-presentacion', 'title' => 'Tarjetas de presentación', 'excerpt' => '', 'img' => 'tarjetas-sencillas.webp', 'meta' => array( 'lp_material' => 'Papel mate 300 g con UV parcial', 'lp_servicio_txt' => 'Diseño e impresión litográfica' ) ),
		),
	);
}

function lp_demo_exists( $post_type, $slug ) {
	$found = get_posts(
		array(
			'post_type'   => $post_type,
			'name'        => $slug,
			'post_status' => 'any',
			'numberposts' => 1,
			'fields'      => 'ids',
		)
	);
	return ! empty( $found );
}

function lp_demo_attachment( $file, $parent_id ) {
	static $cache = array();
	if ( isset( $cache[ $file ] ) ) {
		return $cache[ $file ];
	}
	$src = LP_DIR . '/assets/img/catalogo/' . basename( $file );
	if ( ! file_exists( $src ) ) {
		return 0;
	}
	$tmp = wp_tempnam( $file );
	if ( ! $tmp || ! copy( $src, $tmp ) ) {
		return 0;
	}
	$arr = array(
		'name'     => basename( $file ),
		'tmp_name' => $tmp,
	);
	$id  = media_handle_sideload( $arr, $parent_id );
	if ( is_wp_error( $id ) ) {
		@unlink( $tmp ); // phpcs:ignore
		return 0;
	}
	$cache[ $file ] = (int) $id;
	return (int) $id;
}

function lp_page_demo() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$done = isset( $_GET['creados'] ) ? intval( $_GET['creados'] ) : -1;
	?>
	<div class="wrap">
		<h1>Contenido de demostración</h1>
		<?php if ( $done >= 0 ) : ?>
			<div class="notice notice-success"><p><?php echo (int) $done; ?> elementos creados. Revisa Productos, Servicios y Portafolio.</p></div>
		<?php endif; ?>
		<p>Crea 12 productos (para el carrusel), 6 servicios y 8 trabajos del portafolio con sus fotos. No duplica lo que ya exista. Después puedes editar, borrar o reemplazar todo.</p>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="lp_demo">
			<?php wp_nonce_field( 'lp_demo' ); ?>
			<button type="submit" class="button button-primary button-hero">Importar contenido de demostración</button>
		</form>
	</div>
	<?php
}

function lp_action_demo() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Sin permiso.' );
	}
	check_admin_referer( 'lp_demo' );
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';
	if ( function_exists( 'set_time_limit' ) ) {
		@set_time_limit( 300 ); // phpcs:ignore
	}

	$created = 0;
	foreach ( lp_demo_data() as $post_type => $items ) {
		foreach ( $items as $order => $it ) {
			if ( lp_demo_exists( $post_type, $it['slug'] ) ) {
				continue;
			}
			$pid = wp_insert_post(
				array(
					'post_type'    => $post_type,
					'post_status'  => 'publish',
					'post_title'   => $it['title'],
					'post_name'    => $it['slug'],
					'post_excerpt' => $it['excerpt'],
					'post_content' => '',
					'menu_order'   => $order,
				),
				true
			);
			if ( is_wp_error( $pid ) ) {
				continue;
			}
			foreach ( $it['meta'] as $k => $v ) {
				update_post_meta( $pid, $k, $v );
			}
			if ( ! empty( $it['img'] ) ) {
				$att = lp_demo_attachment( $it['img'], $pid );
				if ( $att ) {
					set_post_thumbnail( $pid, $att );
				}
			}
			$created++;
		}
	}
	flush_rewrite_rules();
	wp_safe_redirect( add_query_arg( array( 'page' => 'lp-demo', 'creados' => $created ), admin_url( 'admin.php' ) ) );
	exit;
}
add_action( 'admin_post_lp_demo', 'lp_action_demo' );
