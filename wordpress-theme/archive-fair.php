<?php
/**
 * Archivo de ferias (CPT: fair)
 *
 * @package TintaBrava
 */
get_header();
?>

<section class="page-header">
  <div class="container">
    <p class="breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Inicio', 'tinta-brava' ); ?></a> / <?php esc_html_e( 'Ferias', 'tinta-brava' ); ?></p>
    <h1><?php esc_html_e( 'Ferias y eventos', 'tinta-brava' ); ?></h1>
    <p><?php esc_html_e( 'Estaremos en estas ferias con stand propio, demostraciones en vivo y descuento especial por comprar en persona. Pásate, prueba las herramientas y resuelve dudas.', 'tinta-brava' ); ?></p>
  </div>
</section>

<section class="section">
  <div class="container">
    <h2><?php esc_html_e( 'Próximas ferias', 'tinta-brava' ); ?></h2>

    <?php
    $today = date( 'Y-m-d' );
    $upcoming = new WP_Query( array(
      'post_type'      => 'fair',
      'posts_per_page' => -1,
      'meta_query'     => array(
        array( 'key' => 'fair_date', 'value' => $today, 'compare' => '>=', 'type' => 'DATE' ),
      ),
      'orderby' => 'meta_value',
      'meta_key' => 'fair_date',
      'order'   => 'ASC',
    ) );

    if ( $upcoming->have_posts() ) :
      while ( $upcoming->have_posts() ) : $upcoming->the_post();
        $date     = get_post_meta( get_the_ID(), 'fair_date', true );
        $end_date = get_post_meta( get_the_ID(), 'fair_end_date', true );
        $location = get_post_meta( get_the_ID(), 'fair_location', true );
        $city     = get_post_meta( get_the_ID(), 'fair_city', true );
        $stand    = get_post_meta( get_the_ID(), 'fair_stand', true );
        $time     = get_post_meta( get_the_ID(), 'fair_time', true );
        $day      = $date ? date( 'd', strtotime( $date ) ) : '';
        $month    = $date ? date( 'M Y', strtotime( $date ) ) : '';
    ?>
      <article class="fair-card">
        <div class="fair-date">
          <span class="day"><?php echo esc_html( $day ); ?></span>
          <span class="month"><?php echo esc_html( $month ); ?></span>
        </div>
        <div class="fair-info">
          <p class="meta"><?php echo esc_html( $city ); ?> · <span class="badge"><?php esc_html_e( 'Próxima', 'tinta-brava' ); ?></span></p>
          <h3><?php the_title(); ?></h3>
          <?php if ( has_excerpt() ) : ?><p><?php echo get_the_excerpt(); ?></p><?php endif; ?>
          <p>
            <?php if ( $location ) : ?><strong><?php esc_html_e( 'Lugar:', 'tinta-brava' ); ?></strong> <?php echo esc_html( $location . ( $city ? ', ' . $city : '' ) ); ?><br><?php endif; ?>
            <?php if ( $stand ) : ?><strong><?php esc_html_e( 'Stand:', 'tinta-brava' ); ?></strong> <?php echo esc_html( $stand ); ?><br><?php endif; ?>
            <?php if ( $time ) : ?><strong><?php esc_html_e( 'Horario:', 'tinta-brava' ); ?></strong> <?php echo esc_html( $time ); ?><?php endif; ?>
          </p>
          <a class="btn btn-whatsapp" href="<?php echo esc_url( tinta_brava_whatsapp_url( 'Hola, me reservas un kit para ' . get_the_title() ) ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Reservar kit para recoger', 'tinta-brava' ); ?></a>
        </div>
      </article>
    <?php endwhile; wp_reset_postdata();
    else :
      echo '<p>' . esc_html__( 'No hay ferias próximas en este momento. Síguenos en Instagram para enterarte de la próxima.', 'tinta-brava' ) . '</p>';
    endif;
    ?>

    <div class="fair-map" aria-label="Mapa de ferias en Bogotá">
      <p><?php esc_html_e( 'Mapa de ferias en Bogotá (insertar embed de Google Maps aquí)', 'tinta-brava' ); ?></p>
    </div>

    <h2 style="margin-top: 3rem;"><?php esc_html_e( 'Ferias pasadas', 'tinta-brava' ); ?></h2>
    <p style="color: var(--color-muted); margin-bottom: 2rem;"><?php esc_html_e( 'Algunas de las ferias donde ya estuvimos.', 'tinta-brava' ); ?></p>

    <div class="grid grid-3">
      <?php
      $past = new WP_Query( array(
        'post_type'      => 'fair',
        'posts_per_page' => 6,
        'meta_query'     => array(
          array( 'key' => 'fair_date', 'value' => $today, 'compare' => '<', 'type' => 'DATE' ),
        ),
        'orderby' => 'meta_value',
        'meta_key' => 'fair_date',
        'order'   => 'DESC',
      ) );
      if ( $past->have_posts() ) :
        while ( $past->have_posts() ) : $past->the_post();
          $date = get_post_meta( get_the_ID(), 'fair_date', true );
      ?>
        <div class="card">
          <div class="card-img"><?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'tinta-brava-card' ); endif; ?></div>
          <div class="card-body">
            <p class="meta"><?php echo $date ? esc_html( date( 'M Y', strtotime( $date ) ) ) : ''; ?></p>
            <h3><?php the_title(); ?></h3>
            <p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 16 ) ); ?></p>
          </div>
        </div>
      <?php endwhile; wp_reset_postdata(); endif; ?>
    </div>

    <div class="section section-cta" style="margin-top: 4rem;">
      <div class="cta-inner">
        <h2><?php esc_html_e( '¿Quieres que vayamos a tu feria?', 'tinta-brava' ); ?></h2>
        <p><?php esc_html_e( 'Si organizas una feria de diseño, ilustración o arte en Bogotá y crees que encajamos, escríbenos. Llevamos el taller a donde nos necesiten.', 'tinta-brava' ); ?></p>
        <a class="btn btn-primary btn-lg" href="<?php echo esc_url( tinta_brava_whatsapp_url( 'Hola, organizo una feria y me gustaría que Tinta Brava estuviera' ) ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Invitarnos a una feria', 'tinta-brava' ); ?></a>
      </div>
    </div>
  </div>
</section>

<?php get_footer(); ?>
