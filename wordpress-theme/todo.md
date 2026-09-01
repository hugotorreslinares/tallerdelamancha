# TODO — Revisión de estándares (tema Tinta Brava)

Estado: `[ ]` pendiente · `[x]` resuelto · `[~]` en progreso

---

## Crítico

### [x] 1. `woocommerce_init` borra páginas cart/checkout/my-account en cada request
- **Archivo:** `inc/setup.php:36-64`
- **Problema:** `tinta_brava_woocommerce_defaults()` engancha a `woocommerce_init` (dispara en cada carga de página), no solo en instalación. Fuerza opciones de moneda y borra páginas WooCommerce permanentemente si existen.
- **Resuelto:** Guard con `get_option( 'tinta_brava_wc_defaults_done' )` — corre una sola vez, marca la opción al terminar. Añadido también chequeo `class_exists( 'WooCommerce' )` por seguridad.

---

## Alto

### [x] 2. `get_terms()` sin manejo de `WP_Error`
- **Archivo:** `front-page.php:67-91`
- **Problema:** Si `product_cat` no existe (WooCommerce inactivo), `count($categories)` sobre `WP_Error` es fatal en PHP8.
- **Resuelto:** Guard `! is_wp_error( $categories ) && ! empty( $categories )` envolviendo todo el bloque `<div class="grid">`.

### [x] 3. Query N+1 en loop de categorías
- **Archivo:** `front-page.php:77`
- **Problema:** `get_term_by('slug', $category->slug, ...)` es redundante, `$category` ya trae `term_id`.
- **Resuelto:** Reemplazado por `get_term_meta( $category->term_id, 'thumbnail_id', true )` directo.

---

## Medio

### [x] 4. Logo roto puede causar warning
- **Archivo:** `header.php:27-33`
- **Problema:** `wp_get_attachment_image_src()` puede devolver `false`; acceso a `$image[0]` sin chequear.
- **Resuelto:** `$image` se calcula siempre (false si no hay logo o falla), `if ( $image )` controla el bloque, fallback al mark por defecto si falla.

### [x] 5. `alt=""` fijo en imágenes de categoría
- **Archivo:** `front-page.php:81`
- **Problema:** Imagen con contenido real marcada como decorativa, mal para SEO/accesibilidad.
- **Resuelto:** `alt="<?php echo esc_attr( $category->name ); ?>"` (arreglado junto con #2/#3, mismo bloque).

### [x] 6. Typo en título por defecto
- **Archivo:** `inc/setup.php:19`, `front-page.php:23`
- **Problema:** `'2Empieza a estampar...'` — sobraba el "2".
- **Resuelto:** Corregido en ambos archivos (default de `get_theme_mod` y seed del customizer).

### [x] 7. i18n inconsistente
- **Archivo:** `front-page.php` (línea "Ver todas las ferias")
- **Problema:** String suelto sin `esc_html_e()`.
- **Resuelto:** Envuelto en `esc_html_e( 'Ver todas las ferias', 'tinta-brava' )`.

---

## Limpieza

### [x] 8. Bloque HTML comentado (feria hardcoded)
- **Archivo:** `front-page.php` (bloque tras el `endif;` de próxima feria)
- **Resuelto:** Eliminado, ya no se usa (reemplazado por query dinámica de ferias).

### [x] 9. Comentario de debug con fecha/hora
- **Archivo:** `front-page.php` (`<!-- VERSION 2026-07-10 11:42 -->`)
- **Resuelto:** Eliminado.

### [x] 10. Indentación mezclada (tabs/espacios)
- **Archivos:** `functions.php`, `inc/setup.php`, `front-page.php`
- **Resuelto:** Normalizado a 2 espacios en el bloque del customizer (`tinta_brava_customize_register`), que estaba pegado sin indentar. De paso se envolvieron en `__()` los labels que faltaban traducción (`Título principal`, `Descripción`, textos de botones, `Hero inicio`, `Imagen Hero 1/2`).

---

## Opcional / a evaluar

### [ ] 11. Google Fonts vía CDN externo
- **Archivo:** `inc/enqueue.php:48-53`
- **Problema:** Carga desde `fonts.googleapis.com`, tema GDPR si hay tráfico UE.
- **Plan:** Evaluar con el usuario si aplica (mercado es Colombia); si se decide self-host, descargar fuentes a `assets/fonts/` y actualizar `@font-face`.
