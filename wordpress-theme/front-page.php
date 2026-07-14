<?php
/**
 * Front page (inicio)
 *
 * @package TintaBrava
 */
get_header();
$image1 = wp_get_attachment_image_url(
    get_theme_mod( 'tinta_brava_hero_image_1' ),
    'medium'
);

$image2 = wp_get_attachment_image_url(
    get_theme_mod( 'tinta_brava_hero_image_2' ),
    'medium'
);
?>

<section class="hero">
  <div class="container hero-grid">
    <div class="hero-copy">
      <p class="eyebrow"><?php esc_html_e( 'Kits de iniciación · Bogotá', 'tinta-brava' ); ?></p>
      <h1 class="display"><?php echo esc_html( get_theme_mod( 'tinta_brava_hero_title', 'Empieza a estampar en casa, una tirada a la vez.' ) ); ?></h1>
      <p class="lead"><?php echo esc_html( get_theme_mod( 'tinta_brava_hero_lead', 'Kits de linograbado, serigrafía y litografía con todo lo que necesitas para aprender la técnica y terminar tu primer proyecto. Diseñados y armados en taller, con materiales que de verdad se usan.' ) ); ?></p>
      <div class="hero-actions">
        <a class="btn btn-primary" href="<?php echo esc_url( home_url( '/kits/' ) ); ?>"><?php esc_html_e( 'Ver los kits', 'tinta-brava' ); ?></a>
        <a class="btn btn-ghost" href="<?php echo esc_url( home_url( '/tutoriales/' ) ); ?>"><?php esc_html_e( 'Aprende primero', 'tinta-brava' ); ?></a>
      </div>
      <ul class="hero-meta" role="list">
        <li><strong>+50</strong> <?php esc_html_e( 'estudiantes han estampado con nosotros', 'tinta-brava' ); ?></li>
        <li><strong>3</strong> <?php esc_html_e( 'técnicas en un solo catálogo', 'tinta-brava' ); ?></li>
        <li><strong><?php esc_html_e( 'Envíos', 'tinta-brava' ); ?></strong> <?php esc_html_e( 'a toda Colombia', 'tinta-brava' ); ?></li>
      </ul>
    </div>
    <div class="hero-media" aria-hidden="true">
      <div
    class="hero-photo hero-photo-1"
    <?php if ( $image1 ) : ?>
        style="background-image:url('<?php echo esc_url( $image1 ); ?>')"
    <?php endif; ?>
></div>

<div
    class="hero-photo hero-photo-2"
    <?php if ( $image2 ) : ?>
        style="background-image:url('<?php echo esc_url( $image2 ); ?>')"
    <?php endif; ?>
></div>
      <div class="hero-stamp">★ <?php esc_html_e( 'Disponibilidad limitada', 'tinta-brava' ); ?></div>
    </div>
  </div>
</section>

<section class="section section-categories">
  <div class="container">
    <header class="section-head">
      <h2><?php esc_html_e( 'Elige por dónde empezar', 'tinta-brava' ); ?></h2>
      <p><?php esc_html_e( 'Tres técnicas, una misma idea: que termines con una estampa en la mano.', 'tinta-brava' ); ?></p>
    </header>
    <div class="grid grid-3">
      <a class="card category" href="<?php echo esc_url( home_url( '/kits/#linograbado' ) ); ?>">
        <div class="card-img cat-lino"></div>
        <div class="card-body">
          <h3><?php esc_html_e( 'Linograbado', 'tinta-brava' ); ?></h3>
          <p><?php esc_html_e( 'La forma más directa de empezar a grabar. Cortas, entintas, presionas.', 'tinta-brava' ); ?></p>
          <span class="card-link"><?php esc_html_e( 'Ver kit →', 'tinta-brava' ); ?></span>
        </div>
      </a>
      <a class="card category" href="<?php echo esc_url( home_url( '/kits/#serigrafia' ) ); ?>">
        <div class="card-img cat-seri"></div>
        <div class="card-body">
          <h3><?php esc_html_e( 'Serigrafía', 'tinta-brava' ); ?></h3>
          <p><?php esc_html_e( 'Estampado textil y de papel con emulsión y bastidor. Más materiales, más tirada.', 'tinta-brava' ); ?></p>
          <span class="card-link"><?php esc_html_e( 'Ver kit →', 'tinta-brava' ); ?></span>
        </div>
      </a>
      <a class="card category" href="<?php echo esc_url( home_url( '/kits/#litografia' ) ); ?>">
        <div class="card-img cat-lito"></div>
        <div class="card-body">
          <h3><?php esc_html_e( 'Litografía', 'tinta-brava' ); ?> <span class="badge"><?php esc_html_e( 'Pronto', 'tinta-brava' ); ?></span></h3>
          <p><?php esc_html_e( 'La técnica de las piedras. Un kit premium para cuando estés listo para el siguiente paso.', 'tinta-brava' ); ?></p>
          <span class="card-link"><?php esc_html_e( 'Ver kit →', 'tinta-brava' ); ?></span>
        </div>
      </a>
    </div>
  </div>
</section>

<?php
  $featured = new WP_Query( array(
    'post_type'      => 'product',
    'posts_per_page' => 1,
    'meta_key'       => 'total_sales',
    'orderby'        => 'meta_value_num',
    'order'          => 'DESC',
  ) );
  if ( ! $featured->have_posts() ) {
    $featured = new WP_Query( array(
      'post_type'      => 'product',
      'posts_per_page' => 1,
      'orderby'        => 'date',
      'order'          => 'DESC',
    ) );
  }
  if ( $featured->have_posts() ) :
    while ( $featured->have_posts() ) : $featured->the_post();
      global $product;
      $price = $product ? $product->get_price() : '';
      $short_desc = $product ? wp_strip_all_tags( $product->get_short_description() ) : get_the_excerpt();
      $wa_msg = 'Hola, me interesa el ' . get_the_title();
?>
<section class="section section-featured">
  <div class="container">
    <div class="featured-grid">
      <div class="featured-photo" aria-hidden="true">
        <?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'large' ); endif; ?>
      </div>
      <div class="featured-copy">
        <p class="eyebrow"><?php esc_html_e( 'Destacado', 'tinta-brava' ); ?></p>
        <h2><?php the_title(); ?></h2>
        <p class="lead"><?php echo esc_html( $short_desc ); ?></p>
        <ul class="checklist" role="list">
          <li><?php esc_html_e( 'Gubia de grabado profesional', 'tinta-brava' ); ?></li>
          <li><?php esc_html_e( 'Plancha de linóleo 15×10 cm', 'tinta-brava' ); ?></li>
          <li><?php esc_html_e( 'Tintas al agua (negro, ocre, azul)', 'tinta-brava' ); ?></li>
          <li><?php esc_html_e( 'Papel para grabado (10 hojas)', 'tinta-brava' ); ?></li>
          <li><?php esc_html_e( 'Manual de 4 proyectos paso a paso', 'tinta-brava' ); ?></li>
        </ul>
        <?php if ( $price ) : ?>
        <div class="price-row">
          <span class="price-label"><?php esc_html_e( 'Precio', 'tinta-brava' ); ?></span>
          <span class="price"><?php echo esc_html( tinta_brava_format_price( $price ) ); ?></span>
        </div>
        <?php endif; ?>
        <div class="hero-actions">
          <a class="btn btn-primary" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Ver detalles', 'tinta-brava' ); ?></a>
          <a class="btn btn-whatsapp" href="<?php echo esc_url( tinta_brava_whatsapp_url( $wa_msg ) ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Pedir por WhatsApp', 'tinta-brava' ); ?></a>
        </div>
      </div>
    </div>
  </div>
</section>
<?php endwhile; wp_reset_postdata(); endif; ?>

<section class="section section-why">
  <div class="container">
    <header class="section-head">
      <h2><?php esc_html_e( 'Por qué aprender a estampar', 'tinta-brava' ); ?></h2>
      <p><?php esc_html_e( 'El grabado es una de las técnicas más antiguas y más gratificantes. Te obliga a ir lento, a mirar y a decidir cada línea.', 'tinta-brava' ); ?></p>
    </header>
    <div class="grid grid-3">
      <article class="feature">
        <h3><?php esc_html_e( 'Aprendes con las manos', 'tinta-brava' ); ?></h3>
        <p><?php esc_html_e( 'No necesitas pantalla ni electricidad. Un banco, una gubia, una plancha y a trabajar.', 'tinta-brava' ); ?></p>
      </article>
      <article class="feature">
        <h3><?php esc_html_e( 'Terminas con algo real', 'tinta-brava' ); ?></h3>
        <p><?php esc_html_e( 'Tu primera estampa enmarcada vale más que cualquier curso en vídeo que dejaste a la mitad.', 'tinta-brava' ); ?></p>
      </article>
      <article class="feature">
        <h3><?php esc_html_e( 'Comunidad en ferias', 'tinta-brava' ); ?></h3>
        <p><?php esc_html_e( 'En Bogotá hay un circuito enorme de ferias de diseño. Vas a encontrar tu gente.', 'tinta-brava' ); ?></p>
      </article>
    </div>
  </div>
</section>
<?php

$next_fair = new WP_Query( array(
    'post_type'      => 'fair',
    'posts_per_page' => 1,
    'post_status'    => 'publish',
    'meta_key'       => 'fair_date',
    'orderby'        => 'meta_value',
    'order'          => 'ASC',
) );

if ( $next_fair->have_posts() ) :
    $next_fair->the_post();

    $date     = get_post_meta( get_the_ID(), 'fair_date', true );
    $location = get_post_meta( get_the_ID(), 'fair_location', true );
    $city     = get_post_meta( get_the_ID(), 'fair_city', true );
    $stand    = get_post_meta( get_the_ID(), 'fair_stand', true );
?>
<section class="section section-fair">
    <div class="container fair-grid">

        <div>

            <p class="eyebrow">
                <?php esc_html_e( 'Próxima feria', 'tinta-brava' ); ?>
            </p>

            <h2><?php the_title(); ?></h2>

            <p><?php echo esc_html( get_the_excerpt() ); ?></p>

            <ul class="fair-meta">

                <?php if ( $date ) : ?>
                <li>
                    <strong>Fecha:</strong>
                    <?php echo esc_html( date_i18n( 'j \d\e F Y', strtotime( $date ) ) ); ?>
                </li>
                <?php endif; ?>

                <?php if ( $location || $city ) : ?>
                <li>
                    <strong>Lugar:</strong>
                    <?php echo esc_html( trim( $location . ', ' . $city, ', ' ) ); ?>
                </li>
                <?php endif; ?>

                <?php if ( $stand ) : ?>
                <li>
                    <strong>Stand:</strong>
                    <?php echo esc_html( $stand ); ?>
                </li>
                <?php endif; ?>

            </ul>

            <a class="btn btn-primary"
               href="<?php echo esc_url( home_url('/ferias/') ); ?>">
                Ver todas las ferias
            </a>

        </div>

        <div class="fair-photo">
            <?php
            if ( has_post_thumbnail() ) {
                the_post_thumbnail( 'large' );
            }
            ?>
        </div>

    </div>
</section>

<?php
wp_reset_postdata();
endif;
?>
<!-- <section class="section section-fair">
  <div class="container fair-grid">
    <div>
      <p class="eyebrow"><?php esc_html_e( 'Próxima feria', 'tinta-brava' ); ?></p>
      <h2><?php esc_html_e( 'Te esperamos en BADA Bogotá', 'tinta-brava' ); ?></h2>
      <p><?php esc_html_e( 'Estaremos con stand propio, mostrando los kits en vivo y haciendo demostraciones de linograbado. Pásate, prueba las herramientas y te llevas tu kit con descuento de feria.', 'tinta-brava' ); ?></p>
      <ul class="fair-meta" role="list">
        <li><strong><?php esc_html_e( 'Fecha:', 'tinta-brava' ); ?></strong> 12-14 <?php esc_html_e( 'de septiembre', 'tinta-brava' ); ?></li>
        <li><strong><?php esc_html_e( 'Lugar:', 'tinta-brava' ); ?></strong> Corferias, Bogotá</li>
        <li><strong><?php esc_html_e( 'Stand:', 'tinta-brava' ); ?></strong> P-218, <?php esc_html_e( 'pasillo de ilustración', 'tinta-brava' ); ?></li>
      </ul>
      <a class="btn btn-primary" href="<?php echo esc_url( home_url( '/ferias/' ) ); ?>"><?php esc_html_e( 'Ver todas las ferias', 'tinta-brava' ); ?></a>
    </div>
    <div class="fair-photo" aria-hidden="true"></div>
  </div>
</section> -->

<section class="section section-blog">
  <div class="container">
    <header class="section-head section-head-row">
      <h2><?php esc_html_e( 'Del blog', 'tinta-brava' ); ?></h2>
      <a class="section-link" href="<?php echo esc_url( home_url( '/tutoriales/' ) ); ?>"><?php esc_html_e( 'Todos los tutoriales →', 'tinta-brava' ); ?></a>
    </header>
    <div class="grid grid-3">
      <?php
      $posts = new WP_Query( array( 'posts_per_page' => 3 ) );
      if ( $posts->have_posts() ) :
        while ( $posts->have_posts() ) : $posts->the_post();
      ?>
        <article class="card post">
          <div class="card-img">
            <?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'tinta-brava-card' ); endif; ?>
          </div>
          <div class="card-body">
            <p class="meta"><?php echo esc_html( get_the_date() ); ?> · <?php echo esc_html( tinta_brava_reading_time() ); ?> min</p>
            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            <p><?php echo esc_html( get_the_excerpt() ); ?></p>
          </div>
        </article>
      <?php endwhile; wp_reset_postdata(); endif; ?>
    </div>
  </div>
</section>

<section class="section section-cta">
  <div class="container cta-inner">
    <h2><?php esc_html_e( '¿Listo para empezar?', 'tinta-brava' ); ?></h2>
    <p><?php esc_html_e( 'Escríbenos por WhatsApp, te contamos qué kit te conviene según tu experiencia y lo separamos para la próxima feria o te lo enviamos a casa.', 'tinta-brava' ); ?></p>
    <div class="hero-actions">
      <a class="btn btn-primary btn-lg" href="<?php echo esc_url( tinta_brava_whatsapp_url( 'Hola, quiero empezar con Tinta Brava' ) ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Hablar por WhatsApp', 'tinta-brava' ); ?></a>
      <a class="btn btn-ghost btn-lg" href="<?php echo esc_url( home_url( '/kits/' ) ); ?>"><?php esc_html_e( 'Ver los kits primero', 'tinta-brava' ); ?></a>
    </div>
  </div>
</section>
<!-- VERSION 2026-07-10 11:42 -->
<?php get_footer(); ?>
