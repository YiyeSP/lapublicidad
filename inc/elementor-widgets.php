<?php
/**
 * Widgets de Elementor. Este archivo solo se carga cuando Elementor está activo.
 * Cada widget muestra el shortcode correspondiente, con opciones en el panel.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class LP_Widget_Base extends \Elementor\Widget_Base {

	public function get_categories() {
		return array( 'lapublicidad' );
	}

	protected function lp_controls() {
	}

	protected function register_controls() {
		$this->lp_controls();
	}

	abstract protected function lp_shortcode( $s );

	protected function render() {
		$s = $this->get_settings_for_display();
		echo do_shortcode( $this->lp_shortcode( $s ) ); // phpcs:ignore WordPress.Security.EscapeOutput
	}
}

class LP_Widget_Coverflow extends LP_Widget_Base {
	public function get_name() {
		return 'lp_coverflow';
	}
	public function get_title() {
		return 'Carrusel de productos 3D';
	}
	public function get_icon() {
		return 'eicon-slider-3d';
	}
	public function get_keywords() {
		return array( 'coverflow', 'carrusel', 'productos', 'catalogo' );
	}
	protected function lp_controls() {
		$this->start_controls_section( 'sec', array( 'label' => 'Contenido' ) );
		$this->add_control(
			'info',
			array(
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw'  => 'Muestra los elementos de <b>Productos</b> en el menú de WordPress.',
			)
		);
		$this->add_control(
			'cantidad',
			array(
				'label'   => 'Cantidad de productos',
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'default' => 12,
				'min'     => 3,
				'max'     => 30,
			)
		);
		$this->end_controls_section();
	}
	protected function lp_shortcode( $s ) {
		return '[lp_coverflow cantidad="' . (int) $s['cantidad'] . '"]';
	}
}

class LP_Widget_Cotizador extends LP_Widget_Base {
	public function get_name() {
		return 'lp_cotizador';
	}
	public function get_title() {
		return 'Cotizador con calculadora';
	}
	public function get_icon() {
		return 'eicon-price-table';
	}
	public function get_keywords() {
		return array( 'cotizador', 'calculadora', 'precio', 'cotizar' );
	}
	protected function lp_controls() {
		$this->start_controls_section( 'sec', array( 'label' => 'Contenido' ) );
		$this->add_control(
			'info',
			array(
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw'  => 'Los precios se editan en <b>LA PUBLICIDAD → Precios</b>. Las solicitudes quedan en <b>LA PUBLICIDAD → Cotizaciones</b>.',
			)
		);
		$this->end_controls_section();
	}
	protected function lp_shortcode( $s ) {
		return '[lp_cotizador]';
	}
}

class LP_Widget_Servicios extends LP_Widget_Base {
	public function get_name() {
		return 'lp_servicios';
	}
	public function get_title() {
		return 'Tarjetas de servicios';
	}
	public function get_icon() {
		return 'eicon-posts-grid';
	}
	public function get_keywords() {
		return array( 'servicios', 'tarjetas' );
	}
	protected function lp_controls() {
		$this->start_controls_section( 'sec', array( 'label' => 'Contenido' ) );
		$this->add_control(
			'columnas',
			array(
				'label'   => 'Columnas',
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => '3',
				'options' => array( '2' => '2', '3' => '3', '4' => '4' ),
			)
		);
		$this->end_controls_section();
	}
	protected function lp_shortcode( $s ) {
		return '[lp_servicios columnas="' . (int) $s['columnas'] . '"]';
	}
}

class LP_Widget_Portafolio extends LP_Widget_Base {
	public function get_name() {
		return 'lp_portafolio';
	}
	public function get_title() {
		return 'Portafolio (trabajos realizados)';
	}
	public function get_icon() {
		return 'eicon-gallery-grid';
	}
	public function get_keywords() {
		return array( 'portafolio', 'trabajos', 'galeria' );
	}
	protected function lp_controls() {
		$this->start_controls_section( 'sec', array( 'label' => 'Contenido' ) );
		$this->add_control(
			'columnas',
			array(
				'label'   => 'Columnas',
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => '4',
				'options' => array( '2' => '2', '3' => '3', '4' => '4' ),
			)
		);
		$this->add_control(
			'cantidad',
			array(
				'label'   => 'Cantidad de trabajos',
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'default' => 8,
				'min'     => 1,
				'max'     => 24,
			)
		);
		$this->end_controls_section();
	}
	protected function lp_shortcode( $s ) {
		return '[lp_portafolio columnas="' . (int) $s['columnas'] . '" cantidad="' . (int) $s['cantidad'] . '"]';
	}
}

class LP_Widget_Boton_Descuento extends LP_Widget_Base {
	public function get_name() {
		return 'lp_boton_descuento';
	}
	public function get_title() {
		return 'Botón de descuento web';
	}
	public function get_icon() {
		return 'eicon-button';
	}
	public function get_keywords() {
		return array( 'descuento', 'boton', 'promocion' );
	}
	protected function lp_controls() {
		$this->start_controls_section( 'sec', array( 'label' => 'Contenido' ) );
		$this->add_control(
			'texto',
			array(
				'label'   => 'Texto del botón',
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => 'QUIERO MI DESCUENTO',
			)
		);
		$this->add_control(
			'estilo',
			array(
				'label'   => 'Estilo',
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'green',
				'options' => array( 'green' => 'Verde', 'outline' => 'Contorno' ),
			)
		);
		$this->end_controls_section();
	}
	protected function lp_shortcode( $s ) {
		$texto  = str_replace( array( '"', '[', ']' ), '', (string) $s['texto'] );
		$estilo = 'outline' === $s['estilo'] ? 'outline' : 'green';
		return '[lp_boton_descuento texto="' . $texto . '" estilo="' . $estilo . '"]';
	}
}

class LP_Widget_Contacto extends LP_Widget_Base {
	public function get_name() {
		return 'lp_contacto';
	}
	public function get_title() {
		return 'Contacto y formulario';
	}
	public function get_icon() {
		return 'eicon-form-horizontal';
	}
	public function get_keywords() {
		return array( 'contacto', 'formulario', 'whatsapp' );
	}
	protected function lp_controls() {
		$this->start_controls_section( 'sec', array( 'label' => 'Contenido' ) );
		$this->add_control(
			'info',
			array(
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw'  => 'Los datos de contacto se cambian en <b>Apariencia → Personalizar → LA PUBLICIDAD · Datos de contacto</b>.',
			)
		);
		$this->end_controls_section();
	}
	protected function lp_shortcode( $s ) {
		return '[lp_contacto]';
	}
}
