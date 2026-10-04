<?php
/**
 * Template Name: Insumos
 *
 * @package TintaBrava
 */
get_header();

$supplies = new WP_Query( array(
  'post_type'      => 'product',
  'posts_per_page' => 24,
  'orderby'        => 'menu_order title',
  'order'          => 'ASC',
  'tax_query'      => array(
    array(
      'taxonomy' => 'product_cat',
      'field'    => 'slug',
      'terms'    => 'insumos',
    ),
  ),
) );
?>

<section class="page-header">
  <div class="container">
    <p class="breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Inicio', 'tinta-brava' ); ?></a> / <?php the_title(); ?></p>
    <h1><?php the_title(); ?></h1>
    <p><?php esc_html_e( 'Tintas, papeles y herramientas por separado, para reponer tu kit. Los pedimos a nuestro proveedor al recibir tu orden y te confirmamos tiempo de entrega por WhatsApp.', 'tinta-brava' ); ?></p>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="grid grid-3">
      <?php if ( $supplies->have_posts() ) : while ( $supplies->have_posts() ) : $supplies->the_post();
        global $product;
        if ( ! $product ) continue;
        $price = $product->get_price();
      ?>
        <article class="card">
          <div class="card-img">
            <?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'tinta-brava-card' ); endif; ?>
          </div>
          <div class="card-body">
            <p class="meta"><span class="badge"><?php esc_html_e( 'Bajo pedido', 'tinta-brava' ); ?></span></p>
            <h3><?php the_title(); ?></h3>
            <p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 22 ) ); ?></p>
            <?php if ( $price ) : ?>
              <div class="price-row">
                <span class="price-label"><?php esc_html_e( 'Precio', 'tinta-brava' ); ?></span>
                <span class="price"><?php echo esc_html( tinta_brava_format_price( $price ) ); ?></span>
              </div>
            <?php endif; ?>
            <a class="btn btn-whatsapp" href="<?php echo esc_url( tinta_brava_whatsapp_url( 'Hola, quiero pedir: ' . get_the_title() ) ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Pedir por WhatsApp', 'tinta-brava' ); ?></a>
          </div>
        </article>
      <?php endwhile; wp_reset_postdata();
      else : ?>
        <p><?php esc_html_e( 'Aún no hay insumos disponibles.', 'tinta-brava' ); ?></p>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php get_footer(); ?>
