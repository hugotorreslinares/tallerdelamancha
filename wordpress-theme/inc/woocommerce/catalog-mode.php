<?php
/**
 * WooCommerce: Modo Catálogo + WhatsApp
 *
 * - Oculta precios (opcional, controlado por filtro)
 * - Reemplaza el botón "Añadir al carrito" por un botón de WhatsApp
 * - Oculta carrito, checkout y mi cuenta
 * - Redirige intentos de compra a la ficha del producto
 *
 * @package TintaBrava
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Quitar botones de compra de WooCommerce
 */
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );

/**
 * Quitar tabs y meta de la ficha (puesto, categoría, etiqueta) — los gestionamos nosotros
 */
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );

/**
 * Quitar el breadcrumb por defecto
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

/**
 * Quitar el sidebar de WooCommerce
 */
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

/**
 * Quitar "Productos relacionados" automático
 */
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

/**
 * Vaciar el carrito si alguien intenta comprar y redirigir al inicio
 */
function tinta_brava_empty_cart_redirect() {
  if ( is_cart() || is_checkout() ) {
    wp_safe_redirect( home_url( '/kits/' ) );
    exit;
  }
}
add_action( 'template_redirect', 'tinta_brava_empty_cart_redirect' );

/**
 * Quitar páginas de WooCommerce del menú (cuando se activen)
 */
function tinta_brava_remove_wc_menu_items( $items ) {
  $to_remove = array( 'cart', 'checkout', 'my-account' );
  foreach ( $items as $key => $item ) {
    if ( in_array( $item->object, $to_remove, true ) ) {
      unset( $items[ $key ] );
    }
  }
  return $items;
}
add_filter( 'wp_nav_menu_objects', 'tinta_brava_remove_wc_menu_items', 10 );

/**
 * Forzar que las páginas de WooCommerce no se enlacen desde ningún sitio
 */
function tinta_brava_block_wc_pages() {
  $blocked = array( 'cart', 'checkout', 'my-account' );
  if ( is_page( $blocked ) ) {
    wp_safe_redirect( home_url( '/kits/' ) );
    exit;
  }
}
add_action( 'template_redirect', 'tinta_brava_block_wc_pages', 5 );

/**
 * Quitar "Añadir al carrito" via AJAX
 */
function tinta_brava_disable_ajax_add_to_cart( $support ) {
  return false;
}
add_filter( 'woocommerce_product_supports_ajax_add_to_cart', '__return_false' );

/**
 * Botón de WhatsApp en el loop (catálogo) — reemplaza el "Añadir al carrito"
 */
function tinta_brava_loop_whatsapp_button() {
  global $product;
  if ( ! $product ) return;

  $is_coming = get_post_meta( $product->get_id(), '_tinta_brava_coming_soon', true );
  $message   = sprintf( 'Hola, me interesa el %s', $product->get_name() );
  $wa_url    = tinta_brava_whatsapp_url( $is_coming ? $message . ' (preventa)' : $message );

  if ( $is_coming ) {
    echo '<a class="button btn btn-whatsapp product_type_simple" href="' . esc_url( $wa_url ) . '" target="_blank" rel="noopener">' . esc_html__( 'Avisarme', 'tinta-brava' ) . '</a>';
  } else {
    echo '<a class="button btn btn-whatsapp product_type_simple" href="' . esc_url( $wa_url ) . '" target="_blank" rel="noopener">' . esc_html__( 'WhatsApp', 'tinta-brava' ) . '</a>';
  }
}
add_action( 'woocommerce_after_shop_loop_item', 'tinta_brava_loop_whatsapp_button', 10 );

/**
 * Mostrar precio en el loop como "Consultar" (cuando esté activado ocultar precios)
 */
function tinta_brava_loop_price() {
  global $product;
  if ( ! $product ) return;

  $is_coming = get_post_meta( $product->get_id(), '_tinta_brava_coming_soon', true );
  if ( $is_coming ) {
    echo '<span class="price"><span class="price-label">' . esc_html__( 'Próximamente', 'tinta-brava' ) . '</span></span>';
    return;
  }

  $price = $product->get_price();
  if ( $price ) {
    echo '<span class="price">' . esc_html( tinta_brava_format_price( $price ) ) . '</span>';
  } else {
    echo '<span class="price"><span class="price-label">' . esc_html__( 'Consultar', 'tinta-brava' ) . '</span></span>';
  }
}
add_action( 'woocommerce_after_shop_loop_item_title', 'tinta_brava_loop_price', 10 );

/**
 * Ocultar notificación "Producto añadido al carrito"
 */
function tinta_brava_hide_cart_notices() {
  remove_action( 'woocommerce_before_shop_loop', 'wc_print_notices', 10 );
  remove_action( 'woocommerce_before_single_product', 'wc_print_notices', 10 );
  remove_action( 'woocommerce_before_cart', 'wc_print_notices', 10 );
  remove_action( 'woocommerce_before_checkout_form', 'wc_print_notices', 10 );
}
add_action( 'init', 'tinta_brava_hide_cart_notices' );

/**
 * Desactivar fragments de carrito (no los necesitamos sin carrito activo)
 */
function tinta_brava_disable_cart_fragments() {
  wp_dequeue_script( 'wc-cart-fragments' );
}
add_action( 'wp_enqueue_scripts', 'tinta_brava_disable_cart_fragments', 99 );

/**
 * Quitar el botón "Pay" del order received
 */
remove_action( 'woocommerce_thankyou', 'woocommerce_pay_order_button', 10 );

/**
 * Quitar el review tab
 */
function tinta_brava_remove_reviews_tab( $tabs ) {
  unset( $tabs['reviews'] );
  return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'tinta_brava_remove_reviews_tab', 98 );

/**
 * Quitar campo de cantidad en el loop (no aplica sin carrito)
 */
function tinta_brava_remove_quantity_field() {
  return false;
}
add_filter( 'woocommerce_is_sold_individually', '__return_true' );
