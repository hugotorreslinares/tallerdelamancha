<?php
// Uso: php tests/delivery-zone.test.php
define( 'ABSPATH', __DIR__ );
function remove_accents( $s ) { return strtr( $s, 'áéíóúñÁÉÍÓÚÑ', 'aeiounAEIOUN' ); }
function add_filter() {}
function __( $s ) { return $s; }
require __DIR__ . '/../wordpress-theme/inc/woocommerce/delivery-zone.php';

$cases = array(
  'Calle 100 # 15-20'        => true,
  'Carrera 7 # 72-41'        => true,
  'Cra 15 No. 100-20'        => true,
  'Calle 183 # 20-10'        => true,
  'Calle 184 # 20-10'        => false,
  'Calle 22 Sur # 10-5'      => true,
  'Calle 23 Sur # 10-5'      => false,
  'Carrera 86 # 50-10'       => true,
  'Carrera 87 # 50-10'       => false,
  'Carrera 5 # 32-10'        => false,
  'Carrera 10 Este # 70-10'  => false,
  'Av. Carrera 19 # 100-10'  => true,
  'AK 19 # 100-10'           => true,
  'Kr 45 # 120-30'           => true,
  'Autopista Norte # 115-30' => false,
  'Calle 140 # 5-20'         => false,
  'texto cualquiera'         => false,
  ''                         => false,
);
$fail = 0;
foreach ( $cases as $addr => $expected ) {
  $got = tinta_brava_bogota_address_in_zone( $addr );
  if ( $got !== $expected ) { $fail++; echo "FALLA: '$addr' esperado " . var_export( $expected, true ) . " obtuvo " . var_export( $got, true ) . "\n"; }
}
echo $fail ? "$fail fallas\n" : "OK: " . count( $cases ) . " casos\n";
exit( $fail ? 1 : 0 );
