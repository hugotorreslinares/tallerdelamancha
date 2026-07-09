<?php
/**
 * Plugin Name: Tinta Brava Demo Importer
 * Description: Importa contenido demo (3 productos, 3 ferias, 4 tutoriales, páginas, menú) para Tinta Brava. Ejecutar una sola vez desde Herramientas → Importar demo Tinta Brava.
 * Version: 1.0.0
 * Author: Tinta Brava
 *
 * Para activarlo: copia este archivo a wp-content/plugins/ y actívalo desde el admin.
 * Una vez ejecutado, puedes desactivarlo y eliminarlo.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

require_once __DIR__ . '/../../wordpress-theme/inc/importer/tinta-brava-importer.php';
