<?php
/**
 * Ajustes generales del tema.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ---------- Datos de contacto (se cambian en Apariencia → Personalizar) ---------- */
function lp_defaults() {
	return array(
		'whatsapp'  => '573208058411',
		'telefono'  => '+57 310 578 4288',
		'correo'    => 'info@lapublicidad.co',
		'direccion' => 'Mocoa, Putumayo, Colombia',
		'vigencia'  => '2027',
	);
}

function lp_opt( $key ) {
	$d       = lp_defaults();
	$default = isset( $d[ $key ] ) ? $d[ $key ] : '';
	return get_theme_mod( 'lp_' . $key, $default );
}

function lp_asset( $path ) {
	return LP_URI . '/assets/' . ltrim( $path, '/' );
}

function lp_wa_digits() {
	return preg_replace( '/\D/', '', (string) lp_opt( 'whatsapp' ) );
}

function lp_wa_link( $text = '' ) {
	$url = 'https://wa.me/' . lp_wa_digits();
	if ( '' !== $text ) {
		$url .= '?text=' . rawurlencode( $text );
	}
	return $url;
}

function lp_wa_pretty() {
	$d = lp_wa_digits();
	if ( strlen( $d ) === 12 && '57' === substr( $d, 0, 2 ) ) {
		return '+57 ' . substr( $d, 2, 3 ) . ' ' . substr( $d, 5, 3 ) . ' ' . substr( $d, 8 );
	}
	return $d;
}

function lp_money( $n ) {
	return '$' . number_format( (float) $n, 0, ',', '.' );
}

/* ---------- Soporte del tema ---------- */
function lp_setup() {
	load_theme_textdomain( 'lapublicidad', LP_DIR . '/languages' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
	);
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 80,
			'width'       => 400,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);
	register_nav_menus(
		array(
			'primary' => __( 'Menú principal', 'lapublicidad' ),
			'footer'  => __( 'Menú del pie de página', 'lapublicidad' ),
		)
	);
	add_image_size( 'lap-card', 640, 440, true );
}
add_action( 'after_setup_theme', 'lp_setup' );

/* ---------- Estilos y scripts ---------- */
function lp_enqueue() {
	wp_enqueue_style(
		'lp-fonts',
		'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@400;500;600;700;800;900&display=swap',
		array(),
		null
	);
	wp_enqueue_style( 'dashicons' );
	// Bootstrap 5.3.3: solo la rejilla, y solo en la página individual de servicio (integración acotada).
	if ( is_singular( 'lp_servicio' ) ) {
		wp_enqueue_style( 'lp-bootstrap-grid', lp_asset( 'vendor/bootstrap-5.3.3/bootstrap-grid.min.css' ), array(), '5.3.3' );
	}
	wp_enqueue_style( 'lp-main', lp_asset( 'css/main.css' ), array( 'lp-fonts' ), LP_VERSION );
	wp_enqueue_style( 'lp-elementor-kit', lp_asset( 'css/elementor-kit.css' ), array( 'lp-main' ), LP_VERSION );
	wp_enqueue_script( 'lp-main', lp_asset( 'js/main.js' ), array(), LP_VERSION, true );
	wp_localize_script(
		'lp-main',
		'LP',
		array(
			'ajax'     => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 'lp_nonce' ),
			'precios'  => function_exists( 'lp_get_precios' ) ? lp_get_precios() : array(),
			'whatsapp' => lp_wa_digits(),
			'vigencia' => lp_opt( 'vigencia' ),
			'home'     => home_url( '/' ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'lp_enqueue' );

function lp_head_extras() {
	if ( ! has_site_icon() ) {
		echo '<link rel="icon" href="' . esc_url( lp_asset( 'img/icono.svg' ) ) . '" type="image/svg+xml">' . "\n";
	}
}
add_action( 'wp_head', 'lp_head_extras', 5 );

/* ---------- Personalizar: datos de contacto ---------- */
function lp_customize( $wp_customize ) {
	$wp_customize->add_section(
		'lp_contacto',
		array(
			'title'    => __( 'LA PUBLICIDAD · Datos de contacto', 'lapublicidad' ),
			'priority' => 30,
		)
	);
	$fields = array(
		'whatsapp'  => 'WhatsApp del negocio (solo números con indicativo, ej. 573208058411)',
		'telefono'  => 'Teléfono',
		'correo'    => 'Correo electrónico',
		'direccion' => 'Ubicación',
		'vigencia'  => 'Año de vigencia de los precios (ej. 2027)',
	);
	$defaults = lp_defaults();
	foreach ( $fields as $key => $label ) {
		$wp_customize->add_setting(
			'lp_' . $key,
			array(
				'default'           => $defaults[ $key ],
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			'lp_' . $key,
			array(
				'label'   => $label,
				'section' => 'lp_contacto',
				'type'    => 'text',
			)
		);
	}
}
add_action( 'customize_register', 'lp_customize' );

/* ---------- Piezas reutilizables de la plantilla ---------- */
function lp_logo() {
	if ( function_exists( 'has_custom_logo' ) && has_custom_logo() ) {
		return get_custom_logo();
	}
	return '<a class="lap-logo__link" href="' . esc_url( home_url( '/' ) ) . '" aria-label="' . esc_attr( get_bloginfo( 'name' ) ) . ' — inicio">'
		. '<img src="' . esc_url( lp_asset( 'img/logo-blanco.png' ) ) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" width="1000" height="169">'
		. '</a>';
}

function lp_menu_fallback_items() {
	return array(
		'inicio'     => 'Inicio',
		'productos'  => 'Productos',
		'servicios'  => 'Servicios',
		'nosotros'   => 'Nosotros',
		'portafolio' => 'Portafolio',
		'contacto'   => 'Contacto',
	);
}

function lp_render_menu( $location, $class = '' ) {
	if ( has_nav_menu( $location ) ) {
		wp_nav_menu(
			array(
				'theme_location' => $location,
				'container'      => false,
				'menu_class'     => $class,
				'depth'          => 1,
				'fallback_cb'    => false,
			)
		);
		return;
	}
	echo '<ul class="' . esc_attr( $class ) . '">';
	foreach ( lp_menu_fallback_items() as $anchor => $label ) {
		$href = is_front_page() ? '#' . $anchor : home_url( '/#' . $anchor );
		echo '<li><a href="' . esc_url( $href ) . '">' . esc_html( $label ) . '</a></li>';
	}
	echo '</ul>';
}
