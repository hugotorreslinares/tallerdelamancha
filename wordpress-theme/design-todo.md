# Plan — estilos y pendientes (auditoría 2026-10-22)

Estado: `[ ]` pendiente · `[x]` resuelto. Referencia: [STYLEGUIDE.md](STYLEGUIDE.md).

Auditoría hecha en producción (claro, oscuro, móvil 375px) más lectura del CSS/PHP. Sin desbordes horizontales, sin imágenes sin `alt`, un solo `h1` por página, modo oscuro correcto en general.

Ya resuelto de la lista anterior: hover del menú, plantilla "Sobre el taller", paleta nueva, botones `.btn-primary`/`.btn-whatsapp`, `/tutoriales/`, página Insumos, estilos de carrito/checkout.

---

## P0 — rompe algo visible o funcional

### [ ] 1. Texto fantasma sobre las fotos de producto
`.gallery-main::after { content: 'Kit Linograbado' }` (pages.css) se dibuja encima de la foto de **todos** los productos, con opacidad .4. Mismo patrón: `.fair-photo::after 'BADA'`, `.about-photo::after 'Atelier'`.
**Fix:** borrar esos `::after` (eran placeholders de maqueta). Esfuerzo: 10 min.

### [ ] 2. La prosa no tiene estilos
Dentro de `.post-content`/`.post-body` los `p`, `h2`, `h3` tienen `margin: 0` (el reset), así que las entradas del blog, "Sobre el taller" y páginas legales salen con títulos pegados al texto y sin aire entre párrafos. Tampoco hay estilo de `ul/ol`, `blockquote`, `img`, `a`.
**Fix:** bloque "prosa" en pages.css (márgenes por `--space-*`, listas, citas, imágenes a 100%, enlaces subrayados). Esfuerzo: 1 h. Es el arreglo de mayor impacto para el blog.

### [ ] 3. No existe `404.php`
Una URL inexistente cae en `index.php` y muestra "Tutoriales — Aún no hay tutoriales publicados".
**Fix:** `404.php` con mensaje, buscador y enlaces a Kits/Tutoriales. Esfuerzo: 30 min.

### [x] 4. `theme.css` está desfasado de sus fuentes (57 líneas) y hay trabajo sin commitear
Hay reglas que solo existen en el bundle (`.post-content-page/-single`, `.post-hero`, `.author-box`, `.related-*`, `.hero-photo img`), y `page.php`/`single.php`/`theme.css` modificados sin commit (otra sesión). Si alguien regenera el bundle con `cat`, esas reglas desaparecen.
**Fix:** pasar esas reglas a `pages.css`, commitear el trabajo de `page.php`/`single.php`, regenerar el bundle y comprobar con `diff`. Esfuerzo: 30 min. **Hacerlo antes que cualquier otro cambio de CSS.**

### [ ] 5. Header más transparente al hacer scroll
`.site-header.is-scrolled` baja el fondo al 24 % de opacidad justo cuando hay contenido debajo. Además usa `20px/15px` sueltos.
**Fix:** subir a ~92 % y usar `--space-*`. Esfuerzo: 5 min.

### [ ] 6. Checkout: revisar la parte baja con estilos
Resumen del pedido, envío y botón de pago solo se verificaron por DOM, no visualmente (el interruptor se apagó durante la revisión). Ver sección "Tienda" abajo.

---

## P1 — consistencia visual y de marca

### [ ] 7. Bloque raro detrás del texto del hero
Los `radial-gradient` de `.hero` siguen con `rgba(115,135,123)` y `rgba(131,151,136)` (verdes grisáceos de la paleta pastel anterior). Se nota como un panel más claro detrás del texto, en claro y en oscuro.
**Fix:** quitarlos o usar terracota/navy al 5–8 %. 5 min.

### [ ] 8. Íconos de categoría débiles
Los SVG de `.category-block` son pequeños y de línea fina dentro de bloques de ~380 px; en la referencia ocupan mucho más y son más gruesos.
**Fix:** agrandar y engrosar, y usar la imagen de la categoría de WooCommerce (`thumbnail_id`) si existe, con el SVG como respaldo. 1 h.

### [ ] 9. Rebrand "Tinta Brava" → "Taller de la mancha"
Aún aparece en: mensajes de WhatsApp (header, portada, ferias, shortcodes), asunto del correo de contacto (`functions.php`), metabox "Detalles Tinta Brava" y el texto de la página "Sobre el taller" (se edita en wp-admin). **No renombrar** carpeta, `Theme Name` ni text-domain (rompería traducciones y la instalación). 30 min.

### [ ] 10. "Sobre el taller"
Rectángulo oscuro enorme a la izquierda (no hay imagen destacada en la página) y el `h1` pegado al párrafo del hero.
**Tuyo:** subir imagen destacada a la página. **Código:** margen del `h1` del hero. 10 min.

### [ ] 11. 33 estilos inline en 10 archivos PHP
`contacto.php` (8), `single-product.php` (6), `functions.php` (6), `catalogo-kits.php` (3), `archive-fair.php` (3), etc. `.callout` no tiene CSS. Lo hace imposible de cambiar desde la guía.
**Fix:** pasar a clases (`.contact-icon`, `.callout`, `.cta-row`…). 1,5 h.

### [ ] 12. Colores y valores sueltos
`rgba(241,232,214,x)` ×7 (footer, `.feature p`, `.about-hero .lead`) → token `--color-fixed-light-muted` o `color-mix`. `rgba(26,26,26)` en foco de formularios. Letter-spacing con 4 valores sin escala. `top:100px` del sticky de la galería ligado a la altura del header. 45 min.

### [ ] 13. Placeholders sin contenido real
`/ferias/` muestra un bloque gris-azul vacío (`.fair-map`). Decidir: mapa real, imagen o quitarlo.

---

## P2 — accesibilidad, móvil y limpieza

### [ ] 14. Objetivos táctiles pequeños en móvil
Enlaces del footer de ~17 px de alto, botón de tema de 36 px. Mínimo 44 px.

### [ ] 15. Footer en móvil
La marca queda apretada en 2 columnas ("Taller de / la"). Pasarla a ancho completo sobre las columnas de enlaces.

### [ ] 16. Contraste del eyebrow en oscuro
Terracota sobre fondo oscuro ≈ 3,6:1. Aclarar el acento en el tema oscuro para llegar a 4,5:1.

### [ ] 17. CSS muerto y tokens sin uso
`.hero-photo-2` (`display:none`), `.category .card-img`, `--fs-4xl`.

### [ ] 18. STYLEGUIDE.md desactualizado
Falta: bundle + `woocommerce.css` aparte, `--fs-2xl` sí se usa (en `.price`), plantillas Tutoriales/Insumos/404, regla "nada de texto en `::after`".

---

## Auditoría con el checklist de ui-styling (accesibilidad, tokens, mobile-first)

El skill `ui-styling` es para React + shadcn + Tailwind; aquí no se instala (obligaría a reescribir el tema). Se aplicó su checklist al CSS actual. Contrastes medidos en producción (WCAG, modo claro).

### [x] 19. P0 — Botones de WhatsApp con contraste 1,98:1
`.btn-whatsapp` usa texto blanco sobre `#25D366`: falla WCAG AA (mínimo 4,5:1) en la ficha, el destacado y el CTA final. Además el del header usa texto oscuro (8,28:1) porque `.primary-nav a` (más específico) le pisa el color: dos botones iguales se ven distintos.
**Resuelto (8,28:1 medido en producción).** Fix aplicado: texto oscuro en `.btn-whatsapp`; el ícono flotante conserva el blanco de marca.
**Fix original:** texto `--color-ink` en `.btn-whatsapp` y en `.btn-whatsapp:hover`, y subir su especificidad en el header. 10 min.

### [x] 20. P1 — Sin indicador de foco propio
No hay `:focus-visible` global: enlaces, botones, tarjetas y el botón de tema usan el contorno por defecto del navegador. En los campos de formulario se quita el contorno (`outline: none`) y el reemplazo es un halo al 8 % de opacidad, casi invisible.
**Fix:** `:focus-visible { outline: 2px solid var(--color-ink); outline-offset: 2px }` global y halo real en campos (también en `woocommerce.css`). 15 min.

### [x] 21. P1 — Menú móvil sin teclado
El panel cerrado queda fuera de pantalla con `transform` pero con `visibility: visible`, así que sus enlaces siguen en el orden de tabulación. Tampoco cierra con Escape ni devuelve el foco al botón.
**Fix:** `visibility: hidden` cuando no está abierto, Escape en `main.js` y devolver el foco. 20 min.

### [x] 22. P1 — Sin `prefers-reduced-motion`
El botón flotante de WhatsApp pulsa para siempre (`pulse-wa 2s infinite`) y las tarjetas se mueven al pasar el mouse. Para quien pidió menos movimiento no hay alternativa.
**Fix:** bloque `@media (prefers-reduced-motion: reduce)` que apaga animación y transformaciones. 5 min.

### [ ] 23. P2 — Terracota en texto pequeño: 4,18:1
Eyebrows, `.btn-primary` y nombres de categoría en 14–18 px quedan por debajo de 4,5:1. `#B33F29` da ≈4,7:1 (calculado) y se ve casi igual. Cambiar `--color-terracotta` y volver a medir; en oscuro el eyebrow sigue en ≈3,6:1.

### [ ] 24. P2 — CSS desktop-first con 6 breakpoints sueltos
13 `max-width` y ningún `min-width`; valores 560/600/700/768/800/900 sin escala. Unificar a 2 o 3 puntos (por ejemplo 600 y 900) como tokens documentados. Riesgo medio: probar todas las páginas después.

### [ ] 25. P2 — Tokens que faltan para cerrar el sistema
Escala de `letter-spacing`, altura del header (`--header-h`, hoy 72 px repetido en 3 sitios), `--color-fixed-light-muted` y tamaño mínimo táctil (`--tap-min: 44px`). Es la base para cerrar los ítems 12, 14 y 15.

---

## Tienda en línea (no es estilo, pero queda pendiente)

- [ ] Reactivar Personalizar → Tienda → "Activar compra en línea" (está **apagado**).
- [ ] Registrar la URL de eventos `https://tallerdelamancha.com/?wc-api=wc_wompi` en el panel de Wompi (Sandbox).
- [ ] Compra de prueba: dentro de la zona (envío $0) y fuera (envío $15.000), con tarjeta sandbox.
- [ ] Confirmar que el pedido pasa a "Procesando" (si queda "Pendiente", falta la URL de eventos).
- [ ] Producción: llaves reales, desmarcar modo de prueba, URL de eventos en Producción.
- [ ] Insumos: faltan precios (costo × 1,35) en los borradores 200–207; publicar la página Insumos (id 209) y añadirla al menú.

---

## Orden sugerido

1. **Base** (ítems 4, 1, 5, 7, 19): consolidar el bundle, quitar lo que ensucia y arreglar el contraste de WhatsApp. ~1,5 h.
2. **Contenido** (ítems 2, 3, 10): prosa, 404, "Sobre el taller". ~2 h.
3. **Marca** (ítems 9, 8): rebrand y categorías. ~1,5 h.
4. **Limpieza** (ítems 11, 12, 13, 17): inline y tokens. ~3 h.
5. **Accesibilidad y móvil** (ítems 20, 21, 22, 23, 14, 15, 16, 24, 25) y **docs** (18). ~3 h.
6. **Tienda**: cuando retomes, con el checklist de arriba.
