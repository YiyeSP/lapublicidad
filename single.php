<?php
/**
 * Entradas individuales y tipos de contenido sin plantilla propia.
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
			<article class="lap-container lap-prose">
				<h1><?php the_title(); ?></h1>
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="lap-prose__img"><?php the_post_thumbnail( 'large' ); ?></div>
				<?php endif; ?>
				<?php the_content(); ?>
				<?php if ( 'lp_producto' === get_post_type() ) : ?>
					<p><a class="lap-btn lap-btn--green" href="<?php echo esc_url( home_url( '/#cotizador' ) ); ?>">Cotizar ahora</a></p>
				<?php endif; ?>
			</article>
		</main>
		<?php
	}
endwhile;
get_footer();
