<?php
/**
 * Páginas. Si la página se edita con Elementor, ocupa todo el ancho; si no, se muestra centrada.
 * (En Elementor también puedes elegir las plantillas "Elementor Canvas" o "Elementor ancho completo".)
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
while ( have_posts() ) :
	the_post();
	$lp_built = 'builder' === get_post_meta( get_the_ID(), '_elementor_edit_mode', true );
	if ( $lp_built ) {
		echo '<main id="contenido" class="lap-main lap-main--builder">';
		the_content();
		echo '</main>';
	} else {
		?>
		<main id="contenido" class="lap-main lap-main--pad">
			<div class="lap-container lap-prose">
				<h1><?php the_title(); ?></h1>
				<?php the_content(); ?>
			</div>
		</main>
		<?php
	}
endwhile;
get_footer();
