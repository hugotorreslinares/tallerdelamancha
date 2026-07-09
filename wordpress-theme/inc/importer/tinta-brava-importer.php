<?php
/**
 * Plugin Name: Tinta Brava Demo Importer
 * Description: Importa contenido demo (3 productos, 3 ferias, 4 tutoriales, páginas) para Tinta Brava. Ejecutar una sola vez desde el menú Herramientas.
 * Version: 1.0.0
 * Author: Tinta Brava
 *
 * Este plugin viene con el tema Tinta Brava y se activa/desactiva según se necesite.
 *
 * @package TintaBrava
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Menú de administración
 */
function tinta_brava_importer_menu() {
  add_management_page(
    'Tinta Brava — Importar contenido demo',
    'Importar demo Tinta Brava',
    'manage_options',
    'tinta-brava-importer',
    'tinta_brava_importer_page'
  );
}
add_action( 'admin_menu', 'tinta_brava_importer_menu' );

/**
 * Renderizar página
 */
function tinta_brava_importer_page() {
  if ( ! current_user_can( 'manage_options' ) ) {
    wp_die( __( 'No tienes permisos.', 'tinta-brava' ) );
  }

  $imported = false;
  $log      = array();

  if ( isset( $_POST['tinta_brava_import'] ) && check_admin_referer( 'tinta_brava_import_action' ) ) {
    $log      = tinta_brava_run_import();
    $imported = true;
  }
  ?>
  <div class="wrap">
    <h1>Tinta Brava — Importar contenido demo</h1>
    <p>Este importador crea el contenido inicial del sitio: 3 productos (linograbado, serigrafía, litografía), 3 ferias de ejemplo, 4 tutoriales del blog y las páginas básicas (Contacto, Sobre el taller, Catálogo de kits).</p>

    <div class="card" style="max-width: 800px; padding: 1rem 1.5rem; background: #fff; border: 1px solid #ccd0d4; border-left: 4px solid #B4651A; margin: 1rem 0;">
      <h3>⚠️ Antes de ejecutar</h3>
      <ol>
        <li>Asegúrate de tener WooCommerce instalado y activado.</li>
        <li>Verifica que los <em>product categories</em> de WooCommerce existan (el importador los crea si no).</li>
        <li>No ejecutes dos veces seguidas — los productos y tutoriales se duplican.</li>
        <li>Si algo falla, puedes borrar lo creado desde el menú de Productos, Tutoriales y Ferias.</li>
      </ol>
    </div>

    <?php if ( $imported ) : ?>
      <div class="notice notice-success" style="padding: 1rem 1.5rem;">
        <h3>✓ Importación completada</h3>
        <ul style="margin-left: 1.5rem; list-style: disc;">
          <?php foreach ( $log as $line ) : ?>
            <li><?php echo esc_html( $line ); ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form method="post" style="margin-top: 1.5rem;">
      <?php wp_nonce_field( 'tinta_brava_import_action' ); ?>
      <button type="submit" name="tinta_brava_import" class="button button-primary button-large">
        Ejecutar importador
      </button>
    </form>

    <h2 style="margin-top: 2rem;">Lo que se va a crear</h2>
    <h3>Productos (3)</h3>
    <ul style="list-style: disc; margin-left: 1.5rem;">
      <li>Kit iniciación linograbado — $145.000 COP</li>
      <li>Kit iniciación serigrafía — $195.000 COP</li>
      <li>Kit iniciación litografía — $385.000 COP <em>(próximamente)</em></li>
    </ul>
    <h3>Ferias (3)</h3>
    <ul style="list-style: disc; margin-left: 1.5rem;">
      <li>BADA Bogotá — septiembre 2026</li>
      <li>Mercado de las Pulgas — Usaquén, octubre 2026</li>
      <li>Festival de Arte Emergente — noviembre 2026</li>
    </ul>
    <h3>Tutoriales del blog (4)</h3>
    <ul style="list-style: disc; margin-left: 1.5rem;">
      <li>Cómo afilar tu gubia (y por qué importa)</li>
      <li>Tu primera emulsión para serigrafía en 30 minutos</li>
      <li>Tu primera estampa en 4 pasos</li>
      <li>Tipos de tinta para grabado: cuál usar y dónde comprarla en Bogotá</li>
    </ul>
    <h3>Páginas (3)</h3>
    <ul style="list-style: disc; margin-left: 1.5rem;">
      <li>Sobre el taller</li>
      <li>Catálogo de kits <em>(usa la plantilla personalizada)</em></li>
      <li>Contacto <em>(usa la plantilla personalizada)</em></li>
    </ul>
  </div>
  <?php
}

/**
 * Ejecutar la importación
 *
 * @return array
 */
function tinta_brava_run_import() {
  $log = array();

  // Categorías de producto
  $cats = array( 'Linograbado', 'Serigrafía', 'Litografía' );
  $cat_ids = array();
  foreach ( $cats as $cat ) {
    $term = term_exists( $cat, 'product_cat' );
    if ( ! $term ) {
      $term = wp_insert_term( $cat, 'product_cat' );
    }
    if ( ! is_wp_error( $term ) ) {
      $cat_ids[ $cat ] = is_array( $term ) ? $term['term_id'] : $term;
    }
  }
  $log[] = 'Categorías de producto listas (' . count( $cat_ids ) . ')';

  // Productos
  $products = array(
    'kit-linograbado' => array(
      'title'         => 'Kit iniciación linograbado',
      'cat'           => 'Linograbado',
      'price'         => '145000',
      'short_desc'    => 'Una gubia, una plancha suave, tres tubos de tinta, papel y un manual de cuatro proyectos. Suficiente para terminar tu primera estampa el mismo día que abras la caja.',
      'description'   => '<p>El kit de iniciación en linograbado de Tinta Brava incluye todo lo que necesitas para aprender la técnica y terminar tu primer proyecto el mismo día.</p><p>Una gubia Pfeil de 5 mm, una plancha de linóleo suave de 15×10 cm, tres tubos de tinta al agua (negro, ocre y azul Prusia), 10 hojas de papel para grabado de 200 g/m², un rodillo brayer de 10 cm, un manual impreso de 32 páginas con 4 proyectos paso a paso, y acceso a la comunidad de WhatsApp.</p><p>Diseñado para principiantes: si nunca has cogido una gubia, este kit es para ti.</p>',
      'time'          => '2-3 h',
      'prints'        => '+15',
      'persons'       => '1',
      'level'         => 'Principiante',
      'includes'      => "1 gubia de grabado profesional Pfeil (mango de madera, hoja en V de 5 mm) — la misma que usamos en taller\n1 plancha de linóleo suave 15×10 cm, gris claro\n3 tubos de tinta al agua (negro, ocre y azul Prusia) — 25 ml cada uno\n10 hojas de papel para grabado 200 g/m², A5\n1 rodillo (brayer) de 10 cm, goma dura\n1 manual impreso de 32 páginas con 4 proyectos\nAcceso al taller virtual y a la comunidad de WhatsApp",
      'coming_soon'   => '',
    ),
    'kit-serigrafia' => array(
      'title'         => 'Kit iniciación serigrafía',
      'cat'           => 'Serigrafía',
      'price'         => '195000',
      'short_desc'    => 'Bastidor, emulsión, rasero, tinta textil y papel. Estampa en tela y papel desde la primera sesión.',
      'description'   => '<p>El kit de iniciación en serigrafía está pensado para que estampa en tela y en papel sin tener que comprar emulsión, químicos ni equipos pesados.</p><p>Incluye un bastidor de 25×30 cm con malla 110, emulsión fotosensible 100 ml, rasero de goma de 25 cm, espátula para emulsionar, dos tubos de tinta textil de 100 ml (negro y ocre), 5 hojas de papel para serigrafía A3, una franela de algodón 40×40 cm, un manual impreso de 40 páginas y acceso a la comunidad de WhatsApp.</p><p>Incluye todo lo necesario para tu primera tirada de 20 unidades.</p>',
      'time'          => '1-2 h',
      'prints'        => '+20',
      'persons'       => '1-2',
      'level'         => 'Principiante',
      'includes'      => "1 bastidor de 25×30 cm con malla 110, listo para emulsionar\nEmulsión fotosensible 100 ml — sensibilidad media\n1 rasero de goma 25 cm, dureza media\n1 espátula para emulsionar (coater)\n2 tubos de tinta textil 100 ml — negro y ocre, base agua\n5 hojas de papel para serigrafía A3\n1 franela de algodón 40×40 cm\n1 manual impreso de 40 páginas\nAcceso a la comunidad de WhatsApp",
      'coming_soon'   => '',
    ),
    'kit-litografia' => array(
      'title'         => 'Kit iniciación litografía',
      'cat'           => 'Litografía',
      'price'         => '385000',
      'short_desc'    => 'Piedra, lápices grasos, ácidos, planchas y manual. La técnica clásica, en un kit de altura.',
      'description'   => '<p>La litografía es la técnica de grabado más sutil: trabajar la piedra con lápices grasos, mordentarla con ácido graso, entintarla y estampar. El resultado es de una finura que ningún otro proceso logra.</p><p>Este kit es el siguiente paso natural después de dominar el linograbado. Recomendamos tener experiencia previa en algún tipo de grabado antes de lanzarse a la litografía.</p><p>El proceso tiene más química y requiere más espacio. Si nunca has estampado nada, te recomendamos empezar por nuestro kit de linograbado.</p>',
      'time'          => '2-3 h',
      'prints'        => '20-30',
      'persons'       => '1',
      'level'         => 'Intermedio',
      'includes'      => "1 piedra litográfica 15×20 cm — caliza de Baviera, granulada media\nSet de 6 lápices grasos litográficos (Cretacolor, durezas 0-5)\nTinta litográfica negra 100 ml — Charbonnel, base aceite\nÁcido graso 250 ml para mordentar la piedra\nPlancha de zinc 15×20 cm (alternativa reutilizable)\n20 hojas de papel para litografía 250 g/m²\n1 rodillo de entintar profesional\n1 manual de 60 páginas\nAcceso a la comunidad de WhatsApp",
      'coming_soon'   => '1',
    ),
  );

  foreach ( $products as $slug => $data ) {
    $existing = get_page_by_path( $slug, OBJECT, 'product' );
    if ( $existing ) {
      $log[] = 'Producto ya existe: ' . $data['title'];
      continue;
    }
    $product = new WC_Product_Simple();
    $product->set_name( $data['title'] );
    $product->set_slug( $slug );
    $product->set_status( 'publish' );
    $product->set_catalog_visibility( 'visible' );
    $product->set_description( $data['description'] );
    $product->set_short_description( $data['short_desc'] );
    $product->set_sku( strtoupper( str_replace( 'kit-', '', $slug ) ) );
    $product->set_regular_price( $data['price'] );
    $product->set_manage_stock( false );
    $product->set_stock_status( 'instock' );
    if ( ! empty( $cat_ids[ $data['cat'] ] ) ) {
      $product->set_category_ids( array( $cat_ids[ $data['cat'] ] ) );
    }
    $product->save();

    update_post_meta( $product->get_id(), '_tinta_brava_coming_soon', $data['coming_soon'] );
    update_post_meta( $product->get_id(), '_tinta_brava_time', $data['time'] );
    update_post_meta( $product->get_id(), '_tinta_brava_prints', $data['prints'] );
    update_post_meta( $product->get_id(), '_tinta_brava_persons', $data['persons'] );
    update_post_meta( $product->get_id(), '_tinta_brava_level', $data['level'] );
    update_post_meta( $product->get_id(), '_tinta_brava_includes', $data['includes'] );

    $log[] = 'Producto creado: ' . $data['title'];
  }

  // Ferias
  $fairs = array(
    array(
      'title'    => 'BADA — Boutique de Arte Directo',
      'date'     => date( 'Y' ) . '-09-12',
      'end_date' => date( 'Y' ) . '-09-14',
      'location' => 'Corferias',
      'city'     => 'Bogotá',
      'stand'    => 'P-218, pasillo de ilustración',
      'time'     => '11:00–20:00',
      'excerpt'  => 'La feria de ilustración y arte independiente más importante del país. Tendremos demostraciones de linograbado cada hora y descuento del 10% en todos los kits.',
    ),
    array(
      'title'    => 'Mercado de las Pulgas — Usaquén',
      'date'     => date( 'Y' ) . '-10-04',
      'end_date' => '',
      'location' => 'Plaza de Usaquén',
      'city'     => 'Bogotá',
      'stand'    => 'Zona de ilustradores',
      'time'     => '09:00–18:00',
      'excerpt'  => 'Edición especial de diseño independiente. Talleres abiertos de estampa en vivo y kits en preventa.',
    ),
    array(
      'title'    => 'Festival de Arte Emergente',
      'date'     => date( 'Y' ) . '-11-22',
      'end_date' => '',
      'location' => 'Casa Cuervo, La Candelaria',
      'city'     => 'Bogotá',
      'stand'    => '',
      'time'     => '10:00–19:00',
      'excerpt'  => 'Encuentro de artistas emergentes del circuito independiente bogotano. Exhibimos piezas originales y kits en preventa para fin de año.',
    ),
  );

  foreach ( $fairs as $f ) {
    $existing = get_page_by_path( sanitize_title( $f['title'] ), OBJECT, 'fair' );
    if ( $existing ) {
      $log[] = 'Feria ya existe: ' . $f['title'];
      continue;
    }
    $post_id = wp_insert_post( array(
      'post_title'   => $f['title'],
      'post_content' => $f['excerpt'],
      'post_excerpt' => $f['excerpt'],
      'post_status'  => 'publish',
      'post_type'    => 'fair',
      'post_name'    => sanitize_title( $f['title'] ),
    ) );
    if ( ! is_wp_error( $post_id ) ) {
      update_post_meta( $post_id, 'fair_date', $f['date'] );
      update_post_meta( $post_id, 'fair_end_date', $f['end_date'] );
      update_post_meta( $post_id, 'fair_location', $f['location'] );
      update_post_meta( $post_id, 'fair_city', $f['city'] );
      update_post_meta( $post_id, 'fair_stand', $f['stand'] );
      update_post_meta( $post_id, 'fair_time', $f['time'] );
      $log[] = 'Feria creada: ' . $f['title'];
    }
  }

  // Tutoriales
  $tutorials = array(
    array(
      'title'   => 'Cómo afilar tu gubia (y por qué importa)',
      'cat'     => 'Linograbado',
      'excerpt' => 'Una gubia roma es la diferencia entre un buen corte y un desastre. Aquí te explico cómo mantenerla en forma con una piedra de afilar y por qué es el hábito que separa a quien avanza de quien se frustra.',
      'content' => '<p>Una gubia roma es la diferencia entre un corte limpio y un desastre. La buena noticia: afilarla es más fácil de lo que parece, y solo necesitas 5 minutos cada dos semanas para mantenerla en forma.</p><h2>Por qué importa la afilada</h2><p>Cuando la hoja de tu gubia pierde filo, no la notas de inmediato. Empiezas a "raspar" en lugar de cortar. La plancha se desgarra en lugar de cortarse limpia. Te frustras. Y lo peor: lo achacas a que "el linograbado es difícil" cuando el problema es la herramienta.</p><p>Una gubia afilada corta por su propio peso. Si tienes que empujar, no está afilada.</p><h2>Lo que necesitas</h2><ul><li>Una piedra de afilar de grano 1000 (suave, para uso general).</li><li>Opcional: una piedra de grano 3000 para el acabado final.</li><li>Un trapo húmedo.</li><li>La gubia, obviamente.</li></ul><h2>El proceso, paso a paso</h2><ol><li>Moja la piedra con agua. Debe quedar una película fina encima.</li><li>Sujeta la gubia por el mango, con la hoja apuntando a la piedra en un ángulo de 15-20°.</li><li>Pasa la hoja de un extremo a otro de la piedra, siempre en la misma dirección.</li><li>Repite 10 veces por cada cara de la hoja.</li><li>Verifica el filo pasando la uña suavemente por el borde.</li><li>Seca bien la gubia y guárdala.</li></ol><h2>Errores comunes</h2><ul><li>Apurar demasiado. Más presión no significa más filo.</li><li>Cambiar el ángulo. Mantén los 15-20° todo el rato.</li><li>Afilarla en seco. Siempre con agua.</li></ul>',
    ),
    array(
      'title'   => 'Tu primera emulsión para serigrafía en 30 minutos',
      'cat'     => 'Serigrafía',
      'excerpt' => 'El paso más técnico de la serigrafía, explicado sin química innecesaria y con materiales que consigues en Bogotá. Lo que sale mal, cómo evitarlo, y cuándo descartar la emulsión.',
      'content' => '<p>El paso más técnico de la serigrafía, explicado sin química innecesaria y con materiales que consigues en Bogotá. Lo que sale mal, cómo evitarlo, y cuándo descartar la emulsión.</p><h2>Qué es la emulsión y por qué importa</h2><p>La emulsión es una pasta fotosensible que aplicas a la pantalla. Una vez seca y revelada con luz UV, deja una plantilla a través de la cual pasa la tinta. Si la emulsión queda mal, tu estampa queda mal. Por eso es el paso al que más miedo le tiene la gente, y el más importante de dominar.</p><h2>El proceso</h2><ol><li>Prepara la pantalla: límpiala con desengrasante, sécala bien.</li><li>Trabaja en semi-oscuridad: no expongas la emulsión a la luz directa.</li><li>Aplica la emulsión con la espátula, de abajo hacia arriba en una pasada.</li><li>Seca en la oscuridad por 2-3 horas.</li><li>Insola con tu diseño: positivo sobre la pantalla, luz UV o sol directo 8-12 minutos.</li><li>Revela con agua tibia hasta que veas aparecer el diseño.</li></ol><h2>Errores comunes</h2><ul><li>Exposición corta: el diseño no aparece completo.</li><li>Exposición larga: el revelado es muy lento.</li><li>Aplicar capa gruesa: capa fina funciona mejor.</li></ul>',
    ),
    array(
      'title'   => 'Tu primera estampa en 4 pasos',
      'cat'     => 'Linograbado',
      'excerpt' => 'De la idea al papel: el proyecto mínimo viable de un linograbado de 15×10 cm. Lo que aprendes en 30 minutos y por qué no necesitas más para empezar.',
      'content' => '<p>De la idea al papel: el proyecto mínimo viable de un linograbado de 15×10 cm. Lo que aprendes en 30 minutos y por qué no necesitas más para empezar.</p><h2>El proyecto: una estampa mínima</h2><p>Vamos a hacer una estampa simple pero bien hecha. No una obra maestra, sino una pieza limpia que puedas firmar y colgar. Es la base sobre la que construyes todo lo demás.</p><h2>Paso 1: Dibuja tu diseño</h2><p>Empieza con algo simple: un círculo, una hoja, una cara, una palabra. Dibuja directamente sobre el linóleo con un marcador permanente fino. Recuerda: lo que no dibujes será lo que se imprima.</p><h2>Paso 2: Corta el linóleo</h2><p>Sujeta la gubia como un lápiz, pero con la otra mano guiando la hoja. Corta por la línea, no la atravieses. Vacía las áreas grandes con cortes largos y parejos.</p><h2>Paso 3: Entinta y estampa</h2><p>Coloca una pizca de tinta en una superficie plana. Pasa el rodillo hasta que esté parejo. Entinta la plancha con 3-4 pasadas de rodillo. Coloca el papel encima y presiona con una cuchara por el reverso.</p><h2>Paso 4: Revela</h2><p>Levanta una esquina del papel con cuidado. Tu primera estampa está lista. Fírmala con lápiz en una esquina. Ponle la fecha. En 6 meses vas a ver la diferencia.</p>',
    ),
    array(
      'title'   => 'Tipos de tinta para grabado: cuál usar y dónde comprarla en Bogotá',
      'cat'     => 'Materiales',
      'excerpt' => 'Al agua, al aceite, espesa, fluida, opaca, translúcida. Una guía sin jerga para que elijas bien la primera vez y no desperdicies papel.',
      'content' => '<p>En grabado se usan principalmente dos tipos de tinta: al agua y al aceite. Cada una tiene su personalidad. La regla simple: para empezar, tinta al agua. Más fácil de limpiar, más barata, suficiente para aprender.</p><h2>Tinta al agua</h2><p>Base de agua, se limpia con jabón. Secado rápido. Ideal para papel, no tan buena para tela. Marcas recomendadas: Speedball o CalSafe (marca local, buena calidad).</p><h2>Tinta al aceite</h2><p>Base de aceite mineral, se limpia con disolvente. Tiempos de secado largos, acabado más vibrante y profesional. Para cuando ya manejes bien la técnica. Charbonnel es la marca de referencia.</p><h2>Dónde comprar en Bogotá</h2><ul><li>Casa de la Serigrafía (Cra 25 con Cll 13, centro).</li><li>El Hueco (Chapinero).</li><li>San Victorino (centro). Más económico, menos especializado.</li><li>Gráficas Andinas y otros proveedores en línea con envío a todo Colombia.</li></ul>',
    ),
  );

  foreach ( $tutorials as $t ) {
    $existing = get_page_by_path( sanitize_title( $t['title'] ), OBJECT, 'post' );
    if ( $existing ) {
      $log[] = 'Tutorial ya existe: ' . $t['title'];
      continue;
    }

    $cat_id = wp_create_category( $t['cat'] );
    $post_id = wp_insert_post( array(
      'post_title'   => $t['title'],
      'post_content' => $t['content'],
      'post_excerpt' => $t['excerpt'],
      'post_status'  => 'publish',
      'post_type'    => 'post',
      'post_name'    => sanitize_title( $t['title'] ),
      'post_category'=> $cat_id ? array( $cat_id ) : array(),
    ) );
    if ( ! is_wp_error( $post_id ) ) {
      $log[] = 'Tutorial creado: ' . $t['title'];
    }
  }

  // Páginas
  $pages = array(
    'sobre-el-taller' => array(
      'title'   => 'Sobre el taller',
      'content' => '<p>Tinta Brava es un taller de grabado en Bogotá. Diseñamos kits para que otras personas puedan empezar a estampar en casa, sin necesidad de un estudio grande ni de años de escuela de bellas artes.</p><p>Empezamos en 2023 haciendo tiradas pequeñas para amigos y ferias locales. Hoy tenemos una línea de tres kits, un calendario de talleres presenciales y una comunidad en WhatsApp donde la gente comparte sus primeras estampas.</p><h2>En qué creemos</h2><h3>Materiales que sí se usan</h3><p>Probamos todo en taller antes de incluirlo. Si un material no aporta, no va en la caja aunque sea bonito en la foto.</p><h3>Manuales honestos</h3><p>Escribimos para la persona que nunca ha cogido una gubia. Sin romanticismo, sin química innecesaria, sin pedirte que compres más.</p><h3>Comunidad, no clientela</h3><p>Quien compra un kit entra a un grupo de WhatsApp donde compartimos proyectos, resolvemos dudas y avisamos de ferias.</p>',
      'excerpt' => 'Conoce el taller detrás de Tinta Brava: cómo nacen los kits, qué creemos y por qué enseñamos grabado en Bogotá.',
    ),
    'kits' => array(
      'title'   => 'Catálogo de kits',
      'content' => 'Página de catálogo de productos. Usa la plantilla "Catálogo de Kits" para mostrar los productos en formato grid.',
      'excerpt' => 'Tres técnicas, una sola idea: que abras la caja y termines con una estampa en la mano.',
    ),
    'contacto' => array(
      'title'   => 'Contacto',
      'content' => 'Página de contacto. Usa la plantilla "Contacto" para mostrar el formulario y los canales directos.',
      'excerpt' => 'La forma más rápida de hablar con nosotros es WhatsApp. Si prefieres correo o redes, también respondemos.',
    ),
    'tutoriales' => array(
      'title'   => 'Tutoriales',
      'content' => 'Listado de tutoriales del blog. Esta página se alimenta automáticamente de los posts con categoría "Linograbado", "Serigrafía", "Litografía" o "Materiales".',
      'excerpt' => 'Guías paso a paso, técnicas probadas en taller y respuestas a las preguntas que nos hacen por WhatsApp cada semana.',
    ),
    'ferias' => array(
      'title'   => 'Ferias',
      'content' => 'Listado de ferias donde Tinta Brava tiene stand propio. Las ferias se gestionan desde el CPT "fair" en el menú lateral del admin.',
      'excerpt' => 'Calendario de ferias de diseño y arte en Bogotá donde Tinta Brava tendrá stand con kits, demostraciones y descuentos.',
    ),
    'terminos' => array(
      'title'   => 'Términos y condiciones',
      'content' => '<p>[Pendiente: redactar los términos y condiciones de la tienda. Por ahora este es un placeholder.]</p><p>Esta tienda opera como catálogo en línea. Las ventas se procesan a través de WhatsApp y pago por transferencia. Hasta no completar el registro ante la DIAN, no emitimos factura electrónica.</p>',
      'excerpt' => 'Términos y condiciones de Tinta Brava.',
    ),
    'privacidad' => array(
      'title'   => 'Política de privacidad',
      'content' => '<p>[Pendiente: redactar la política de privacidad conforme a la Ley 1581 de 2012 de Colombia.]</p><p>Este sitio recoge datos personales únicamente para responder a consultas y procesar pedidos. No compartimos datos con terceros.</p>',
      'excerpt' => 'Política de privacidad y tratamiento de datos de Tinta Brava.',
    ),
    'devoluciones' => array(
      'title'   => 'Política de devoluciones',
      'content' => '<p>Conforme al Estatuto del Consumidor colombiano (Ley 1480 de 2011), el cliente tiene derecho de retracto dentro de los 5 días hábiles siguientes a la entrega del producto, siempre que el kit esté sin abrir y en su empaque original.</p><p>Para ejercer el derecho de retracto, escríbenos por WhatsApp indicando el número de pedido. La devolución del dinero se realiza por el mismo medio de pago usado, dentro de los 15 días hábiles siguientes.</p><p>Si recibes un producto defectuoso o con materiales faltantes, escríbenos de inmediato y lo resolvemos.</p>',
      'excerpt' => 'Política de devoluciones de Tinta Brava.',
    ),
  );

  foreach ( $pages as $slug => $p ) {
    $existing = get_page_by_path( $slug, OBJECT, 'page' );
    if ( $existing ) {
      $log[] = 'Página ya existe: ' . $p['title'];
      continue;
    }
    $post_id = wp_insert_post( array(
      'post_title'   => $p['title'],
      'post_content' => $p['content'],
      'post_excerpt' => $p['excerpt'],
      'post_status'  => 'publish',
      'post_type'    => 'page',
      'post_name'    => $slug,
    ) );
    if ( ! is_wp_error( $post_id ) ) {
      // Asignar plantilla personalizada si aplica
      if ( $slug === 'contacto' ) {
        update_post_meta( $post_id, '_wp_page_template', 'templates/contacto.php' );
      } elseif ( $slug === 'kits' ) {
        update_post_meta( $post_id, '_wp_page_template', 'templates/catalogo-kits.php' );
      }
      $log[] = 'Página creada: ' . $p['title'];
    }
  }

  // Asignar página de inicio
  $home_id = get_option( 'page_on_front' );
  if ( ! $home_id ) {
    $front = get_page_by_path( 'sobre-el-taller' );
    if ( ! $front ) {
      $front = get_page_by_path( 'contacto' );
    }
    if ( $front ) {
      update_option( 'show_on_front', 'page' );
      update_option( 'page_on_front', $front->ID );
      $log[] = 'Página de inicio asignada';
    }
  }

  // Asignar página de blog
  $posts_page = get_page_by_path( 'tutoriales' );
  if ( $posts_page ) {
    update_option( 'page_for_posts', $posts_page->ID );
    $log[] = 'Página de blog asignada (Tutoriales)';
  }

  // Menú principal
  $menu_name = 'Principal';
  $menu_id = wp_create_nav_menu( $menu_name );
  if ( is_wp_error( $menu_id ) ) {
    $menu_obj = wp_get_nav_menu_object( $menu_name );
    $menu_id = $menu_obj ? $menu_obj->term_id : 0;
  }
  if ( $menu_id ) {
    $items = array(
      'kits'           => 'Kits',
      'sobre-el-taller'=> 'El taller',
      'tutoriales'     => 'Tutoriales',
      'ferias'         => 'Ferias',
      'contacto'       => 'Contacto',
    );
    foreach ( $items as $slug => $label ) {
      $page = get_page_by_path( $slug, OBJECT, 'page' );
      if ( $page ) {
        wp_update_nav_menu_item( $menu_id, 0, array(
          'menu-item-title'  => $label,
          'menu-item-object' => 'page',
          'menu-item-object-id' => $page->ID,
          'menu-item-status' => 'publish',
        ) );
      }
    }
    // Asignar a la ubicación "primary"
    $locations = get_theme_mod( 'nav_menu_locations', array() );
    $locations['primary'] = $menu_id;
    set_theme_mod( 'nav_menu_locations', $locations );
    $log[] = 'Menú principal creado y asignado';
  }

  $log[] = 'Importación completada.';
  return $log;
}
