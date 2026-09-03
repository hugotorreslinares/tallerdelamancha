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
  <script>
    (function () {
      try {
        var t = localStorage.getItem( 'tinta-brava-theme' );
        if ( t === 'light' || t === 'dark' ) {
          document.documentElement.setAttribute( 'data-theme', t );
        }
      } catch ( e ) {}
    })();
  </script>
  <meta charset="<?php bloginfo( 'charset' ); ?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="theme-color" content="#1A1A1A" />
  <link rel="profile" href="https://gmpg.org/xfn/11" />
  <?php wp_head(); ?>
  <!-- DEBUG i18n:
    locale=<?php echo esc_html( get_locale() ); ?>
    loaded=<?php echo is_textdomain_loaded( 'tinta-brava' ) ? 'yes' : 'no'; ?>
    pll_current=<?php echo function_exists( 'pll_current_language' ) ? esc_html( pll_current_language() ) : 'no-pll'; ?>
    test="<?php echo esc_html__( 'Contacto', 'tinta-brava' ); ?>"
    mofile=<?php echo esc_html( TINTA_BRAVA_DIR . '/languages/tinta-brava-' . get_locale() . '.mo' ); ?>
    mofile_exists=<?php echo file_exists( TINTA_BRAVA_DIR . '/languages/tinta-brava-' . get_locale() . '.mo' ) ? 'yes' : 'no'; ?>
    load_result=<?php echo isset( $GLOBALS['tinta_brava_i18n_debug'] ) ? var_export( $GLOBALS['tinta_brava_i18n_debug'], true ) : 'hook-did-not-run'; ?>
  -->
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link" href="#main"><?php esc_html_e( 'Saltar al contenido', 'tinta-brava' ); ?></a>

<header class="site-header" id="site-header">
  <div class="container header-inner">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="brand" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
      <?php
      $image = has_custom_logo() ? wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' ) : false;
      if ( $image ) :
      ?>
        <span class="brand-mark custom-logo" aria-hidden="true">
          <img src="<?php echo esc_url( $image[0] ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" />
        </span>
      <?php else : ?>
        <span class="brand-mark" aria-hidden="true">◐</span>
      <?php endif; ?>
      <span class="brand-name"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>
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
      <?php if ( function_exists( 'pll_the_languages' ) ) :
        $languages = pll_the_languages( array( 'raw' => 1 ) );
        if ( $languages ) :
      ?>
        <ul class="lang-switch">
          <?php foreach ( $languages as $lang ) : ?>
            <li>
              <a href="<?php echo esc_url( $lang['url'] ); ?>"<?php echo $lang['current_lang'] ? ' aria-current="true"' : ''; ?>>
                <?php echo esc_html( strtoupper( $lang['slug'] ) ); ?>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; endif; ?>
      <button class="theme-toggle" id="theme-toggle" type="button" aria-label="<?php esc_attr_e( 'Cambiar a modo oscuro', 'tinta-brava' ); ?>">
        <svg class="icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="4"></circle><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"></path></svg>
        <svg class="icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79Z"></path></svg>
      </button>
    </nav>
  </div>
</header>

<main id="main">
