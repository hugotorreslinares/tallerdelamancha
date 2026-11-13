<?php
// Uso: php tests/seo-security.test.php
$src = file_get_contents( __DIR__ . '/../wordpress-theme/inc/seo-security.php' );
preg_match( '/function tinta_brava_demote_content_h1.*?\n}\n/s', $src, $m );
eval( $m[0] );

$cases = array(
  array( '<h1 class="wp-block-heading">A</h1><p>x</p>', '<h2 class="wp-block-heading">A</h2><p>x</p>' ),
  array( '<h1>A</h1><h1>B</h1>', '<h2>A</h2><h2>B</h2>' ),
  array( '<header>x</header><h2>A</h2>', '<header>x</header><h2>A</h2>' ),
);
$fail = 0;
foreach ( $cases as $i => $c ) {
  $got = tinta_brava_demote_content_h1( $c[0] );
  if ( $got !== $c[1] ) {
    echo "FAIL caso $i: $got\n";
    $fail++;
  }
}
echo $fail ? "FALLÓ\n" : "OK\n";
exit( $fail ? 1 : 0 );
