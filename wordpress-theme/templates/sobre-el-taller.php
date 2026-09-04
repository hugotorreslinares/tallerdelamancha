<?php
/**
 * Template Name: Sobre el taller
 *
 * @package TintaBrava
 */
get_header();
$about_photo = has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), 'large' ) : '';
?>

<section class="about-hero">
  <div class="container">
    <h1><?php the_title(); ?></h1>
    <?php if ( has_excerpt() ) : ?>
      <p class="lead"><?php echo esc_html( get_the_excerpt() ); ?></p>
    <?php endif; ?>
  </div>
</section>

<section class="section">
  <div class="container about-grid">
    <div class="about-photo" <?php if ( $about_photo ) : ?>style="background-image:url('<?php echo esc_url( $about_photo ); ?>'); background-size: cover; background-position: center;"<?php endif; ?>></div>
    <div class="post-content" style="line-height: 1.7;">
      <?php the_content(); ?>
    </div>
  </div>
</section>

<section class="values">
  <div class="container">
    <header class="section-head">
      <h2><?php esc_html_e( 'Cómo trabajamos', 'tinta-brava' ); ?></h2>
    </header>
    <div class="grid grid-3">
      <div class="value-item">
        <span class="value-num">01</span>
        <div>
          <h3><?php esc_html_e( 'Materiales que se usan de verdad', 'tinta-brava' ); ?></h3>
          <p><?php esc_html_e( 'Cada kit lleva lo que realmente necesitas para terminar tu primer proyecto, sin sobras ni relleno.', 'tinta-brava' ); ?></p>
        </div>
      </div>
      <div class="value-item">
        <span class="value-num">02</span>
        <div>
          <h3><?php esc_html_e( 'Armado a mano en el taller', 'tinta-brava' ); ?></h3>
          <p><?php esc_html_e( 'Cada pedido lo armamos nosotros mismos en Bogotá, no es dropshipping ni producción masiva.', 'tinta-brava' ); ?></p>
        </div>
      </div>
      <div class="value-item">
        <span class="value-num">03</span>
        <div>
          <h3><?php esc_html_e( 'Acompañamiento por WhatsApp', 'tinta-brava' ); ?></h3>
          <p><?php esc_html_e( 'Si te trabas en algún paso, nos escribes y te ayudamos a terminar tu estampa.', 'tinta-brava' ); ?></p>
        </div>
      </div>
    </div>
  </div>
</section>

<?php get_footer(); ?>
