<?php
/**
 * Página de un servicio: es la "ventana" que se abre desde el botón "Ver →" de cada servicio.
 * Muestra los productos o trabajos del servicio (campo "Productos o trabajos del servicio").
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
while ( have_posts() ) :
	the_post();
	$lp_built = 'builder' === get_post_meta( get_the_ID(), '_elementor_edit_mode', true );
	$aliado   = '1' === (string) get_post_meta( get_the_ID(), 'lp_aliado', true );
	$items    = lp_parse_items( get_post_meta( get_the_ID(), 'lp_items', true ) );
	?>
	<main id="contenido" class="lap-main">
		<section class="lap-svc-hero">
			<div class="lap-container">
				<a class="lap-back" href="<?php echo esc_url( home_url( '/#servicios' ) ); ?>">← Volver a servicios</a>
				<h1><?php the_title(); ?></h1>
				<?php if ( $aliado ) : ?>
					<p class="lap-ally-note"><span class="lap-card__tag">Aliado</span> Producción mediante aliado externo, coordinada por nuestro equipo.</p>
				<?php endif; ?>
				<?php if ( has_excerpt() ) : ?>
					<p class="lap-svc-hero__lead"><?php echo esc_html( get_the_excerpt() ); ?></p>
				<?php endif; ?>
			</div>
		</section>

		<?php if ( $lp_built ) : ?>
			<?php the_content(); ?>
		<?php endif; ?>

		<?php if ( ! empty( $items ) ) : ?>
			<section class="lap-section lap-bg-navy">
				<div class="lap-container">
					<?php /* Rejilla Bootstrap 5.3.3 (solo grid, cargada solo en esta plantilla). */ ?>
					<div class="row g-4">
						<?php foreach ( $items as $it ) : ?>
							<div class="col-12 col-md-6 col-lg-4">
								<article class="lap-card lap-card--item">
								<span class="lap-card__media">
									<?php if ( $it['img'] ) : ?>
										<img src="<?php echo esc_url( $it['img'] ); ?>" alt="<?php echo esc_attr( $it['name'] ); ?>" loading="lazy">
									<?php else : ?>
										<span class="lap-card__ph dashicons dashicons-format-image" aria-hidden="true"></span>
									<?php endif; ?>
								</span>
								<span class="lap-card__body">
									<h2><?php echo esc_html( $it['name'] ); ?></h2>
									<?php
									$lp_cot_key = function_exists( 'lp_cotizador_key_for_name' ) ? lp_cotizador_key_for_name( $it['name'] ) : '';
									$lp_cot_url = $lp_cot_key ? add_query_arg( 'producto', $lp_cot_key, home_url( '/' ) ) . '#cotizador' : home_url( '/#cotizador' );
									?>
									<a class="lap-btn lap-btn--green lap-btn--sm" href="<?php echo esc_url( $lp_cot_url ); ?>">Cotizar</a>
								</span>
								</article>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</section>
		<?php endif; ?>

		<?php if ( ! $lp_built && '' !== trim( wp_strip_all_tags( get_the_content() ) ) ) : ?>
			<section class="lap-section lap-bg-surface">
				<div class="lap-container lap-prose"><?php the_content(); ?></div>
			</section>
		<?php endif; ?>
	</main>
	<?php
endwhile;
get_footer();
