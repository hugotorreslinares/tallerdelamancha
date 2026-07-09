<?php
/**
 * Plantilla principal / blog
 *
 * @package TintaBrava
 */
get_header();
?>

<section class="page-header">
  <div class="container">
    <?php if ( is_home() && ! is_front_page() ) : ?>
      <h1><?php single_post_title(); ?></h1>
    <?php else : ?>
      <h1><?php esc_html_e( 'Tutoriales', 'tinta-brava' ); ?></h1>
    <?php endif; ?>
    <p><?php esc_html_e( 'Guías paso a paso, técnicas probadas en taller y respuestas a las preguntas que nos hacen por WhatsApp cada semana.', 'tinta-brava' ); ?></p>
  </div>
</section>

<section class="section">
  <div class="container">
    <?php if ( have_posts() ) : ?>
      <?php while ( have_posts() ) : the_post(); ?>
        <article class="post-card-full">
          <div class="card-img">
            <?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'tinta-brava-card' ); endif; ?>
          </div>
          <div class="card-body">
            <p class="meta"><?php echo esc_html( get_the_date() ); ?> · <?php echo esc_html( tinta_brava_reading_time() ); ?> min</p>
            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            <p><?php echo esc_html( get_the_excerpt() ); ?></p>
            <a class="card-link" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Leer →', 'tinta-brava' ); ?></a>
          </div>
        </article>
      <?php endwhile; ?>

      <div class="pagination" style="margin-top: 2rem; text-align: center;">
        <?php the_posts_pagination( array( 'mid_size' => 2, 'prev_text' => '←', 'next_text' => '→' ) ); ?>
      </div>
    <?php else : ?>
      <p><?php esc_html_e( 'Aún no hay tutoriales publicados.', 'tinta-brava' ); ?></p>
    <?php endif; ?>
  </div>
</section>

<?php get_footer(); ?>
