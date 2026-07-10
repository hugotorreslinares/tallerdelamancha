<?php
/**
 * Funciones auxiliares del tema
 *
 * @package TintaBrava
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Tiempo de lectura estimado en minutos
 */
function tinta_brava_reading_time( $post_id = null ) {
  $post_id = $post_id ?: get_the_ID();
  $content = get_post_field( 'post_content', $post_id );
  $words   = str_word_count( strip_tags( $content ) );
  $minutes = max( 1, ceil( $words / 200 ) );
  return (int) $minutes;
}

/**
 * Posts relacionados
 */
function tinta_brava_related_posts( $post_id = null, $count = 3 ) {
  $post_id = $post_id ?: get_the_ID();
  $cats    = wp_get_post_terms( $post_id, 'category', array( 'fields' => 'ids' ) );
  $query   = new WP_Query( array(
    'post_type'           => 'post',
    'posts_per_page'      => $count,
    'post__not_in'        => array( $post_id ),
    'category__in'        => $cats,
    'ignore_sticky_posts' => 1,
  ) );
  return $query;
}

/**
 * Obtener ferias (CPT: feria)
 */
function tinta_brava_get_fairs( $args = array() ) {
  $defaults = array(
    'post_type'      => 'fair',
    'posts_per_page' => -1,
    'orderby'        => 'meta_value',
    'meta_key'       => 'fair_date',
    'order'          => 'ASC',
  );
  return new WP_Query( wp_parse_args( $args, $defaults ) );
}

/**
 * Esquema de eventos para ferias (SEO)
 */
function tinta_brava_event_schema( $post_id ) {
  $date  = get_post_meta( $post_id, 'fair_date', true );
  $loc   = get_post_meta( $post_id, 'fair_location', true );
  $city  = get_post_meta( $post_id, 'fair_city', true );
  if ( ! $date || ! $loc ) return;
  $iso = date( 'c', strtotime( $date ) );
  $schema = array(
    '@context'    => 'https://schema.org',
    '@type'       => 'Event',
    'name'        => get_the_title( $post_id ),
    'startDate'   => $iso,
    'eventStatus' => 'https://schema.org/EventScheduled',
    'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
    'location'    => array(
      '@type'   => 'Place',
      'name'    => $loc,
      'address' => $city,
    ),
    'description' => wp_strip_all_tags( get_the_excerpt( $post_id ) ),
    'url'         => get_permalink( $post_id ),
  );
  echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>';
}


/**
 * Helpers
 */
function tinta_brava_whatsapp_url( $message = '' ) {
  $num = get_theme_mod( 'tinta_brava_whatsapp', '573000000000' );
  return 'https://wa.me/' . $num . '?text=' . rawurlencode( $message );
}

function tinta_brava_phone_link() {
  return get_theme_mod( 'tinta_brava_whatsapp', '573000000000' );
}

function tinta_brava_instagram_url() {
  $user = get_theme_mod( 'tinta_brava_instagram', 'tintabrava' );
  return 'https://instagram.com/' . $user;
}

function tinta_brava_email_link() {
  return 'mailto:' . get_theme_mod( 'tinta_brava_email', 'hola@tintabrava.co' );
}

/**
 * Formatear precio en COP
 */
function tinta_brava_format_price( $amount ) {
  return '$ ' . number_format( (float) $amount, 0, ',', '.' ) . ' COP';
}

/**
 * Personalizar excerpt
 */
function tinta_brava_excerpt_length( $length ) {
  return 24;
}
add_filter( 'excerpt_length', 'tinta_brava_excerpt_length' );

function tinta_brava_excerpt_more( $more ) {
  return '…';
}
add_filter( 'excerpt_more', 'tinta_brava_excerpt_more' );

/**
 * Soporte para WooCommerce
 */
function tinta_brava_woocommerce_support() {
  add_theme_support( 'woocommerce', array(
    'thumbnail_image_width' => 600,
    'single_image_width'    => 800,
    'product_grid'          => array(
      'default_rows'    => 3,
      'min_rows'        => 1,
      'max_rows'        => 10,
      'default_columns' => 3,
      'min_columns'     => 1,
      'max_columns'     => 4,
    ),
  ) );
  add_theme_support( 'wc-product-gallery-zoom' );
  add_theme_support( 'wc-product-gallery-lightbox' );
  add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'tinta_brava_woocommerce_support' );

/**
 * Desactivar estilos por defecto de WooCommerce (usamos los nuestros)
 */
function tinta_brava_dequeue_wc_styles( $enqueue_styles ) {
  unset( $enqueue_styles['woocommerce-general'] );
  return $enqueue_styles;
}
add_filter( 'woocommerce_enqueue_styles', 'tinta_brava_dequeue_wc_styles' );

