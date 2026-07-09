<?php
/**
 * Metaboxes de producto: campos personalizados Tinta Brava
 *
 * Campos:
 * - _tinta_brava_coming_soon: marca el producto como "Próximamente"
 * - _tinta_brava_time: tiempo estimado por proyecto (ej. "2-3 h")
 * - _tinta_brava_prints: número de estampas por kit (ej. "+15")
 * - _tinta_brava_persons: número de personas (ej. "1-2")
 * - _tinta_brava_level: nivel del kit (Principiante, Intermedio, Avanzado)
 * - _tinta_brava_includes: lista de materiales (uno por línea)
 * - _tinta_brava_wa_message: mensaje personalizado de WhatsApp (opcional)
 *
 * @package TintaBrava
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Registrar metabox
 */
function tinta_brava_product_meta_box() {
  add_meta_box(
    'tinta_brava_product_details',
    __( 'Detalles Tinta Brava', 'tinta-brava' ),
    'tinta_brava_product_meta_box_render',
    'product',
    'side',
    'high'
  );
}
add_action( 'add_meta_boxes', 'tinta_brava_product_meta_box' );

/**
 * Renderizar metabox
 */
function tinta_brava_product_meta_box_render( $post ) {
  wp_nonce_field( 'tinta_brava_save_product', 'tinta_brava_product_nonce' );

  $coming   = get_post_meta( $post->ID, '_tinta_brava_coming_soon', true );
  $time     = get_post_meta( $post->ID, '_tinta_brava_time', true );
  $prints   = get_post_meta( $post->ID, '_tinta_brava_prints', true );
  $persons  = get_post_meta( $post->ID, '_tinta_brava_persons', true );
  $level    = get_post_meta( $post->ID, '_tinta_brava_level', true );
  $includes = get_post_meta( $post->ID, '_tinta_brava_includes', true );
  $wa_msg   = get_post_meta( $post->ID, '_tinta_brava_wa_message', true );

  $levels = array( 'Principiante', 'Intermedio', 'Avanzado' );
  ?>
  <p>
    <label>
      <input type="checkbox" name="_tinta_brava_coming_soon" value="1" <?php checked( $coming, '1' ); ?>>
      <?php esc_html_e( 'Marcar como "Próximamente" (preventa)', 'tinta-brava' ); ?>
    </label>
  </p>
  <p>
    <label><strong><?php esc_html_e( 'Nivel', 'tinta-brava' ); ?></strong></label><br>
    <select name="_tinta_brava_level" style="width:100%">
      <option value=""><?php esc_html_e( '— Seleccionar —', 'tinta-brava' ); ?></option>
      <?php foreach ( $levels as $lvl ) : ?>
        <option value="<?php echo esc_attr( $lvl ); ?>" <?php selected( $level, $lvl ); ?>><?php echo esc_html( $lvl ); ?></option>
      <?php endforeach; ?>
    </select>
  </p>
  <p>
    <label><strong><?php esc_html_e( 'Tiempo por proyecto', 'tinta-brava' ); ?></strong></label><br>
    <input type="text" name="_tinta_brava_time" value="<?php echo esc_attr( $time ); ?>" placeholder="2-3 h" style="width:100%">
  </p>
  <p>
    <label><strong><?php esc_html_e( 'Estampas por kit', 'tinta-brava' ); ?></strong></label><br>
    <input type="text" name="_tinta_brava_prints" value="<?php echo esc_attr( $prints ); ?>" placeholder="+15" style="width:100%">
  </p>
  <p>
    <label><strong><?php esc_html_e( 'Personas', 'tinta-brava' ); ?></strong></label><br>
    <input type="text" name="_tinta_brava_persons" value="<?php echo esc_attr( $persons ); ?>" placeholder="1" style="width:100%">
  </p>
  <p>
    <label><strong><?php esc_html_e( 'Qué incluye (uno por línea)', 'tinta-brava' ); ?></strong></label><br>
    <textarea name="_tinta_brava_includes" rows="8" style="width:100%; font-family: monospace; font-size: 12px;"><?php echo esc_textarea( $includes ); ?></textarea>
  </p>
  <p>
    <label><strong><?php esc_html_e( 'Mensaje WhatsApp personalizado (opcional)', 'tinta-brava' ); ?></strong></label><br>
    <textarea name="_tinta_brava_wa_message" rows="2" style="width:100%; font-size: 12px;"><?php echo esc_textarea( $wa_msg ); ?></textarea>
    <span class="description" style="font-size: 11px; color: #666;"><?php esc_html_e( 'Si se deja vacío, se usa el nombre del producto.', 'tinta-brava' ); ?></span>
  </p>
  <?php
}

/**
 * Guardar metabox
 */
function tinta_brava_save_product_meta( $post_id ) {
  if ( ! isset( $_POST['tinta_brava_product_nonce'] ) ) return;
  if ( ! wp_verify_nonce( $_POST['tinta_brava_product_nonce'], 'tinta_brava_save_product' ) ) return;
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
  if ( ! current_user_can( 'edit_post', $post_id ) ) return;

  $fields = array(
    '_tinta_brava_coming_soon' => 'absint',
    '_tinta_brava_time'        => 'sanitize_text_field',
    '_tinta_brava_prints'      => 'sanitize_text_field',
    '_tinta_brava_persons'     => 'sanitize_text_field',
    '_tinta_brava_level'       => 'sanitize_text_field',
    '_tinta_brava_includes'    => 'sanitize_textarea_field',
    '_tinta_brava_wa_message'  => 'sanitize_textarea_field',
  );

  foreach ( $fields as $key => $sanitize ) {
    if ( isset( $_POST[ $key ] ) ) {
      $value = call_user_func( $sanitize, $_POST[ $key ] );
      update_post_meta( $post_id, $key, $value );
    } else {
      delete_post_meta( $post_id, $key );
    }
  }
}
add_action( 'save_post_product', 'tinta_brava_save_product_meta' );

/**
 * Mostrar los campos en la API REST (opcional, por si usas Gutenberg)
 */
function tinta_brava_register_product_meta_rest() {
  $fields = array(
    '_tinta_brava_coming_soon' => 'boolean',
    '_tinta_brava_time'        => 'string',
    '_tinta_brava_prints'      => 'string',
    '_tinta_brava_persons'     => 'string',
    '_tinta_brava_level'       => 'string',
    '_tinta_brava_includes'    => 'string',
    '_tinta_brava_wa_message'  => 'string',
  );
  foreach ( $fields as $key => $type ) {
    register_post_meta( 'product', $key, array(
      'show_in_rest'  => true,
      'single'        => true,
      'type'          => $type,
      'auth_callback' => function() { return current_user_can( 'edit_posts' ); },
    ) );
  }
}
add_action( 'rest_api_init', 'tinta_brava_register_product_meta_rest' );
