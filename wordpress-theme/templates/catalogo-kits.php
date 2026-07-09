<?php
/**
 * Template Name: Catálogo de Kits
 *
 * @package TintaBrava
 */
get_header();
?>

<section class="page-header">
  <div class="container">
    <p class="breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Inicio', 'tinta-brava' ); ?></a> / <?php the_title(); ?></p>
    <h1><?php the_title(); ?></h1>
    <?php if ( has_excerpt() ) : ?><p><?php echo get_the_excerpt(); ?></p><?php endif; ?>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="grid grid-3">
      <?php
      $products = new WP_Query( array(
        'post_type'      => 'product',
        'posts_per_page' => 12,
        'orderby'        => 'menu_order date',
        'order'          => 'ASC',
      ) );
      if ( $products->have_posts() ) :
        while ( $products->have_posts() ) : $products->the_post();
          global $product;
          if ( ! $product ) continue;
          $price = $product->get_price();
          $is_coming = get_post_meta( get_the_ID(), '_tinta_brava_coming_soon', true );
      ?>
        <article class="card" id="<?php echo esc_attr( $product->get_slug() ); ?>" data-cat="<?php echo esc_attr( wp_get_post_terms( get_the_ID(), 'product_cat', array( 'fields' => 'slugs' ) )[0] ?? '' ); ?>">
          <div class="card-img">
            <?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'tinta-brava-card' ); endif; ?>
          </div>
          <div class="card-body">
            <p class="meta">
              <?php
              $cats = wp_get_post_terms( get_the_ID(), 'product_cat', array( 'fields' => 'names' ) );
              echo esc_html( implode( ' · ', $cats ) );
              if ( $is_coming ) echo ' · <span class="badge">' . esc_html__( 'Pronto', 'tinta-brava' ) . '</span>';
              ?>
            </p>
            <h3><?php the_title(); ?></h3>
            <p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 22 ) ); ?></p>
            <?php if ( $price && ! $is_coming ) : ?>
              <div class="price-row" style="margin: 1rem 0;">
                <span class="price-label"><?php esc_html_e( 'Precio', 'tinta-brava' ); ?></span>
                <span class="price"><?php echo esc_html( tinta_brava_format_price( $price ) ); ?></span>
              </div>
            <?php endif; ?>
            <div style="display:flex; gap: 0.5rem; flex-wrap: wrap;">
              <a class="btn btn-primary" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Ver detalles', 'tinta-brava' ); ?></a>
              <a class="btn btn-whatsapp" href="<?php echo esc_url( tinta_brava_whatsapp_url( 'Hola, me interesa el ' . get_the_title() ) ); ?>" target="_blank" rel="noopener">WhatsApp</a>
            </div>
          </div>
        </article>
      <?php endwhile; wp_reset_postdata();
      else :
        echo '<p>' . esc_html__( 'Aún no hay productos en el catálogo.', 'tinta-brava' ) . '</p>';
      endif;
      ?>
    </div>

    <div class="section section-cta" style="margin-top: 4rem;">
      <div class="cta-inner">
        <h2><?php esc_html_e( '¿No sabes cuál te conviene?', 'tinta-brava' ); ?></h2>
        <p><?php esc_html_e( 'Escríbenos por WhatsApp, te preguntamos tu experiencia y te recomendamos el kit adecuado.', 'tinta-brava' ); ?></p>
        <a class="btn btn-primary btn-lg" href="<?php echo esc_url( tinta_brava_whatsapp_url( 'Hola, necesito ayuda eligiendo un kit' ) ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Recomendadme un kit', 'tinta-brava' ); ?></a>
      </div>
    </div>
  </div>
</section>

<?php get_footer(); ?>
