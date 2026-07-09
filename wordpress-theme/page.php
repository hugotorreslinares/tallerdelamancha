<?php
/**
 * Plantilla de página
 *
 * @package TintaBrava
 */
get_header();
?>

<section class="page-header">
  <div class="container">
    <h1><?php the_title(); ?></h1>
    <?php if ( has_excerpt() ) : ?>
      <p><?php echo get_the_excerpt(); ?></p>
    <?php endif; ?>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="post-content" style="max-width: 760px; margin: 0 auto; line-height: 1.7;">
      <?php the_content(); ?>
    </div>
  </div>
</section>

<?php get_footer(); ?>
