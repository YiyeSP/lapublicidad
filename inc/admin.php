<?php
/**
 * Panel "LA PUBLICIDAD" en el administrador: cotizaciones, descuentos y precios.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function lp_admin_menu() {
	add_menu_page( 'LA PUBLICIDAD', 'LA PUBLICIDAD', 'manage_options', 'lp-cotizaciones', 'lp_page_cotizaciones', 'dashicons-megaphone', 26 );
	add_submenu_page( 'lp-cotizaciones', 'Cotizaciones', 'Cotizaciones', 'manage_options', 'lp-cotizaciones', 'lp_page_cotizaciones' );
	add_submenu_page( 'lp-cotizaciones', 'Descuentos', 'Descuentos', 'manage_options', 'lp-descuentos', 'lp_page_descuentos' );
	add_submenu_page( 'lp-cotizaciones', 'Precios', 'Precios', 'manage_options', 'lp-precios', 'lp_page_precios' );
	add_submenu_page( 'lp-cotizaciones', 'Contenido demo', 'Contenido demo', 'manage_options', 'lp-demo', 'lp_page_demo' );
}
add_action( 'admin_menu', 'lp_admin_menu' );

/* ---------- Cotizaciones ---------- */
function lp_page_cotizaciones() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	global $wpdb;
	$t    = $wpdb->prefix . 'lp_cotizaciones';
	$rows = $wpdb->get_results( "SELECT * FROM $t ORDER BY id DESC LIMIT 200" );
	?>
	<div class="wrap">
		<h1>Cotizaciones recibidas</h1>
		<p>Se muestran las últimas 200. El total siempre es el calculado por el servidor con la tabla de precios vigente.</p>
		<p><a class="button button-primary" href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=lp_csv' ), 'lp_csv' ) ); ?>">Descargar todo en CSV (Excel)</a></p>
		<table class="widefat striped">
			<thead><tr><th>#</th><th>Fecha</th><th>Tipo</th><th>Cliente</th><th>WhatsApp</th><th>Producto</th><th>Cant.</th><th>Total (COP)</th><th>Código</th><th>Estado</th><th></th></tr></thead>
			<tbody>
			<?php if ( empty( $rows ) ) : ?>
				<tr><td colspan="11">Todavía no hay cotizaciones.</td></tr>
			<?php else : ?>
				<?php foreach ( $rows as $r ) : ?>
					<tr>
						<td><?php echo (int) $r->id; ?></td>
						<td><?php echo esc_html( $r->creado ); ?></td>
						<td><?php echo esc_html( $r->tipo ); ?></td>
						<td><strong><?php echo esc_html( $r->nombre ); ?></strong>
							<?php if ( $r->correo ) : ?><br><?php echo esc_html( $r->correo ); ?><?php endif; ?>
							<?php if ( $r->documento ) : ?><br>Doc: <?php echo esc_html( $r->documento ); ?><?php endif; ?>
							<?php if ( $r->ciudad ) : ?><br><?php echo esc_html( $r->ciudad ); ?><?php endif; ?></td>
						<td><a href="<?php echo esc_url( 'https://wa.me/57' . $r->whatsapp ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $r->whatsapp ); ?></a></td>
						<td><?php echo esc_html( $r->producto ); ?><br><small><?php echo nl2br( esc_html( $r->resumen ) ); ?></small></td>
						<td><?php echo (int) $r->cantidad; ?></td>
						<td><?php echo esc_html( lp_money( $r->total ) ); ?></td>
						<td><?php echo esc_html( $r->codigo ); ?></td>
						<td><?php echo esc_html( $r->estado ); ?></td>
						<td>
							<?php if ( 'atendida' !== $r->estado ) : ?>
								<a class="button" href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=lp_estado&id=' . (int) $r->id ), 'lp_estado_' . (int) $r->id ) ); ?>">Marcar atendida</a>
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
			</tbody>
		</table>
	</div>
	<?php
}

function lp_action_estado() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Sin permiso.' );
	}
	$id = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0;
	check_admin_referer( 'lp_estado_' . $id );
	global $wpdb;
	$wpdb->update( $wpdb->prefix . 'lp_cotizaciones', array( 'estado' => 'atendida' ), array( 'id' => $id ), array( '%s' ), array( '%d' ) );
	wp_safe_redirect( admin_url( 'admin.php?page=lp-cotizaciones' ) );
	exit;
}
add_action( 'admin_post_lp_estado', 'lp_action_estado' );

function lp_csv_cell( $v ) {
	$v = (string) $v;
	if ( '' !== $v && in_array( $v[0], array( '=', '+', '-', '@' ), true ) ) {
		$v = "'" . $v; // evita fórmulas al abrir en Excel.
	}
	return $v;
}

function lp_action_csv() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Sin permiso.' );
	}
	check_admin_referer( 'lp_csv' );
	global $wpdb;
	$t    = $wpdb->prefix . 'lp_cotizaciones';
	$rows = $wpdb->get_results( "SELECT * FROM $t ORDER BY id DESC", ARRAY_A );
	nocache_headers();
	header( 'Content-Type: text/csv; charset=utf-8' );
	header( 'Content-Disposition: attachment; filename=cotizaciones-lapublicidad-' . gmdate( 'Y-m-d' ) . '.csv' );
	$out = fopen( 'php://output', 'w' );
	fwrite( $out, "\xEF\xBB\xBF" );
	fputcsv( $out, array( 'id', 'fecha', 'tipo', 'nombre', 'whatsapp', 'correo', 'documento', 'ciudad', 'producto', 'resumen', 'cantidad', 'total', 'codigo', 'estado' ) );
	foreach ( $rows as $r ) {
		fputcsv(
			$out,
			array(
				$r['id'],
				$r['creado'],
				$r['tipo'],
				lp_csv_cell( $r['nombre'] ),
				$r['whatsapp'],
				lp_csv_cell( $r['correo'] ),
				lp_csv_cell( $r['documento'] ),
				lp_csv_cell( $r['ciudad'] ),
				lp_csv_cell( $r['producto'] ),
				lp_csv_cell( $r['resumen'] ),
				$r['cantidad'],
				$r['total'],
				$r['codigo'],
				$r['estado'],
			)
		);
	}
	fclose( $out );
	exit;
}
add_action( 'admin_post_lp_csv', 'lp_action_csv' );

/* ---------- Descuentos ---------- */
function lp_page_descuentos() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	global $wpdb;
	$t    = $wpdb->prefix . 'lp_descuentos';
	$rows = $wpdb->get_results( "SELECT * FROM $t ORDER BY id DESC LIMIT 200" );
	$now  = current_time( 'mysql' );
	?>
	<div class="wrap">
		<h1>Códigos de descuento web</h1>
		<p>10 % para trabajos cotizados desde la web. Vigencia de 5 días. Un código por mes por número de WhatsApp.</p>
		<table class="widefat striped">
			<thead><tr><th>Código</th><th>Nombre</th><th>WhatsApp</th><th>Creado</th><th>Vence</th><th>Estado</th></tr></thead>
			<tbody>
			<?php if ( empty( $rows ) ) : ?>
				<tr><td colspan="6">Todavía no se han generado códigos.</td></tr>
			<?php else : ?>
				<?php foreach ( $rows as $r ) : ?>
					<?php $estado = $r->usado ? 'Usado' : ( $r->expira < $now ? 'Vencido' : 'Vigente' ); ?>
					<tr>
						<td><code><?php echo esc_html( $r->codigo ); ?></code></td>
						<td><?php echo esc_html( $r->nombre ); ?></td>
						<td><?php echo esc_html( $r->whatsapp ); ?></td>
						<td><?php echo esc_html( $r->creado ); ?></td>
						<td><?php echo esc_html( $r->expira ); ?></td>
						<td><?php echo esc_html( $estado ); ?></td>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
			</tbody>
		</table>
		<p><em>Nota: el código se entrega en pantalla y el cliente lo envía por WhatsApp con un clic. El envío automático desde el servidor requiere la API de WhatsApp Business (de pago).</em></p>
	</div>
	<?php
}

/* ---------- Precios ---------- */
function lp_page_precios() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$json = wp_json_encode( lp_get_precios(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
	$msg  = isset( $_GET['msg'] ) ? sanitize_key( wp_unslash( $_GET['msg'] ) ) : '';
	$err  = isset( $_GET['err'] ) ? sanitize_text_field( wp_unslash( $_GET['err'] ) ) : '';
	?>
	<div class="wrap">
		<h1>Tabla de precios del cotizador</h1>
		<?php if ( 'ok' === $msg ) : ?><div class="notice notice-success"><p>Precios guardados.</p></div><?php endif; ?>
		<?php if ( 'reset' === $msg ) : ?><div class="notice notice-success"><p>Se restauraron los precios iniciales.</p></div><?php endif; ?>
		<?php if ( 'err' === $msg ) : ?><div class="notice notice-error"><p><?php echo esc_html( $err ); ?></p></div><?php endif; ?>
		<p>Edita los valores numéricos con cuidado (puedes cambiar tarifas por m², mínimos, límites en cm, medidas, materiales y acabados). Los precios se aplican al cotizador y al cálculo del servidor. Tipos de acabado: <code>unidad</code>, <code>m2</code> o <code>fijo</code>.</p>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="lp_save_precios">
			<?php wp_nonce_field( 'lp_save_precios' ); ?>
			<textarea name="precios_json" rows="30" class="large-text code" spellcheck="false"><?php echo esc_textarea( $json ); ?></textarea>
			<p><button type="submit" class="button button-primary">Guardar precios</button></p>
		</form>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" onsubmit="return confirm('¿Restaurar los precios iniciales?');">
			<input type="hidden" name="action" value="lp_reset_precios">
			<?php wp_nonce_field( 'lp_reset_precios' ); ?>
			<button type="submit" class="button">Restaurar precios iniciales</button>
		</form>
	</div>
	<?php
}

function lp_action_save_precios() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Sin permiso.' );
	}
	check_admin_referer( 'lp_save_precios' );
	$raw = isset( $_POST['precios_json'] ) ? wp_unslash( $_POST['precios_json'] ) : '';
	$arr = json_decode( $raw, true );
	if ( null === $arr ) {
		wp_safe_redirect( add_query_arg( array( 'page' => 'lp-precios', 'msg' => 'err', 'err' => rawurlencode( 'El texto no es un JSON válido (revisa comas, comillas y llaves).' ) ), admin_url( 'admin.php' ) ) );
		exit;
	}
	$valid = lp_validate_precios( $arr );
	if ( true !== $valid ) {
		wp_safe_redirect( add_query_arg( array( 'page' => 'lp-precios', 'msg' => 'err', 'err' => rawurlencode( $valid ) ), admin_url( 'admin.php' ) ) );
		exit;
	}
	update_option( 'lp_precios', $arr );
	wp_safe_redirect( add_query_arg( array( 'page' => 'lp-precios', 'msg' => 'ok' ), admin_url( 'admin.php' ) ) );
	exit;
}
add_action( 'admin_post_lp_save_precios', 'lp_action_save_precios' );

function lp_action_reset_precios() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Sin permiso.' );
	}
	check_admin_referer( 'lp_reset_precios' );
	delete_option( 'lp_precios' );
	wp_safe_redirect( add_query_arg( array( 'page' => 'lp-precios', 'msg' => 'reset' ), admin_url( 'admin.php' ) ) );
	exit;
}
add_action( 'admin_post_lp_reset_precios', 'lp_action_reset_precios' );
