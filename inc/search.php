<?php
/**
 * Mejora de búsqueda: CPTs, sinónimos y variantes comunes (acentos/mayúsculas/ortografía frecuente).
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Grupos de sinónimos/palabras clave para búsquedas de publicidad.
 * Cada grupo se expande cuando la búsqueda contiene cualquiera de sus términos.
 *
 * @return array<int, array<int, string>>
 */
function lp_search_synonym_groups() {
	return array(
		array( 'sticker', 'stickers', 'esticker', 'estickers', 'estiker', 'estiquers', 'stiker', 'stikers', 'calcomania', 'calcomanias', 'adhesivo', 'adhesivos', 'etiqueta', 'etiquetas', 'pegatina', 'pegatinas', 'vinilo' ),
		array( 'aviso', 'avisos', 'letrero', 'letreros', 'senaletica', 'senalizacion', 'señaletica', 'señalizacion', 'cartel', 'carteles', 'marco', 'metalico', 'metálico' ),
		array( 'pendon', 'pendones', 'banner', 'banners', 'roll', 'rollup', 'roll-up', 'porta', 'portapendon' ),
		array( 'pasacalle', 'pasacalles', 'valla', 'vallas', 'lona', 'lamina', 'lámina', 'outdoor', 'exterior', 'publicitaria', 'publicitario' ),
		array( 'caja', 'cajas', 'luminosa', 'luminosas', 'luminoso', 'luminosos', 'lightbox', 'cajon', 'cajón' ),
		array( 'cuadrada', 'cuadrado', 'cuadradas', 'cuadrados' ),
		array( 'diseno', 'diseño', 'disenos', 'diseños', 'grafico', 'grafica', 'graficos', 'graficas', 'gráfico', 'gráfica', 'gráficos', 'gráficas', 'arte', 'publicidad', 'logotipo', 'logotipos', 'logo', 'logos', 'marca', 'marcas', 'identidad', 'corporativa', 'corporativo', 'imagen' ),
		array( 'vinilo', 'vinilos', 'microperforado', 'microperforados', 'laminado', 'laminados', 'adhesivo', 'adhesivos' ),
		array( 'tarjeta', 'tarjetas', 'presentacion', 'presentación', 'corporativa', 'corporativas', 'mate', 'uv', 'parcial', 'sencilla', 'sencillas' ),
		array( 'volante', 'volantes', 'flyer', 'flyers', 'plegable', 'plegables', 'brochure' ),
		array( 'folleto', 'folletos', 'triptico', 'tríptico', 'diptico', 'díptico' ),
		array( 'revista', 'revistas', 'catalogo', 'catálogo', 'catalogos', 'catálogos' ),
		array( 'carpeta', 'carpetas', 'carta', 'oficio', 'folder' ),
		array( 'mug', 'mugs', 'pocillo', 'pocillos', 'taza', 'tazas' ),
		array( 'camiseta', 'camisetas', 'camisa', 'camisas', 'estampado', 'estampados', 'sublimacion', 'sublimación' ),
		array( 'impresion', 'impresión', 'impresiones', 'imprimir', 'digital', 'gran', 'formato' ),
		array( 'corte', 'troquelado', 'troquel', 'troquelados' ),
		array( 'laser', 'láser', 'mdf', 'polietileno', 'acrilico', 'acrílico' ),
		array( 'corporeo', 'corpóreo', 'corporeos', 'corpóreos', '3d', '2d', 'neon', 'neón', 'flex' ),
		array( 'instalacion', 'instalación', 'montaje', 'instalar', 'avisos', 'vinilos' ),
		array( 'litografia', 'litografía', 'litografico', 'litográfico', 'offset' ),
		array( 'sello', 'sellos', 'sellado' ),
		array( 'factura', 'facturas', 'facturero', 'factureros', 'talonario', 'talonarios', 'recibo', 'recibos' ),
	);
}

/**
 * Normaliza texto para comparación: minúsculas y sin acentos comunes.
 *
 * @param string $text Texto.
 * @return string
 */
function lp_search_normalize( $text ) {
	$text = strtolower( remove_accents( (string) $text ) );
	$text = preg_replace( '/[^a-z0-9\s\-]/u', ' ', $text );
	$text = preg_replace( '/\s+/', ' ', $text );
	return trim( $text );
}

/**
 * Expande términos de búsqueda con sinónimos/variantes.
 *
 * @param array<int, string> $terms Términos originales.
 * @return array<int, string>
 */
function lp_search_expand_terms( $terms ) {
	$groups   = lp_search_synonym_groups();
	$expanded = array();

	foreach ( $terms as $term ) {
		$term = trim( (string) $term );
		if ( '' === $term ) {
			continue;
		}
		$expanded[] = $term;
		$norm       = lp_search_normalize( $term );
		if ( '' === $norm ) {
			continue;
		}
		foreach ( $groups as $group ) {
			$match = false;
			foreach ( $group as $word ) {
				$nw = lp_search_normalize( $word );
				if ( $nw === $norm || false !== strpos( $nw, $norm ) || false !== strpos( $norm, $nw ) ) {
					$match = true;
					break;
				}
			}
			if ( $match ) {
				foreach ( $group as $word ) {
					$expanded[] = $word;
				}
			}
		}
	}

	// También agrega la forma sin acentos de términos originales.
	foreach ( $terms as $term ) {
		$na = remove_accents( (string) $term );
		if ( $na !== $term ) {
			$expanded[] = $na;
		}
	}

	$uniq = array();
	foreach ( $expanded as $t ) {
		$t = trim( (string) $t );
		if ( '' === $t ) {
			continue;
		}
		$key = lp_search_normalize( $t );
		if ( '' === $key || isset( $uniq[ $key ] ) ) {
			continue;
		}
		$uniq[ $key ] = $t;
	}
	return array_values( $uniq );
}

/**
 * Incluye CPTs del tema en la búsqueda principal.
 *
 * @param WP_Query $query Consulta.
 * @return void
 */
function lp_search_include_cpts( $query ) {
	if ( is_admin() || ! $query->is_main_query() || ! $query->is_search() ) {
		return;
	}
	$query->set( 'post_type', array( 'post', 'page', 'lp_producto', 'lp_servicio', 'lp_trabajo' ) );
	$front = (int) get_option( 'page_on_front' );
	if ( $front > 0 ) {
		$not_in   = (array) $query->get( 'post__not_in' );
		$not_in[] = $front;
		$query->set( 'post__not_in', array_values( array_unique( array_map( 'intval', $not_in ) ) ) );
	}
}
add_action( 'pre_get_posts', 'lp_search_include_cpts' );

/**
 * Amplía el SQL de búsqueda con sinónimos.
 *
 * @param string   $search SQL de búsqueda.
 * @param WP_Query $query  Consulta.
 * @return string
 */
function lp_search_expand_sql( $search, $query ) {
	global $wpdb;
	if ( is_admin() || ! $query->is_main_query() || ! $query->is_search() ) {
		return $search;
	}
	if ( '' === trim( $search ) ) {
		return $search;
	}

	$terms = $query->get( 'search_terms' );
	if ( empty( $terms ) || ! is_array( $terms ) ) {
		$s = $query->get( 's' );
		$terms = '' !== $s ? preg_split( '/\s+/', $s, -1, PREG_SPLIT_NO_EMPTY ) : array();
	}
	$expanded = lp_search_expand_terms( $terms );
	if ( empty( $expanded ) ) {
		return $search;
	}

	// Limita para evitar consultas excesivas.
	$expanded = array_slice( $expanded, 0, 40 );

	$clauses = array();
	$fields  = array( "$wpdb->posts.post_title", "$wpdb->posts.post_content", "$wpdb->posts.post_excerpt" );
	foreach ( $expanded as $term ) {
		$like = '%' . $wpdb->esc_like( $term ) . '%';
		$or   = array();
		foreach ( $fields as $field ) {
			$or[] = $wpdb->prepare( "$field LIKE %s", $like ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		}
		$clauses[] = '(' . implode( ' OR ', $or ) . ')';
	}

	if ( empty( $clauses ) ) {
		return $search;
	}

	// Reemplaza la búsqueda por una cláusula OR amplia de términos/sinónimos.
	// Mantiene el filtro por post_password/status que WP ya aplica fuera de $search.
	return ' AND (' . implode( ' OR ', $clauses ) . ') ';
}
add_filter( 'posts_search', 'lp_search_expand_sql', 10, 2 );