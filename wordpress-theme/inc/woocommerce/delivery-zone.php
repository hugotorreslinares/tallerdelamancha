<?php
/**
 * Zona de entrega sin costo en Bogotá.
 *
 * Entre la Av. Primero de Mayo (Calle 22 Sur) y la Calle 183, y entre la
 * Carrera 7 y la Av. Ciudad de Cali (Carrera 86). Fuera de esa zona rige
 * la tarifa fija de las zonas de envío de WooCommerce.
 *
 * @package TintaBrava
 */

if ( ! defined( 'ABSPATH' ) ) exit;

const TINTA_BRAVA_ZONE_CALLE_NORTE_MAX = 183;
const TINTA_BRAVA_ZONE_CALLE_SUR_MAX   = 22;
const TINTA_BRAVA_ZONE_CARRERA_MIN     = 7;
const TINTA_BRAVA_ZONE_CARRERA_MAX     = 86;

/**
 * ¿La dirección ("Calle 100 # 15-20", "Cra 7 No. 72-41", "AK 19 # 100-10") cae en la zona?
 * Si no se puede interpretar devuelve false: se cobra el envío y el cliente puede pedir ajuste.
 * ponytail: parser de nomenclatura calle/carrera, no geocodifica. Avenidas con nombre
 * (Autopista Norte, Av. Boyacá…) no se reconocen; si hace falta, geocodificar con Google.
 */
function tinta_brava_bogota_address_in_zone( $address ) {
  $a = remove_accents( strtolower( (string) $address ) );
  $a = preg_replace( '/\b(no|nro|num)\b\.?|n°|nº/', '#', $a );
  $a = preg_replace( '/[.,]/', ' ', $a );
  $a = preg_replace( '/\s+/', ' ', trim( $a ) );

  $pattern = '/^(?:av(?:enida)? )?(?:(calle|cl|cll|ac|diagonal|dg|diag)|(carrera|cra|cr|kr|k|ak|transversal|tv|trans)) ?(\d{1,3}) ?(?:bis)? ?[a-z]? ?(sur|este)? ?#? ?(\d{1,3}) ?(?:bis)? ?[a-z]? ?(sur|este)? ?-? ?\d/';
  if ( ! preg_match( $pattern, $a, $m ) ) {
    return false;
  }

  $is_calle = '' !== $m[1];
  $first    = (int) $m[3];
  $second   = (int) $m[5];
  $suffix_1 = $m[4];
  $suffix_2 = isset( $m[6] ) ? $m[6] : '';

  $calle    = $is_calle ? $first : $second;
  $carrera  = $is_calle ? $second : $first;
  $calle_sur    = 'sur' === ( $is_calle ? $suffix_1 : $suffix_2 );
  $carrera_este = 'este' === ( $is_calle ? $suffix_2 : $suffix_1 );

  $calle_ok   = $calle_sur ? $calle <= TINTA_BRAVA_ZONE_CALLE_SUR_MAX : $calle <= TINTA_BRAVA_ZONE_CALLE_NORTE_MAX;
  $carrera_ok = ! $carrera_este && $carrera >= TINTA_BRAVA_ZONE_CARRERA_MIN && $carrera <= TINTA_BRAVA_ZONE_CARRERA_MAX;

  return $calle >= 1 && $calle_ok && $carrera_ok;
}

/**
 * Dentro de la zona: el domicilio (flat_rate de la zona Bogotá) no cuesta.
 */
function tinta_brava_free_delivery_in_zone( $rates, $package ) {
  $dest = $package['destination'];
  if ( 'CO-DC' !== $dest['state'] || ! tinta_brava_bogota_address_in_zone( $dest['address'] ) ) {
    return $rates;
  }
  foreach ( $rates as $rate ) {
    if ( 'flat_rate' === $rate->get_method_id() ) {
      $rate->set_cost( 0 );
      $rate->set_taxes( array() );
      $rate->set_label( __( 'Domicilio sin costo (zona de cobertura)', 'tinta-brava' ) );
    }
  }
  return $rates;
}
add_filter( 'woocommerce_package_rates', 'tinta_brava_free_delivery_in_zone', 10, 2 );
