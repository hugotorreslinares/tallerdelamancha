# Guía visual — Tinta Brava / Taller de la mancha

Fuente de verdad del sistema de diseño. Todo cambio visual nuevo se hace citando esta guía — si algo no está acá, se agrega acá primero, después se usa en el código.

Los valores reales viven en `assets/css/tokens.css` (variables) y `assets/css/base.css` + `components.css` (aplicación). Esta guía documenta ese sistema, no lo reemplaza — si hay diferencia entre este archivo y el código, el código manda y hay que actualizar la guía.

---

## 1. Color

### Paleta base (modo claro)

| Token | Valor | Uso |
|---|---|---|
| `--color-ink` | `#323232` | Texto principal, fondos oscuros puntuales |
| `--color-paper` | `#F4EFE6` | Fondo de página |
| `--color-paper-2` | `#FFFFFF` | Superficies elevadas (cards, secciones alternas) |
| `--color-ochre` | `#B4651A` | Acento de marca — eyebrows, links en hover, precios, badges |
| `--color-ochre-soft` | `#E8C9A3` | Fondo de badges/placeholders con `--color-ochre` de texto |
| `--color-moss` | `#2D4A3E` | Verde de marca — bandas de sección, iconos |
| `--color-moss-soft` | `#C5D2C9` | Fondo de badges/placeholders con `--color-moss` de texto |
| `--color-line` | `#D9D2C5` | Bordes, separadores |
| `--color-muted` | `#6B6357` | Texto secundario |
| `--color-whatsapp` / `--color-whatsapp-dark` | `#25D366` / `#128C7E` | Exclusivo para CTAs de WhatsApp |

### Modo oscuro

Solo 5 tokens cambian: `--color-ink`, `--color-paper`, `--color-paper-2`, `--color-line`, `--color-muted`. El resto (`ochre`, `moss`, `whatsapp`) se queda **igual en los dos temas** — ya tienen buen contraste en ambos fondos y varios se usan en pares bg+texto autocontenidos que se romperían si cambiaran.

Se activa por `prefers-color-scheme` del sistema o por `data-theme="dark"` explícito (toggle del header, persiste en `localStorage`).

### Par fijo — regla importante

`--color-fixed-dark` (`#323232`) y `--color-fixed-light` (`#F4EFE6`) **no cambian nunca**, ni con el tema. Son para bandas de color propio que deben leerse igual sin importar el tema del sitio: el footer, y cualquier sección con fondo `--color-moss` u `--color-ochre` (fondo constante).

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
| Titular hero de portada (`.display`) | IM Fell English itálica | hardcodeada en `.display`, con fallback a `--font-serif` |

Las tres están autoalojadas en `assets/fonts/` (no CDN externo — decisión ya tomada por privacidad/rendimiento, no reabrir).

`.display` es la ÚNICA excepción a Fraunces/Inter — es el titular principal del hero de portada, con voz propia (grabado/imprenta antigua). No usar IM Fell English en ningún otro lugar sin decisión explícita; no es una fuente de uso general.

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
| `.btn-primary` | `--color-whatsapp` | `--color-ink` | CTA principal genérico (no siempre es WhatsApp pese al color) |
| `.btn-whatsapp` | `--color-whatsapp` | blanco | CTA que específicamente abre WhatsApp |
| `.btn-ghost` | transparente, borde ink | `--color-ink` | CTA secundario |
| `.btn-outline` | transparente, borde `--color-line` | `--color-ink` | Terciario, bajo énfasis |

> Nota de inconsistencia conocida: `.btn-primary` y `.btn-whatsapp` comparten el mismo fondo verde pero difieren en color de texto (ink vs blanco). Es así en +50 usos ya en producción — no "corregirlo" sin que sea una decisión explícita, documentarlo acá alcanza.

`.btn-lg` como modificador de tamaño (hero, CTAs grandes), no crear una clase nueva por página.

### Cards

`.card` = fondo `--color-paper-2`, borde `--color-line`, radio `--radius-lg`, sombra `--shadow-lg` en hover con `translateY(-4px)`. `.card-img` con `aspect-ratio: 4/3` y fondo placeholder (`--color-ochre-soft` por defecto, `--color-moss-soft` en `.category`).

### Badges

`.badge` = texto `--fs-xs` uppercase, `letter-spacing: 0.08em`, fondo `--color-ochre-soft` + texto `--color-ochre`, pill. Es el único patrón de badge — no crear variantes de color sin agregarlas acá primero.

### Eyebrow (label sobre títulos)

`.eyebrow` = `--font-sans`, `--fs-sm`, 600, uppercase, `letter-spacing: 0.12em`, color `--color-ochre`.

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
