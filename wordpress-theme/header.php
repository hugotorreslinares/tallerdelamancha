<?php
/**
 * Header principal
 *
 * @package TintaBrava
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="theme-color" content="#1A1A1A" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="profile" href="https://gmpg.org/xfn/11" />
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link" href="#main"><?php esc_html_e( 'Saltar al contenido', 'tinta-brava' ); ?></a>

<header class="site-header" id="site-header">
  <div class="container header-inner">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="brand" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
      <?php if ( has_custom_logo() ) : the_custom_logo(); else : ?>
        <span class="brand-mark" aria-hidden="true">◐</span>
        <span class="brand-name"><?php bloginfo( 'name' ); ?></span>
      <?php endif; ?>
    </a>

    <button class="nav-toggle" id="nav-toggle" aria-expanded="false" aria-controls="primary-nav">
      <span class="sr-only"><?php esc_html_e( 'Menú', 'tinta-brava' ); ?></span>
      <span class="nav-toggle-bar"></span>
      <span class="nav-toggle-bar"></span>
      <span class="nav-toggle-bar"></span>
    </button>

    <nav class="primary-nav" id="primary-nav" aria-label="<?php esc_attr_e( 'Navegación principal', 'tinta-brava' ); ?>">
      <?php
      if ( has_nav_menu( 'primary' ) ) {
        wp_nav_menu( array(
          'theme_location' => 'primary',
          'container'      => false,
          'menu_class'     => 'primary-menu',
          'fallback_cb'    => false,
        ) );
      } else { ?>
        <ul>
          <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Inicio', 'tinta-brava' ); ?></a></li>
          <li><a href="<?php echo esc_url( home_url( '/kits/' ) ); ?>"><?php esc_html_e( 'Kits', 'tinta-brava' ); ?></a></li>
          <li><a href="<?php echo esc_url( home_url( '/sobre-el-taller/' ) ); ?>"><?php esc_html_e( 'El taller', 'tinta-brava' ); ?></a></li>
          <li><a href="<?php echo esc_url( home_url( '/tutoriales/' ) ); ?>"><?php esc_html_e( 'Tutoriales', 'tinta-brava' ); ?></a></li>
          <li><a href="<?php echo esc_url( home_url( '/ferias/' ) ); ?>"><?php esc_html_e( 'Ferias', 'tinta-brava' ); ?></a></li>
          <li><a href="<?php echo esc_url( home_url( '/contacto/' ) ); ?>"><?php esc_html_e( 'Contacto', 'tinta-brava' ); ?></a></li>
        </ul>
      <?php } ?>
      <a class="btn btn-primary nav-cta" href="<?php echo esc_url( tinta_brava_whatsapp_url( 'Hola, me interesa un kit de Tinta Brava' ) ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Pedir por WhatsApp', 'tinta-brava' ); ?></a>
    </nav>
  </div>
</header>

<main id="main">
