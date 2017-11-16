<?php

namespace ucare;


function allowed_mime_types( $type = null ) {

    $file_types  = explode( ',', get_option( Options::FILE_MIME_TYPES, Defaults::FILE_MIME_TYPES   ) );
    $image_types = explode( ',', get_option( Options::IMAGE_MIME_TYPES, Defaults::IMAGE_MIME_TYPES ) );

    if ( $type == 'image' ) {
        return $image_types;
    } else if ( $type == 'file' ) {
        return $file_types;
    }

    return array_merge( $file_types, $image_types );

}


function support_page_url( $path = '' ) {
    return get_the_permalink( get_option( Options::TEMPLATE_PAGE_ID ) ) . $path;
}

/**
 * @return null
 * @deprecated
 */
function plugin_dir() {
    return Plugin::plugin_dir( PLUGIN_ID );
}

/**
 * @param string $path
 *
 * @return string
 * @deprecated
 */
function plugin_url( $path = '' ) {
    return trailingslashit( Plugin::plugin_url( PLUGIN_ID ) ) . ltrim( $path, '/' );
}

function selectbox( $name, $options, $selected = '', $attrs = array() ) { ?>

    <select name="<?php esc_attr_e( $name ); ?>"

            <?php foreach ( $attrs as $attr => $values ) : ?>

                <?php echo $attr . '="' . esc_attr( $values ) . '"' ?>

            <?php endforeach; ?>>

        <?php foreach ( $options as $value => $label ) : ?>

            <option value="<?php esc_attr_e( $value ); ?>"

                <?php selected( $selected, $value ); ?> ><?php echo $label ?></option>

        <?php endforeach; ?>

    </select>

<?php }


/**
 * @param $key
 * @param $value
 * @deprecated
 */
function cache_put( $key, $value ) {

    $plugin = Plugin::get_plugin( PLUGIN_ID );

    $plugin->$key = $value;

}

/**
 * @param $key
 * @deprecated
 */
function cache_delete( $key ) {

    $plugin = Plugin::get_plugin( PLUGIN_ID );

    unset( $plugin->$key );

}

/**
 * @param $key
 * @param bool $default
 *
 * @return bool
 * @deprecated
 */
function cache_get( $key, $default = false ) {

    $plugin = Plugin::get_plugin( PLUGIN_ID );

    if( isset( $plugin->$key ) ) {
        return $plugin->$key;
    } else {
        return $default;
    }

}

function fonts() {

    $new_fonts = array( 'ABeeZee, sans-serif' => 'ABeeZee:regular,italic', 'Abel, sans-serif' => 'Abel:regular', 'Abhaya Libre, serif' => 'Abhaya+Libre:regular,500,600,700,800', 'Abril Fatface, display' => 'Abril+Fatface:regular', 'Aclonica, sans-serif' => 'Aclonica:regular', 'Acme, sans-serif' => 'Acme:regular', 'Actor, sans-serif' => 'Actor:regular', 'Adamina, serif' => 'Adamina:regular', 'Advent Pro, sans-serif' => 'Advent+Pro:100,200,300,regular,500,600,700', 'Aguafina Script, handwriting' => 'Aguafina+Script:regular', 'Akronim, display' => 'Akronim:regular', 'Aladin, handwriting' => 'Aladin:regular', 'Aldrich, sans-serif' => 'Aldrich:regular', 'Alef, sans-serif' => 'Alef:regular,700', 'Alegreya, serif' => 'Alegreya:regular,italic,700,700italic,900,900italic', 'Alegreya SC, serif' => 'Alegreya+SC:regular,italic,700,700italic,900,900italic', 'Alegreya Sans, sans-serif' => 'Alegreya+Sans:100,100italic,300,300italic,regular,italic,500,500italic,700,700italic,800,800italic,900,900italic', 'Alegreya Sans SC, sans-serif' => 'Alegreya+Sans+SC:100,100italic,300,300italic,regular,italic,500,500italic,700,700italic,800,800italic,900,900italic', 'Alex Brush, handwriting' => 'Alex+Brush:regular', 'Alfa Slab One, display' => 'Alfa+Slab+One:regular', 'Alice, serif' => 'Alice:regular', 'Alike, serif' => 'Alike:regular', 'Alike Angular, serif' => 'Alike+Angular:regular', 'Allan, display' => 'Allan:regular,700', 'Allerta, sans-serif' => 'Allerta:regular', 'Allerta Stencil, sans-serif' => 'Allerta+Stencil:regular', 'Allura, handwriting' => 'Allura:regular', 'Almendra, serif' => 'Almendra:regular,italic,700,700italic', 'Almendra Display, display' => 'Almendra+Display:regular', 'Almendra SC, serif' => 'Almendra+SC:regular', 'Amarante, display' => 'Amarante:regular', 'Amaranth, sans-serif' => 'Amaranth:regular,italic,700,700italic', 'Amatic SC, handwriting' => 'Amatic+SC:regular,700', 'Amethysta, serif' => 'Amethysta:regular', 'Amiko, sans-serif' => 'Amiko:regular,600,700', 'Amiri, serif' => 'Amiri:regular,italic,700,700italic', 'Amita, handwriting' => 'Amita:regular,700', 'Anaheim, sans-serif' => 'Anaheim:regular', 'Andada, serif' => 'Andada:regular', 'Andika, sans-serif' => 'Andika:regular', 'Angkor, display' => 'Angkor:regular', 'Annie Use Your Telescope, handwriting' => 'Annie+Use+Your+Telescope:regular', 'Anonymous Pro, monospace' => 'Anonymous+Pro:regular,italic,700,700italic', 'Antic, sans-serif' => 'Antic:regular', 'Antic Didone, serif' => 'Antic+Didone:regular', 'Antic Slab, serif' => 'Antic+Slab:regular', 'Anton, sans-serif' => 'Anton:regular', 'Arapey, serif' => 'Arapey:regular,italic', 'Arbutus, display' => 'Arbutus:regular', 'Arbutus Slab, serif' => 'Arbutus+Slab:regular', 'Architects Daughter, handwriting' => 'Architects+Daughter:regular', 'Archivo, sans-serif' => 'Archivo:regular,italic,500,500italic,600,600italic,700,700italic', 'Archivo Black, sans-serif' => 'Archivo+Black:regular', 'Archivo Narrow, sans-serif' => 'Archivo+Narrow:regular,italic,500,500italic,600,600italic,700,700italic', 'Aref Ruqaa, serif' => 'Aref+Ruqaa:regular,700', 'Arima Madurai, display' => 'Arima+Madurai:100,200,300,regular,500,700,800,900', 'Arimo, sans-serif' => 'Arimo:regular,italic,700,700italic', 'Arizonia, handwriting' => 'Arizonia:regular', 'Armata, sans-serif' => 'Armata:regular', 'Arsenal, sans-serif' => 'Arsenal:regular,italic,700,700italic', 'Artifika, serif' => 'Artifika:regular', 'Arvo, serif' => 'Arvo:regular,italic,700,700italic', 'Arya, sans-serif' => 'Arya:regular,700', 'Asap, sans-serif' => 'Asap:regular,italic,500,500italic,600,600italic,700,700italic', 'Asap Condensed, sans-serif' => 'Asap+Condensed:regular,italic,500,500italic,600,600italic,700,700italic', 'Asar, serif' => 'Asar:regular', 'Asset, display' => 'Asset:regular', 'Assistant, sans-serif' => 'Assistant:200,300,regular,600,700,800', 'Astloch, display' => 'Astloch:regular,700', 'Asul, sans-serif' => 'Asul:regular,700', 'Athiti, sans-serif' => 'Athiti:200,300,regular,500,600,700', 'Atma, display' => 'Atma:300,regular,500,600,700', 'Atomic Age, display' => 'Atomic+Age:regular', 'Aubrey, display' => 'Aubrey:regular', 'Audiowide, display' => 'Audiowide:regular', 'Autour One, display' => 'Autour+One:regular', 'Average, serif' => 'Average:regular', 'Average Sans, sans-serif' => 'Average+Sans:regular', 'Averia Gruesa Libre, display' => 'Averia+Gruesa+Libre:regular', 'Averia Libre, display' => 'Averia+Libre:300,300italic,regular,italic,700,700italic', 'Averia Sans Libre, display' => 'Averia+Sans+Libre:300,300italic,regular,italic,700,700italic', 'Averia Serif Libre, display' => 'Averia+Serif+Libre:300,300italic,regular,italic,700,700italic', 'Bad Script, handwriting' => 'Bad+Script:regular', 'Bahiana, display' => 'Bahiana:regular', 'Baloo, display' => 'Baloo:regular', 'Baloo Bhai, display' => 'Baloo+Bhai:regular', 'Baloo Bhaijaan, display' => 'Baloo+Bhaijaan:regular', 'Baloo Bhaina, display' => 'Baloo+Bhaina:regular', 'Baloo Chettan, display' => 'Baloo+Chettan:regular', 'Baloo Da, display' => 'Baloo+Da:regular', 'Baloo Paaji, display' => 'Baloo+Paaji:regular', 'Baloo Tamma, display' => 'Baloo+Tamma:regular', 'Baloo Tammudu, display' => 'Baloo+Tammudu:regular', 'Baloo Thambi, display' => 'Baloo+Thambi:regular', 'Balthazar, serif' => 'Balthazar:regular', 'Bangers, display' => 'Bangers:regular', 'Barrio, display' => 'Barrio:regular', 'Basic, sans-serif' => 'Basic:regular', 'Battambang, display' => 'Battambang:regular,700', 'Baumans, display' => 'Baumans:regular', 'Bayon, display' => 'Bayon:regular', 'Belgrano, serif' => 'Belgrano:regular', 'Bellefair, serif' => 'Bellefair:regular', 'Belleza, sans-serif' => 'Belleza:regular', 'BenchNine, sans-serif' => 'BenchNine:300,regular,700', 'Bentham, serif' => 'Bentham:regular', 'Berkshire Swash, handwriting' => 'Berkshire+Swash:regular', 'Bevan, display' => 'Bevan:regular', 'Bigelow Rules, display' => 'Bigelow+Rules:regular', 'Bigshot One, display' => 'Bigshot+One:regular', 'Bilbo, handwriting' => 'Bilbo:regular', 'Bilbo Swash Caps, handwriting' => 'Bilbo+Swash+Caps:regular', 'BioRhyme, serif' => 'BioRhyme:200,300,regular,700,800', 'BioRhyme Expanded, serif' => 'BioRhyme+Expanded:200,300,regular,700,800', 'Biryani, sans-serif' => 'Biryani:200,300,regular,600,700,800,900', 'Bitter, serif' => 'Bitter:regular,italic,700', 'Black Ops One, display' => 'Black+Ops+One:regular', 'Bokor, display' => 'Bokor:regular', 'Bonbon, handwriting' => 'Bonbon:regular', 'Boogaloo, display' => 'Boogaloo:regular', 'Bowlby One, display' => 'Bowlby+One:regular', 'Bowlby One SC, display' => 'Bowlby+One+SC:regular', 'Brawler, serif' => 'Brawler:regular', 'Bree Serif, serif' => 'Bree+Serif:regular', 'Bubblegum Sans, display' => 'Bubblegum+Sans:regular', 'Bubbler One, sans-serif' => 'Bubbler+One:regular', 'Buda, display' => 'Buda:300', 'Buenard, serif' => 'Buenard:regular,700', 'Bungee, display' => 'Bungee:regular', 'Bungee Hairline, display' => 'Bungee+Hairline:regular', 'Bungee Inline, display' => 'Bungee+Inline:regular', 'Bungee Outline, display' => 'Bungee+Outline:regular', 'Bungee Shade, display' => 'Bungee+Shade:regular', 'Butcherman, display' => 'Butcherman:regular', 'Butterfly Kids, handwriting' => 'Butterfly+Kids:regular', 'Cabin, sans-serif' => 'Cabin:regular,italic,500,500italic,600,600italic,700,700italic', 'Cabin Condensed, sans-serif' => 'Cabin+Condensed:regular,500,600,700', 'Cabin Sketch, display' => 'Cabin+Sketch:regular,700', 'Caesar Dressing, display' => 'Caesar+Dressing:regular', 'Cagliostro, sans-serif' => 'Cagliostro:regular', 'Cairo, sans-serif' => 'Cairo:200,300,regular,600,700,900', 'Calligraffitti, handwriting' => 'Calligraffitti:regular', 'Cambay, sans-serif' => 'Cambay:regular,italic,700,700italic', 'Cambo, serif' => 'Cambo:regular', 'Candal, sans-serif' => 'Candal:regular', 'Cantarell, sans-serif' => 'Cantarell:regular,italic,700,700italic', 'Cantata One, serif' => 'Cantata+One:regular', 'Cantora One, sans-serif' => 'Cantora+One:regular', 'Capriola, sans-serif' => 'Capriola:regular', 'Cardo, serif' => 'Cardo:regular,italic,700', 'Carme, sans-serif' => 'Carme:regular', 'Carrois Gothic, sans-serif' => 'Carrois+Gothic:regular', 'Carrois Gothic SC, sans-serif' => 'Carrois+Gothic+SC:regular', 'Carter One, display' => 'Carter+One:regular', 'Catamaran, sans-serif' => 'Catamaran:100,200,300,regular,500,600,700,800,900', 'Caudex, serif' => 'Caudex:regular,italic,700,700italic', 'Caveat, handwriting' => 'Caveat:regular,700', 'Caveat Brush, handwriting' => 'Caveat+Brush:regular', 'Cedarville Cursive, handwriting' => 'Cedarville+Cursive:regular', 'Ceviche One, display' => 'Ceviche+One:regular', 'Changa, sans-serif' => 'Changa:200,300,regular,500,600,700,800', 'Changa One, display' => 'Changa+One:regular,italic', 'Chango, display' => 'Chango:regular', 'Chathura, sans-serif' => 'Chathura:100,300,regular,700,800', 'Chau Philomene One, sans-serif' => 'Chau+Philomene+One:regular,italic', 'Chela One, display' => 'Chela+One:regular', 'Chelsea Market, display' => 'Chelsea+Market:regular', 'Chenla, display' => 'Chenla:regular', 'Cherry Cream Soda, display' => 'Cherry+Cream+Soda:regular', 'Cherry Swash, display' => 'Cherry+Swash:regular,700', 'Chewy, display' => 'Chewy:regular', 'Chicle, display' => 'Chicle:regular', 'Chivo, sans-serif' => 'Chivo:300,300italic,regular,italic,700,700italic,900,900italic', 'Chonburi, display' => 'Chonburi:regular', 'Cinzel, serif' => 'Cinzel:regular,700,900', 'Cinzel Decorative, display' => 'Cinzel+Decorative:regular,700,900', 'Clicker Script, handwriting' => 'Clicker+Script:regular', 'Coda, display' => 'Coda:regular,800', 'Coda Caption, sans-serif' => 'Coda+Caption:800', 'Codystar, display' => 'Codystar:300,regular', 'Coiny, display' => 'Coiny:regular', 'Combo, display' => 'Combo:regular', 'Comfortaa, display' => 'Comfortaa:300,regular,700', 'Coming Soon, handwriting' => 'Coming+Soon:regular', 'Concert One, display' => 'Concert+One:regular', 'Condiment, handwriting' => 'Condiment:regular', 'Content, display' => 'Content:regular,700', 'Contrail One, display' => 'Contrail+One:regular', 'Convergence, sans-serif' => 'Convergence:regular', 'Cookie, handwriting' => 'Cookie:regular', 'Copse, serif' => 'Copse:regular', 'Corben, display' => 'Corben:regular,700', 'Cormorant, serif' => 'Cormorant:300,300italic,regular,italic,500,500italic,600,600italic,700,700italic', 'Cormorant Garamond, serif' => 'Cormorant+Garamond:300,300italic,regular,italic,500,500italic,600,600italic,700,700italic', 'Cormorant Infant, serif' => 'Cormorant+Infant:300,300italic,regular,italic,500,500italic,600,600italic,700,700italic', 'Cormorant SC, serif' => 'Cormorant+SC:300,regular,500,600,700', 'Cormorant Unicase, serif' => 'Cormorant+Unicase:300,regular,500,600,700', 'Cormorant Upright, serif' => 'Cormorant+Upright:300,regular,500,600,700', 'Courgette, handwriting' => 'Courgette:regular', 'Cousine, monospace' => 'Cousine:regular,italic,700,700italic', 'Coustard, serif' => 'Coustard:regular,900', 'Covered By Your Grace, handwriting' => 'Covered+By+Your+Grace:regular', 'Crafty Girls, handwriting' => 'Crafty+Girls:regular', 'Creepster, display' => 'Creepster:regular', 'Crete Round, serif' => 'Crete+Round:regular,italic', 'Crimson Text, serif' => 'Crimson+Text:regular,italic,600,600italic,700,700italic', 'Croissant One, display' => 'Croissant+One:regular', 'Crushed, display' => 'Crushed:regular', 'Cuprum, sans-serif' => 'Cuprum:regular,italic,700,700italic', 'Cutive, serif' => 'Cutive:regular', 'Cutive Mono, monospace' => 'Cutive+Mono:regular', 'Damion, handwriting' => 'Damion:regular', 'Dancing Script, handwriting' => 'Dancing+Script:regular,700', 'Dangrek, display' => 'Dangrek:regular', 'David Libre, serif' => 'David+Libre:regular,500,700', 'Dawning of a New Day, handwriting' => 'Dawning+of+a+New+Day:regular', 'Days One, sans-serif' => 'Days+One:regular', 'Dekko, handwriting' => 'Dekko:regular', 'Delius, handwriting' => 'Delius:regular', 'Delius Swash Caps, handwriting' => 'Delius+Swash+Caps:regular', 'Delius Unicase, handwriting' => 'Delius+Unicase:regular,700', 'Della Respira, serif' => 'Della+Respira:regular', 'Denk One, sans-serif' => 'Denk+One:regular', 'Devonshire, handwriting' => 'Devonshire:regular', 'Dhurjati, sans-serif' => 'Dhurjati:regular', 'Didact Gothic, sans-serif' => 'Didact+Gothic:regular', 'Diplomata, display' => 'Diplomata:regular', 'Diplomata SC, display' => 'Diplomata+SC:regular', 'Domine, serif' => 'Domine:regular,700', 'Donegal One, serif' => 'Donegal+One:regular', 'Doppio One, sans-serif' => 'Doppio+One:regular', 'Dorsa, sans-serif' => 'Dorsa:regular', 'Dosis, sans-serif' => 'Dosis:200,300,regular,500,600,700,800', 'Dr Sugiyama, handwriting' => 'Dr+Sugiyama:regular', 'Droid Sans, sans-serif' => 'Droid+Sans:regular,700', 'Droid Sans Mono, monospace' => 'Droid+Sans+Mono:regular', 'Droid Serif, serif' => 'Droid+Serif:regular,italic,700,700italic', 'Duru Sans, sans-serif' => 'Duru+Sans:regular', 'Dynalight, display' => 'Dynalight:regular', 'EB Garamond, serif' => 'EB+Garamond:regular', 'Eagle Lake, handwriting' => 'Eagle+Lake:regular', 'Eater, display' => 'Eater:regular', 'Economica, sans-serif' => 'Economica:regular,italic,700,700italic', 'Eczar, serif' => 'Eczar:regular,500,600,700,800', 'El Messiri, sans-serif' => 'El+Messiri:regular,500,600,700', 'Electrolize, sans-serif' => 'Electrolize:regular', 'Elsie, display' => 'Elsie:regular,900', 'Elsie Swash Caps, display' => 'Elsie+Swash+Caps:regular,900', 'Emblema One, display' => 'Emblema+One:regular', 'Emilys Candy, display' => 'Emilys+Candy:regular', 'Encode Sans, sans-serif' => 'Encode+Sans:100,200,300,regular,500,600,700,800,900', 'Encode Sans Condensed, sans-serif' => 'Encode+Sans+Condensed:100,200,300,regular,500,600,700,800,900', 'Encode Sans Expanded, sans-serif' => 'Encode+Sans+Expanded:100,200,300,regular,500,600,700,800,900', 'Encode Sans Semi Condensed, sans-serif' => 'Encode+Sans+Semi+Condensed:100,200,300,regular,500,600,700,800,900', 'Encode Sans Semi Expanded, sans-serif' => 'Encode+Sans+Semi+Expanded:100,200,300,regular,500,600,700,800,900', 'Engagement, handwriting' => 'Engagement:regular', 'Englebert, sans-serif' => 'Englebert:regular', 'Enriqueta, serif' => 'Enriqueta:regular,700', 'Erica One, display' => 'Erica+One:regular', 'Esteban, serif' => 'Esteban:regular', 'Euphoria Script, handwriting' => 'Euphoria+Script:regular', 'Ewert, display' => 'Ewert:regular', 'Exo, sans-serif' => 'Exo:100,100italic,200,200italic,300,300italic,regular,italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic', 'Exo 2, sans-serif' => 'Exo+2:100,100italic,200,200italic,300,300italic,regular,italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic', 'Expletus Sans, display' => 'Expletus+Sans:regular,italic,500,500italic,600,600italic,700,700italic', 'Fanwood Text, serif' => 'Fanwood+Text:regular,italic', 'Farsan, display' => 'Farsan:regular', 'Fascinate, display' => 'Fascinate:regular', 'Fascinate Inline, display' => 'Fascinate+Inline:regular', 'Faster One, display' => 'Faster+One:regular', 'Fasthand, serif' => 'Fasthand:regular', 'Fauna One, serif' => 'Fauna+One:regular', 'Faustina, serif' => 'Faustina:regular,italic,500,500italic,600,600italic,700,700italic', 'Federant, display' => 'Federant:regular', 'Federo, sans-serif' => 'Federo:regular', 'Felipa, handwriting' => 'Felipa:regular', 'Fenix, serif' => 'Fenix:regular', 'Finger Paint, display' => 'Finger+Paint:regular', 'Fira Mono, monospace' => 'Fira+Mono:regular,500,700', 'Fira Sans, sans-serif' => 'Fira+Sans:100,100italic,200,200italic,300,300italic,regular,italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic', 'Fira Sans Condensed, sans-serif' => 'Fira+Sans+Condensed:100,100italic,200,200italic,300,300italic,regular,italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic', 'Fira Sans Extra Condensed, sans-serif' => 'Fira+Sans+Extra+Condensed:100,100italic,200,200italic,300,300italic,regular,italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic', 'Fjalla One, sans-serif' => 'Fjalla+One:regular', 'Fjord One, serif' => 'Fjord+One:regular', 'Flamenco, display' => 'Flamenco:300,regular', 'Flavors, display' => 'Flavors:regular', 'Fondamento, handwriting' => 'Fondamento:regular,italic', 'Fontdiner Swanky, display' => 'Fontdiner+Swanky:regular', 'Forum, display' => 'Forum:regular', 'Francois One, sans-serif' => 'Francois+One:regular', 'Frank Ruhl Libre, serif' => 'Frank+Ruhl+Libre:300,regular,500,700,900', 'Freckle Face, display' => 'Freckle+Face:regular', 'Fredericka the Great, display' => 'Fredericka+the+Great:regular', 'Fredoka One, display' => 'Fredoka+One:regular', 'Freehand, display' => 'Freehand:regular', 'Fresca, sans-serif' => 'Fresca:regular', 'Frijole, display' => 'Frijole:regular', 'Fruktur, display' => 'Fruktur:regular', 'Fugaz One, display' => 'Fugaz+One:regular', 'GFS Didot, serif' => 'GFS+Didot:regular', 'GFS Neohellenic, sans-serif' => 'GFS+Neohellenic:regular,italic,700,700italic', 'Gabriela, serif' => 'Gabriela:regular', 'Gafata, sans-serif' => 'Gafata:regular', 'Galada, display' => 'Galada:regular', 'Galdeano, sans-serif' => 'Galdeano:regular', 'Galindo, display' => 'Galindo:regular', 'Gentium Basic, serif' => 'Gentium+Basic:regular,italic,700,700italic', 'Gentium Book Basic, serif' => 'Gentium+Book+Basic:regular,italic,700,700italic', 'Geo, sans-serif' => 'Geo:regular,italic', 'Geostar, display' => 'Geostar:regular', 'Geostar Fill, display' => 'Geostar+Fill:regular', 'Germania One, display' => 'Germania+One:regular', 'Gidugu, sans-serif' => 'Gidugu:regular', 'Gilda Display, serif' => 'Gilda+Display:regular', 'Give You Glory, handwriting' => 'Give+You+Glory:regular', 'Glass Antiqua, display' => 'Glass+Antiqua:regular', 'Glegoo, serif' => 'Glegoo:regular,700', 'Gloria Hallelujah, handwriting' => 'Gloria+Hallelujah:regular', 'Goblin One, display' => 'Goblin+One:regular', 'Gochi Hand, handwriting' => 'Gochi+Hand:regular', 'Gorditas, display' => 'Gorditas:regular,700', 'Goudy Bookletter 1911, serif' => 'Goudy+Bookletter+1911:regular', 'Graduate, display' => 'Graduate:regular', 'Grand Hotel, handwriting' => 'Grand+Hotel:regular', 'Gravitas One, display' => 'Gravitas+One:regular', 'Great Vibes, handwriting' => 'Great+Vibes:regular', 'Griffy, display' => 'Griffy:regular', 'Gruppo, display' => 'Gruppo:regular', 'Gudea, sans-serif' => 'Gudea:regular,italic,700', 'Gurajada, serif' => 'Gurajada:regular', 'Habibi, serif' => 'Habibi:regular', 'Halant, serif' => 'Halant:300,regular,500,600,700', 'Hammersmith One, sans-serif' => 'Hammersmith+One:regular', 'Hanalei, display' => 'Hanalei:regular', 'Hanalei Fill, display' => 'Hanalei+Fill:regular', 'Handlee, handwriting' => 'Handlee:regular', 'Hanuman, serif' => 'Hanuman:regular,700', 'Happy Monkey, display' => 'Happy+Monkey:regular', 'Harmattan, sans-serif' => 'Harmattan:regular', 'Headland One, serif' => 'Headland+One:regular', 'Heebo, sans-serif' => 'Heebo:100,300,regular,500,700,800,900', 'Henny Penny, display' => 'Henny+Penny:regular', 'Herr Von Muellerhoff, handwriting' => 'Herr+Von+Muellerhoff:regular', 'Hind, sans-serif' => 'Hind:300,regular,500,600,700', 'Hind Guntur, sans-serif' => 'Hind+Guntur:300,regular,500,600,700', 'Hind Madurai, sans-serif' => 'Hind+Madurai:300,regular,500,600,700', 'Hind Siliguri, sans-serif' => 'Hind+Siliguri:300,regular,500,600,700', 'Hind Vadodara, sans-serif' => 'Hind+Vadodara:300,regular,500,600,700', 'Holtwood One SC, serif' => 'Holtwood+One+SC:regular', 'Homemade Apple, handwriting' => 'Homemade+Apple:regular', 'Homenaje, sans-serif' => 'Homenaje:regular', 'IM Fell DW Pica, serif' => 'IM+Fell+DW+Pica:regular,italic', 'IM Fell DW Pica SC, serif' => 'IM+Fell+DW+Pica+SC:regular', 'IM Fell Double Pica, serif' => 'IM+Fell+Double+Pica:regular,italic', 'IM Fell Double Pica SC, serif' => 'IM+Fell+Double+Pica+SC:regular', 'IM Fell English, serif' => 'IM+Fell+English:regular,italic', 'IM Fell English SC, serif' => 'IM+Fell+English+SC:regular', 'IM Fell French Canon, serif' => 'IM+Fell+French+Canon:regular,italic', 'IM Fell French Canon SC, serif' => 'IM+Fell+French+Canon+SC:regular', 'IM Fell Great Primer, serif' => 'IM+Fell+Great+Primer:regular,italic', 'IM Fell Great Primer SC, serif' => 'IM+Fell+Great+Primer+SC:regular', 'Iceberg, display' => 'Iceberg:regular', 'Iceland, display' => 'Iceland:regular', 'Imprima, sans-serif' => 'Imprima:regular', 'Inconsolata, monospace' => 'Inconsolata:regular,700', 'Inder, sans-serif' => 'Inder:regular', 'Indie Flower, handwriting' => 'Indie+Flower:regular', 'Inika, serif' => 'Inika:regular,700', 'Inknut Antiqua, serif' => 'Inknut+Antiqua:300,regular,500,600,700,800,900', 'Irish Grover, display' => 'Irish+Grover:regular', 'Istok Web, sans-serif' => 'Istok+Web:regular,italic,700,700italic', 'Italiana, serif' => 'Italiana:regular', 'Italianno, handwriting' => 'Italianno:regular', 'Itim, handwriting' => 'Itim:regular', 'Jacques Francois, serif' => 'Jacques+Francois:regular', 'Jacques Francois Shadow, display' => 'Jacques+Francois+Shadow:regular', 'Jaldi, sans-serif' => 'Jaldi:regular,700', 'Jim Nightshade, handwriting' => 'Jim+Nightshade:regular', 'Jockey One, sans-serif' => 'Jockey+One:regular', 'Jolly Lodger, display' => 'Jolly+Lodger:regular', 'Jomhuria, display' => 'Jomhuria:regular', 'Josefin Sans, sans-serif' => 'Josefin+Sans:100,100italic,300,300italic,regular,italic,600,600italic,700,700italic', 'Josefin Slab, serif' => 'Josefin+Slab:100,100italic,300,300italic,regular,italic,600,600italic,700,700italic', 'Joti One, display' => 'Joti+One:regular', 'Judson, serif' => 'Judson:regular,italic,700', 'Julee, handwriting' => 'Julee:regular', 'Julius Sans One, sans-serif' => 'Julius+Sans+One:regular', 'Junge, serif' => 'Junge:regular', 'Jura, sans-serif' => 'Jura:300,regular,500,600,700', 'Just Another Hand, handwriting' => 'Just+Another+Hand:regular', 'Just Me Again Down Here, handwriting' => 'Just+Me+Again+Down+Here:regular', 'Kadwa, serif' => 'Kadwa:regular,700', 'Kalam, handwriting' => 'Kalam:300,regular,700', 'Kameron, serif' => 'Kameron:regular,700', 'Kanit, sans-serif' => 'Kanit:100,100italic,200,200italic,300,300italic,regular,italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic', 'Kantumruy, sans-serif' => 'Kantumruy:300,regular,700', 'Karla, sans-serif' => 'Karla:regular,italic,700,700italic', 'Karma, serif' => 'Karma:300,regular,500,600,700', 'Katibeh, display' => 'Katibeh:regular', 'Kaushan Script, handwriting' => 'Kaushan+Script:regular', 'Kavivanar, handwriting' => 'Kavivanar:regular', 'Kavoon, display' => 'Kavoon:regular', 'Kdam Thmor, display' => 'Kdam+Thmor:regular', 'Keania One, display' => 'Keania+One:regular', 'Kelly Slab, display' => 'Kelly+Slab:regular', 'Kenia, display' => 'Kenia:regular', 'Khand, sans-serif' => 'Khand:300,regular,500,600,700', 'Khmer, display' => 'Khmer:regular', 'Khula, sans-serif' => 'Khula:300,regular,600,700,800', 'Kite One, sans-serif' => 'Kite+One:regular', 'Knewave, display' => 'Knewave:regular', 'Kotta One, serif' => 'Kotta+One:regular', 'Koulen, display' => 'Koulen:regular', 'Kranky, display' => 'Kranky:regular', 'Kreon, serif' => 'Kreon:300,regular,700', 'Kristi, handwriting' => 'Kristi:regular', 'Krona One, sans-serif' => 'Krona+One:regular', 'Kumar One, display' => 'Kumar+One:regular', 'Kumar One Outline, display' => 'Kumar+One+Outline:regular', 'Kurale, serif' => 'Kurale:regular', 'La Belle Aurore, handwriting' => 'La+Belle+Aurore:regular', 'Laila, serif' => 'Laila:300,regular,500,600,700', 'Lakki Reddy, handwriting' => 'Lakki+Reddy:regular', 'Lalezar, display' => 'Lalezar:regular', 'Lancelot, display' => 'Lancelot:regular', 'Lateef, handwriting' => 'Lateef:regular', 'Lato, sans-serif' => 'Lato:100,100italic,300,300italic,regular,italic,700,700italic,900,900italic', 'League Script, handwriting' => 'League+Script:regular', 'Leckerli One, handwriting' => 'Leckerli+One:regular', 'Ledger, serif' => 'Ledger:regular', 'Lekton, sans-serif' => 'Lekton:regular,italic,700', 'Lemon, display' => 'Lemon:regular', 'Lemonada, display' => 'Lemonada:300,regular,600,700', 'Libre Barcode 128, display' => 'Libre+Barcode+128:regular', 'Libre Barcode 128 Text, display' => 'Libre+Barcode+128+Text:regular', 'Libre Barcode 39, display' => 'Libre+Barcode+39:regular', 'Libre Barcode 39 Extended, display' => 'Libre+Barcode+39+Extended:regular', 'Libre Barcode 39 Extended Text, display' => 'Libre+Barcode+39+Extended+Text:regular', 'Libre Barcode 39 Text, display' => 'Libre+Barcode+39+Text:regular', 'Libre Baskerville, serif' => 'Libre+Baskerville:regular,italic,700', 'Libre Franklin, sans-serif' => 'Libre+Franklin:100,100italic,200,200italic,300,300italic,regular,italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic', 'Life Savers, display' => 'Life+Savers:regular,700', 'Lilita One, display' => 'Lilita+One:regular', 'Lily Script One, display' => 'Lily+Script+One:regular', 'Limelight, display' => 'Limelight:regular', 'Linden Hill, serif' => 'Linden+Hill:regular,italic', 'Lobster, display' => 'Lobster:regular', 'Lobster Two, display' => 'Lobster+Two:regular,italic,700,700italic', 'Londrina Outline, display' => 'Londrina+Outline:regular', 'Londrina Shadow, display' => 'Londrina+Shadow:regular', 'Londrina Sketch, display' => 'Londrina+Sketch:regular', 'Londrina Solid, display' => 'Londrina+Solid:100,300,regular,900', 'Lora, serif' => 'Lora:regular,italic,700,700italic', 'Love Ya Like A Sister, display' => 'Love+Ya+Like+A+Sister:regular', 'Loved by the King, handwriting' => 'Loved+by+the+King:regular', 'Lovers Quarrel, handwriting' => 'Lovers+Quarrel:regular', 'Luckiest Guy, display' => 'Luckiest+Guy:regular', 'Lusitana, serif' => 'Lusitana:regular,700', 'Lustria, serif' => 'Lustria:regular', 'Macondo, display' => 'Macondo:regular', 'Macondo Swash Caps, display' => 'Macondo+Swash+Caps:regular', 'Mada, sans-serif' => 'Mada:200,300,regular,500,600,700,900', 'Magra, sans-serif' => 'Magra:regular,700', 'Maiden Orange, display' => 'Maiden+Orange:regular', 'Maitree, serif' => 'Maitree:200,300,regular,500,600,700', 'Mako, sans-serif' => 'Mako:regular', 'Mallanna, sans-serif' => 'Mallanna:regular', 'Mandali, sans-serif' => 'Mandali:regular', 'Manuale, serif' => 'Manuale:regular,italic,500,500italic,600,600italic,700,700italic', 'Marcellus, serif' => 'Marcellus:regular', 'Marcellus SC, serif' => 'Marcellus+SC:regular', 'Marck Script, handwriting' => 'Marck+Script:regular', 'Margarine, display' => 'Margarine:regular', 'Marko One, serif' => 'Marko+One:regular', 'Marmelad, sans-serif' => 'Marmelad:regular', 'Martel, serif' => 'Martel:200,300,regular,600,700,800,900', 'Martel Sans, sans-serif' => 'Martel+Sans:200,300,regular,600,700,800,900', 'Marvel, sans-serif' => 'Marvel:regular,italic,700,700italic', 'Mate, serif' => 'Mate:regular,italic', 'Mate SC, serif' => 'Mate+SC:regular', 'Maven Pro, sans-serif' => 'Maven+Pro:regular,500,700,900', 'McLaren, display' => 'McLaren:regular', 'Meddon, handwriting' => 'Meddon:regular', 'MedievalSharp, display' => 'MedievalSharp:regular', 'Medula One, display' => 'Medula+One:regular', 'Meera Inimai, sans-serif' => 'Meera+Inimai:regular', 'Megrim, display' => 'Megrim:regular', 'Meie Script, handwriting' => 'Meie+Script:regular', 'Merienda, handwriting' => 'Merienda:regular,700', 'Merienda One, handwriting' => 'Merienda+One:regular', 'Merriweather, serif' => 'Merriweather:300,300italic,regular,italic,700,700italic,900,900italic', 'Merriweather Sans, sans-serif' => 'Merriweather+Sans:300,300italic,regular,italic,700,700italic,800,800italic', 'Metal, display' => 'Metal:regular', 'Metal Mania, display' => 'Metal+Mania:regular', 'Metamorphous, display' => 'Metamorphous:regular', 'Metrophobic, sans-serif' => 'Metrophobic:regular', 'Michroma, sans-serif' => 'Michroma:regular', 'Milonga, display' => 'Milonga:regular', 'Miltonian, display' => 'Miltonian:regular', 'Miltonian Tattoo, display' => 'Miltonian+Tattoo:regular', 'Miniver, display' => 'Miniver:regular', 'Miriam Libre, sans-serif' => 'Miriam+Libre:regular,700', 'Mirza, display' => 'Mirza:regular,500,600,700', 'Miss Fajardose, handwriting' => 'Miss+Fajardose:regular', 'Mitr, sans-serif' => 'Mitr:200,300,regular,500,600,700', 'Modak, display' => 'Modak:regular', 'Modern Antiqua, display' => 'Modern+Antiqua:regular', 'Mogra, display' => 'Mogra:regular', 'Molengo, sans-serif' => 'Molengo:regular', 'Molle, handwriting' => 'Molle:italic', 'Monda, sans-serif' => 'Monda:regular,700', 'Monofett, display' => 'Monofett:regular', 'Monoton, display' => 'Monoton:regular', 'Monsieur La Doulaise, handwriting' => 'Monsieur+La+Doulaise:regular', 'Montaga, serif' => 'Montaga:regular', 'Montez, handwriting' => 'Montez:regular', 'Montserrat, sans-serif' => 'Montserrat:100,100italic,200,200italic,300,300italic,regular,italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic', 'Montserrat Alternates, sans-serif' => 'Montserrat+Alternates:100,100italic,200,200italic,300,300italic,regular,italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic', 'Montserrat Subrayada, sans-serif' => 'Montserrat+Subrayada:regular,700', 'Moul, display' => 'Moul:regular', 'Moulpali, display' => 'Moulpali:regular', 'Mountains of Christmas, display' => 'Mountains+of+Christmas:regular,700', 'Mouse Memoirs, sans-serif' => 'Mouse+Memoirs:regular', 'Mr Bedfort, handwriting' => 'Mr+Bedfort:regular', 'Mr Dafoe, handwriting' => 'Mr+Dafoe:regular', 'Mr De Haviland, handwriting' => 'Mr+De+Haviland:regular', 'Mrs Saint Delafield, handwriting' => 'Mrs+Saint+Delafield:regular', 'Mrs Sheppards, handwriting' => 'Mrs+Sheppards:regular', 'Mukta, sans-serif' => 'Mukta:200,300,regular,500,600,700,800', 'Mukta Mahee, sans-serif' => 'Mukta+Mahee:200,300,regular,500,600,700,800', 'Mukta Malar, sans-serif' => 'Mukta+Malar:200,300,regular,500,600,700,800', 'Mukta Vaani, sans-serif' => 'Mukta+Vaani:200,300,regular,500,600,700,800', 'Muli, sans-serif' => 'Muli:200,200italic,300,300italic,regular,italic,600,600italic,700,700italic,800,800italic,900,900italic', 'Mystery Quest, display' => 'Mystery+Quest:regular', 'NTR, sans-serif' => 'NTR:regular', 'Neucha, handwriting' => 'Neucha:regular', 'Neuton, serif' => 'Neuton:200,300,regular,italic,700,800', 'New Rocker, display' => 'New+Rocker:regular', 'News Cycle, sans-serif' => 'News+Cycle:regular,700', 'Niconne, handwriting' => 'Niconne:regular', 'Nixie One, display' => 'Nixie+One:regular', 'Nobile, sans-serif' => 'Nobile:regular,italic,500,500italic,700,700italic', 'Nokora, serif' => 'Nokora:regular,700', 'Norican, handwriting' => 'Norican:regular', 'Nosifer, display' => 'Nosifer:regular', 'Nothing You Could Do, handwriting' => 'Nothing+You+Could+Do:regular', 'Noticia Text, serif' => 'Noticia+Text:regular,italic,700,700italic', 'Noto Sans, sans-serif' => 'Noto+Sans:regular,italic,700,700italic', 'Noto Serif, serif' => 'Noto+Serif:regular,italic,700,700italic', 'Nova Cut, display' => 'Nova+Cut:regular', 'Nova Flat, display' => 'Nova+Flat:regular', 'Nova Mono, monospace' => 'Nova+Mono:regular', 'Nova Oval, display' => 'Nova+Oval:regular', 'Nova Round, display' => 'Nova+Round:regular', 'Nova Script, display' => 'Nova+Script:regular', 'Nova Slim, display' => 'Nova+Slim:regular', 'Nova Square, display' => 'Nova+Square:regular', 'Numans, sans-serif' => 'Numans:regular', 'Nunito, sans-serif' => 'Nunito:200,200italic,300,300italic,regular,italic,600,600italic,700,700italic,800,800italic,900,900italic', 'Nunito Sans, sans-serif' => 'Nunito+Sans:200,200italic,300,300italic,regular,italic,600,600italic,700,700italic,800,800italic,900,900italic', 'Odor Mean Chey, display' => 'Odor+Mean+Chey:regular', 'Offside, display' => 'Offside:regular', 'Old Standard TT, serif' => 'Old+Standard+TT:regular,italic,700', 'Oldenburg, display' => 'Oldenburg:regular', 'Oleo Script, display' => 'Oleo+Script:regular,700', 'Oleo Script Swash Caps, display' => 'Oleo+Script+Swash+Caps:regular,700', 'Open Sans, sans-serif' => 'Open+Sans:300,300italic,regular,italic,600,600italic,700,700italic,800,800italic', 'Open Sans Condensed, sans-serif' => 'Open+Sans+Condensed:300,300italic,700', 'Oranienbaum, serif' => 'Oranienbaum:regular', 'Orbitron, sans-serif' => 'Orbitron:regular,500,700,900', 'Oregano, display' => 'Oregano:regular,italic', 'Orienta, sans-serif' => 'Orienta:regular', 'Original Surfer, display' => 'Original+Surfer:regular', 'Oswald, sans-serif' => 'Oswald:200,300,regular,500,600,700', 'Over the Rainbow, handwriting' => 'Over+the+Rainbow:regular', 'Overlock, display' => 'Overlock:regular,italic,700,700italic,900,900italic', 'Overlock SC, display' => 'Overlock+SC:regular', 'Overpass, sans-serif' => 'Overpass:100,100italic,200,200italic,300,300italic,regular,italic,600,600italic,700,700italic,800,800italic,900,900italic', 'Overpass Mono, monospace' => 'Overpass+Mono:300,regular,600,700', 'Ovo, serif' => 'Ovo:regular', 'Oxygen, sans-serif' => 'Oxygen:300,regular,700', 'Oxygen Mono, monospace' => 'Oxygen+Mono:regular', 'PT Mono, monospace' => 'PT+Mono:regular', 'PT Sans, sans-serif' => 'PT+Sans:regular,italic,700,700italic', 'PT Sans Caption, sans-serif' => 'PT+Sans+Caption:regular,700', 'PT Sans Narrow, sans-serif' => 'PT+Sans+Narrow:regular,700', 'PT Serif, serif' => 'PT+Serif:regular,italic,700,700italic', 'PT Serif Caption, serif' => 'PT+Serif+Caption:regular,italic', 'Pacifico, handwriting' => 'Pacifico:regular', 'Padauk, sans-serif' => 'Padauk:regular,700', 'Palanquin, sans-serif' => 'Palanquin:100,200,300,regular,500,600,700', 'Palanquin Dark, sans-serif' => 'Palanquin+Dark:regular,500,600,700', 'Pangolin, handwriting' => 'Pangolin:regular', 'Paprika, display' => 'Paprika:regular', 'Parisienne, handwriting' => 'Parisienne:regular', 'Passero One, display' => 'Passero+One:regular', 'Passion One, display' => 'Passion+One:regular,700,900', 'Pathway Gothic One, sans-serif' => 'Pathway+Gothic+One:regular', 'Patrick Hand, handwriting' => 'Patrick+Hand:regular', 'Patrick Hand SC, handwriting' => 'Patrick+Hand+SC:regular', 'Pattaya, sans-serif' => 'Pattaya:regular', 'Patua One, display' => 'Patua+One:regular', 'Pavanam, sans-serif' => 'Pavanam:regular', 'Paytone One, sans-serif' => 'Paytone+One:regular', 'Peddana, serif' => 'Peddana:regular', 'Peralta, display' => 'Peralta:regular', 'Permanent Marker, handwriting' => 'Permanent+Marker:regular', 'Petit Formal Script, handwriting' => 'Petit+Formal+Script:regular', 'Petrona, serif' => 'Petrona:regular', 'Philosopher, sans-serif' => 'Philosopher:regular,italic,700,700italic', 'Piedra, display' => 'Piedra:regular', 'Pinyon Script, handwriting' => 'Pinyon+Script:regular', 'Pirata One, display' => 'Pirata+One:regular', 'Plaster, display' => 'Plaster:regular', 'Play, sans-serif' => 'Play:regular,700', 'Playball, display' => 'Playball:regular', 'Playfair Display, serif' => 'Playfair+Display:regular,italic,700,700italic,900,900italic', 'Playfair Display SC, serif' => 'Playfair+Display+SC:regular,italic,700,700italic,900,900italic', 'Podkova, serif' => 'Podkova:regular,500,600,700,800', 'Poiret One, display' => 'Poiret+One:regular', 'Poller One, display' => 'Poller+One:regular', 'Poly, serif' => 'Poly:regular,italic', 'Pompiere, display' => 'Pompiere:regular', 'Pontano Sans, sans-serif' => 'Pontano+Sans:regular', 'Poppins, sans-serif' => 'Poppins:100,100italic,200,200italic,300,300italic,regular,italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic', 'Port Lligat Sans, sans-serif' => 'Port+Lligat+Sans:regular', 'Port Lligat Slab, serif' => 'Port+Lligat+Slab:regular', 'Pragati Narrow, sans-serif' => 'Pragati+Narrow:regular,700', 'Prata, serif' => 'Prata:regular', 'Preahvihear, display' => 'Preahvihear:regular', 'Press Start 2P, display' => 'Press+Start+2P:regular', 'Pridi, serif' => 'Pridi:200,300,regular,500,600,700', 'Princess Sofia, handwriting' => 'Princess+Sofia:regular', 'Prociono, serif' => 'Prociono:regular', 'Prompt, sans-serif' => 'Prompt:100,100italic,200,200italic,300,300italic,regular,italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic', 'Prosto One, display' => 'Prosto+One:regular', 'Proza Libre, sans-serif' => 'Proza+Libre:regular,italic,500,500italic,600,600italic,700,700italic,800,800italic', 'Puritan, sans-serif' => 'Puritan:regular,italic,700,700italic', 'Purple Purse, display' => 'Purple+Purse:regular', 'Quando, serif' => 'Quando:regular', 'Quantico, sans-serif' => 'Quantico:regular,italic,700,700italic', 'Quattrocento, serif' => 'Quattrocento:regular,700', 'Quattrocento Sans, sans-serif' => 'Quattrocento+Sans:regular,italic,700,700italic', 'Questrial, sans-serif' => 'Questrial:regular', 'Quicksand, sans-serif' => 'Quicksand:300,regular,500,700', 'Quintessential, handwriting' => 'Quintessential:regular', 'Qwigley, handwriting' => 'Qwigley:regular', 'Racing Sans One, display' => 'Racing+Sans+One:regular', 'Radley, serif' => 'Radley:regular,italic', 'Rajdhani, sans-serif' => 'Rajdhani:300,regular,500,600,700', 'Rakkas, display' => 'Rakkas:regular', 'Raleway, sans-serif' => 'Raleway:100,100italic,200,200italic,300,300italic,regular,italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic', 'Raleway Dots, display' => 'Raleway+Dots:regular', 'Ramabhadra, sans-serif' => 'Ramabhadra:regular', 'Ramaraja, serif' => 'Ramaraja:regular', 'Rambla, sans-serif' => 'Rambla:regular,italic,700,700italic', 'Rammetto One, display' => 'Rammetto+One:regular', 'Ranchers, display' => 'Ranchers:regular', 'Rancho, handwriting' => 'Rancho:regular', 'Ranga, display' => 'Ranga:regular,700', 'Rasa, serif' => 'Rasa:300,regular,500,600,700', 'Rationale, sans-serif' => 'Rationale:regular', 'Ravi Prakash, display' => 'Ravi+Prakash:regular', 'Redressed, handwriting' => 'Redressed:regular', 'Reem Kufi, sans-serif' => 'Reem+Kufi:regular', 'Reenie Beanie, handwriting' => 'Reenie+Beanie:regular', 'Revalia, display' => 'Revalia:regular', 'Rhodium Libre, serif' => 'Rhodium+Libre:regular', 'Ribeye, display' => 'Ribeye:regular', 'Ribeye Marrow, display' => 'Ribeye+Marrow:regular', 'Righteous, display' => 'Righteous:regular', 'Risque, display' => 'Risque:regular', 'Roboto, sans-serif' => 'Roboto:100,100italic,300,300italic,regular,italic,500,500italic,700,700italic,900,900italic', 'Roboto Condensed, sans-serif' => 'Roboto+Condensed:300,300italic,regular,italic,700,700italic', 'Roboto Mono, monospace' => 'Roboto+Mono:100,100italic,300,300italic,regular,italic,500,500italic,700,700italic', 'Roboto Slab, serif' => 'Roboto+Slab:100,300,regular,700', 'Rochester, handwriting' => 'Rochester:regular', 'Rock Salt, handwriting' => 'Rock+Salt:regular', 'Rokkitt, serif' => 'Rokkitt:100,200,300,regular,500,600,700,800,900', 'Romanesco, handwriting' => 'Romanesco:regular', 'Ropa Sans, sans-serif' => 'Ropa+Sans:regular,italic', 'Rosario, sans-serif' => 'Rosario:regular,italic,700,700italic', 'Rosarivo, serif' => 'Rosarivo:regular,italic', 'Rouge Script, handwriting' => 'Rouge+Script:regular', 'Rozha One, serif' => 'Rozha+One:regular', 'Rubik, sans-serif' => 'Rubik:300,300italic,regular,italic,500,500italic,700,700italic,900,900italic', 'Rubik Mono One, sans-serif' => 'Rubik+Mono+One:regular', 'Ruda, sans-serif' => 'Ruda:regular,700,900', 'Rufina, serif' => 'Rufina:regular,700', 'Ruge Boogie, handwriting' => 'Ruge+Boogie:regular', 'Ruluko, sans-serif' => 'Ruluko:regular', 'Rum Raisin, sans-serif' => 'Rum+Raisin:regular', 'Ruslan Display, display' => 'Ruslan+Display:regular', 'Russo One, sans-serif' => 'Russo+One:regular', 'Ruthie, handwriting' => 'Ruthie:regular', 'Rye, display' => 'Rye:regular', 'Sacramento, handwriting' => 'Sacramento:regular', 'Sahitya, serif' => 'Sahitya:regular,700', 'Sail, display' => 'Sail:regular', 'Saira, sans-serif' => 'Saira:100,200,300,regular,500,600,700,800,900', 'Saira Condensed, sans-serif' => 'Saira+Condensed:100,200,300,regular,500,600,700,800,900', 'Saira Extra Condensed, sans-serif' => 'Saira+Extra+Condensed:100,200,300,regular,500,600,700,800,900', 'Saira Semi Condensed, sans-serif' => 'Saira+Semi+Condensed:100,200,300,regular,500,600,700,800,900', 'Salsa, display' => 'Salsa:regular', 'Sanchez, serif' => 'Sanchez:regular,italic', 'Sancreek, display' => 'Sancreek:regular', 'Sansita, sans-serif' => 'Sansita:regular,italic,700,700italic,800,800italic,900,900italic', 'Sarala, sans-serif' => 'Sarala:regular,700', 'Sarina, display' => 'Sarina:regular', 'Sarpanch, sans-serif' => 'Sarpanch:regular,500,600,700,800,900', 'Satisfy, handwriting' => 'Satisfy:regular', 'Scada, sans-serif' => 'Scada:regular,italic,700,700italic', 'Scheherazade, serif' => 'Scheherazade:regular,700', 'Schoolbell, handwriting' => 'Schoolbell:regular', 'Scope One, serif' => 'Scope+One:regular', 'Seaweed Script, display' => 'Seaweed+Script:regular', 'Secular One, sans-serif' => 'Secular+One:regular', 'Sedgwick Ave, handwriting' => 'Sedgwick+Ave:regular', 'Sedgwick Ave Display, handwriting' => 'Sedgwick+Ave+Display:regular', 'Sevillana, display' => 'Sevillana:regular', 'Seymour One, sans-serif' => 'Seymour+One:regular', 'Shadows Into Light, handwriting' => 'Shadows+Into+Light:regular', 'Shadows Into Light Two, handwriting' => 'Shadows+Into+Light+Two:regular', 'Shanti, sans-serif' => 'Shanti:regular', 'Share, display' => 'Share:regular,italic,700,700italic', 'Share Tech, sans-serif' => 'Share+Tech:regular', 'Share Tech Mono, monospace' => 'Share+Tech+Mono:regular', 'Shojumaru, display' => 'Shojumaru:regular', 'Short Stack, handwriting' => 'Short+Stack:regular', 'Shrikhand, display' => 'Shrikhand:regular', 'Siemreap, display' => 'Siemreap:regular', 'Sigmar One, display' => 'Sigmar+One:regular', 'Signika, sans-serif' => 'Signika:300,regular,600,700', 'Signika Negative, sans-serif' => 'Signika+Negative:300,regular,600,700', 'Simonetta, display' => 'Simonetta:regular,italic,900,900italic', 'Sintony, sans-serif' => 'Sintony:regular,700', 'Sirin Stencil, display' => 'Sirin+Stencil:regular', 'Six Caps, sans-serif' => 'Six+Caps:regular', 'Skranji, display' => 'Skranji:regular,700', 'Slabo 13px, serif' => 'Slabo+13px:regular', 'Slabo 27px, serif' => 'Slabo+27px:regular', 'Slackey, display' => 'Slackey:regular', 'Smokum, display' => 'Smokum:regular', 'Smythe, display' => 'Smythe:regular', 'Sniglet, display' => 'Sniglet:regular,800', 'Snippet, sans-serif' => 'Snippet:regular', 'Snowburst One, display' => 'Snowburst+One:regular', 'Sofadi One, display' => 'Sofadi+One:regular', 'Sofia, handwriting' => 'Sofia:regular', 'Sonsie One, display' => 'Sonsie+One:regular', 'Sorts Mill Goudy, serif' => 'Sorts+Mill+Goudy:regular,italic', 'Source Code Pro, monospace' => 'Source+Code+Pro:200,300,regular,500,600,700,900', 'Source Sans Pro, sans-serif' => 'Source+Sans+Pro:200,200italic,300,300italic,regular,italic,600,600italic,700,700italic,900,900italic', 'Source Serif Pro, serif' => 'Source+Serif+Pro:regular,600,700', 'Space Mono, monospace' => 'Space+Mono:regular,italic,700,700italic', 'Special Elite, display' => 'Special+Elite:regular', 'Spectral, serif' => 'Spectral:200,200italic,300,300italic,regular,italic,500,500italic,600,600italic,700,700italic,800,800italic', 'Spicy Rice, display' => 'Spicy+Rice:regular', 'Spinnaker, sans-serif' => 'Spinnaker:regular', 'Spirax, display' => 'Spirax:regular', 'Squada One, display' => 'Squada+One:regular', 'Sree Krushnadevaraya, serif' => 'Sree+Krushnadevaraya:regular', 'Sriracha, handwriting' => 'Sriracha:regular', 'Stalemate, handwriting' => 'Stalemate:regular', 'Stalinist One, display' => 'Stalinist+One:regular', 'Stardos Stencil, display' => 'Stardos+Stencil:regular,700', 'Stint Ultra Condensed, display' => 'Stint+Ultra+Condensed:regular', 'Stint Ultra Expanded, display' => 'Stint+Ultra+Expanded:regular', 'Stoke, serif' => 'Stoke:300,regular', 'Strait, sans-serif' => 'Strait:regular', 'Sue Ellen Francisco, handwriting' => 'Sue+Ellen+Francisco:regular', 'Suez One, serif' => 'Suez+One:regular', 'Sumana, serif' => 'Sumana:regular,700', 'Sunshiney, handwriting' => 'Sunshiney:regular', 'Supermercado One, display' => 'Supermercado+One:regular', 'Sura, serif' => 'Sura:regular,700', 'Suranna, serif' => 'Suranna:regular', 'Suravaram, serif' => 'Suravaram:regular', 'Suwannaphum, display' => 'Suwannaphum:regular', 'Swanky and Moo Moo, handwriting' => 'Swanky+and+Moo+Moo:regular', 'Syncopate, sans-serif' => 'Syncopate:regular,700', 'Tangerine, handwriting' => 'Tangerine:regular,700', 'Taprom, display' => 'Taprom:regular', 'Tauri, sans-serif' => 'Tauri:regular', 'Taviraj, serif' => 'Taviraj:100,100italic,200,200italic,300,300italic,regular,italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic', 'Teko, sans-serif' => 'Teko:300,regular,500,600,700', 'Telex, sans-serif' => 'Telex:regular', 'Tenali Ramakrishna, sans-serif' => 'Tenali+Ramakrishna:regular', 'Tenor Sans, sans-serif' => 'Tenor+Sans:regular', 'Text Me One, sans-serif' => 'Text+Me+One:regular', 'The Girl Next Door, handwriting' => 'The+Girl+Next+Door:regular', 'Tienne, serif' => 'Tienne:regular,700,900', 'Tillana, handwriting' => 'Tillana:regular,500,600,700,800', 'Timmana, sans-serif' => 'Timmana:regular', 'Tinos, serif' => 'Tinos:regular,italic,700,700italic', 'Titan One, display' => 'Titan+One:regular', 'Titillium Web, sans-serif' => 'Titillium+Web:200,200italic,300,300italic,regular,italic,600,600italic,700,700italic,900', 'Trade Winds, display' => 'Trade+Winds:regular', 'Trirong, serif' => 'Trirong:100,100italic,200,200italic,300,300italic,regular,italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic', 'Trocchi, serif' => 'Trocchi:regular', 'Trochut, display' => 'Trochut:regular,italic,700', 'Trykker, serif' => 'Trykker:regular', 'Tulpen One, display' => 'Tulpen+One:regular', 'Ubuntu, sans-serif' => 'Ubuntu:300,300italic,regular,italic,500,500italic,700,700italic', 'Ubuntu Condensed, sans-serif' => 'Ubuntu+Condensed:regular', 'Ubuntu Mono, monospace' => 'Ubuntu+Mono:regular,italic,700,700italic', 'Ultra, serif' => 'Ultra:regular', 'Uncial Antiqua, display' => 'Uncial+Antiqua:regular', 'Underdog, display' => 'Underdog:regular', 'Unica One, display' => 'Unica+One:regular', 'UnifrakturCook, display' => 'UnifrakturCook:700', 'UnifrakturMaguntia, display' => 'UnifrakturMaguntia:regular', 'Unkempt, display' => 'Unkempt:regular,700', 'Unlock, display' => 'Unlock:regular', 'Unna, serif' => 'Unna:regular,italic,700,700italic', 'VT323, monospace' => 'VT323:regular', 'Vampiro One, display' => 'Vampiro+One:regular', 'Varela, sans-serif' => 'Varela:regular', 'Varela Round, sans-serif' => 'Varela+Round:regular', 'Vast Shadow, display' => 'Vast+Shadow:regular', 'Vesper Libre, serif' => 'Vesper+Libre:regular,500,700,900', 'Vibur, handwriting' => 'Vibur:regular', 'Vidaloka, serif' => 'Vidaloka:regular', 'Viga, sans-serif' => 'Viga:regular', 'Voces, display' => 'Voces:regular', 'Volkhov, serif' => 'Volkhov:regular,italic,700,700italic', 'Vollkorn, serif' => 'Vollkorn:regular,italic,600,600italic,700,700italic,900,900italic', 'Voltaire, sans-serif' => 'Voltaire:regular', 'Waiting for the Sunrise, handwriting' => 'Waiting+for+the+Sunrise:regular', 'Wallpoet, display' => 'Wallpoet:regular', 'Walter Turncoat, handwriting' => 'Walter+Turncoat:regular', 'Warnes, display' => 'Warnes:regular', 'Wellfleet, display' => 'Wellfleet:regular', 'Wendy One, sans-serif' => 'Wendy+One:regular', 'Wire One, sans-serif' => 'Wire+One:regular', 'Work Sans, sans-serif' => 'Work+Sans:100,200,300,regular,500,600,700,800,900', 'Yanone Kaffeesatz, sans-serif' => 'Yanone+Kaffeesatz:200,300,regular,700', 'Yantramanav, sans-serif' => 'Yantramanav:100,300,regular,500,700,900', 'Yatra One, display' => 'Yatra+One:regular', 'Yellowtail, handwriting' => 'Yellowtail:regular', 'Yeseva One, display' => 'Yeseva+One:regular', 'Yesteryear, handwriting' => 'Yesteryear:regular', 'Yrsa, serif' => 'Yrsa:300,regular,500,600,700', 'Zeyada, handwriting' => 'Zeyada:regular', 'Zilla Slab, serif' => 'Zilla+Slab:300,300italic,regular,italic,500,500italic,600,600italic,700,700italic', 'Zilla Slab Highlight, display' => 'Zilla+Slab+Highlight:regular,700', );

    return $new_fonts;

}


namespace ucare\util;

use ucare\Options;
use ucare\Plugin;

/**
 * @param $template
 * @param array $data
 *
 * @return string
 * @deprecated
 */
function render( $template, array $data = array() ) {
    extract($data);
    ob_start();

    include($template);

    return ob_get_clean();
}

function user_full_name( $user ) {
    
    if( $user ) {
        return $user->first_name . ' ' . $user->last_name;
    }
    
    return;

}

function can_use_support( $id = false ) {
    if( $id ) {

        $result = user_can( $id, 'use_support' );
    } else {
        $result = current_user_can( 'use_support' );
    }

    return $result;
}

function can_manage_tickets( $id = false ) {
    if( $id ) {
        $result = user_can( $id, 'manage_support_tickets' );
    } else {
        $result = current_user_can( 'manage_support_tickets' );
    }

    return $result;
}

function can_manage_support( $id = false ) {
    if( $id ) {
        $result = user_can( $id, 'manage_support' );
    } else {
        $result = current_user_can( 'manage_support' );
    }

    return $result;
}

function just_now( $stamp ) {
    $now = date_create();
    $date = date_create( $stamp );

    if( $now->diff( $date )->format( '%i' ) == 0 ) {
        $out = __( 'Just Now', 'ucare' );
    } else {
        $out = __( human_time_diff( strtotime( $stamp ), current_time( 'timestamp' ) ) . ' ago', 'ucare' );
    }

    return $out;
}

function extract_tags( $str, $open, $close ) {
    $matches = array();
    $regex = $pattern =  '~' . preg_quote( $open ) . '(.+)' . preg_quote( $close) . '~misU';

    preg_match_all( $regex, $str, $matches );

    return empty( $matches ) ? false : $matches[1];
}

function encode_code_blocks( $str ) {
    $blocks = extract_tags( $str, '<code>', '</code>' );

    foreach( $blocks as $block ) {
        $str = str_replace( $block, trim(  htmlentities( $block ) ), $str );
    }

    return $str;
}

function author_email( $ticket ) {
    
    $user = get_user_by( 'ID', $ticket->post_author );
    
    if( $user ) {
        
        return $user->user_email;
        
    }
    
    return;
    
}

function priorities () {
    return array(
        __( 'Low', 'ucare' ),
        __( 'Medium', 'ucare' ),
        __( 'High', 'ucare' )
    );
}

function statuses () {
    return array(
        'new'               => __( 'New', 'ucare' ),
        'waiting'           => __( 'Waiting', 'ucare' ),
        'opened'            => __( 'Opened', 'ucare' ),
        'responded'         => __( 'Responded', 'ucare' ),
        'needs_attention'   => __( 'Needs Attention', 'ucare' ),
        'closed'            => __( 'Closed', 'ucare' ),
    );
}

function filter_defaults() {
    $defaults = array(
        'status' => array(
            'new'               => true,
            'waiting'           => true,
            'opened'            => true,
            'responded'         => true,
            'needs_attention'   => true,
            'closed'            => true
        )
    );

    if( current_user_can( 'manage_support_tickets' ) ) {
        $defaults['status']['closed'] = false;
    }

    return $defaults;
}

function products() {
    $plugin = Plugin::get_plugin( \ucare\PLUGIN_ID );
    $products = array();

    if( get_option( Options::ECOMMERCE, \ucare\Defaults::ECOMMERCE ) ) {
        $post_type = array();

        if ( $plugin->woo_active ) {
            $post_type[] = 'product';
        }

        if ( $plugin->edd_active ) {
            $post_type[] = 'download';
        }

        $post_type = implode('","', $post_type );

        if( !empty( $post_type ) ) {

            global $wpdb;

            $query = 'select ID from ' . $wpdb->prefix . 'posts where post_type in ("' . $post_type . '") and post_status = "publish"';

            $posts = $wpdb->get_results( $query );

            foreach( $posts as $post ) {

                $products[ $post->ID ] = get_the_title( $post->ID );
            }

        }
    }

    return $products;
}

function ecommerce_enabled( $strict = true ) {
    $plugin = Plugin::get_plugin( \ucare\PLUGIN_ID );
    $enabled = false;

    if( get_option( Options::ECOMMERCE, \ucare\Defaults::ECOMMERCE == 'on' ) ) {
        if( $strict && ( $plugin->woo_active || $plugin->edd_active ) ) {
            $enabled = true;
        } else {
            $enabled = true;
        }
    }

    return $enabled;
}

function list_agents() {
    $users = get_users();
    $agents = array();

    foreach( $users as $user ) {
        if( $user->has_cap( 'manage_support_tickets' ) ) {
            $agents[ $user->ID ] = $user->display_name;
        }
    }

    return $agents;
}


function roles() {
    return array(
        'support_admin' => __( 'Support Admin', 'ucare' ),
        'support_agent' => __( 'Support Agent', 'ucare' ),
        'support_user'  => __( 'Support User', 'ucare' ),
    );
}

function add_caps( $role, $privilege = '' ) {
    $role = get_role( $role );

    if( !empty( $role ) ) {
        switch( $privilege ) {
            case 'manage':
                $role->add_cap( 'create_support_tickets' );
                $role->add_cap( 'use_support' );
                $role->add_cap( 'manage_support_tickets' );
                $role->add_cap( 'edit_support_ticket_comments' );

                break;

            case 'admin':
                $role->add_cap( 'create_support_tickets' );
                $role->add_cap( 'use_support' );
                $role->add_cap( 'manage_support_tickets' );
                $role->add_cap( 'edit_support_ticket_comments' );
                $role->add_cap( 'manage_support' );

                break;

            default:
                $role->add_cap( 'create_support_tickets' );
                $role->add_cap( 'use_support' );

                break;
        }
    }
}

function remove_caps( $role ) {
    $role = get_role( $role );

    if( !empty( $role ) ) {
        $role->remove_cap( 'create_support_tickets' );
        $role->remove_cap( 'use_support' );
        $role->remove_cap( 'manage_support_tickets' );
        $role->remove_cap( 'edit_support_ticket_comments' );
        $role->remove_cap( 'manage_support' );
    }
}

function get_attachments( $ticket, $orderby = 'post_date', $order = 'DESC', $mime_type = '' ) {
    $query = new \WP_Query(
        array(
            'post_parent'       => $ticket->ID,
            'post_type'         => 'attachment',
            'post_status'       => 'inherit',
            'orderby'           => $order,
            'order'             => $order,
            'post_mime_type'    => $mime_type
        ) );

    return $query->posts;
}


namespace ucare\proc;

use ucare\Options;

function schedule_cron_jobs() {
    if ( !wp_next_scheduled( 'ucare_cron_stale_tickets' ) ) {
        wp_schedule_event( time(), 'daily', 'ucare_cron_stale_tickets' );
    }

    if ( !wp_next_scheduled( 'ucare_cron_close_tickets' ) ) {
        wp_schedule_event( time(), 'daily', 'ucare_cron_close_tickets' );
    }

    if ( !wp_next_scheduled( 'ucare_check_extension_licenses' ) ) {
        wp_schedule_event( time(), 'daily', 'ucare_check_extension_licenses' );
    }
}

function clear_scheduled_jobs() {
    wp_clear_scheduled_hook( 'ucare_cron_stale_tickets' );
    wp_clear_scheduled_hook( 'ucare_cron_close_tickets' );
    wp_clear_scheduled_hook( 'ucare_check_extension_licenses' );
}

function setup_template_page() {
    $post_id = null;
    $post = get_post( get_option( Options::TEMPLATE_PAGE_ID ) ) ;

    if( empty( $post ) ) {
        $post_id = wp_insert_post(
            array(
                'post_type' =>  'page',
                'post_status' => 'publish',
                'post_title' => __( 'Support', 'ucare' )
            )
        );
    } else if( $post->post_status == 'trash' ) {
        wp_untrash_post( $post->ID );

        $post_id = $post->ID;
    } else {
        $post_id = $post->ID;
    }

    if( !empty( $post_id ) ) {
        update_option( Options::TEMPLATE_PAGE_ID, $post_id );
    }
}

function create_email_templates() {

    $default_templates = array(
        array(
            'template' => '/emails/ticket-created.html',
            'option' => Options::TICKET_CREATED_EMAIL,
            'subject' => __( 'You have created a new request for support', 'ucare' )
        ),
        array(
            'template' => '/emails/welcome.html',
            'option' => Options::WELCOME_EMAIL_TEMPLATE,
            'subject' => __( 'Welcome to Support', 'ucare' )
        ),
        array(
            'template' => '/emails/ticket-closed.html',
            'option' => Options::TICKET_CLOSED_EMAIL_TEMPLATE,
            'subject' => __( 'Your request for support has been closed', 'ucare' )
        ),
        array(
            'template' => '/emails/ticket-reply.html',
            'option' => Options::AGENT_REPLY_EMAIL,
            'subject' => __( 'Reply to your request for support', 'ucare' )
        ),
        array(
            'template' => '/emails/password-reset.html',
            'option' => Options::PASSWORD_RESET_EMAIL,
            'subject' => __( 'Your password has been reset', 'ucare' )
        ),
        array(
            'template' => '/emails/ticket-close-warning.html',
            'option' => Options::INACTIVE_EMAIL,
            'subject' => __( 'You have a ticket awaiting action', 'ucare' )
        )
    );

    $default_style = file_get_contents( \ucare\plugin_dir() . '/emails/default-style.css' );

    foreach( $default_templates as $config ) {
        $template = get_post( get_option( $config['option'] ) );

        if( is_null( get_post( $template ) ) ) {
            $id = wp_insert_post(
                array(
                    'post_type'     => 'email_template',
                    'post_status'   => 'publish',
                    'post_title'    => $config['subject'],
                    'post_content'  => file_get_contents( \ucare\plugin_dir() . $config['template'] )
                )
            );

            if( !empty( $id ) ) {
                update_post_meta( $id, 'styles', $default_style );
                update_option( $config['option'], $id );
            }
        } else {
            wp_untrash_post( $template );
        }
    }
}

function configure_roles() {
    $administrator = get_role( 'administrator' );

    $administrator->add_cap( 'read_support_ticket' );
    $administrator->add_cap( 'read_support_tickets' );
    $administrator->add_cap( 'edit_support_ticket' );
    $administrator->add_cap( 'edit_support_tickets' );
    $administrator->add_cap( 'edit_others_support_tickets' );
    $administrator->add_cap( 'edit_published_support_tickets' );
    $administrator->add_cap( 'publish_support_tickets' );
    $administrator->add_cap( 'delete_support_tickets' );
    $administrator->add_cap( 'delete_others_support_tickets' );
    $administrator->add_cap( 'delete_private_support_tickets' );
    $administrator->add_cap( 'delete_published_support_tickets' );

    foreach( \ucare\util\roles() as $role => $name ) {
        add_role( $role, $name );
    }

    \ucare\util\add_caps( 'customer' );
    \ucare\util\add_caps( 'subscriber' );
    \ucare\util\add_caps( 'support_user' );

    \ucare\util\add_caps( 'support_agent' , 'manage' );

    \ucare\util\add_caps( 'support_admin' , 'admin' );
    \ucare\util\add_caps( 'administrator' , 'admin' );
}

function cleanup_roles() {
    foreach( \ucare\util\roles() as $role => $name ) {
        remove_role( $role );
    }

    \ucare\util\remove_caps( 'customer' );
    \ucare\util\remove_caps( 'subscriber' );
    \ucare\util\remove_caps( 'administrator' );

    $administrator = get_role( 'administrator' );

    $administrator->remove_cap( 'read_support_ticket' );
    $administrator->remove_cap( 'read_support_tickets' );
    $administrator->remove_cap( 'edit_support_ticket' );
    $administrator->remove_cap( 'edit_support_tickets' );
    $administrator->remove_cap( 'edit_others_support_tickets' );
    $administrator->remove_cap( 'edit_published_support_tickets' );
    $administrator->remove_cap( 'publish_support_tickets' );
    $administrator->remove_cap( 'delete_support_tickets' );
    $administrator->remove_cap( 'delete_others_support_tickets' );
    $administrator->remove_cap( 'delete_private_support_tickets' );
    $administrator->remove_cap( 'delete_published_support_tickets' );
}

function hex2rgb( $hex ) {
    $hex = str_replace( "#", "", $hex );

    if ( strlen( $hex ) == 3 ) {
        $r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
        $g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
        $b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
    } else {
        $r = hexdec( substr( $hex, 0, 2 ) );
        $g = hexdec( substr( $hex, 2, 2 ) );
        $b = hexdec( substr( $hex, 4, 2 ) );
    }
    $rgb = array ( $r, $g, $b );
    //return implode(",", $rgb); // returns the rgb values separated by commas
    return $rgb; // returns an array with the rgb values
}



namespace ucare\statprocs;

function count_tickets( $start, $end, $args = array() ) {
    global $wpdb;

    $start = is_a( $start, 'DateTimeInterface' ) ? $start : date_create( strtotime( $start ) );
    $end =   is_a( $end, 'DateTimeInterface' )   ? $end   : date_create( strtotime( $end ) );

    if( !$start || !$end || $start > $end ) {
        return new \WP_Error( 'invalid date supplied' );
    }

    // Default count by day
    $range = "%Y-%m-%d";
    $interval = new \DateInterval( 'P1D' );
    $diff = $end->diff( $start )->format( '%a' );

    // Get monthly totals if greater than 2 months
    if ( $diff > 62 ) {
        $range = "%Y-%m";
        $interval = new \DateInterval( 'P1M' );
    }

    $values = array($range, $start->format( 'Y-m-d: 00:00:00' ), $end->format( 'Y-m-d 23:59:59' ) );

    if( !empty( $args['closed'] ) ) {

        $q = "SELECT DATE_FORMAT(DATE(m.meta_value), %s ) as d,
          COUNT(m.meta_value) as c
          FROM {$wpdb->posts} p
          INNER JOIN {$wpdb->postmeta} m 
            ON p.ID = m.post_id
          WHERE p.post_type = 'support_ticket'
            AND p.post_status = 'publish' 
            AND m.meta_key = 'closed_date'
            AND (DATE(m.meta_value) BETWEEN DATE( %s ) AND DATE( %s )) ";

    } else {

        $q = "SELECT DATE_FORMAT(DATE(p.post_date), %s ) as d,
          COUNT(p.post_date) as c
          FROM {$wpdb->posts} p
          WHERE p.post_type = 'support_ticket'
            AND p.post_status = 'publish' 
            AND (DATE(p.post_date) BETWEEN DATE( %s ) AND DATE( %s )) ";

    }

    $q .= " GROUP BY d ORDER BY d";

    // Get the data from the query
    $results = $wpdb->get_results( $wpdb->prepare( $q, $values ), ARRAY_A );
    $data = array();

    // All dates in the period at a set interval
    $dates = new \DatePeriod( $start, $interval, clone $end->modify( '+1 second' ) );

    foreach( $dates as $date ) {

        $curr = $date->format( 'Y-m-d' );

        // Set it to 0 by default for this date
        $data[ $curr ] = 0;

        // Loop through each found total
        foreach( $results as $result ) {

            // If the total's date is like the current date set it
            if( strpos( $curr, $result['d'] ) !== false ) {

                $data[ $curr ] = ( int ) $result['c'];

            }

        }

    }

    return $data;
}

function get_unclosed_tickets() {

    global $wpdb;

    $q = 'select ifnull( count(*), 0 ) from ' . $wpdb->prefix . 'posts as a '
            . 'left join ' . $wpdb->prefix . 'postmeta as b '
            . 'on a.ID = b.post_id '
            . 'where a.post_type = "support_ticket" and a.post_status = "publish" '
            . 'and b.meta_key = "status" and b.meta_value != "closed"';

    return $wpdb->get_var( $q );

}

function get_ticket_count( $args = array() ) {

    global $wpdb;

    $defaults = array(
        'status'   => false,
        'priority' => false,
        'agent'    => false,
        'author'   => false
    );

    $args = wp_parse_args( $args, $defaults );


    $q = 'select ifnull( count( DISTINCT a.ID ), 0 ) from ' . $wpdb->prefix . 'posts as a '
            . 'left join ' . $wpdb->prefix . 'postmeta as b '
            . 'on a.ID = b.post_id '
            . 'where a.post_type = "support_ticket" and a.post_status = "publish"';

    if ( $args['status'] ) {
        $q .= ' and b.meta_key = "status" and b.meta_value in ("'. esc_sql( $args['status'] ) . '")';
    }

    if ( $args['priority'] ) {
        $q .= ' and b.meta_key = "priority" and b.meta_value in ("'. esc_sql( $args['priority'] ) . '")';
    }

    if ( $args['agent'] ) {
        $q .= ' and b.meta_key = "agent" and b.meta_value in ("'. esc_sql( $args['agent'] ) . '")';
    }

    if ( $args['author'] ) {
        $q .= " AND a.post_author = " . absint( $args['author'] );
    }

    return $wpdb->get_var( $q );

}

function get_user_assigned( $agents ) {

    $args = array(
        'post_type'     => 'support_ticket',
        'post_status'   => 'publish',
        'meta_query'    => array(
            'relation'  => 'AND',
            array(
                'key'       => 'agent',
                'value'     => $agents,
                'compare'   => 'IN'
            ),
            array(
                'key'       => 'status',
                'value'     => 'closed',
                'compare'   => '!='
            )
        )
    );

    $results = new \WP_Query( $args );

    return $results->found_posts;

}

