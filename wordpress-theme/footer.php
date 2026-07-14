<?php
/**
 * Footer principal
 *
 * @package TintaBrava
 */
?>
</main>

<footer class="site-footer">
  <div class="container footer-grid">
    <div>
       <?php if ( has_custom_logo() ) :  
      $image = wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' );
      ?>
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="brand brand-footer">
        <span class="brand-mark custom-logo" aria-hidden="true">
          <img src="<?php echo esc_url( $image[0] ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" />
       </span>
        <span class="brand-name"><?php bloginfo( 'name' ); ?></span>
      </a>
      <?php else : ?>   
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="brand brand-footer">
        <span class="brand-mark" aria-hidden="true">◐</span>
        <span class="brand-name"><?php bloginfo( 'name' ); ?></span>
      </a>
         <?php endif; ?>
      <p class="footer-tag"><?php esc_html_e( 'Kits de iniciación en linograbado y serigrafía. Bogotá, Colombia.', 'tinta-brava' ); ?></p>
    </div>
    <div>
      <h4><?php esc_html_e( 'Catálogo', 'tinta-brava' ); ?></h4>
      <ul class="footer-links">
        <li><a href="<?php echo esc_url( home_url( '/kits/#linograbado' ) ); ?>"><?php esc_html_e( 'Linograbado', 'tinta-brava' ); ?></a></li>
        <li><a href="<?php echo esc_url( home_url( '/kits/#serigrafia' ) ); ?>"><?php esc_html_e( 'Serigrafía', 'tinta-brava' ); ?></a></li>
        <li><a href="<?php echo esc_url( home_url( '/kits/#litografia' ) ); ?>"><?php esc_html_e( 'Litografía', 'tinta-brava' ); ?></a></li>
      </ul>
    </div>
    <div>
      <h4><?php esc_html_e( 'Aprende', 'tinta-brava' ); ?></h4>
      <ul class="footer-links">
        <li><a href="<?php echo esc_url( home_url( '/tutoriales/' ) ); ?>"><?php esc_html_e( 'Tutoriales', 'tinta-brava' ); ?></a></li>
        <li><a href="<?php echo esc_url( home_url( '/ferias/' ) ); ?>"><?php esc_html_e( 'Ferias', 'tinta-brava' ); ?></a></li>
        <li><a href="<?php echo esc_url( home_url( '/sobre-el-taller/' ) ); ?>"><?php esc_html_e( 'El taller', 'tinta-brava' ); ?></a></li>
      </ul>
    </div>
    <div>
      <h4><?php esc_html_e( 'Contacto', 'tinta-brava' ); ?></h4>
      <ul class="footer-links">
        <li><a href="<?php echo esc_url( tinta_brava_whatsapp_url() ); ?>" target="_blank" rel="noopener">WhatsApp</a></li>
        <li><a href="<?php echo esc_url( tinta_brava_instagram_url() ); ?>" target="_blank" rel="noopener">Instagram</a></li>
        <li><a href="<?php echo esc_url( tinta_brava_email_link() ); ?>"><?php echo esc_html( get_theme_mod( 'tinta_brava_email', 'hola@tintabrava.co' ) ); ?></a></li>
        <li>Bogotá, Colombia</li>
      </ul>
    </div>
  </div>
  <div class="container footer-bottom">
    <p>© <?php echo date( 'Y' ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'Hecho con tinta y paciencia en Bogotá.', 'tinta-brava' ); ?></p>
    <p class="footer-legal">
      <a href="<?php echo esc_url( home_url( '/terminos/' ) ); ?>"><?php esc_html_e( 'Términos', 'tinta-brava' ); ?></a> ·
      <a href="<?php echo esc_url( home_url( '/privacidad/' ) ); ?>"><?php esc_html_e( 'Privacidad', 'tinta-brava' ); ?></a> ·
      <a href="<?php echo esc_url( home_url( '/devoluciones/' ) ); ?>"><?php esc_html_e( 'Devoluciones', 'tinta-brava' ); ?></a>
    </p>
  </div>
</footer>

<a class="float-whatsapp" href="<?php echo esc_url( tinta_brava_whatsapp_url( 'Hola, me interesa un kit de Tinta Brava' ) ); ?>" target="_blank" rel="noopener" aria-label="WhatsApp">
  <svg viewBox="0 0 32 32" width="28" height="28" aria-hidden="true"><path fill="currentColor" d="M19.11 17.205c-.372 0-1.088 1.39-1.518 1.39a.63.63 0 0 1-.315-.1c-.802-.402-1.504-.817-2.163-1.447-.545-.516-1.146-1.29-1.46-1.963a.426.426 0 0 1-.073-.215c0-.33.99-.945.99-1.49 0-.143-.73-2.09-.832-2.335-.143-.372-.214-.487-.6-.487-.187 0-.36-.043-.53-.043-.302 0-.53.115-.746.315-.688.645-1.032 1.318-1.06 2.264v.114c-.015.99.472 1.977.873 2.78 1.247 2.477 2.81 4.382 5.32 5.629.741.372 2.139.93 2.965.93.873 0 2.749-.358 3.349-1.146.23-.3.372-.645.372-1.004 0-.23-.043-.444-.158-.658-.302-.558-2.18-1.78-2.582-1.78zM16 0C7.16 0 0 7.16 0 16c0 3.36.99 6.51 2.71 9.18L0 32l6.97-2.65A15.9 15.9 0 0 0 16 32c8.84 0 16-7.16 16-16S24.84 0 16 0zm0 29.21c-2.75 0-5.31-.84-7.43-2.27l-.53-.32-4.13 1.57 1.4-4.02-.35-.55A13.21 13.21 0 0 1 2.79 16C2.79 8.69 8.69 2.79 16 2.79S29.21 8.69 29.21 16 23.31 29.21 16 29.21z"/></svg>
</a>

<?php wp_footer(); ?>
</body>
</html>
