<?php
/**
 * Plantilla general (blog, búsqueda, archivos y 404).
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
?>
<main id="contenido" class="lap-main lap-main--pad">
	<div class="lap-container">
		<header class="lap-pagehead">
			<h1>
				<?php
				if ( is_search() ) {
					echo 'Resultados para: ' . esc_html( get_search_query() );
				} elseif ( is_archive() ) {
					echo wp_kses_post( get_the_archive_title() );
				} elseif ( is_404() ) {
					echo 'Página no encontrada';
				} else {
					echo 'Novedades';
				}
				?>
			</h1>
		</header>
		<?php if ( have_posts() ) : ?>
			<div class="lap-grid lap-grid--3">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<a class="lap-card" href="<?php the_permalink(); ?>">
						<span class="lap-card__media"><?php echo has_post_thumbnail() ? get_the_post_thumbnail( get_the_ID(), 'lap-card', array( 'loading' => 'lazy' ) ) : ''; // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
						<span class="lap-card__body">
							<h2><?php the_title(); ?></h2>
							<span class="lap-card__text"><?php echo esc_html( wp_trim_words( wp_strip_all_tags( get_the_excerpt() ), 22 ) ); ?></span>
							<span class="lap-card__more">Leer más →</span>
						</span>
					</a>
				<?php endwhile; ?>
			</div>
			<?php the_posts_pagination(); ?>
		<?php else : ?>
			<p>No encontramos contenido aquí. <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Volver al inicio</a>.</p>
			<?php get_search_form(); ?>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();
