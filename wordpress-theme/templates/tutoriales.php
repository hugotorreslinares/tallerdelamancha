<?php
/**
 * Template Name: Tutoriales
 *
 * @package TintaBrava
 */
get_header();

$paged = max( 1, get_query_var( 'paged' ) ?: get_query_var( 'page' ) );
$posts = new WP_Query( array(
  'post_type'      => 'post',
  'posts_per_page' => 10,
  'paged'          => $paged,
) );
?>

<section class="page-header">
  <div class="container">
    <h1><?php the_title(); ?></h1>
    <?php if ( has_excerpt() ) : ?><p><?php echo esc_html( get_the_excerpt() ); ?></p><?php endif; ?>
  </div>
</section>

<section class="section">
  <div class="container">
    <?php if ( $posts->have_posts() ) : ?>
      <?php while ( $posts->have_posts() ) : $posts->the_post(); ?>
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
      <?php endwhile; wp_reset_postdata(); ?>

      <div class="pagination" style="margin-top: 2rem; text-align: center;">
        <?php
        echo paginate_links( array(
          'total'   => $posts->max_num_pages,
          'current' => $paged,
          'mid_size' => 2,
          'prev_text' => '←',
          'next_text' => '→',
        ) );
        ?>
      </div>
    <?php else : ?>
      <p><?php esc_html_e( 'Aún no hay tutoriales publicados.', 'tinta-brava' ); ?></p>
    <?php endif; ?>
  </div>
</section>

<?php get_footer(); ?>
