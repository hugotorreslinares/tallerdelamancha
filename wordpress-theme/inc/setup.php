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
 * Sembrar valores por defecto al instalar WooCommerce por primera vez
 */
function tinta_brava_woocommerce_defaults() {
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
}
add_action( 'woocommerce_init', 'tinta_brava_woocommerce_defaults' );
