<?php
/**
 * Template Name: Contacto
 *
 * @package TintaBrava
 */
get_header();
?>

<section class="page-header">
  <div class="container">
    <p class="breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Inicio', 'tinta-brava' ); ?></a> / <?php the_title(); ?></p>
    <h1><?php the_title(); ?></h1>
    <?php if ( has_excerpt() ) : ?><p><?php echo get_the_excerpt(); ?></p><?php endif; ?>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="contact-grid">
      <div class="contact-info">
        <h3><?php esc_html_e( 'Canales directos', 'tinta-brava' ); ?></h3>
        <p style="color: var(--color-muted);"><?php esc_html_e( 'Si quieres un kit, resolver una duda, invitarnos a una feria o pedir un kit personalizado, escríbenos por aquí:', 'tinta-brava' ); ?></p>
        <div class="contact-channels">
          <a class="contact-channel" href="<?php echo esc_url( tinta_brava_whatsapp_url() ); ?>" target="_blank" rel="noopener">
            <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--color-whatsapp); display: grid; place-items: center; color: white; flex-shrink: 0;">✉</div>
            <div>
              <strong>WhatsApp</strong>
              <span>+<?php echo esc_html( tinta_brava_phone_link() ); ?> · <?php esc_html_e( 'Respuesta en horas', 'tinta-brava' ); ?></span>
            </div>
          </a>
          <a class="contact-channel" href="<?php echo esc_url( tinta_brava_instagram_url() ); ?>" target="_blank" rel="noopener">
            <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #F58529, #DD2A7B, #8134AF); display: grid; place-items: center; color: white; flex-shrink: 0;">◉</div>
            <div>
              <strong>Instagram</strong>
              <span>@<?php echo esc_html( get_theme_mod( 'tinta_brava_instagram', 'eltallerdelamancha' ) ); ?> · <?php esc_html_e( 'DM abiertas', 'tinta-brava' ); ?></span>
            </div>
          </a>
          <a class="contact-channel" href="<?php echo esc_url( tinta_brava_email_link() ); ?>">
            <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--color-ink); display: grid; place-items: center; color: var(--color-paper); flex-shrink: 0;">@</div>
            <div>
              <strong><?php esc_html_e( 'Correo', 'tinta-brava' ); ?></strong>
              <span><?php echo esc_html( get_theme_mod( 'tinta_brava_email', 'contacto@tallerdelamancha.com' ) ); ?></span>
            </div>
          </a>
          <!-- <div class="contact-channel">
            <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--color-moss); display: grid; place-items: center; color: var(--color-paper); flex-shrink: 0;">◐</div>
            <div>
              <strong><?php esc_html_e( 'Taller físico', 'tinta-brava' ); ?></strong>
              <span>Chapinero, Bogotá · <?php esc_html_e( 'Con cita previa', 'tinta-brava' ); ?></span>
            </div>
          </div> -->
        </div>
      </div>

      <div>
        <h3><?php esc_html_e( 'O escríbenos aquí', 'tinta-brava' ); ?></h3>
        <p style="color: var(--color-muted); margin-bottom: 1.5rem;"><?php esc_html_e( 'Te respondemos por correo. Si prefieres WhatsApp, usa el botón verde de la esquina.', 'tinta-brava' ); ?></p>

        <?php if ( isset( $_GET['contacto'] ) && $_GET['contacto'] === 'ok' ) : ?>
          <div class="callout" style="background: var(--color-moss-soft); border-left-color: var(--color-moss); padding: 1rem 1.5rem; border-radius: 0 var(--radius-md) var(--radius-md) 0; margin: 0 0 1.5rem 0;">
            <strong><?php esc_html_e( '¡Gracias!', 'tinta-brava' ); ?></strong> <?php esc_html_e( 'Tu mensaje ha sido enviado. Te respondemos en menos de 24 horas.', 'tinta-brava' ); ?>
          </div>
        <?php endif; ?>

        <form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
          <input type="hidden" name="action" value="tinta_brava_contact">
          <?php wp_nonce_field( 'tinta_brava_contact', 'tinta_brava_contact_nonce' ); ?>
          <div class="form-group">
            <label class="form-label" for="name"><?php esc_html_e( 'Nombre', 'tinta-brava' ); ?></label>
            <input class="form-input" type="text" id="name" name="name" required />
          </div>
          <div class="form-group">
            <label class="form-label" for="email"><?php esc_html_e( 'Correo electrónico', 'tinta-brava' ); ?></label>
            <input class="form-input" type="email" id="email" name="email" required />
          </div>
          <div class="form-group">
            <label class="form-label" for="subject"><?php esc_html_e( 'Asunto', 'tinta-brava' ); ?></label>
            <select class="form-select" id="subject" name="subject">
              <option><?php esc_html_e( 'Quiero pedir un kit', 'tinta-brava' ); ?></option>
              <option><?php esc_html_e( 'Duda sobre un kit', 'tinta-brava' ); ?></option>
              <option><?php esc_html_e( 'Invitar a una feria', 'tinta-brava' ); ?></option>
              <option><?php esc_html_e( 'Taller personalizado', 'tinta-brava' ); ?></option>
              <option><?php esc_html_e( 'Otro', 'tinta-brava' ); ?></option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label" for="message"><?php esc_html_e( 'Mensaje', 'tinta-brava' ); ?></label>
            <textarea class="form-textarea" id="message" name="message" required></textarea>
          </div>
          <button type="submit" class="btn btn-primary btn-lg" style="width: 100%;"><?php esc_html_e( 'Enviar mensaje', 'tinta-brava' ); ?></button>
        </form>
      </div>
    </div>
  </div>
</section>

<?php get_footer(); ?>
