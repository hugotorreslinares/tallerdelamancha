<?php
// Uso: php tests/whatsapp-url.test.php
function get_theme_mod( $k, $d = '' ) { return '573001112233'; }
function get_bloginfo( $k ) { return 'Taller de la mancha'; }
$src = file_get_contents( __DIR__ . '/../wordpress-theme/inc/helpers.php' );
preg_match( '/function tinta_brava_whatsapp_url.*?\n}\n/s', $src, $m );
eval( $m[0] );

$cases = array(
  array( array( 'Hola, me interesa un kit de Taller de la mancha', 'Menú · Pedir por WhatsApp' ), "Hola, me interesa un kit de Taller de la mancha\n\n(Desde: Menú · Pedir por WhatsApp)" ),
  array( array( '', 'Footer · enlace' ), "Hola, quiero información de Taller de la mancha\n\n(Desde: Footer · enlace)" ),
  array( array( 'Hola' ), 'Hola' ),
);
$fail = 0;
foreach ( $cases as $i => $c ) {
  $url = tinta_brava_whatsapp_url( ...$c[0] );
  $got = urldecode( substr( $url, strpos( $url, 'text=' ) + 5 ) );
  if ( $got !== $c[1] || 0 !== strpos( $url, 'https://wa.me/573001112233?text=' ) ) { $fail++; echo "FALLA caso $i: " . json_encode( $got ) . "\n"; }
}
echo $fail ? "$fail fallas\n" : "OK: " . count( $cases ) . " casos\n";
exit( $fail ? 1 : 0 );
