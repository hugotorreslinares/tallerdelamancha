<?php
/**
 * Script: generar favicon multi-tamaño y OG image por defecto
 *
 * Ejecutar una sola vez desde CLI: `php assets/img/generate.php`
 * O desde el navegador accediendo a este archivo si está en un WP_DEBUG.
 *
 * NOTA: Este script requiere la extensión GD de PHP.
 * Si no la tienes, sube manualmente un favicon.ico de 32×32 a la raíz.
 */

if ( ! defined( 'ABSPATH' ) && php_sapi_name() !== 'cli' ) {
  http_response_code( 403 );
  exit( 'Forbidden' );
}

$svg_path = __DIR__ . '/favicon.svg';
if ( ! file_exists( $svg_path ) ) {
  echo "No se encontró favicon.svg\n";
  exit( 1 );
}

$svg_content = file_get_contents( $svg_path );

// Función helper: genera un PNG básico a partir del SVG (placeholder)
// En producción, mejor usar librerías como Imagick o un servicio en línea
function create_placeholder_png( $width, $height, $bg = '#1A1A1A', $fg = '#F4EFE6' ) {
  $img = imagecreatetruecolor( $width, $height );
  $bg_rgb = sscanf( $bg, '#%02x%02x%02x' );
  $fg_rgb = sscanf( $fg, '#%02x%02x%02x' );
  $bg_color = imagecolorallocate( $img, $bg_rgb[0], $bg_rgb[1], $bg_rgb[2] );
  $fg_color = imagecolorallocate( $img, $fg_rgb[0], $fg_rgb[1], $fg_rgb[2] );
  imagefill( $img, 0, 0, $bg_color );
  // Círculo blanco en la mitad
  imagefilledellipse( $img, $width / 2, $height / 2, (int)( $width * 0.6 ), (int)( $height * 0.6 ), $fg_color );
  return $img;
}

$sizes = array( 32, 64, 180, 192, 512 );
foreach ( $sizes as $size ) {
  $img = create_placeholder_png( $size, $size );
  $path = __DIR__ . "/favicon-{$size}.png";
  imagepng( $img, $path );
  imagedestroy( $img );
  echo "Generado: favicon-{$size}.png\n";
}

// OG image 1200x630
$og = create_placeholder_png( 1200, 630 );
imagepng( $og, __DIR__ . '/og-default.png' );
imagedestroy( $og );
echo "Generado: og-default.png\n";

echo "Listo. Sube los archivos PNG generados a /wp-content/uploads/.\n";
