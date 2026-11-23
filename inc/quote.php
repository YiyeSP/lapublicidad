<?php
/**
 * Precios, cálculo de cotizaciones, base de datos y AJAX.
 *
 * El cálculo se hace en JavaScript (para mostrar el precio en vivo) y se REPITE en el servidor
 * al guardar: el total que queda en la base de datos es siempre el del servidor, así nunca hay
 * diferencias entre el precio mostrado y la tabla de precios.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* =====================================================================
 * 1. TABLA DE PRECIOS (valores iniciales; se editan en LA PUBLICIDAD → Precios)
 * ===================================================================== */
function lp_default_precios() {
	return array(
		'pendon'       => array(
			'nombre'     => 'Pendón',
			'tipo'       => 'm2',
			'minimo'     => 35000,
			'limites'    => array( 30, 300 ),
			'medidas'    => array( array( 80, 200 ), array( 100, 200 ), array( 120, 240 ) ),
			'materiales' => array(
				array( 'id' => 'frontlit13', 'nombre' => 'Lona frontlit 13 oz', 'tarifa' => 35000, 'tarifa_diseno' => 50000 ),
			),
			'diseno'     => true,
			'acabados'   => array(
				array( 'id' => 'ojales', 'nombre' => 'Ojales', 'valor' => 5000, 'tipo' => 'unidad' ),
				array( 'id' => 'bolsillo', 'nombre' => 'Bolsillos para tubo', 'valor' => 8000, 'tipo' => 'unidad' ),
			),
		),
		'vinilo'       => array(
			'nombre'     => 'Vinilo impreso',
			'tipo'       => 'm2',
			'minimo'     => 30000,
			'limites'    => array( 30, 300 ),
			'medidas'    => array( array( 50, 50 ), array( 100, 100 ), array( 100, 150 ) ),
			'materiales' => array(
				array( 'id' => 'mate', 'nombre' => 'Vinilo mate', 'tarifa' => 45000, 'tarifa_diseno' => 90000 ),
				array( 'id' => 'brillante', 'nombre' => 'Vinilo brillante', 'tarifa' => 45000, 'tarifa_diseno' => 90000 ),
				array( 'id' => 'transparente', 'nombre' => 'Vinilo transparente (sin tinta blanca)', 'tarifa' => 45000, 'tarifa_diseno' => 90000 ),
			),
			'diseno'     => true,
			'acabados'   => array(
				array( 'id' => 'laminado', 'nombre' => 'Laminado', 'valor' => 25000, 'tipo' => 'm2' ),
				array( 'id' => 'contorno', 'nombre' => 'Corte de contorno', 'valor' => 20000, 'tipo' => 'm2' ),
				array( 'id' => 'instalacion', 'nombre' => 'Instalación', 'valor' => 16000, 'tipo' => 'm2' ),
			),
			'descuentos' => array( array( 1, 0 ), array( 2, 5 ), array( 3, 12.5 ), array( 4, 20 ), array( 5, 25 ), array( 6, 30 ), array( 7, 32.5 ), array( 8, 35 ), array( 10, 40 ) ),
		),
		'stickers'     => array(
			'nombre'      => 'Stickers troquelados (para emprendedores)',
			'tipo'        => 'paquete',
			'precio'      => 45000,
			'descripcion' => 'Paquete de 500 unidades de 3×3 cm con corte de contorno',
		),
		'pasacalle'    => array(
			'nombre'          => 'Pasacalle',
			'tipo'            => 'm2',
			'minimo'          => 90000,
			'limites'         => array( 100, 600 ),
			'medidas'         => array( array( 300, 100 ), array( 400, 100 ), array( 500, 100 ) ),
			'materiales'      => array(
				array( 'id' => 'frontlit13', 'nombre' => 'Lona frontlit 13 oz', 'tarifa' => 35000 ),
			),
			'diseno'          => false,
			'diseno_incluido' => true,
			'acabados'        => array(
				array( 'id' => 'refuerzo', 'nombre' => 'Ojales y refuerzo perimetral', 'valor' => 12000, 'tipo' => 'fijo' ),
			),
		),
		'cajaluminosa' => array(
			'nombre'     => 'Caja luminosa',
			'tipo'       => 'm2',
			'minimo'     => 200000,
			'limites'    => array( 40, 300 ),
			'medidas'    => array( array( 60, 40 ), array( 100, 50 ), array( 120, 60 ) ),
			'materiales' => array(
				array( 'id' => 'estandar', 'nombre' => 'Caja luminosa con impresión en lona', 'tarifa' => 300000 ),
			),
			'diseno'     => false,
			'acabados'   => array(
				array( 'id' => 'instalacion', 'nombre' => 'Instalación', 'valor' => 60000, 'tipo' => 'fijo' ),
			),
		),
		'neon'         => array(
			'nombre'     => 'Aviso LED neón',
			'tipo'       => 'm2',
			'minimo'     => 150000,
			'limites'    => array( 20, 150 ),
			'medidas'    => array( array( 40, 20 ), array( 60, 30 ), array( 100, 40 ) ),
			'materiales' => array(
				array( 'id' => 'neonflex', 'nombre' => 'Neón flex LED', 'tarifa' => 1200000 ),
			),
			'diseno'     => false,
			'acabados'   => array(
				array( 'id' => 'instalacion', 'nombre' => 'Instalación', 'valor' => 40000, 'tipo' => 'fijo' ),
			),
		),
		'letras3d'     => array(
			'nombre'     => 'Letras 3D',
			'tipo'       => 'm2',
			'minimo'     => 150000,
			'limites'    => array( 20, 300 ),
			'medidas'    => array(),
			'materiales' => array(
				array( 'id' => 'acrilico3d', 'nombre' => 'Acrílico 3D', 'tarifa' => 1200000 ),
			),
			'diseno'     => false,
			'acabados'   => array(),
		),
	);
}

function lp_get_precios() {
	$saved = get_option( 'lp_precios' );
	if ( is_array( $saved ) && ! empty( $saved ) ) {
		return $saved;
	}
	return lp_default_precios();
}

/**
 * Valida la tabla de precios que se guarda desde el panel. Devuelve true o un mensaje de error.
 */
function lp_validate_precios( $arr ) {
	if ( ! is_array( $arr ) || empty( $arr ) ) {
		return 'La tabla está vacía o no es válida.';
	}
	foreach ( $arr as $key => $p ) {
		if ( ! is_string( $key ) || ! preg_match( '/^[a-z0-9]+$/', $key ) ) {
			return 'La clave "' . $key . '" solo puede tener letras minúsculas sin tildes y números.';
		}
		if ( ! is_array( $p ) || empty( $p['nombre'] ) ) {
			return 'El producto "' . $key . '" necesita un "nombre".';
		}
		if ( isset( $p['tipo'] ) && 'paquete' === $p['tipo'] ) {
			if ( ! isset( $p['precio'] ) || ! is_numeric( $p['precio'] ) ) {
				return 'El producto "' . $key . '" (paquete) necesita un "precio" numérico.';
			}
			continue;
		}
		if ( ! isset( $p['minimo'] ) || ! is_numeric( $p['minimo'] ) ) {
			return 'El producto "' . $key . '" necesita un "minimo" numérico.';
		}
		if ( empty( $p['limites'] ) || ! is_array( $p['limites'] ) || count( $p['limites'] ) !== 2 ) {
			return 'El producto "' . $key . '" necesita "limites": [mínimo, máximo] en cm.';
		}
		if ( empty( $p['materiales'] ) || ! is_array( $p['materiales'] ) ) {
			return 'El producto "' . $key . '" necesita al menos un material.';
		}
		foreach ( $p['materiales'] as $m ) {
			if ( empty( $m['id'] ) || empty( $m['nombre'] ) || ! isset( $m['tarifa'] ) || ! is_numeric( $m['tarifa'] ) ) {
				return 'Cada material de "' . $key . '" necesita id, nombre y tarifa numérica.';
			}
		}
		if ( ! empty( $p['acabados'] ) ) {
			foreach ( $p['acabados'] as $a ) {
				if ( empty( $a['id'] ) || empty( $a['nombre'] ) || ! isset( $a['valor'] ) || ! is_numeric( $a['valor'] ) ) {
					return 'Cada acabado de "' . $key . '" necesita id, nombre y valor numérico.';
				}
				if ( empty( $a['tipo'] ) || ! in_array( $a['tipo'], array( 'unidad', 'm2', 'fijo' ), true ) ) {
					return 'El "tipo" de cada acabado de "' . $key . '" debe ser unidad, m2 o fijo.';
				}
			}
		}
	}
	return true;
}

/* =====================================================================
 * 2. MOTOR DE CÁLCULO (el mismo orden de operaciones que assets/js/main.js)
 * ===================================================================== */
function lp_calcular( $in ) {
	$precios = lp_get_precios();
	$key     = isset( $in['producto'] ) ? sanitize_key( $in['producto'] ) : '';
	if ( ! isset( $precios[ $key ] ) ) {
		return array( 'ok' => false, 'error' => 'Producto no válido.' );
	}
	$p    = $precios[ $key ];
	$cant = isset( $in['cantidad'] ) ? intval( $in['cantidad'] ) : 1;
	$cant = max( 1, min( 1000, $cant ) );

	if ( isset( $p['tipo'] ) && 'paquete' === $p['tipo'] ) {
		$t = (int) round( $p['precio'] * $cant );
		return array(
			'ok'              => true,
			'producto'        => $p['nombre'],
			'medidas'         => isset( $p['descripcion'] ) ? $p['descripcion'] : '',
			'material'        => '',
			'diseno'          => false,
			'area'            => 0,
			'cantidad'        => $cant,
			'impresion'       => $t,
			'minimo_aplicado' => false,
			'pct_vol'         => 0,
			'desc_vol'        => 0,
			'acabados'        => 0,
			'acabados_det'    => array(),
			'total'           => $t,
		);
	}

	$ancho = isset( $in['ancho'] ) ? floatval( $in['ancho'] ) : 0;
	$alto  = isset( $in['alto'] ) ? floatval( $in['alto'] ) : 0;
	if ( $ancho <= 0 || $alto <= 0 ) {
		return array( 'ok' => false, 'error' => 'Ingresa el ancho y el alto.' );
	}
	$lmin = $p['limites'][0];
	$lmax = $p['limites'][1];
	if ( $ancho < $lmin || $ancho > $lmax || $alto < $lmin || $alto > $lmax ) {
		return array( 'ok' => false, 'error' => 'Cada lado debe medir entre ' . $lmin . ' y ' . $lmax . ' cm.' );
	}
	$area = ( $ancho * $alto ) / 10000;

	$mid = isset( $in['material'] ) ? sanitize_text_field( $in['material'] ) : '';
	$mat = $p['materiales'][0];
	foreach ( $p['materiales'] as $m ) {
		if ( $m['id'] === $mid ) {
			$mat = $m;
		}
	}
	$diseno = ! empty( $in['diseno'] ) && ! empty( $p['diseno'] );
	$tarifa = ( $diseno && ! empty( $mat['tarifa_diseno'] ) ) ? $mat['tarifa_diseno'] : $mat['tarifa'];

	$impresion = $area * $tarifa;
	$min_ap    = $impresion < $p['minimo'];
	$base      = max( $impresion, $p['minimo'] );
	$sub       = $base * $cant;

	$pct = 0;
	if ( ! empty( $p['descuentos'] ) ) {
		$m2 = $area * $cant;
		foreach ( $p['descuentos'] as $d ) {
			if ( $m2 >= $d[0] ) {
				$pct = $d[1];
			}
		}
	}
	$desc_vol = $sub * $pct / 100;

	$ids = array();
	if ( isset( $in['acabados'] ) && is_array( $in['acabados'] ) ) {
		$ids = array_map( 'sanitize_text_field', $in['acabados'] );
	}
	$ac_total = 0;
	$ac_det   = array();
	if ( ! empty( $p['acabados'] ) ) {
		foreach ( $p['acabados'] as $a ) {
			if ( ! in_array( $a['id'], $ids, true ) ) {
				continue;
			}
			if ( 'unidad' === $a['tipo'] ) {
				$v = $a['valor'] * $cant;
			} elseif ( 'm2' === $a['tipo'] ) {
				$v = $a['valor'] * $area * $cant;
			} else {
				$v = $a['valor'];
			}
			$ac_total += $v;
			$ac_det[]  = array( 'nombre' => $a['nombre'], 'valor' => (int) round( $v ) );
		}
	}

	$total = (int) round( $sub - $desc_vol + $ac_total );

	return array(
		'ok'              => true,
		'producto'        => $p['nombre'],
		'medidas'         => $ancho . ' × ' . $alto . ' cm',
		'material'        => $mat['nombre'],
		'diseno'          => $diseno,
		'area'            => $area,
		'cantidad'        => $cant,
		'impresion'       => (int) round( $impresion ),
		'minimo_aplicado' => $min_ap,
		'pct_vol'         => $pct,
		'desc_vol'        => (int) round( $desc_vol ),
		'acabados'        => (int) round( $ac_total ),
		'acabados_det'    => $ac_det,
		'total'           => $total,
	);
}

function lp_resumen_texto( $c ) {
	$l   = array();
	$l[] = 'Producto: ' . $c['producto'];
	if ( ! empty( $c['medidas'] ) ) {
		$l[] = 'Medidas: ' . $c['medidas'];
	}
	if ( ! empty( $c['material'] ) ) {
		$l[] = 'Material: ' . $c['material'];
	}
	$l[] = 'Cantidad: ' . $c['cantidad'];
	if ( ! empty( $c['diseno'] ) ) {
		$l[] = 'Incluye diseño gráfico';
	}
	foreach ( $c['acabados_det'] as $a ) {
		$l[] = 'Acabado: ' . $a['nombre'];
	}
	if ( ! empty( $c['pct_vol'] ) ) {
		$l[] = 'Descuento por volumen: ' . $c['pct_vol'] . ' %';
	}
	return implode( "\n", $l );
}

/* =====================================================================
 * 3. BASE DE DATOS (tablas propias: cotizaciones y descuentos)
 * ===================================================================== */
function lp_install_db() {
	global $wpdb;
	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	$charset = $wpdb->get_charset_collate();
	$t_cot   = $wpdb->prefix . 'lp_cotizaciones';
	$t_desc  = $wpdb->prefix . 'lp_descuentos';

	dbDelta(
		"CREATE TABLE $t_cot (
  id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  creado datetime NOT NULL,
  tipo varchar(10) NOT NULL DEFAULT 'rapida',
  nombre varchar(120) NOT NULL,
  whatsapp varchar(15) NOT NULL,
  correo varchar(120) NOT NULL DEFAULT '',
  documento varchar(40) NOT NULL DEFAULT '',
  ciudad varchar(80) NOT NULL DEFAULT '',
  producto varchar(120) NOT NULL,
  resumen text NOT NULL,
  cantidad int(11) NOT NULL DEFAULT 1,
  total bigint(20) NOT NULL DEFAULT 0,
  codigo varchar(20) NOT NULL DEFAULT '',
  estado varchar(20) NOT NULL DEFAULT 'nueva',
  PRIMARY KEY  (id),
  KEY whatsapp (whatsapp)
) $charset;"
	);

	dbDelta(
		"CREATE TABLE $t_desc (
  id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  codigo varchar(20) NOT NULL,
  nombre varchar(120) NOT NULL DEFAULT '',
  whatsapp varchar(15) NOT NULL,
  creado datetime NOT NULL,
  expira datetime NOT NULL,
  usado tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY  (id),
  UNIQUE KEY codigo (codigo),
  KEY whatsapp (whatsapp)
) $charset;"
	);

	update_option( 'lp_db_version', LP_VERSION );
}

function lp_maybe_install_db() {
	if ( get_option( 'lp_db_version' ) !== LP_VERSION ) {
		lp_install_db();
	}
}
add_action( 'init', 'lp_maybe_install_db' );

/* =====================================================================
 * 4. AJAX
 * ===================================================================== */
function lp_client_ip() {
	return isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '0';
}

function lp_rate_limit( $bucket, $max, $window ) {
	$k = 'lp_rl_' . $bucket . '_' . md5( lp_client_ip() );
	$n = (int) get_transient( $k );
	if ( $n >= $max ) {
		return false;
	}
	set_transient( $k, $n + 1, $window );
	return true;
}

/** Número colombiano de 10 dígitos que empieza por 3 (acepta +57). Devuelve '' si no es válido. */
function lp_clean_wa( $raw ) {
	$d = preg_replace( '/\D/', '', (string) $raw );
	if ( strlen( $d ) === 12 && '57' === substr( $d, 0, 2 ) ) {
		$d = substr( $d, 2 );
	}
	return preg_match( '/^3\d{9}$/', $d ) ? $d : '';
}

function lp_ajax_guardar() {
	check_ajax_referer( 'lp_nonce', 'nonce' );
	if ( ! empty( $_POST['web'] ) ) {
		wp_send_json_error( array( 'message' => 'Solicitud no válida.' ) );
	}
	if ( ! lp_rate_limit( 'cot', 12, HOUR_IN_SECONDS ) ) {
		wp_send_json_error( array( 'message' => 'Demasiados intentos. Intenta de nuevo más tarde.' ) );
	}
	global $wpdb;
	$post   = wp_unslash( $_POST );
	$tipo   = ( isset( $post['tipo'] ) && 'formal' === $post['tipo'] ) ? 'formal' : 'rapida';
	$nombre = isset( $post['nombre'] ) ? sanitize_text_field( $post['nombre'] ) : '';
	$wa     = lp_clean_wa( isset( $post['whatsapp'] ) ? $post['whatsapp'] : '' );
	$errors = array();

	if ( strlen( $nombre ) < 3 ) {
		$errors['nombre'] = 'Escribe tu nombre completo.';
	}
	if ( '' === $wa ) {
		$errors['whatsapp'] = 'Ingresa un WhatsApp colombiano de 10 dígitos (empieza por 3).';
	}
	if ( empty( $post['consent'] ) ) {
		$errors['consent'] = 'Debes autorizar el tratamiento de tus datos personales.';
	}

	$correo    = '';
	$documento = '';
	$ciudad    = '';
	if ( 'formal' === $tipo ) {
		$correo    = isset( $post['correo'] ) ? sanitize_email( $post['correo'] ) : '';
		$documento = isset( $post['documento'] ) ? preg_replace( '/[^A-Za-z0-9\-\.]/', '', $post['documento'] ) : '';
		$ciudad    = isset( $post['ciudad'] ) ? sanitize_text_field( $post['ciudad'] ) : '';
		if ( ! is_email( $correo ) ) {
			$errors['correo'] = 'Escribe un correo válido.';
		}
		if ( strlen( $documento ) < 6 ) {
			$errors['documento'] = 'Escribe tu cédula o NIT.';
		}
		if ( strlen( $ciudad ) < 2 ) {
			$errors['ciudad'] = 'Escribe tu ciudad.';
		}
	}

	$calc = lp_calcular( $post );
	if ( empty( $calc['ok'] ) ) {
		$errors['cotizacion'] = $calc['error'];
	}
	if ( $errors ) {
		wp_send_json_error( array( 'message' => 'Revisa los campos marcados.', 'errors' => $errors ) );
	}

	$total          = (int) $calc['total'];
	$total_original = $total;
	$descuento_web  = 0;
	$codigo         = isset( $post['codigo'] ) ? strtoupper( preg_replace( '/[^A-Za-z0-9\-]/', '', $post['codigo'] ) ) : '';
	$t_desc         = $wpdb->prefix . 'lp_descuentos';
	$row            = null;
	if ( '' !== $codigo ) {
		$row = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM $t_desc WHERE codigo = %s AND whatsapp = %s AND usado = 0 AND expira >= %s",
				$codigo,
				$wa,
				current_time( 'mysql' )
			)
		);
		if ( ! $row ) {
			wp_send_json_error(
				array(
					'message' => 'Revisa los campos marcados.',
					'errors'  => array( 'codigo' => 'El código no es válido, ya se usó o está vencido (debe ser el del mismo WhatsApp).' ),
				)
			);
		}
		$descuento_web = (int) round( $total * 0.10 );
		$total         = $total - $descuento_web;
	}

	$resumen = lp_resumen_texto( $calc );
	$ok      = $wpdb->insert(
		$wpdb->prefix . 'lp_cotizaciones',
		array(
			'creado'    => current_time( 'mysql' ),
			'tipo'      => $tipo,
			'nombre'    => $nombre,
			'whatsapp'  => $wa,
			'correo'    => $correo,
			'documento' => $documento,
			'ciudad'    => $ciudad,
			'producto'  => $calc['producto'],
			'resumen'   => $resumen,
			'cantidad'  => $calc['cantidad'],
			'total'     => $total,
			'codigo'    => $row ? $codigo : '',
			'estado'    => 'nueva',
		),
		array( '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%d', '%s', '%s' )
	);
	if ( false === $ok ) {
		wp_send_json_error( array( 'message' => 'No pudimos guardar tu cotización. Intenta de nuevo.' ) );
	}
	$id = (int) $wpdb->insert_id;
	if ( $row ) {
		$wpdb->update( $t_desc, array( 'usado' => 1 ), array( 'id' => $row->id ), array( '%d' ), array( '%d' ) );
	}

	$msg  = 'Hola, soy ' . $nombre . '. Solicito cotización ' . ( 'formal' === $tipo ? 'formal ' : '' ) . '#' . $id . " de LA PUBLICIDAD:\n" . $resumen . "\n";
	$msg .= 'Total estimado: ' . lp_money( $total ) . ' COP (precio sujeto a verificación).';
	if ( $row ) {
		$msg .= "\nCódigo de descuento web aplicado: " . $codigo . ' (10 %).';
	}

	wp_send_json_success(
		array(
			'id'             => $id,
			'total'          => $total,
			'total_original' => $total_original,
			'descuento_web'  => $descuento_web,
			'resumen'        => $resumen,
			'whatsapp_url'   => lp_wa_link( $msg ),
		)
	);
}
add_action( 'wp_ajax_nopriv_lp_guardar_cotizacion', 'lp_ajax_guardar' );
add_action( 'wp_ajax_lp_guardar_cotizacion', 'lp_ajax_guardar' );

function lp_ajax_descuento() {
	check_ajax_referer( 'lp_nonce', 'nonce' );
	if ( ! empty( $_POST['web'] ) ) {
		wp_send_json_error( array( 'message' => 'Solicitud no válida.' ) );
	}
	if ( ! lp_rate_limit( 'desc', 6, HOUR_IN_SECONDS ) ) {
		wp_send_json_error( array( 'message' => 'Demasiados intentos. Intenta de nuevo más tarde.' ) );
	}
	global $wpdb;
	$post   = wp_unslash( $_POST );
	$nombre = isset( $post['nombre'] ) ? sanitize_text_field( $post['nombre'] ) : '';
	$wa     = lp_clean_wa( isset( $post['whatsapp'] ) ? $post['whatsapp'] : '' );
	if ( strlen( $nombre ) < 3 ) {
		wp_send_json_error( array( 'message' => 'Escribe tu nombre completo.' ) );
	}
	if ( '' === $wa ) {
		wp_send_json_error( array( 'message' => 'Ingresa un WhatsApp colombiano de 10 dígitos (empieza por 3).' ) );
	}
	if ( empty( $post['consent'] ) ) {
		wp_send_json_error( array( 'message' => 'Debes autorizar el tratamiento de tus datos personales.' ) );
	}

	$t    = $wpdb->prefix . 'lp_descuentos';
	$mes  = gmdate( 'Y-m', current_time( 'timestamp' ) );
	$ya   = $wpdb->get_row(
		$wpdb->prepare(
			"SELECT codigo, expira FROM $t WHERE whatsapp = %s AND DATE_FORMAT(creado, '%%Y-%%m') = %s ORDER BY id DESC LIMIT 1",
			$wa,
			$mes
		)
	);
	if ( $ya ) {
		$vence = date_i18n( 'j \d\e F \d\e Y', strtotime( $ya->expira ) );
		wp_send_json_error(
			array( 'message' => 'Este número ya generó su descuento de este mes (código ' . $ya->codigo . ', vence el ' . $vence . '). Se permite uno por mes.' )
		);
	}

	$codigo = '';
	for ( $i = 0; $i < 5; $i++ ) {
		$try = 'LP-' . strtoupper( wp_generate_password( 5, false, false ) );
		if ( ! $wpdb->get_var( $wpdb->prepare( "SELECT id FROM $t WHERE codigo = %s", $try ) ) ) {
			$codigo = $try;
			break;
		}
	}
	if ( '' === $codigo ) {
		wp_send_json_error( array( 'message' => 'No pudimos generar el código. Intenta de nuevo.' ) );
	}

	$ahora  = current_time( 'timestamp' );
	$expira = $ahora + 5 * DAY_IN_SECONDS;
	$ok     = $wpdb->insert(
		$t,
		array(
			'codigo'   => $codigo,
			'nombre'   => $nombre,
			'whatsapp' => $wa,
			'creado'   => gmdate( 'Y-m-d H:i:s', $ahora ),
			'expira'   => gmdate( 'Y-m-d H:i:s', $expira ),
			'usado'    => 0,
		),
		array( '%s', '%s', '%s', '%s', '%s', '%d' )
	);
	if ( false === $ok ) {
		wp_send_json_error( array( 'message' => 'No pudimos generar el código. Intenta de nuevo.' ) );
	}

	$vence = date_i18n( 'j \d\e F \d\e Y', $expira );
	$msg   = 'Hola, soy ' . $nombre . '. Quiero usar mi código de descuento web ' . $codigo . ' (10 %, vence el ' . $vence . ').';
	wp_send_json_success(
		array(
			'codigo'       => $codigo,
			'vence'        => $vence,
			'whatsapp_url' => lp_wa_link( $msg ),
		)
	);
}
add_action( 'wp_ajax_nopriv_lp_generar_descuento', 'lp_ajax_descuento' );
add_action( 'wp_ajax_lp_generar_descuento', 'lp_ajax_descuento' );
