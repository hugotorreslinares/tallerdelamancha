# TODO — Diseño (auditoría vs. STYLEGUIDE.md)

Estado: `[ ]` pendiente · `[x]` resuelto

Referencia: [STYLEGUIDE.md](STYLEGUIDE.md). Cada fix abajo cita la regla que viola.

---

## Alta prioridad (bugs reales de UX)

### [x] 1. Links del menú no dan feedback en hover
- **Archivo:** `assets/css/base.css:136`
- **Bug:** `.primary-nav a:hover { color: var(--color-ink); }` — mismo color que el estado normal. La regla global (`a:hover` → ochre) queda anulada. El menú principal no muestra ningún cambio visual al pasar el mouse.
- **Fix:** cambiar a `color: var(--color-ochre)` (o quitar la regla y dejar que herede la global).

### [ ] 2. Header se vuelve MÁS transparente al hacer scroll
- **Archivo:** `assets/css/base.css:286-291`
- **Bug:** `.site-header` normal usa `color-mix(in srgb, var(--color-paper) 92%, transparent)`. Al scrollear, `.is-scrolled` baja a `color-mix(in srgb, var(--color-paper-2) 24%, transparent)` — pasa de 92% opaco a 24% opaco justo cuando hay contenido debajo para tapar. Se ve el texto de la página atravesando el header.
- **Fix:** subir el % de opacidad en `.is-scrolled` (ej. 90-95%), no bajarlo.

### [x] 3. "Sobre el taller" no tiene plantilla propia — CSS muerto
- **Fix aplicado:** `templates/sobre-el-taller.php` — usa `.about-hero` (título + extracto), `.about-grid` (foto destacada de la página + `the_content()`) y `.values`/`.value-item` (3 valores fijos, copy nueva).
- **Pendiente de tu lado:** en wp-admin, editar la página "Sobre el taller" → Atributos de página → Plantilla → elegir "Sobre el taller". Si la página tiene imagen destacada, se usa como foto; si no, queda el degradado decorativo existente.

### [ ] 4. Contacto: estilos inline en vez de clases/tokens
- **Archivo:** `templates/contacto.php`
- **Bug:** 3 círculos de ícono (WhatsApp/Instagram/Correo) repiten el mismo `style="width:40px;height:40px;border-radius:50%;..."` a mano — mismo patrón, cero clase reutilizable. El cuadro de "¡Gracias!" (`.callout`) es 100% inline, sin clase — no tiene variante para modo oscuro y no sigue la escala de espaciado (`padding: 1rem 1.5rem` en vez de `--space-4 --space-5`).
- **Fix:** extraer `.contact-icon` y `.callout` a `components.css`, documentarlas en STYLEGUIDE §5.

---

## Media prioridad (consistencia de tokens)

### [ ] 5. Color `rgba(244,239,230,X)` hardcodeado en vez de token
- **Archivos:** `base.css` (footer-tag, footer-links, footer-bottom, footer-legal — 6 veces), `pages.css` (`.about-hero .lead`, `.feature p`)
- **Problema:** es `--color-fixed-light` con opacidad, escrito a mano cada vez. Viola regla 1 de STYLEGUIDE ("todo color sale de un token").
- **Fix:** agregar `--color-fixed-light-muted` (o usar `color-mix(in srgb, var(--color-fixed-light) 80%, transparent)`) como token único.

### [ ] 6. Tamaños de fuente decorativos fuera de la escala `--fs-*`
- **Archivo:** `assets/css/pages.css`
- **Casos:** `4rem` (`.hero-photo-2`), `6rem` (`.fair-photo::after`), `3rem` (`.value-num`, `.fair-date .day`), `2.5rem` (`.about-photo::after`), `2rem` (`.gallery-main::after`), `1.2rem` (`.kit-includes::before`)
- **Fix:** son decorativos/grandes, no necesitan encajar en `--fs-*` textual — pero si se van a usar en más de un lugar, agregar un token `--fs-display-sm` o similar en vez de repetir valores sueltos.

### [ ] 7. `letter-spacing` sin escala — 4 valores distintos sin patrón
- **Archivo:** varios (`components.css` badge `0.08em`, `pages.css` price-label `0.1em`, `base.css` eyebrow/footer h4 `0.12em`, `pages.css` fair-date month `0.15em`)
- **Fix:** definir 2-3 tokens (`--ls-tight`, `--ls-wide`) y mapear los usos existentes.

### [ ] 8. CSS muerto: `.hero-photo-2`
- **Archivo:** `assets/css/pages.css:62-74`, usado en `front-page.php:50`
- **Problema:** la regla tiene `display: none` — el bloque nunca se muestra. Es un remanente de una versión anterior del hero (foto secundaria superpuesta).
- **Fix:** confirmar con el usuario si se retoma esa idea (foto secundaria) o se borra el markup + CSS.

### [ ] 9. `.is-scrolled` usa `px` sueltos en vez de `--space-*`
- **Archivo:** `assets/css/base.css:288-289`
- **Fix:** `padding-top: 20px` → el valor más cercano en la escala es `--space-5` (24px) o `--space-4` (16px), definir cuál.

---

## Baja prioridad / limpieza de la guía

### [ ] 10. STYLEGUIDE.md dice `--fs-2xl` sin uso — es falso
- **Archivo:** `STYLEGUIDE.md:68` y `:141`
- `--fs-2xl` sí se usa en `.price` (`pages.css:153`). Solo `--fs-4xl` está realmente sin uso. Corregir la guía.

### [ ] 11. `.product-gallery { top: 100px }` hardcodeado
- **Archivo:** `assets/css/pages.css:274`
- El header mide `72px` (`--header-h` no existe como token). Si cambia la altura del header, este sticky se desalinea.
- **Fix:** agregar `--header-h: 72px` en `tokens.css`, usarlo en `.site-header`-relacionados y en este `top`.

---

## Orden sugerido de ejecución

1 y 2 primero (bugs visibles, arreglo rápido, cero riesgo).
4 y 5 juntos (mismo trabajo: extraer componentes + token de color).
3 es la de más esfuerzo (nueva plantilla) — confirmar antes si "Sobre el taller" vale la pena rediseñar o se deja simple a propósito.
6-9 son pulido, se pueden agrupar en un solo commit.
10-11 triviales.
