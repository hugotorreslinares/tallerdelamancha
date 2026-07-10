<?php
/**
 * Plantilla de producto personalizado Tinta Brava
 * Modo catálogo + WhatsApp
 *
 * @package TintaBrava
 */

defined( 'ABSPATH' ) || exit;
get_header();
$product = wc_get_product( get_the_ID() );

if ( ! $product ) {
    wp_die('No se pudo cargar el producto.');
}
$is_coming_soon = get_post_meta( $product->get_id(), '_tinta_brava_coming_soon', true );
$wa_message     = sprintf( 'Hola, quiero el %s', $product->get_name() );
?>

<section class="page-header">
  <div class="container">
    <p class="breadcrumb">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Inicio', 'tinta-brava' ); ?></a> /
      <a href="<?php echo esc_url( home_url( '/kits/' ) ); ?>"><?php esc_html_e( 'Kits', 'tinta-brava' ); ?></a> /
      <?php the_title(); ?>
    </p>
  </div>
</section>

<section class="product-detail">
  <div class="container">
    <div class="product-grid">
      <div class="product-gallery">
        <div class="gallery-main">
          <?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'large' ); endif; ?>
        </div>
        <?php
        $gallery_ids = $product->get_gallery_image_ids();
        if ( $gallery_ids ) : ?>
          <div class="gallery-thumbs">
            <?php $thumb = 0; foreach ( $gallery_ids as $id ) : $thumb++; ?>
              <button class="gallery-thumb"<?php echo $thumb === 1 ? ' aria-current="true"' : ''; ?>>
                <?php echo wp_get_attachment_image( $id, 'thumbnail' ); ?>
              </button>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>

      <div class="product-info">
        <p class="eyebrow">
          <?php
          $cats = wp_get_post_terms( $product->get_id(), 'product_cat', array( 'fields' => 'names' ) );
          echo esc_html( implode( ' · ', $cats ) );
          ?>
        </p>
        <h1><?php the_title(); ?></h1>
        <p class="lead"><?php echo esc_html( wp_strip_all_tags( $product->get_description() ?: get_the_excerpt() ) ); ?></p>

        <div class="product-meta">
          <div class="product-meta-item">
            <strong><?php echo esc_html( get_post_meta( $product->get_id(), '_tinta_brava_time', true ) ?: '2-3 h' ); ?></strong>
            <?php esc_html_e( 'Por proyecto', 'tinta-brava' ); ?>
          </div>
          <div class="product-meta-item">
            <strong><?php echo esc_html( get_post_meta( $product->get_id(), '_tinta_brava_prints', true ) ?: '+15' ); ?></strong>
            <?php esc_html_e( 'Estampas por kit', 'tinta-brava' ); ?>
          </div>
          <div class="product-meta-item">
            <strong>1</strong>
            <?php esc_html_e( 'Persona (compartible)', 'tinta-brava' ); ?>
          </div>
        </div>

        <?php if ( $is_coming_soon ) : ?>
          <div class="callout" style="background: var(--color-moss-soft); border-left-color: var(--color-moss); padding: 1rem 1.5rem; border-radius: 0 var(--radius-md) var(--radius-md) 0; margin: 1rem 0;">
            <strong><?php esc_html_e( 'Próximamente:', 'tinta-brava' ); ?></strong> <?php esc_html_e( 'Este kit está en desarrollo. Avísame por WhatsApp cuando esté disponible.', 'tinta-brava' ); ?>
          </div>
          <div class="product-cta">
            <a class="btn btn-whatsapp btn-lg" href="<?php echo esc_url( tinta_brava_whatsapp_url( $wa_message . ' (preventa)' ) ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Avísame cuando llegue', 'tinta-brava' ); ?></a>
            <a class="btn btn-ghost btn-lg" href="<?php echo esc_url( home_url( '/kits/' ) ); ?>"><?php esc_html_e( 'Ver otros kits', 'tinta-brava' ); ?></a>
          </div>
        <?php else : ?>
          <?php if ( $product->get_price() ) : ?>
            <div class="price-row">
              <span class="price-label"><?php esc_html_e( 'Precio', 'tinta-brava' ); ?></span>
              <span class="price"><?php echo esc_html( tinta_brava_format_price( $product->get_price() ) ); ?></span>
            </div>
          <?php endif; ?>

          <div class="product-cta">
            <a class="btn btn-whatsapp btn-lg" href="<?php echo esc_url( tinta_brava_whatsapp_url( $wa_message . ' (' . tinta_brava_format_price( $product->get_price() ) . ')' ) ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Pedir por WhatsApp', 'tinta-brava' ); ?></a>
            <a class="btn btn-ghost btn-lg" href="<?php echo esc_url( tinta_brava_whatsapp_url( 'Hola, tienen descuento en ferias?' ) ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Reservar para feria', 'tinta-brava' ); ?></a>
          </div>
          <p style="font-size: var(--fs-sm); color: var(--color-muted); margin-top: 1rem;">
            <?php esc_html_e( 'Envíos a toda Colombia · Pago por transferencia Bancolombia, Nequi o Daviplata · Contra entrega solo en Bogotá', 'tinta-brava' ); ?>
          </p>
        <?php endif; ?>
      </div>
    </div>

    <div class="section" style="padding-top: 4rem;">
      <h2><?php esc_html_e( 'Descripción completa', 'tinta-brava' ); ?></h2>
      <div class="post-content" style="line-height: 1.7;">
        <?php the_content(); ?>
      </div>
    </div>

    <?php
    $includes = get_post_meta( $product->get_id(), '_tinta_brava_includes', true );
    if ( $includes ) :
      $items = array_filter( array_map( 'trim', explode( "\n", $includes ) ) );
    ?>
      <div class="section" style="padding-top: 2rem;">
        <h2><?php esc_html_e( 'Qué incluye el kit', 'tinta-brava' ); ?></h2>
        <ul class="kit-includes" role="list">
          <?php foreach ( $items as $item ) : ?>
            <li><?php echo esc_html( $item ); ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <div class="section section-cta" style="padding: 3rem 0;">
      <div class="cta-inner">
        <h2><?php esc_html_e( '¿Te animas?', 'tinta-brava' ); ?></h2>
        <p><?php esc_html_e( 'Escríbenos, separamos tu kit y te lo enviamos o lo recoges en la próxima feria.', 'tinta-brava' ); ?></p>
        <a class="btn btn-whatsapp btn-lg" href="<?php echo esc_url( tinta_brava_whatsapp_url( $wa_message ) ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Pedir este kit por WhatsApp', 'tinta-brava' ); ?></a>
      </div>
    </div>
  </div>
</section>
<?php get_footer(); ?>