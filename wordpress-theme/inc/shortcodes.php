<?php
/**
 * Shortcodes Tinta Brava
 *
 * - [tinta_brava_whatsapp_button message="..."]Botón WhatsApp[/...]
 * - [tinta_brava_whatsapp_button message="..." preset="lino|seri|lito" label="..." style="primary|whatsapp|ghost"]
 *
 * @package TintaBrava
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Shortcode: [tinta_brava_whatsapp]
 *
 * Atributos:
 * - message (string): mensaje prellenado
 * - label   (string): texto del botón
 * - style   (string): primary | whatsapp | ghost
 * - size    (string): '' | 'lg'
 * - class   (string): clases extra
 *
 * @param array $atts
 * @param string $content
 * @return string
 */
function tinta_brava_whatsapp_shortcode( $atts, $content = '' ) {
  $atts = shortcode_atts( array(
    'message' => 'Hola, me interesa un kit de Tinta Brava',
    'label'   => $content ?: __( 'Pedir por WhatsApp', 'tinta-brava' ),
    'style'   => 'whatsapp',
    'size'    => '',
    'class'   => '',
  ), $atts, 'tinta_brava_whatsapp' );

  $size_class = $atts['size'] === 'lg' ? ' btn-lg' : '';
  $classes = trim( 'btn btn-' . $atts['style'] . $size_class . ' ' . $atts['class'] );
  $url     = tinta_brava_whatsapp_url( $atts['message'] );

  return sprintf(
    '<a class="%s" href="%s" target="_blank" rel="noopener">%s</a>',
    esc_attr( $classes ),
    esc_url( $url ),
    esc_html( $atts['label'] )
  );
}
add_shortcode( 'tinta_brava_whatsapp', 'tinta_brava_whatsapp_shortcode' );
add_shortcode( 'tinta_brava_whatsapp_button', 'tinta_brava_whatsapp_shortcode' );

/**
 * Shortcode: [tinta_brava_kit_card slug="kit-linograbado"]
 * Muestra una card de producto usando los datos reales de WooCommerce
 */
function tinta_brava_kit_card_shortcode( $atts ) {
  $atts = shortcode_atts( array(
    'slug' => '',
  ), $atts, 'tinta_brava_kit_card' );

  if ( empty( $atts['slug'] ) ) return '';
  $product = get_page_by_path( $atts['slug'], OBJECT, 'product' );
  if ( ! $product ) return '';
  $p = wc_get_product( $product );
  if ( ! $p ) return '';

  $is_coming = get_post_meta( $p->get_id(), '_tinta_brava_coming_soon', true );
  $img       = get_the_post_thumbnail_url( $p->get_id(), 'tinta-brava-card' );
  $cats      = wp_get_post_terms( $p->get_id(), 'product_cat', array( 'fields' => 'names' ) );
  $wa_msg    = sprintf( 'Hola, me interesa el %s', $p->get_name() );
  $wa_url    = tinta_brava_whatsapp_url( $is_coming ? $wa_msg . ' (preventa)' : $wa_msg );

  ob_start();
  ?>
  <article class="card">
    <div class="card-img" style="<?php echo $img ? 'background-image:url(' . esc_url( $img ) . ')' : ''; ?>"></div>
    <div class="card-body">
      <p class="meta">
        <?php echo esc_html( implode( ' · ', $cats ) ); ?>
        <?php if ( $is_coming ) echo ' · <span class="badge">' . esc_html__( 'Pronto', 'tinta-brava' ) . '</span>'; ?>
      </p>
      <h3><?php echo esc_html( $p->get_name() ); ?></h3>
      <p><?php echo esc_html( wp_trim_words( $p->get_short_description(), 22 ) ); ?></p>
      <?php if ( $p->get_price() && ! $is_coming ) : ?>
        <div class="price-row" style="margin: 1rem 0;">
          <span class="price-label"><?php esc_html_e( 'Precio', 'tinta-brava' ); ?></span>
          <span class="price"><?php echo esc_html( tinta_brava_format_price( $p->get_price() ) ); ?></span>
        </div>
      <?php endif; ?>
      <div style="display:flex; gap: 0.5rem; flex-wrap: wrap;">
        <a class="btn btn-primary" href="<?php echo esc_url( $p->get_permalink() ); ?>"><?php esc_html_e( 'Ver detalles', 'tinta-brava' ); ?></a>
        <a class="btn btn-whatsapp" href="<?php echo esc_url( $wa_url ); ?>" target="_blank" rel="noopener">WhatsApp</a>
      </div>
    </div>
  </article>
  <?php
  return ob_get_clean();
}
add_shortcode( 'tinta_brava_kit_card', 'tinta_brava_kit_card_shortcode' );

/**
 * Shortcode: [tinta_brava_price amount="145000"]
 * Muestra un precio formateado en COP
 */
function tinta_brava_price_shortcode( $atts ) {
  $atts = shortcode_atts( array( 'amount' => 0 ), $atts, 'tinta_brava_price' );
  return esc_html( tinta_brava_format_price( (float) $atts['amount'] ) );
}
add_shortcode( 'tinta_brava_price', 'tinta_brava_price_shortcode' );

/**
 * Shortcode: [tinta_brava_fair_count type="upcoming|past"]
 * Cuenta de ferias
 */
function tinta_brava_fair_count_shortcode( $atts ) {
  $atts = shortcode_atts( array( 'type' => 'upcoming' ), $atts, 'tinta_brava_fair_count' );
  $today = date( 'Y-m-d' );
  $query = new WP_Query( array(
    'post_type'      => 'fair',
    'posts_per_page' => -1,
    'meta_query'     => array(
      array(
        'key'     => 'fair_date',
        'value'   => $today,
        'compare' => $atts['type'] === 'past' ? '<' : '>=',
        'type'    => 'DATE',
      ),
    ),
    'fields' => 'ids',
  ) );
  return (string) $query->post_count;
}
add_shortcode( 'tinta_brava_fair_count', 'tinta_brava_fair_count_shortcode' );

/**
 * Botón flotante (opcional vía shortcode para A/B testing o por si se quiere otro)
 * [tinta_brava_floating_whatsapp]
 */
function tinta_brava_floating_whatsapp_shortcode() {
  ob_start();
  ?>
  <a class="float-whatsapp" href="<?php echo esc_url( tinta_brava_whatsapp_url( 'Hola, me interesa un kit de Tinta Brava' ) ); ?>" target="_blank" rel="noopener" aria-label="WhatsApp">
    <svg viewBox="0 0 32 32" width="28" height="28" aria-hidden="true"><path fill="currentColor" d="M19.11 17.205c-.372 0-1.088 1.39-1.518 1.39a.63.63 0 0 1-.315-.1c-.802-.402-1.504-.817-2.163-1.447-.545-.516-1.146-1.29-1.46-1.963a.426.426 0 0 1-.073-.215c0-.33.99-.945.99-1.49 0-.143-.73-2.09-.832-2.335-.143-.372-.214-.487-.6-.487-.187 0-.36-.043-.53-.043-.302 0-.53.115-.746.315-.688.645-1.032 1.318-1.06 2.264v.114c-.015.99.472 1.977.873 2.78 1.247 2.477 2.81 4.382 5.32 5.629.741.372 2.139.93 2.965.93.873 0 2.749-.358 3.349-1.146.23-.3.372-.645.372-1.004 0-.23-.043-.444-.158-.658-.302-.558-2.18-1.78-2.582-1.78zM16 0C7.16 0 0 7.16 0 16c0 3.36.99 6.51 2.71 9.18L0 32l6.97-2.65A15.9 15.9 0 0 0 16 32c8.84 0 16-7.16 16-16S24.84 0 16 0zm0 29.21c-2.75 0-5.31-.84-7.43-2.27l-.53-.32-4.13 1.57 1.4-4.02-.35-.55A13.21 13.21 0 0 1 2.79 16C2.79 8.69 8.69 2.79 16 2.79S29.21 8.69 29.21 16 23.31 29.21 16 29.21z"/></svg>
  </a>
  <?php
  return ob_get_clean();
}
add_shortcode( 'tinta_brava_floating_whatsapp', 'tinta_brava_floating_whatsapp_shortcode' );
