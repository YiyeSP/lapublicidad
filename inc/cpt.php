<?php
/**
 * Tipos de contenido (Productos, Servicios, Portafolio) y sus campos.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function lp_register_cpts() {
	$common = array(
		'public'       => true,
		'show_in_rest' => true,
		'has_archive'  => false,
		'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes' ),
	);

	register_post_type(
		'lp_producto',
		array_merge(
			$common,
			array(
				'labels'    => array(
					'name'          => 'Productos',
					'singular_name' => 'Producto',
					'add_new_item'  => 'Añadir producto',
					'edit_item'     => 'Editar producto',
					'menu_name'     => 'Productos',
				),
				'menu_icon' => 'dashicons-cart',
				'rewrite'   => array( 'slug' => 'producto' ),
			)
		)
	);

	register_post_type(
		'lp_servicio',
		array_merge(
			$common,
			array(
				'labels'    => array(
					'name'          => 'Servicios',
					'singular_name' => 'Servicio',
					'add_new_item'  => 'Añadir servicio',
					'edit_item'     => 'Editar servicio',
					'menu_name'     => 'Servicios',
				),
				'menu_icon' => 'dashicons-admin-tools',
				'rewrite'   => array( 'slug' => 'servicios' ),
			)
		)
	);

	register_post_type(
		'lp_trabajo',
		array_merge(
			$common,
			array(
				'labels'    => array(
					'name'          => 'Portafolio',
					'singular_name' => 'Trabajo',
					'add_new_item'  => 'Añadir trabajo',
					'edit_item'     => 'Editar trabajo',
					'menu_name'     => 'Portafolio',
				),
				'menu_icon' => 'dashicons-format-gallery',
				'rewrite'   => array( 'slug' => 'trabajos' ),
			)
		)
	);
}
add_action( 'init', 'lp_register_cpts' );

/* ---------- Campos personalizados ---------- */
function lp_meta_fields() {
	$cotizador = array( '' => 'Ninguno (se cotiza por WhatsApp)' );
	if ( function_exists( 'lp_get_precios' ) ) {
		foreach ( lp_get_precios() as $key => $p ) {
			$cotizador[ $key ] = isset( $p['nombre'] ) ? $p['nombre'] : $key;
		}
	}
	return array(
		'lp_producto' => array(
			array(
				'key'   => 'lp_precio_desde',
				'label' => 'Precio desde (COP, solo números; 0 = "a cotizar")',
				'type'  => 'number',
			),
			array(
				'key'   => 'lp_etiqueta',
				'label' => 'Etiqueta (ej. Más vendido)',
				'type'  => 'text',
			),
			array(
				'key'     => 'lp_cotizador',
				'label'   => 'Producto del cotizador que se preselecciona al pulsar "Cotizar ahora"',
				'type'    => 'select',
				'options' => $cotizador,
			),
		),
		'lp_servicio' => array(
			array(
				'key'     => 'lp_icono',
				'label'   => 'Icono',
				'type'    => 'select',
				'options' => array(
					'printer'     => 'Impresora',
					'image-crop'  => 'Corte',
					'art'         => 'Diseño',
					'admin-tools' => 'Herramientas',
					'media-text'  => 'Documento',
					'lightbulb'   => 'Idea',
				),
			),
			array(
				'key'   => 'lp_aliado',
				'label' => 'Se produce mediante aliado externo',
				'type'  => 'checkbox',
			),
			array(
				'key'   => 'lp_items',
				'label' => 'Productos o trabajos del servicio (uno por línea: Nombre | imagen opcional)',
				'type'  => 'textarea',
			),
		),
		'lp_trabajo'  => array(
			array(
				'key'   => 'lp_material',
				'label' => 'Material',
				'type'  => 'text',
			),
			array(
				'key'   => 'lp_servicio_txt',
				'label' => 'Servicio realizado',
				'type'  => 'text',
			),
		),
	);
}

function lp_add_meta_boxes() {
	foreach ( array( 'lp_producto', 'lp_servicio', 'lp_trabajo' ) as $pt ) {
		add_meta_box( 'lp_meta', 'Datos de LA PUBLICIDAD', 'lp_render_meta_box', $pt, 'normal', 'high' );
	}
}
add_action( 'add_meta_boxes', 'lp_add_meta_boxes' );

function lp_render_meta_box( $post ) {
	wp_nonce_field( 'lp_meta_save', 'lp_meta_nonce' );
	$all = lp_meta_fields();
	if ( empty( $all[ $post->post_type ] ) ) {
		return;
	}
	echo '<table class="form-table" role="presentation">';
	foreach ( $all[ $post->post_type ] as $f ) {
		$val = get_post_meta( $post->ID, $f['key'], true );
		$id  = esc_attr( $f['key'] );
		echo '<tr><th scope="row"><label for="' . $id . '">' . esc_html( $f['label'] ) . '</label></th><td>';
		if ( 'textarea' === $f['type'] ) {
			echo '<textarea class="large-text" rows="7" id="' . $id . '" name="' . $id . '">' . esc_textarea( $val ) . '</textarea>';
		} elseif ( 'select' === $f['type'] ) {
			echo '<select id="' . $id . '" name="' . $id . '">';
			foreach ( $f['options'] as $ov => $ol ) {
				echo '<option value="' . esc_attr( $ov ) . '"' . selected( (string) $val, (string) $ov, false ) . '>' . esc_html( $ol ) . '</option>';
			}
			echo '</select>';
		} elseif ( 'checkbox' === $f['type'] ) {
			echo '<input type="checkbox" id="' . $id . '" name="' . $id . '" value="1"' . checked( (string) $val, '1', false ) . '>';
		} else {
			echo '<input class="regular-text" type="' . esc_attr( $f['type'] ) . '" id="' . $id . '" name="' . $id . '" value="' . esc_attr( $val ) . '">';
		}
		echo '</td></tr>';
	}
	echo '</table>';
}

function lp_save_meta( $post_id ) {
	if ( ! isset( $_POST['lp_meta_nonce'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['lp_meta_nonce'] ) ), 'lp_meta_save' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	$all = lp_meta_fields();
	$pt  = get_post_type( $post_id );
	if ( empty( $all[ $pt ] ) ) {
		return;
	}
	foreach ( $all[ $pt ] as $f ) {
		$key = $f['key'];
		if ( 'checkbox' === $f['type'] ) {
			update_post_meta( $post_id, $key, isset( $_POST[ $key ] ) ? '1' : '0' );
			continue;
		}
		$raw = isset( $_POST[ $key ] ) ? wp_unslash( $_POST[ $key ] ) : '';
		if ( 'textarea' === $f['type'] ) {
			$val = sanitize_textarea_field( $raw );
		} elseif ( 'number' === $f['type'] ) {
			$val = (string) max( 0, intval( $raw ) );
		} else {
			$val = sanitize_text_field( $raw );
		}
		if ( 'select' === $f['type'] && ! isset( $f['options'][ $val ] ) ) {
			$val = '';
		}
		update_post_meta( $post_id, $key, $val );
	}
}
add_action( 'save_post', 'lp_save_meta' );

/* ---------- Al activar el tema: reglas de URL, tablas y Elementor en los nuevos tipos ---------- */
function lp_on_activate() {
	lp_register_cpts();
	flush_rewrite_rules();
	if ( function_exists( 'lp_install_db' ) ) {
		lp_install_db();
	}
	$cpt = get_option( 'elementor_cpt_support', array( 'page', 'post' ) );
	if ( ! is_array( $cpt ) ) {
		$cpt = array( 'page', 'post' );
	}
	$cpt = array_values( array_unique( array_merge( $cpt, array( 'lp_producto', 'lp_servicio', 'lp_trabajo' ) ) ) );
	update_option( 'elementor_cpt_support', $cpt );
}
add_action( 'after_switch_theme', 'lp_on_activate' );
