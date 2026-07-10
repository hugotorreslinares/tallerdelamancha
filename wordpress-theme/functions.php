<?php
/**
 * Tinta Brava theme functions and definitions
 *
 * @package TintaBrava
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

define( 'TINTA_BRAVA_VERSION', '1.0.0' );
define( 'TINTA_BRAVA_DIR', get_template_directory() );
define( 'TINTA_BRAVA_URI', get_template_directory_uri() );

/**
 * Setup del tema
 */
function tinta_brava_setup() {
  load_theme_textdomain( 'tinta-brava', TINTA_BRAVA_DIR . '/languages' );
  add_theme_support( 'title-tag' );
  add_theme_support( 'post-thumbnails' );
  add_theme_support( 'custom-logo', array(
    'height'      => 100,
    'width'       => 400,
    'flex-height' => true,
    'flex-width'  => true,
  ) );
  add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
  add_theme_support( 'customize-selective-refresh-widgets' );
  add_theme_support( 'responsive-embeds' );
  add_theme_support( 'align-wide' );
  add_theme_support( 'editor-styles' );

  register_nav_menus( array(
    'primary' => __( 'Menú principal', 'tinta-brava' ),
    'footer'  => __( 'Menú del pie', 'tinta-brava' ),
  ) );

  add_image_size( 'tinta-brava-card', 600, 450, true );
  add_image_size( 'tinta-brava-hero', 1600, 1000, true );
}
add_action( 'after_setup_theme', 'tinta_brava_setup' );


/**
 * Widgets
 */
function tinta_brava_widgets_init() {
  register_sidebar( array(
    'name'          => __( 'Pie de página', 'tinta-brava' ),
    'id'            => 'footer-1',
    'description'   => __( 'Widgets para el pie de página.', 'tinta-brava' ),
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h4 class="widget-title">',
    'after_title'   => '</h4>',
  ) );
}
add_action( 'widgets_init', 'tinta_brava_widgets_init' );

/**
 * Personalizador
 */
function tinta_brava_customize_register( $wp_customize ) {
  $wp_customize->add_section( 'tinta_brava_contact', array(
    'title'    => __( 'Datos de contacto', 'tinta-brava' ),
    'priority' => 30,
  ) );

  $wp_customize->add_setting( 'tinta_brava_whatsapp', array(
    'default'           => '573000000000',
    'sanitize_callback' => 'sanitize_text_field',
  ) );
  $wp_customize->add_control( 'tinta_brava_whatsapp', array(
    'label'   => __( 'Número de WhatsApp (con código de país, sin +)', 'tinta-brava' ),
    'section' => 'tinta_brava_contact',
    'type'    => 'text',
  ) );

  $wp_customize->add_setting( 'tinta_brava_instagram', array(
    'default'           => 'tintabrava',
    'sanitize_callback' => 'sanitize_text_field',
  ) );
  $wp_customize->add_control( 'tinta_brava_instagram', array(
    'label'   => __( 'Usuario de Instagram (sin @)', 'tinta-brava' ),
    'section' => 'tinta_brava_contact',
    'type'    => 'text',
  ) );

  $wp_customize->add_setting( 'tinta_brava_email', array(
    'default'           => 'hola@tintabrava.co',
    'sanitize_callback' => 'sanitize_email',
  ) );
  $wp_customize->add_control( 'tinta_brava_email', array(
    'label'   => __( 'Correo de contacto', 'tinta-brava' ),
    'section' => 'tinta_brava_contact',
    'type'    => 'email',
  ) );


  /**
   * Sección de imagenes home
   */

      $wp_customize->add_section(
        'tinta_brava_home',
        array(
            'title'    => 'Inicio',
            'priority' => 30,
        )
    );

    // Imagen 1
    $wp_customize->add_setting(
        'tinta_brava_hero_image_1',
        array(
            'sanitize_callback' => 'absint',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Media_Control(
            $wp_customize,
            'tinta_brava_hero_image_1',
            array(
                'label'     => 'Imagen Hero 1',
                'section'   => 'tinta_brava_home',
                'mime_type' => 'image',
            )
        )
    );

    // Imagen 2
    $wp_customize->add_setting(
        'tinta_brava_hero_image_2',
        array(
            'sanitize_callback' => 'absint',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Media_Control(
            $wp_customize,
            'tinta_brava_hero_image_2',
            array(
                'label'     => 'Imagen Hero 2',
                'section'   => 'tinta_brava_home',
                'mime_type' => 'image',
            )
        )
    );
}
add_action( 'customize_register', 'tinta_brava_customize_register' );

/**
 * Cargar helpers
 */
require_once TINTA_BRAVA_DIR . '/inc/enqueue.php';
require_once TINTA_BRAVA_DIR . '/inc/helpers.php';
require_once TINTA_BRAVA_DIR . '/inc/shortcodes.php';
require_once TINTA_BRAVA_DIR . '/inc/setup.php';

/**
 * Cargar WooCommerce (solo si está activo)
 */
if ( class_exists( 'WooCommerce' ) ) {
  require_once TINTA_BRAVA_DIR . '/inc/woocommerce/catalog-mode.php';
  require_once TINTA_BRAVA_DIR . '/inc/woocommerce/product-meta.php';
}

/**
 * Registrar Custom Post Types
 */
function tinta_brava_register_cpts() {
  register_post_type( 'fair', array(
    'labels' => array(
      'name'          => __( 'Ferias', 'tinta-brava' ),
      'singular_name' => __( 'Feria', 'tinta-brava' ),
      'add_new_item'  => __( 'Añadir nueva feria', 'tinta-brava' ),
    ),
    'public'       => true,
    'has_archive'  => true,
    'menu_icon'    => 'dashicons-calendar-alt',
    'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
    'show_in_rest' => true,
    'rewrite'      => array( 'slug' => 'ferias' ),
  ) );

  register_taxonomy( 'fair_status', 'fair', array(
    'labels' => array(
      'name' => __( 'Estado', 'tinta-brava' ),
      'singular_name' => __( 'Estado', 'tinta-brava' ),
    ),
    'public'       => true,
    'hierarchical' => true,
    'show_in_rest' => true,
    'rewrite'      => array( 'slug' => 'estado-feria' ),
  ) );
}
add_action( 'init', 'tinta_brava_register_cpts' );

/**
 * Metaboxes para ferias
 */
function tinta_brava_fair_meta_boxes() {
  add_meta_box(
    'tinta_brava_fair_details',
    __( 'Detalles de la feria', 'tinta-brava' ),
    'tinta_brava_fair_details_render',
    'fair',
    'side',
    'high'
  );
}
add_action( 'add_meta_boxes', 'tinta_brava_fair_meta_boxes' );

function tinta_brava_fair_details_render( $post ) {
  wp_nonce_field( 'tinta_brava_save_fair', 'tinta_brava_fair_nonce' );
  $date     = get_post_meta( $post->ID, 'fair_date', true );
  $end_date = get_post_meta( $post->ID, 'fair_end_date', true );
  $location = get_post_meta( $post->ID, 'fair_location', true );
  $city     = get_post_meta( $post->ID, 'fair_city', true );
  $stand    = get_post_meta( $post->ID, 'fair_stand', true );
  $time     = get_post_meta( $post->ID, 'fair_time', true );
  ?>
  <p><label><strong><?php esc_html_e( 'Fecha inicio', 'tinta-brava' ); ?></strong></label><br>
    <input type="date" name="fair_date" value="<?php echo esc_attr( $date ); ?>" style="width:100%"></p>
  <p><label><strong><?php esc_html_e( 'Fecha fin', 'tinta-brava' ); ?></strong></label><br>
    <input type="date" name="fair_end_date" value="<?php echo esc_attr( $end_date ); ?>" style="width:100%"></p>
  <p><label><strong><?php esc_html_e( 'Lugar', 'tinta-brava' ); ?></strong></label><br>
    <input type="text" name="fair_location" value="<?php echo esc_attr( $location ); ?>" style="width:100%"></p>
  <p><label><strong><?php esc_html_e( 'Ciudad', 'tinta-brava' ); ?></strong></label><br>
    <input type="text" name="fair_city" value="<?php echo esc_attr( $city ); ?>" style="width:100%"></p>
  <p><label><strong><?php esc_html_e( 'Stand (opcional)', 'tinta-brava' ); ?></strong></label><br>
    <input type="text" name="fair_stand" value="<?php echo esc_attr( $stand ); ?>" style="width:100%"></p>
  <p><label><strong><?php esc_html_e( 'Horario', 'tinta-brava' ); ?></strong></label><br>
    <input type="text" name="fair_time" value="<?php echo esc_attr( $time ); ?>" style="width:100%"></p>
  <?php
}

function tinta_brava_save_fair( $post_id ) {
  if ( ! isset( $_POST['tinta_brava_fair_nonce'] ) ) return;
  if ( ! wp_verify_nonce( $_POST['tinta_brava_fair_nonce'], 'tinta_brava_save_fair' ) ) return;
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
  if ( ! current_user_can( 'edit_post', $post_id ) ) return;
  $fields = array( 'fair_date', 'fair_end_date', 'fair_location', 'fair_city', 'fair_stand', 'fair_time' );
  foreach ( $fields as $f ) {
    if ( isset( $_POST[ $f ] ) ) {
      update_post_meta( $post_id, $f, sanitize_text_field( $_POST[ $f ] ) );
    }
  }
}
add_action( 'save_post_fair', 'tinta_brava_save_fair' );

/**
 * Formulario de contacto: handler
 */
function tinta_brava_handle_contact_form() {
  if ( ! isset( $_POST['tinta_brava_contact_nonce'] ) ) return;
  if ( ! wp_verify_nonce( $_POST['tinta_brava_contact_nonce'], 'tinta_brava_contact' ) ) return;

  $name    = sanitize_text_field( $_POST['name'] ?? '' );
  $email   = sanitize_email( $_POST['email'] ?? '' );
  $subject = sanitize_text_field( $_POST['subject'] ?? '' );
  $message = sanitize_textarea_field( $_POST['message'] ?? '' );

  if ( empty( $name ) || empty( $email ) || empty( $message ) ) {
    wp_die( __( 'Por favor completa todos los campos.', 'tinta-brava' ) );
  }

  $to      = get_theme_mod( 'tinta_brava_email', 'hola@tintabrava.co' );
  $subj    = '[Tinta Brava] ' . $subject;
  $body    = sprintf( "Nombre: %s\nCorreo: %s\nAsunto: %s\n\nMensaje:\n%s", $name, $email, $subject, $message );
  $headers = array( 'Reply-To: ' . $name . ' <' . $email . '>' );

  wp_mail( $to, $subj, $body, $headers );

  wp_safe_redirect( add_query_arg( 'contacto', 'ok', wp_get_referer() ?: home_url( '/contacto/' ) ) );
  exit;
}
add_action( 'admin_post_nopriv_tinta_brava_contact', 'tinta_brava_handle_contact_form' );
add_action( 'admin_post_tinta_brava_contact', 'tinta_brava_handle_contact_form' );
