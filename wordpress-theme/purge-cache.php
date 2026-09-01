<?php
/**
 * Endpoint standalone para purgar LiteSpeed Cache tras el deploy.
 * No pasa por WordPress: header('X-LiteSpeed-Purge') lo procesa LiteSpeed
 * directamente porque el archivo vive dentro del vhost del sitio.
 *
 * @package TintaBrava
 */

// Token compartido con el secret LSCACHE_PURGE_TOKEN del workflow de GitHub Actions.
$token = '04809df8996abfb8ddc52e1cc47ce8435f56869de7dcf01f';

if ( ! isset( $_GET['token'] ) || ! hash_equals( $token, $_GET['token'] ) ) {
	http_response_code( 403 );
	exit( 'Forbidden' );
}

header( 'X-LiteSpeed-Purge: *' );
echo 'purged';
