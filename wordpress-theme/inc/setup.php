<?php
/**
 * Configuración inicial al activar el tema
 *
 * @package TintaBrava
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Al activar el tema, sembrar valores por defecto en el personalizador
 */
function tinta_brava_after_switch_theme() {
  // Defaults del customizer
  $defaults = array(
    'tinta_brava_whatsapp' => '573000000000',
    'tinta_brava_instagram' => 'tintabrava',
    'tinta_brava_email'    => 'hola@tintabrava.co',
    'tinta_brava_hero_title' => 'Empieza a estampar en casa, una tirada a la vez.',
    'tinta_brava_hero_lead'  => 'Kits de linograbado, serigrafía y litografía con todo lo que necesitas para aprender la técnica y terminar tu primer proyecto. Diseñados y armados en taller, con materiales que de verdad se usan.',
  );
  foreach ( $defaults as $key => $value ) {
    if ( false === get_theme_mod( $key ) ) {
      set_theme_mod( $key, $value );
    }
  }

  // Refrescar rewrite rules
  flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'tinta_brava_after_switch_theme' );

/**
 * Sembrar valores por defecto al instalar WooCommerce por primera vez.
 * Corre una sola vez (flag en opción), no en cada request.
 */
function tinta_brava_woocommerce_defaults() {
  if ( ! class_exists( 'WooCommerce' ) ) {
    return;
  }
  if ( get_option( 'tinta_brava_wc_defaults_done' ) ) {
    return;
  }

  // Forzar pesos colombianos como moneda por defecto
  update_option( 'woocommerce_currency', 'COP' );
  update_option( 'woocommerce_currency_pos', 'left_space' );
  update_option( 'woocommerce_price_thousand_sep', '.' );
  update_option( 'woocommerce_price_decimal_sep', ',' );
  update_option( 'woocommerce_price_num_decimals', '0' );
  update_option( 'woocommerce_enable_signup_and_login_from_checkout', 'no' );
  update_option( 'woocommerce_enable_guest_checkout', 'no' );
  update_option( 'woocommerce_registration_generate_password', 'no' );

  // Desactivar reseñas (no encajan con nuestro modelo)
  update_option( 'woocommerce_enable_reviews', 'no' );

  // Quitar páginas por defecto de WooCommerce si existen
  $pages_to_remove = array( 'cart', 'checkout', 'my-account' );
  foreach ( $pages_to_remove as $slug ) {
    $page = get_page_by_path( $slug, OBJECT, 'page' );
    if ( $page ) {
      wp_delete_post( $page->ID, true );
    }
  }

  update_option( 'tinta_brava_wc_defaults_done', 1 );
}
add_action( 'woocommerce_init', 'tinta_brava_woocommerce_defaults' );

/**
 * Redirección 301 desde el slug viejo de producto /product/ hacia el
 * actual /producto/ (cambió al forzar la regeneración de permalinks
 * el 2026-09-03). Evita romper enlaces ya compartidos o indexados.
 */
function tinta_brava_redirect_old_product_slug() {
  if ( ! is_404() ) {
    return;
  }
  $uri = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
  if ( strpos( $uri, '/product/' ) === false ) {
    return;
  }
  $new_uri = str_replace( '/product/', '/producto/', $uri );
  wp_safe_redirect( home_url( $new_uri ), 301 );
  exit;
}
add_action( 'template_redirect', 'tinta_brava_redirect_old_product_slug' );
