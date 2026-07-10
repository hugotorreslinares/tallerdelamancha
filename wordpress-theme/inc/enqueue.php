<?php
/**
 * Enqueue de scripts y estilos
 *
 * @package TintaBrava
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Obtener la versión de los assets.
 */
function tinta_brava_asset_version( $file ) {
	$path = TINTA_BRAVA_DIR . '/' . ltrim( $file, '/' );

	return file_exists( $path )
		? filemtime( $path )
		: TINTA_BRAVA_VERSION;
}

/**
 * Registrar estilos y scripts.
 */
function tinta_brava_scripts() {

	$css_files = array(
		'reset.css',
		'tokens.css',
		'base.css',
		'components.css',
		'pages.css',
	);

	foreach ( $css_files as $file ) {

		$handle = 'tinta-brava-' . pathinfo( $file, PATHINFO_FILENAME );

		wp_enqueue_style(
			$handle,
			TINTA_BRAVA_URI . '/assets/css/' . $file,
			array(),
			tinta_brava_asset_version( 'assets/css/' . $file )
		);
	}

	wp_enqueue_style(
		'tinta-brava-fonts',
		'https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,600;9..144,700&family=Inter:wght@400;500;600;700&display=swap',
		array(),
		null
	);

	wp_enqueue_script(
		'tinta-brava-main',
		TINTA_BRAVA_URI . '/assets/js/main.js',
		array(),
		tinta_brava_asset_version( 'assets/js/main.js' ),
		true
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}

add_action( 'wp_enqueue_scripts', 'tinta_brava_scripts' );