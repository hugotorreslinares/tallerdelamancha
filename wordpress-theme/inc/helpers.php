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

/**
 * Ícono lineal estilo grabado para las categorías de producto.
 * Devuelve SVG inline (currentColor) para poder colorearlo por CSS.
 */
function tinta_brava_category_icon_svg( $slug ) {
  $icons = array(
    'linograbado' => '<svg viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="12" y1="52" x2="34" y2="30"/><line x1="34" y1="30" x2="47" y2="17"/><polygon points="47,17 56,8 53,17 47,20" fill="currentColor" stroke="none"/></svg>',
    'serigrafia'  => '<svg viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="12" y="24" width="32" height="16" rx="8"/><line x1="20" y1="24" x2="20" y2="40"/><line x1="28" y1="24" x2="28" y2="40"/><line x1="36" y1="24" x2="36" y2="40"/><line x1="44" y1="32" x2="56" y2="20"/></svg>',
    'litografia'  => '<svg viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polygon points="12,44 12,28 24,18 52,18 52,34 40,44"/><line x1="12" y1="28" x2="40" y2="28"/><line x1="52" y1="18" x2="40" y2="28"/><line x1="40" y1="28" x2="40" y2="44"/></svg>',
  );
  if ( isset( $icons[ $slug ] ) ) {
    return $icons[ $slug ];
  }
  // Genérico para categorías sin ícono dedicado.
  return '<svg viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="32" cy="32" r="18"/><line x1="32" y1="20" x2="32" y2="32"/><line x1="32" y1="32" x2="40" y2="38"/></svg>';
}

/**
 * Color de acento rotativo para bloques de categoría (terracota, azul, mostaza).
 */
function tinta_brava_category_accent( $index ) {
  $accents = array( 'terracotta', 'navy', 'mustard' );
  return $accents[ $index % count( $accents ) ];
}

/**
 * Rama con hojas, estilo grabado — decoración pequeña junto a titulares.
 */
function tinta_brava_leaf_branch_svg() {
  return '<svg viewBox="0 0 60 140" fill="currentColor" aria-hidden="true">
    <path d="M30 4 C31 30 29 55 30 80 C31 100 29 118 30 136" stroke="currentColor" stroke-width="3" fill="none" stroke-linecap="round"/>
    <path d="M30 20 C18 14 8 18 4 28 C14 30 24 28 30 20 Z"/>
    <path d="M30 42 C44 34 54 40 56 52 C44 52 34 50 30 42 Z"/>
    <path d="M30 64 C17 58 7 63 4 74 C15 75 25 72 30 64 Z"/>
    <path d="M30 88 C43 81 53 86 55 97 C44 98 34 96 30 88 Z"/>
    <path d="M30 112 C19 107 10 111 8 121 C18 123 27 120 30 112 Z"/>
  </svg>';
}

/**
 * Silueta de Bogotá (cerros + skyline) para la sección "Hecho en Bogotá".
 */
function tinta_brava_bogota_skyline_svg() {
  return '<svg viewBox="0 0 800 220" fill="currentColor" aria-hidden="true" preserveAspectRatio="xMidYMax meet">
    <polygon points="0,150 60,95 130,140 210,70 300,130 380,90 460,135 540,80 620,132 700,100 800,145 800,220 0,220" opacity="0.55"/>
    <rect x="40" y="150" width="34" height="60"/>
    <rect x="90" y="130" width="26" height="80"/>
    <rect x="130" y="160" width="30" height="50"/>
    <rect x="175" y="120" width="22" height="90"/>
    <rect x="210" y="145" width="36" height="65"/>
    <rect x="260" y="100" width="24" height="110"/>
    <polygon points="272,100 260,100 260,90 284,90 284,100" />
    <rect x="298" y="150" width="28" height="60"/>
    <rect x="336" y="125" width="20" height="85"/>
    <rect x="368" y="155" width="34" height="55"/>
    <rect x="415" y="135" width="24" height="75"/>
    <rect x="450" y="90" width="26" height="120"/>
    <rect x="461" y="78" width="4" height="14"/>
    <rect x="490" y="150" width="30" height="60"/>
    <rect x="530" y="118" width="22" height="92"/>
    <rect x="562" y="145" width="34" height="65"/>
    <rect x="608" y="128" width="24" height="82"/>
    <rect x="642" y="160" width="30" height="50"/>
    <rect x="682" y="110" width="22" height="100"/>
    <rect x="714" y="150" width="30" height="60"/>
    <rect x="752" y="130" width="26" height="80"/>
  </svg>';
}

/**
 * Sello circular "Hecho en Bogotá" estilo timbre de caucho.
 */
function tinta_brava_bogota_stamp_svg() {
  return '<svg viewBox="0 0 140 140" fill="none" stroke="currentColor" aria-hidden="true">
    <circle cx="70" cy="70" r="62" stroke-width="3"/>
    <circle cx="70" cy="70" r="52" stroke-width="1.5" stroke-dasharray="2 4"/>
    <path id="tb-stamp-arc-top" d="M 20 70 A 50 50 0 0 1 120 70" fill="none" stroke="none"/>
    <path id="tb-stamp-arc-bottom" d="M 28 96 A 42 42 0 0 0 112 96" fill="none" stroke="none"/>
    <text font-size="13" font-weight="700" letter-spacing="3" fill="currentColor" stroke="none">
      <textPath href="#tb-stamp-arc-top" startOffset="50%" text-anchor="middle">BOGOTÁ</textPath>
    </text>
    <text font-size="10" font-weight="600" letter-spacing="2" fill="currentColor" stroke="none">
      <textPath href="#tb-stamp-arc-bottom" startOffset="50%" text-anchor="middle">COLOMBIA</textPath>
    </text>
    <g stroke-width="3" stroke-linecap="round">
      <line x1="70" y1="50" x2="70" y2="80"/>
      <line x1="58" y1="62" x2="82" y2="62"/>
      <line x1="62" y1="80" x2="62" y2="90"/>
      <line x1="78" y1="80" x2="78" y2="90"/>
      <line x1="58" y1="90" x2="82" y2="90"/>
    </g>
  </svg>';
}

