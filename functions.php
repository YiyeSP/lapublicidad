<?php
/**
 * LA PUBLICIDAD — Tema para WordPress + Elementor.
 * Autor: Ramiro Solarte Pérez · Aprendiz SENA, Tecnólogo en Desarrollo Multimedia y Web (ficha 3336532).
 *
 * Módulos (carpeta /inc):
 *  setup.php          Ajustes del tema, estilos/scripts, datos de contacto (Personalizar).
 *  cpt.php            Tipos de contenido: Productos, Servicios, Portafolio (+ campos).
 *  quote.php          Precios, motor de cálculo, tablas de base de datos y AJAX (cotizaciones y descuentos).
 *  shortcodes.php     [lp_coverflow] [lp_servicios] [lp_portafolio] [lp_cotizador] [lp_boton_descuento] [lp_contacto].
 *  admin.php          Panel "LA PUBLICIDAD" (cotizaciones, descuentos, precios).
 *  demo.php           Importador de contenido de demostración.
 *  elementor.php      Widgets propios para arrastrar y soltar en Elementor.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'LP_VERSION', '1.0.7' );
define( 'LP_DIR', get_template_directory() );
define( 'LP_URI', get_template_directory_uri() );

$lp_modules = array( 'setup', 'cpt', 'quote', 'shortcodes', 'admin', 'demo', 'elementor', 'search' );
foreach ( $lp_modules as $lp_module ) {
	$lp_file = LP_DIR . '/inc/' . $lp_module . '.php';
	if ( file_exists( $lp_file ) ) {
		require_once $lp_file;
	}
}
