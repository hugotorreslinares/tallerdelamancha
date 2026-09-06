# Guía visual — Tinta Brava / Taller de la mancha

Fuente de verdad del sistema de diseño. Todo cambio visual nuevo se hace citando esta guía — si algo no está acá, se agrega acá primero, después se usa en el código.

Los valores reales viven en `assets/css/tokens.css` (variables) y `assets/css/base.css` + `components.css` (aplicación). Esta guía documenta ese sistema, no lo reemplaza — si hay diferencia entre este archivo y el código, el código manda y hay que actualizar la guía.

---

## 1. Color

### Paleta base (modo claro) — "grabado editorial"

Paleta 2026-09: rediseño completo inspirado en un moodboard de tienda de grabado/linograbado (terracota + azul petróleo + mostaza sobre papel crema, ilustración estilo xilografía).

| Token | Valor | Uso |
|---|---|---|
| `--color-terracotta` | `#C1442D` | Color de marca principal — botón primario, eyebrows, bloque de categoría 1, sello, bandas de acento |
| `--color-navy` | `#1F4959` | Color de marca secundario — bloque de categoría 2, bandas "por qué elegirnos", ilustraciones |
| `--color-mustard` | `#8F6A22` | Color de marca terciario — bloque de categoría 3. Deliberadamente oscuro (no un mostaza brillante) para que el texto claro encima cumpla contraste (ver nota de accesibilidad abajo) |
| `--color-ink` | `#221F1A` | Texto principal, íconos lineales |
| `--color-paper` | `#F1E8D6` | Fondo de página (crema cálido) |
| `--color-paper-2` | `#FBF5E9` | Superficies elevadas (cards, secciones alternas) |
| `--color-line` | `color-mix(in srgb, var(--color-ink) 14%, var(--color-paper))` | Bordes — se deriva de ink+paper, así se ajusta solo si cualquiera de los dos cambia |
| `--color-muted` | `#6E6152` | Texto secundario |
| `--color-whatsapp` / `--color-whatsapp-dark` | `#25D366` / `#128C7E` | Exclusivo para CTAs de WhatsApp |

**Alias históricos:** `--color-ochre` = `var(--color-terracotta)` y `--color-moss` = `var(--color-navy)`. El resto del CSS (`.eyebrow`, `.badge`, `.section-fair`, `.section-why`, etc.) sigue usando esos dos nombres — renombrar cada uso habría sido un cambio enorme sin beneficio real, así que quedan como alias. `--color-ochre-soft`/`--color-moss-soft`/`--color-mustard-soft` se derivan con `color-mix(acento, white)` en vez de un hex fijo, para que el "soft" siga combinando si el acento cambia — y porque mezclar contra `--color-paper-2` rompe el contraste en modo oscuro (ese token se vuelve casi negro ahí).

> **Nota de accesibilidad:** los tres acentos se verificaron contra `--color-fixed-light` antes de aplicarse (cálculo de contraste WCAG): terracota 4.06:1, navy 7.8:1, mustard 3.95:1. El mostaza de moodboards de referencia suele ser más claro/brillante — se oscureció a propósito para que el texto crema encima siga siendo legible.

### Modo oscuro

Solo 4 tokens cambian explícitamente: `--color-ink`, `--color-paper`, `--color-paper-2`, `--color-muted` (`--color-line` se recalcula solo, es un `color-mix` de los otros dos). `terracotta`/`navy`/`mustard`/`whatsapp` se quedan **igual en los dos temas** — son colores de marca con buen contraste en ambos fondos, y varios se usan en pares bg+texto autocontenidos que se romperían si cambiaran.

Se activa por `prefers-color-scheme` del sistema o por `data-theme="dark"` explícito (toggle del header, persiste en `localStorage`).

### Par fijo — regla importante

`--color-fixed-dark` (`#221F1A`) y `--color-fixed-light` (`#F1E8D6`) **no cambian nunca**, ni con el tema. Son para bandas de color propio que deben leerse igual sin importar el tema del sitio: el footer, y cualquier sección con fondo `--color-moss` u `--color-ochre` (fondo constante).

> **Regla:** si el fondo de un bloque es un color de marca constante (moss, ochre, o el footer), el texto va en `--color-fixed-light`, **nunca** en `--color-paper`. `--color-paper` cambia con el tema (se vuelve casi negro en modo oscuro) — usarlo ahí deja el texto ilegible. Este bug ya pasó una vez (section-why, about-hero, section-fair, footer) — no repetirlo.
>
> Al revés, si el fondo también es swappable (ej. `--color-ink` como fondo de un badge), ahí sí usar `--color-paper` normal — se invierte junto con el fondo y sigue siendo legible.

### Excepciones aceptadas (no tocar)

- Gradientes decorativos siempre-oscuros (`.fair-photo`, `.about-photo`): `#1A1A1A`/`#2D4A3E` hardcodeados a propósito, no siguen el tema.
- Gradiente de marca de Instagram (`#F58529, #DD2A7B, #8134AF`): color de marca de terceros, no de Tinta Brava.

---

## 2. Tipografía

| Rol | Fuente | Variable |
|---|---|---|
| Titulares (h1-h4) | Fraunces | `var(--font-serif)` |
| Cuerpo, UI, formularios | Inter | `var(--font-sans)` |
| Titular hero (`.display`) | Fraunces 700, redonda | grande, en negrita, no itálica |
| Frase de acento dentro de `.display` (`.accent-italic`) | IM Fell English itálica | solo la última cláusula del titular (después de la última coma) |

Las tres están autoalojadas en `assets/fonts/` (no CDN externo — decisión ya tomada por privacidad/rendimiento, no reabrir).

`.display` ya no es 100% itálica — es Fraunces en negrita normal, y solo el `<span class="accent-italic">` (la frase de cierre) usa IM Fell English. `front-page.php` arma esto solo: parte el `hero_title` en la última coma y envuelve lo que sigue en ese span. Mismo patrón reusado en la sección "Hecho en Bogotá" (`made-in-bogota-title`). No usar IM Fell English fuera de `.accent-italic`; no es una fuente de uso general.

### Escala

```
--fs-xs   0.75rem   (12px)  — labels, meta, badges
--fs-sm   0.875rem  (14px)  — texto secundario, botones
--fs-base 1rem      (16px)  — cuerpo
--fs-md   1.125rem  (18px)  — lead paragraphs
--fs-lg   1.25rem   (20px)  — h4, marca
--fs-xl   1.5rem    (24px)  — h3
--fs-2xl  2rem      (32px)  — (reservado, sin uso actual)
--fs-3xl  2.75rem   (44px)  — h2
--fs-4xl  3.75rem   (60px)  — (reservado, sin uso actual)
--fs-display  clamp(2.5rem, 5vw + 1rem, 4.5rem)  — solo .display
```

### Pesos

- Titulares: 600 (h1-h4 por defecto), 700 solo si se declara `.display` explícitamente (aunque hoy `.display` usa 400 por la itálica IM Fell English — ver nota abajo).
- Cuerpo: 400 normal, 500-600 para énfasis/botones/nav.
- Nunca usar pesos intermedios (450, 550) — la escala es 400/500/600/700.

---

## 3. Espaciado

Escala de 10 pasos, todo en `rem`, sin excepciones a mitad de camino:

```
--space-1  0.25rem (4px)   --space-6  2rem   (32px)
--space-2  0.5rem  (8px)   --space-7  3rem   (48px)
--space-3  0.75rem (12px)  --space-8  4rem   (64px)
--space-4  1rem    (16px)  --space-9  6rem   (96px)
--space-5  1.5rem  (24px)  --space-10 8rem   (128px)
```

**Regla:** cualquier `margin`/`padding`/`gap` nuevo usa uno de estos tokens. No escribir `px` sueltos salvo casos muy puntuales ya aceptados (ver excepciones abajo). Si ningún paso de la escala encaja, es señal de que el layout necesita revisarse, no de agregar un valor suelto.

**Excepción aceptada:** el ancho de columna de lectura (`post-content`) en `page.php` (760px) y `single.php` (720px) — estos dos deberían unificarse a un solo valor (pendiente, ver sección 6).

---

## 4. Layout

- Contenedor: `--container-max: 1200px`, padding lateral fluido `clamp(1rem, 4vw, 2rem)`.
- Radios: `--radius-sm` (4px, inputs chicos) → `--radius-pill` (999px, botones/badges). Cards usan `--radius-lg` (18px), imágenes hero/destacadas `--radius-xl` (28px).
- Sombras: `--shadow-sm/md/lg`, siempre `rgba(26,26,26,X)` — no usar `box-shadow` con otro color base.
- Transiciones: `--t-fast` (150ms, hover de color/fondo), `--t-base` (250ms, transform/layout), `--t-slow` (400ms, animaciones grandes).

---

## 5. Componentes

### Botones

| Clase | Fondo | Texto | Cuándo usarla |
|---|---|---|---|
| `.btn-primary` | `--color-terracotta` | `--color-fixed-light` | CTA principal genérico (ver kit, ver detalles, enviar formulario) |
| `.btn-whatsapp` | `--color-whatsapp` | blanco | CTA que específicamente abre WhatsApp — antes varios botones de WhatsApp usaban `.btn-primary` por error (compartía el verde por accidente); se corrigieron todos a `.btn-whatsapp` al separar los dos colores |
| `.btn-ghost` | transparente, borde ink | `--color-ink` | CTA secundario |
| `.btn-outline` | transparente, borde `--color-line` | `--color-ink` | Terciario, bajo énfasis |

Todos los `.btn` son uppercase, `font-weight: 700`, `letter-spacing: 0.06em`, radio `--radius-sm` (ya no pill) — coinciden con el estilo "sello/tipografía de imprenta" del resto del sitio.

`.btn-lg` como modificador de tamaño (hero, CTAs grandes), no crear una clase nueva por página.

### Cards

`.card` = fondo `--color-paper-2`, borde `--color-line`, radio `--radius-lg`, sombra `--shadow-lg` en hover con `translateY(-4px)`. `.card-img` con `aspect-ratio: 4/3` y fondo placeholder (`--color-ochre-soft` por defecto, `--color-moss-soft` en `.category`).

### Badges

`.badge` = texto `--fs-xs` uppercase, `letter-spacing: 0.08em`, fondo `--color-ochre-soft` + texto `--color-ochre`, pill. Es el único patrón de badge — no crear variantes de color sin agregarlas acá primero.

### Eyebrow (label sobre títulos)

`.eyebrow` = `--font-sans`, `--fs-sm`, 600, uppercase, `letter-spacing: 0.12em`, color `--color-ochre`.

### Bloques de categoría (`.category-block`)

Bloque cuadrado de color sólido (rotan `--terracotta`/`--navy`/`--mustard` por índice, `tinta_brava_category_accent()` en `inc/helpers.php`) con un ícono lineal centrado (`tinta_brava_category_icon_svg( $slug )`, SVG inline `currentColor`), nombre uppercase y flecha. Reemplaza el patrón anterior de foto+card para las categorías de producto en portada. Íconos dedicados para `linograbado`/`serigrafia`/`litografia`; cualquier otro slug cae en un ícono genérico (círculo con flecha).

### Ilustraciones lineales (`inc/helpers.php`)

Tres funciones devuelven SVG inline en `currentColor` para mantener el lenguaje visual "grabado":
- `tinta_brava_leaf_branch_svg()` — rama decorativa (sección destacados).
- `tinta_brava_bogota_skyline_svg()` — cerros + skyline (sección "Hecho en Bogotá").
- `tinta_brava_bogota_stamp_svg()` — sello circular "BOGOTÁ · COLOMBIA" (misma sección).

Son decorativas (`aria-hidden="true"`), sin restricción de contraste — no aplican las reglas de texto-sobre-fondo de la sección 1.

---

## 6. Deuda conocida (no bloquea, pero está pendiente)

- `page.php` (760px) vs `single.php` (720px): ancho de columna de lectura inconsistente, debería ser un solo valor.
- `--fs-2xl` y `--fs-4xl` están declarados pero sin uso real — o se usan pronto o se limpian.

---

## Reglas rápidas al escribir CSS nuevo

1. Todo color sale de un token de `tokens.css`. Si no existe el token que necesitás, se agrega ahí primero (con su versión dark si aplica), nunca un hex suelto en otro archivo.
2. Fondo de color de marca constante (moss/ochre/footer) → texto en `--color-fixed-light`. Fondo swappable (ink/paper) → texto en el par swappable normal.
3. Espaciado sale de `--space-*`. Tipografía sale de `--fs-*` + `--font-serif`/`--font-sans` (IM Fell English solo en `.display`).
4. Antes de crear un componente nuevo (botón, badge, card), revisar si ya existe algo parecido acá — extender, no duplicar.
5. Todo cambio de token (agregar/renombrar) se documenta en esta guía en el mismo commit que el CSS.
