<?php
/**
 * SEO básico, cabeceras de seguridad y endurecimiento de usuarios
 * (auditoría de noviembre 2026: T1–T5, T7–T9, T12).
 *
 * @package TintaBrava
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * ¿Hay un plugin SEO activo? Si lo hay, el tema no imprime meta/canonical.
 */
function tinta_brava_seo_plugin_active() {
  return defined( 'WPSEO_VERSION' ) || class_exists( 'RankMath' ) || defined( 'AIOSEO_VERSION' );
}

/* ------------------------------------------------------------------
 * T4 — cabeceras de seguridad y huella de versión
 * ------------------------------------------------------------------ */
remove_action( 'wp_head', 'wp_generator' );
add_filter( 'the_generator', '__return_empty_string' );

function tinta_brava_security_headers() {
  header_remove( 'X-Powered-By' );
  header( 'Strict-Transport-Security: max-age=31536000' );
  header( 'X-Content-Type-Options: nosniff' );
  header( 'X-Frame-Options: SAMEORIGIN' );
  header( 'Referrer-Policy: strict-origin-when-cross-origin' );
}
add_action( 'send_headers', 'tinta_brava_security_headers' );

/* ------------------------------------------------------------------
 * T5 — no exponer usuarios
 * ------------------------------------------------------------------ */
function tinta_brava_hide_users_endpoint( $endpoints ) {
  if ( ! is_user_logged_in() ) {
    unset( $endpoints['/wp/v2/users'], $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
  }
  return $endpoints;
}
add_filter( 'rest_endpoints', 'tinta_brava_hide_users_endpoint' );

// Prioridad 1: antes de que redirect_canonical convierta ?author=N en /author/slug/.
function tinta_brava_block_author_archives() {
  if ( is_author() ) {
    wp_safe_redirect( home_url( '/' ), 301 );
    exit;
  }
}
add_action( 'template_redirect', 'tinta_brava_block_author_archives', 1 );

add_filter( 'wp_sitemaps_add_provider', function ( $provider, $name ) {
  return 'users' === $name ? false : $provider;
}, 10, 2 );

/* ------------------------------------------------------------------
 * T7 — un solo <h1> por página: el del tema; los del contenido bajan a <h2>
 * ------------------------------------------------------------------ */
function tinta_brava_demote_content_h1( $content ) {
  return preg_replace( array( '#<h1(?=[\s>])#i', '#</h1>#i' ), array( '<h2', '</h2>' ), $content );
}
add_filter( 'the_content', 'tinta_brava_demote_content_h1', 20 );

/* ------------------------------------------------------------------
 * T6 — imágenes del contenido con alt vacío usan el texto alternativo de la biblioteca
 * ------------------------------------------------------------------ */
function tinta_brava_fill_empty_alt( $content ) {
  $tags = new WP_HTML_Tag_Processor( $content );
  while ( $tags->next_tag( 'img' ) ) {
    if ( '' !== (string) $tags->get_attribute( 'alt' ) ) {
      continue;
    }
    if ( preg_match( '/wp-image-(\d+)/', (string) $tags->get_attribute( 'class' ), $m ) ) {
      $library_alt = get_post_meta( (int) $m[1], '_wp_attachment_image_alt', true );
      if ( $library_alt ) {
        $tags->set_attribute( 'alt', $library_alt );
      }
    }
  }
  return $tags->get_updated_html();
}
add_filter( 'the_content', 'tinta_brava_fill_empty_alt', 21 );

/* ------------------------------------------------------------------
 * T8 — idioma y hreflang de la página en inglés
 * ------------------------------------------------------------------ */
function tinta_brava_translation_pair() {
  return array(
    'es' => 'sobre-el-taller',
    'en' => 'sobre-el-taller-english',
  );
}

function tinta_brava_is_english_page() {
  $pair = tinta_brava_translation_pair();
  return is_page( $pair['en'] );
}

add_filter( 'language_attributes', function ( $output ) {
  return tinta_brava_is_english_page() ? preg_replace( '/lang="[^"]*"/', 'lang="en"', $output ) : $output;
} );

function tinta_brava_hreflang() {
  $pair = tinta_brava_translation_pair();
  if ( ! is_page( array_values( $pair ) ) ) {
    return;
  }
  $es = get_page_by_path( $pair['es'] );
  $en = get_page_by_path( $pair['en'] );
  if ( ! $es || ! $en ) {
    return;
  }
  printf( '<link rel="alternate" hreflang="es" href="%s" />' . "\n", esc_url( get_permalink( $es ) ) );
  printf( '<link rel="alternate" hreflang="en" href="%s" />' . "\n", esc_url( get_permalink( $en ) ) );
  printf( '<link rel="alternate" hreflang="x-default" href="%s" />' . "\n", esc_url( get_permalink( $es ) ) );
}
add_action( 'wp_head', 'tinta_brava_hreflang', 2 );

/* ------------------------------------------------------------------
 * T9 / T12 — contenido duplicado fuera del índice y del sitemap
 * ------------------------------------------------------------------ */
function tinta_brava_non_indexable_page_slugs() {
  return array( 'privacy-policy', 'shop', 'carrito', 'finalizar-compra' );
}

function tinta_brava_noindex_duplicates( $robots ) {
  $is_shop = function_exists( 'is_shop' ) && is_shop();
  if ( $is_shop || is_page( tinta_brava_non_indexable_page_slugs() ) ) {
    $robots['noindex'] = true;
    unset( $robots['max-image-preview'] );
  }
  return $robots;
}
add_filter( 'wp_robots', 'tinta_brava_noindex_duplicates' );

function tinta_brava_sitemap_exclude_pages( $args, $post_type ) {
  if ( 'page' !== $post_type ) {
    return $args;
  }
  $ids = array();
  foreach ( tinta_brava_non_indexable_page_slugs() as $slug ) {
    $page = get_page_by_path( $slug );
    if ( $page ) {
      $ids[] = $page->ID;
    }
  }
  if ( function_exists( 'wc_get_page_id' ) ) {
    $ids = array_merge( $ids, array_filter( array( wc_get_page_id( 'shop' ), wc_get_page_id( 'cart' ), wc_get_page_id( 'checkout' ) ), function ( $id ) {
      return $id > 0;
    } ) );
  }
  $args['post__not_in'] = array_merge( (array) ( $args['post__not_in'] ?? array() ), $ids );
  return $args;
}
add_filter( 'wp_sitemaps_posts_query_args', 'tinta_brava_sitemap_exclude_pages', 10, 2 );

/* ------------------------------------------------------------------
 * T11 — título repetido ("Taller de la mancha – Taller de la mancha")
 * ------------------------------------------------------------------ */
add_filter( 'document_title_parts', function ( $parts ) {
  if ( isset( $parts['title'], $parts['site'] ) && $parts['title'] === $parts['site'] ) {
    unset( $parts['site'] );
  }
  return $parts;
} );

/* ------------------------------------------------------------------
 * T1–T3 — meta description, Open Graph, Twitter Cards y canonical
 * ------------------------------------------------------------------ */
function tinta_brava_seo_description() {
  $text = '';
  if ( is_singular() ) {
    $text = get_the_excerpt();
  } elseif ( is_category() || is_tag() || is_tax() ) {
    $text = term_description();
  }
  $text = wp_trim_words( wp_strip_all_tags( $text ), 30, '…' );
  return '' !== $text ? $text : get_bloginfo( 'description' );
}

function tinta_brava_seo_canonical() {
  if ( is_singular() ) {
    return wp_get_canonical_url();
  }
  if ( is_front_page() || is_home() ) {
    return home_url( '/' );
  }
  $object = get_queried_object();
  if ( is_category() || is_tag() || is_tax() ) {
    $url = get_term_link( $object );
  } elseif ( is_post_type_archive() ) {
    $url = get_post_type_archive_link( $object->name );
  } else {
    return '';
  }
  $paged = (int) get_query_var( 'paged' );
  return is_wp_error( $url ) ? '' : ( $paged > 1 ? get_pagenum_link( $paged ) : $url );
}

function tinta_brava_seo_image() {
  if ( is_singular() && has_post_thumbnail() ) {
    return (string) get_the_post_thumbnail_url( null, 'large' );
  }
  $logo = get_theme_mod( 'custom_logo' );
  return $logo ? (string) wp_get_attachment_image_url( $logo, 'full' ) : '';
}

function tinta_brava_seo_head() {
  if ( is_admin() || is_feed() || is_404() || is_search() ) {
    return;
  }

  $canonical = tinta_brava_seo_canonical();
  if ( $canonical ) {
    printf( '<link rel="canonical" href="%s" />' . "\n", esc_url( $canonical ) );
  }

  $description = tinta_brava_seo_description();
  $title       = wp_get_document_title();
  $image       = tinta_brava_seo_image();
  $locale      = tinta_brava_is_english_page() ? 'en_US' : get_locale();

  $tags = array(
    'og:type'        => is_singular( 'post' ) ? 'article' : 'website',
    'og:site_name'   => get_bloginfo( 'name' ),
    'og:locale'      => $locale,
    'og:title'       => $title,
    'og:description' => $description,
    'og:url'         => $canonical,
    'og:image'       => $image,
  );

  if ( $description ) {
    printf( '<meta name="description" content="%s" />' . "\n", esc_attr( $description ) );
  }
  foreach ( $tags as $property => $value ) {
    if ( $value ) {
      printf( '<meta property="%s" content="%s" />' . "\n", esc_attr( $property ), esc_attr( $value ) );
    }
  }
  printf( '<meta name="twitter:card" content="%s" />' . "\n", $image ? 'summary_large_image' : 'summary' );
}

// Con un plugin SEO el tema no duplica etiquetas; el canonical de singulares sigue siendo el del núcleo.
if ( ! tinta_brava_seo_plugin_active() ) {
  remove_action( 'wp_head', 'rel_canonical' );
  add_action( 'wp_head', 'tinta_brava_seo_head', 3 );
}
