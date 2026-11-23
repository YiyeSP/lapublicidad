<?php
/**
 * Landing por defecto de LA PUBLICIDAD (se usa cuando la página de inicio no tiene contenido propio).
 * Para cambiar textos, edita este archivo; para diseñar arrastrando y soltando, edita la página con Elementor.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<main id="contenido" class="lap-main">

	<!-- 1. HERO -->
	<section id="inicio" class="lap-hero" aria-labelledby="lap-hero-title">
		<div class="lap-container">
			<div class="lap-hero__content">
				<p class="lap-badge"><span class="lap-dot"></span> Putumayo, Colombia — Servicio local</p>
				<h1 id="lap-hero-title" class="lap-hero__title">Haz que tu marca<br><span class="lap-accent">se vea.</span></h1>
				<p class="lap-hero__lead">Impresión digital y soluciones publicitarias para empresas y emprendedores en Putumayo. Calidad profesional, atención cercana y entrega puntual.</p>
				<div class="lap-actions">
					<button type="button" class="lap-btn lap-btn--green" data-lap-open-discount>QUIERO MI DESCUENTO</button>
					<a class="lap-btn lap-btn--outline" href="#servicios">Ver servicios</a>
				</div>
				<ul class="lap-stats">
					<li><strong>[Dato real]</strong><span>Proyectos entregados</span></li>
					<li><strong>[Dato real]</strong><span>Años de experiencia</span></li>
					<li><strong>[Dato real]</strong><span>Clientes satisfechos</span></li>
				</ul>
			</div>
		</div>
		<a class="lap-scroll" href="#productos">Desplázate</a>
	</section>

	<!-- 2. PRODUCTOS DESTACADOS (carrusel 3D) -->
	<section id="productos" class="lap-section lap-bg-surface" aria-labelledby="lap-productos-title">
		<div class="lap-container">
			<header class="lap-head">
				<p class="lap-label">Catálogo</p>
				<h2 id="lap-productos-title" class="lap-h2">Productos <span class="lap-accent">destacados</span></h2>
				<p class="lap-sub">Precios orientativos · cada proyecto se cotiza a medida</p>
			</header>
			<?php echo do_shortcode( '[lp_coverflow]' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</div>
	</section>

	<!-- 3. SERVICIOS -->
	<section id="servicios" class="lap-section lap-bg-navy" aria-labelledby="lap-servicios-title">
		<div class="lap-container">
			<header class="lap-head">
				<p class="lap-label">Lo que hacemos</p>
				<h2 id="lap-servicios-title" class="lap-h2">Nuestros servicios</h2>
				<p class="lap-sub">Desde un sticker hasta una valla de gran formato — cubrimos todo lo que tu marca necesita para destacar.</p>
			</header>
			<?php echo do_shortcode( '[lp_servicios columnas="3"]' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</div>
	</section>

	<!-- 4. COTIZADOR -->
	<section id="cotizador" class="lap-section lap-bg-surface" aria-labelledby="lap-cot-title">
		<div class="lap-container">
			<header class="lap-head lap-head--left">
				<h2 id="lap-cot-title" class="lap-h2">Configura tu proyecto</h2>
				<p class="lap-sub">Calcula un valor estimado con los precios oficiales vigentes para <?php echo esc_html( lp_opt( 'vigencia' ) ); ?>. El valor final será verificado por un asesor.</p>
			</header>
			<?php echo do_shortcode( '[lp_cotizador]' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</div>
	</section>

	<!-- 5. NOSOTROS: emprendedores + por qué elegirnos -->
	<section id="nosotros" class="lap-section lap-bg-light" aria-labelledby="lap-emp-title">
		<div class="lap-container lap-split">
			<div class="lap-collage" aria-hidden="true">
				<img src="<?php echo esc_url( lp_asset( 'img/catalogo/stickers.webp' ) ); ?>" alt="" loading="lazy">
				<img src="<?php echo esc_url( lp_asset( 'img/catalogo/tarjetas-sencillas.webp' ) ); ?>" alt="" loading="lazy">
				<img src="<?php echo esc_url( lp_asset( 'img/catalogo/corte-troquelado.webp' ) ); ?>" alt="" loading="lazy">
				<span class="lap-collage__price"><strong>Desde $25k</strong><small>por proyecto</small></span>
			</div>
			<div>
				<p class="lap-label lap-label--blue">Para emprendedores</p>
				<h2 id="lap-emp-title" class="lap-h2">Impulsa tu emprendimiento con una imagen profesional.</h2>
				<p class="lap-sub lap-sub--left">Sabemos que cuando estás comenzando, cada peso cuenta. Por eso ofrecemos soluciones accesibles y de calidad para pequeños negocios, marcas personales y emprendedores de Putumayo.</p>
				<ul class="lap-chips">
					<li><span class="dashicons dashicons-tag" aria-hidden="true"></span> Etiquetas para productos</li>
					<li><span class="dashicons dashicons-archive" aria-hidden="true"></span> Empaques personalizados</li>
					<li><span class="dashicons dashicons-flag" aria-hidden="true"></span> Pendones para eventos</li>
					<li><span class="dashicons dashicons-store" aria-hidden="true"></span> Avisos para tu local</li>
					<li><span class="dashicons dashicons-format-image" aria-hidden="true"></span> Material POP</li>
					<li><span class="dashicons dashicons-id" aria-hidden="true"></span> Tarjetas y papelería</li>
				</ul>
				<a class="lap-btn lap-btn--green" href="#cotizador">Cotizar mi proyecto →</a>
			</div>
		</div>

		<div class="lap-container lap-why">
			<header class="lap-head">
				<p class="lap-label lap-label--blue">Nuestra diferencia</p>
				<h2 class="lap-h2">¿Por qué elegirnos?</h2>
			</header>
			<div class="lap-grid lap-grid--4">
				<article class="lap-why__card"><span class="dashicons dashicons-groups" aria-hidden="true"></span><small>Trato directo</small><h3>Atención personalizada</h3><p>Te ayudamos a elegir la solución que realmente necesitas, sin tecnicismos ni presión.</p></article>
				<article class="lap-why__card"><span class="dashicons dashicons-awards" aria-hidden="true"></span><small>Alta resolución</small><h3>Calidad de impresión</h3><p>Equipos de gran formato y materiales duraderos para que tu marca se vea profesional.</p></article>
				<article class="lap-why__card"><span class="dashicons dashicons-store" aria-hidden="true"></span><small>Cualquier escala</small><h3>Para empresas y emprendedores</h3><p>Opciones para negocios grandes, pequeños y personas que están comenzando su marca.</p></article>
				<article class="lap-why__card"><span class="dashicons dashicons-admin-tools" aria-hidden="true"></span><small>Servicio completo</small><h3>Instalación y montaje</h3><p>También ofrecemos apoyo para llevar la solución hasta el lugar donde se necesita.</p></article>
			</div>
			<div class="lap-banner">
				<p><strong>No necesitas saber de publicidad.</strong> <span>Nosotros te ayudamos a elegir.</span><small>Cuéntanos qué necesitas y te orientamos sin costo adicional.</small></p>
				<a class="lap-btn lap-btn--green lap-btn--sm" href="<?php echo esc_url( lp_wa_link( 'Hola, necesito asesoría para elegir.' ) ); ?>" target="_blank" rel="noopener">Hablar con un asesor</a>
			</div>
			<figure class="lap-quote">
				<blockquote>Texto de ejemplo: reemplazar por la opinión real de un cliente.</blockquote>
				<figcaption>— Nombre del cliente</figcaption>
			</figure>
		</div>
	</section>

	<!-- 6. PORTAFOLIO -->
	<section id="portafolio" class="lap-section lap-bg-surface" aria-labelledby="lap-port-title">
		<div class="lap-container">
			<header class="lap-head">
				<p class="lap-label">Portafolio</p>
				<h2 id="lap-port-title" class="lap-h2">Trabajos realizados</h2>
				<p class="lap-sub">Una muestra de los proyectos que hemos entregado a clientes de toda la región.</p>
			</header>
			<?php echo do_shortcode( '[lp_portafolio columnas="4" cantidad="8"]' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</div>
	</section>

	<!-- 7. ALIADOS -->
	<section id="aliados" class="lap-section lap-bg-light" aria-labelledby="lap-aliados-title">
		<div class="lap-container">
			<header class="lap-head">
				<p class="lap-label lap-label--blue">Servicios complementarios</p>
				<h2 id="lap-aliados-title" class="lap-h2">Aliados y servicios especializados</h2>
				<p class="lap-sub">Para algunos servicios especializados trabajamos con aliados de confianza, garantizando siempre la mejor calidad y coordinando todo el proceso por ti.</p>
			</header>
			<div class="lap-grid lap-grid--3">
				<article class="lap-ally"><span class="dashicons dashicons-media-text" aria-hidden="true"></span><h3>Litografía <small>Aliado</small></h3><p>Impresión offset de alta calidad para volantes, catálogos, revistas y material editorial en grandes tirajes.</p></article>
				<article class="lap-ally"><span class="dashicons dashicons-lightbulb" aria-hidden="true"></span><h3>Corte láser <small>Aliado</small></h3><p>Corte y grabado de precisión en acrílico, madera, cuero y otros materiales, realizado por nuestros aliados especializados.</p></article>
				<article class="lap-ally"><span class="dashicons dashicons-admin-appearance" aria-hidden="true"></span><h3>Impresión UV <small>Aliado</small></h3><p>Impresión directa sobre superficies rígidas como acrílico, vidrio y metal, a través de aliados certificados.</p></article>
			</div>
			<p class="lap-ally-note lap-ally-note--center">Los servicios de aliados son coordinados por nuestro equipo para garantizar calidad y cumplimiento. Nosotros gestionamos la producción y tú solo recibes el resultado final.</p>
		</div>
	</section>

	<!-- 8. CONTACTO -->
	<section id="contacto" class="lap-section lap-bg-surface" aria-labelledby="lap-contacto-title">
		<div class="lap-container">
			<header class="lap-head lap-head--left">
				<p class="lap-label">Contáctanos</p>
				<h2 id="lap-contacto-title" class="lap-h2">Hablemos de tu proyecto</h2>
				<p class="lap-sub lap-sub--left">Estamos listos para ayudarte. Cuéntanos qué necesitas y te respondemos en menos de 2 horas en días hábiles.</p>
			</header>
			<?php echo do_shortcode( '[lp_contacto]' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</div>
	</section>
</main>
