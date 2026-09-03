# TODO — Optimización de velocidad

Estado: `[ ]` pendiente · `[x]` resuelto · `[~]` en progreso

**Diagnóstico de base:** TTFB (tiempo hasta el primer byte) de ~1.5-2.4 segundos en TODAS las páginas, sin caché de página completa funcionando (`x-hcdn-cache-status: DYNAMIC` incluso en visitas repetidas). Antes de esta sesión sí se veía `x-litespeed-cache: hit`. Esto pesa mucho más que cualquier optimización de imágenes o CSS — es la prioridad #1.

---

## Código (resuelvo yo)

### [x] 1. Imágenes de categoría en tamaño 'large' (deberían ser 'tinta-brava-card')
- **Archivo:** `front-page.php`
- **Problema:** `wp_get_attachment_image_url( $thumbnail_id, 'large' )` pedía el tamaño 'large' (1024px) para imágenes que se muestran en cards chicas. `carving-lonoleum.jpg` pesaba 101KB, `serigrafia.jpg` 130KB.
- **Resuelto:** Cambiado a `'tinta-brava-card'` (600x450, ya registrado y usado en otros lados).

### [x] 2. Logo cargado en tamaño 'full' (original)
- **Archivos:** `header.php`, `footer.php`
- **Problema:** `wp_get_attachment_image_src( ..., 'full' )` — el logo pesaba 74KB, usado 2 veces. Mostrado a 32-40px no necesita el original completo.
- **Resuelto:** Tamaño `'thumbnail'` en ambos. De paso, footer.php no chequeaba si `$image` existía antes de usar `$image[0]` (mismo bug que ya habíamos arreglado en header.php) — corregido también.

### [x] 3. 6 archivos CSS del theme por separado (6 requests)
- **Archivo:** `inc/enqueue.php`, nuevo `assets/css/theme.css`
- **Resuelto:** reset+tokens+base+components+pages combinados en un solo `theme.css` (bundle generado, ver `assets/css/README-build.txt` para regenerarlo tras editar cualquiera de los fuente). fonts.css queda aparte por las rutas relativas de los @font-face. De 6 requests CSS del theme a 2.

### [~] 4. Queries redundantes en el home sin caché
- **Descartado por ahora:** con el catálogo actual (5 productos, pocas ferias/posts) estas queries son triviales para MySQL — cachearlas con transients metería complejidad y riesgo de bugs por una ganancia marginal. Si el catálogo crece mucho, reconsiderar.

### [x] 5. Fuente del hero sin preload
- **Archivo:** `header.php`
- **Resuelto:** `<link rel="preload" as="font">` para IM Fell English, solo en portada (`is_front_page()`).

### [x] 6. Imagen principal del hero sin prioridad de carga
- **Archivo:** `header.php`
- **Nota:** las imágenes del hero se pintan como `background-image` en CSS, no `<img>` — `fetchpriority` no aplica ahí. En su lugar se agregó `<link rel="preload" as="image">` para la primera imagen del hero (la más prominente), solo en portada.

---

## Depende de vos (wp-admin / hosting)

### [ ] A. Por qué dejó de cachear la página completa
- **Qué revisar:** LiteSpeed Cache (plugin) → confirmar que sigue activo y no está excluyendo el home u otras páginas. Es común que WooCommerce (carrito, cookies de sesión) fuerce "no cache" en ciertas condiciones — revisar Ajustes de LiteSpeed Cache → pestaña "Excluye" / "WooCommerce".
- **Impacto:** Es la optimización de mayor impacto de toda la lista — arreglar esto solo probablemente baje el tiempo de carga de ~2.5s a unos cientos de ms para visitantes recurrentes.

### [ ] B. Evaluar plugins pesados que corren en cada página
- **Qué encontré:** Jetpack (stats + WooCommerce Analytics), Google Site Kit, Hostinger Reach (abandoned carts + subscription blocks), WooCommerce Sourcebuster/Order Attribution — todos cargan JS y probablemente lógica en el backend en cada visita.
- **Qué revisar:** ¿Los usás todos activamente? Si no, desactivar los que no aportan (ej. si no mandás campañas de "carrito abandonado", Hostinger Reach no suma nada, solo pesa).

### [ ] C. Object Cache persistente (Redis/Memcached)
- **Qué revisar:** En hPanel, ver si tu plan de hosting incluye Redis u Object Cache persistente activable. Sin esto, cada carga de página recalcula todo desde MySQL de cero.

### [ ] D. Optimizar imágenes originales en la Biblioteca de medios
- **Qué encontré:** El logo (74KB) y varias fotos de producto/categoría (100-130KB) son pesadas incluso antes de cualquier redimensión.
- **Qué revisar:** Instalar un plugin de optimización de imágenes (ShortPixel, Imagify, o el que traiga LiteSpeed Cache) para comprimir/convertir a WebP automáticamente. Yo puedo arreglar el TAMAÑO que se pide en el código (tareas 1-2), pero el archivo original seguirá pesando lo mismo en la Biblioteca de medios.

### [ ] E. Revisar si el plan de hosting da abasto
- Si después de arreglar caché + plugins el TTFB sigue por encima de 500ms-1s, puede ser límite de recursos del plan compartido de Hostinger — vale la pena revisar con soporte de Hostinger o considerar upgrade.
