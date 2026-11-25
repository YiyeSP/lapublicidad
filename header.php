<?php
/**
 * Encabezado del sitio: logo, menú, botones y menú móvil off-canvas.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
	<meta name="theme-color" content="#03041F">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php
if ( function_exists( 'wp_body_open' ) ) {
	wp_body_open();
}
?>
<a class="lap-skip" href="#contenido">Saltar al contenido</a>

<header class="lap-header" id="lap-header">
	<div class="lap-container lap-header__bar">
		<div class="lap-brand">
			<div class="lap-logo"><?php echo lp_logo(); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
		</div>

		<nav class="lap-nav" aria-label="Menú principal">
			<?php lp_render_menu( 'primary', 'lap-nav__list' ); ?>
		</nav>

		<div class="lap-header__actions">
			<a class="lap-btn lap-btn--search lap-btn--sm" href="<?php echo esc_url( home_url( '/?s=' ) ); ?>">
				<span class="dashicons dashicons-search" aria-hidden="true"></span><span>Buscar</span>
			</a>
			<a class="lap-btn lap-btn--green lap-btn--sm" href="<?php echo esc_url( is_front_page() ? '#cotizador' : home_url( '/#cotizador' ) ); ?>">Cotizar ahora</a>
		</div>

		<button class="lap-toggle" type="button" aria-expanded="false" aria-controls="lap-offcanvas" aria-label="Abrir menú">
			<span></span><span></span><span></span>
		</button>
	</div>
</header>

<div class="lap-overlay" aria-hidden="true"></div>
<aside id="lap-offcanvas" class="lap-offcanvas" aria-hidden="true" aria-label="Menú móvil">
	<button class="lap-offcanvas__close" type="button" aria-label="Cerrar menú">×</button>
	<nav aria-label="Menú móvil">
		<?php lp_render_menu( 'primary', 'lap-offcanvas__list' ); ?>
	</nav>
	<a class="lap-btn lap-btn--green lap-btn--block" href="<?php echo esc_url( is_front_page() ? '#cotizador' : home_url( '/#cotizador' ) ); ?>">Cotizar ahora</a>
	<a class="lap-btn lap-btn--outline lap-btn--block" href="<?php echo esc_url( lp_wa_link( 'Hola, quiero información.' ) ); ?>" target="_blank" rel="noopener">Escribir por WhatsApp</a>
</aside>
