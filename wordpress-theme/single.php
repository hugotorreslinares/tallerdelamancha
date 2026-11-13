<?php
/**
 * Single post / tutorial
 *
 * @package TintaBrava
 */
get_header();
?>

<article class="section">
  <div class="container">
    <div class="post-content post-content-single">
      <p class="breadcrumb">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Inicio', 'tinta-brava' ); ?></a> /
        <a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/tutoriales/' ) ); ?>"><?php esc_html_e( 'Tutoriales', 'tinta-brava' ); ?></a> /
        <?php the_title(); ?>
      </p>
      <p class="eyebrow"><?php echo get_the_category_list( ' · ' ) ?: esc_html__( 'Tutorial', 'tinta-brava' ); ?></p>
      <h1><?php the_title(); ?></h1>
      <p class="meta">
        <?php
        printf(
          esc_html__( 'Por %1$s · %2$s · %3$d min de lectura', 'tinta-brava' ),
          get_the_author(),
          get_the_date(),
          tinta_brava_reading_time()
        );
        ?>
      </p>

      <?php if ( has_post_thumbnail() ) : ?>
        <div class="post-hero">
          <?php the_post_thumbnail( 'large' ); ?>
        </div>
      <?php endif; ?>

      <div class="post-body">
        <?php the_content(); ?>
      </div>

      <div class="author-box">
        <?php echo get_avatar( get_the_author_meta( 'ID' ), 56, '', '', array( 'style' => 'border-radius:50%' ) ); ?>
        <div>
          <strong><?php the_author(); ?></strong>
          <p style="margin: 0; font-size: var(--fs-sm); color: var(--color-muted);"><?php esc_html_e( 'Taller de grabado en Bogotá.', 'tinta-brava' ); ?></p>
        </div>
      </div>

      <div style="display: flex; gap: 1rem; flex-wrap: wrap; margin-top: 1.5rem;">
        <a class="btn btn-primary btn-lg" href="<?php echo esc_url( home_url( '/kits/' ) ); ?>"><?php esc_html_e( 'Ver los kits', 'tinta-brava' ); ?></a>
        <a class="btn btn-whatsapp btn-lg" href="<?php echo esc_url( tinta_brava_whatsapp_url( '', 'Tutorial · Resolver una duda' ) ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Resolver una duda', 'tinta-brava' ); ?></a>
      </div>
    </div>
  </div>
</article>

<?php
$related = tinta_brava_related_posts();
if ( $related->have_posts() ) : ?>
<section class="section related-section">
  <div class="container">
    <h2><?php esc_html_e( 'Otros tutoriales', 'tinta-brava' ); ?></h2>
    <div class="grid grid-3 related-grid">
      <?php while ( $related->have_posts() ) : $related->the_post(); ?>
        <article class="card">
          <div class="card-img"><?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'tinta-brava-card' ); endif; ?></div>
          <div class="card-body">
            <p class="meta"><?php echo esc_html( get_the_date() ); ?></p>
            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
          </div>
        </article>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php get_footer(); ?>
