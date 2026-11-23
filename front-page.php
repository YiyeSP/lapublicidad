<?php
/**
 * Página de inicio.
 * - Si la página de inicio está hecha con Elementor (o tiene contenido), se muestra ese contenido.
 * - Si no, se muestra la landing por defecto (template-parts/landing.php).
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();

$lp_custom = false;
if ( is_page() && have_posts() ) {
	the_post();
	$lp_built  = 'builder' === get_post_meta( get_the_ID(), '_elementor_edit_mode', true );
	$lp_custom = $lp_built || '' !== trim( wp_strip_all_tags( get_the_content() ) );
	if ( $lp_custom ) {
		echo '<main id="contenido" class="lap-main lap-main--builder">';
		the_content();
		echo '</main>';
	}
}
if ( ! $lp_custom ) {
	get_template_part( 'template-parts/landing' );
}
get_footer();
