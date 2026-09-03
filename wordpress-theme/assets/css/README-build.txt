theme.css es un bundle generado — NO lo edites directo.

Fuente real: reset.css, tokens.css, base.css, components.css, pages.css
(en ese orden, el orden importa).

Para regenerar después de editar cualquiera de esos archivos, correr
desde wordpress-theme/assets/css/:

  cat reset.css tokens.css base.css components.css pages.css > theme.css

fonts.css se mantiene aparte (no entra en el bundle) por las rutas
relativas de los @font-face.
