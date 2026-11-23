<?php
/**
 * Integración con Elementor (gratuito): categoría y widgets propios "LA PUBLICIDAD".
 * Los widgets aparecen en el panel izquierdo de Elementor y se arrastran a la página.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function lp_elementor_categories( $elements_manager ) {
	$elements_manager->add_category(
		'lapublicidad',
		array(
			'title' => 'LA PUBLICIDAD',
			'icon'  => 'eicon-font',
		)
	);
}
add_action( 'elementor/elements/categories_registered', 'lp_elementor_categories' );

function lp_elementor_register_widgets( $widgets_manager ) {
	if ( ! class_exists( '\Elementor\Widget_Base' ) ) {
		return;
	}
	require_once LP_DIR . '/inc/elementor-widgets.php';
	$list = array(
		'LP_Widget_Coverflow',
		'LP_Widget_Cotizador',
		'LP_Widget_Servicios',
		'LP_Widget_Portafolio',
		'LP_Widget_Boton_Descuento',
		'LP_Widget_Contacto',
	);
	foreach ( $list as $class ) {
		$widget = new $class();
		if ( method_exists( $widgets_manager, 'register' ) ) {
			$widgets_manager->register( $widget );
		} else {
			$widgets_manager->register_widget_type( $widget );
		}
	}
}
if ( defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION, '3.5.0', '<' ) ) {
	add_action( 'elementor/widgets/widgets_registered', 'lp_elementor_register_widgets' );
} else {
	add_action( 'elementor/widgets/register', 'lp_elementor_register_widgets' );
}
