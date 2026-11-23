<?php
/**
 * Pie de página.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<footer class="lap-footer">
	<div class="lap-container lap-footer__grid">
		<div class="lap-footer__brand">
			<div class="lap-logo"><?php echo lp_logo(); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
			<p>Soluciones de impresión digital y publicidad exterior para empresas y emprendedores de Putumayo.</p>
		</div>
		<div>
			<h2 class="lap-footer__title">Navegación</h2>
			<?php lp_render_menu( has_nav_menu( 'footer' ) ? 'footer' : 'primary', 'lap-footer__list' ); ?>
		</div>
		<div>
			<h2 class="lap-footer__title">Contacto</h2>
			<ul class="lap-footer__list">
				<li><?php echo esc_html( lp_opt( 'direccion' ) ); ?></li>
				<li><a href="tel:<?php echo esc_attr( preg_replace( '/[^\d+]/', '', lp_opt( 'telefono' ) ) ); ?>"><?php echo esc_html( lp_opt( 'telefono' ) ); ?></a></li>
				<li><a href="<?php echo esc_url( lp_wa_link() ); ?>" target="_blank" rel="noopener">WhatsApp: <?php echo esc_html( lp_wa_pretty() ); ?></a></li>
				<li><a href="mailto:<?php echo esc_attr( lp_opt( 'correo' ) ); ?>"><?php echo esc_html( lp_opt( 'correo' ) ); ?></a></li>
			</ul>
		</div>
	</div>
	<div class="lap-container lap-footer__bottom">
		<span>© <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. Todos los derechos reservados.</span>
		<?php if ( function_exists( 'get_privacy_policy_url' ) && get_privacy_policy_url() ) : ?>
			<a href="<?php echo esc_url( get_privacy_policy_url() ); ?>">Política de privacidad</a>
		<?php endif; ?>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
