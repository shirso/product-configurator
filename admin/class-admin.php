<?php
if ( ! defined( 'ABSPATH' ) ) exit;
if( !class_exists('WPC_Admin') ) {
    class WPC_Admin{
        public static $optimised_google_webfonts=array (
            'Abel' => 'Abel',
            'Abril+Fatface' => 'Abril Fatface',
            'Aclonica' => 'Aclonica',
            'Actor' => 'Actor',
            'Adamina' => 'Adamina',
            'Aguafina+Script' => 'Aguafina Script',
            'Aladin' => 'Aladin',
            'Aldrich' => 'Aldrich',
            'Alice' => 'Alice',
            'Alike+Angular' => 'Alike Angular',
            'Alike' => 'Alike',
            'Allan' => 'Allan',
            'Allerta+Stencil' => 'Allerta Stencil',
            'Allerta' => 'Allerta',
            'Amaranth' => 'Amaranth',
            'Amatic+SC' => 'Amatic SC',
            'Andada' => 'Andada',
            'Andika' => 'Andika',
            'Annie+Use+Your+Telescope' => 'Annie Use Your Telescope',
            'Anonymous+Pro' => 'Anonymous Pro',
            'Antic' => 'Antic',
            'Anton' => 'Anton',
            'Arapey' => 'Arapey',
            'Architects+Daughter' => 'Architects Daughter',
            'Arimo' => 'Arimo',
            'Artifika' => 'Artifika',
            'Arvo' => 'Arvo',
            'Asset' => 'Asset',
            'Astloch' => 'Astloch',
            'Atomic+Age' => 'Atomic Age',
            'Aubrey' => 'Aubrey',
            'Bangers' => 'Bangers',
            'Bentham' => 'Bentham',
            'Bevan' => 'Bevan',
            'Bigshot+One' => 'Bigshot One',
            'Bitter' => 'Bitter',
            'Black+Ops+One' => 'Black Ops One',
            'Bowlby+One+SC' => 'Bowlby One SC',
            'Bowlby+One' => 'Bowlby One',
            'Brawler' => 'Brawler',
            'Bubblegum+Sans' => 'Bubblegum Sans',
            'Buda' => 'Buda',
            'Butcherman+Caps' => 'Butcherman Caps',
            'Cabin+Condensed' => 'Cabin Condensed',
            'Cabin+Sketch' => 'Cabin Sketch',
            'Cabin' => 'Cabin',
            'Cagliostro' => 'Cagliostro',
            'Calligraffitti' => 'Calligraffitti',
            'Candal' => 'Candal',
            'Cantarell' => 'Cantarell',
            'Cardo' => 'Cardo',
            'Carme' => 'Carme',
            'Carter+One' => 'Carter One',
            'Caudex' => 'Caudex',
            'Cedarville+Cursive' => 'Cedarville Cursive',
            'Changa+One' => 'Changa One',
            'Cherry+Cream+Soda' => 'Cherry Cream Soda',
            'Chewy' => 'Chewy',
            'Chicle' => 'Chicle',
            'Chivo' => 'Chivo',
            'Coda+Caption' => 'Coda Caption',
            'Coda' => 'Coda',
            'Comfortaa' => 'Comfortaa',
            'Coming+Soon' => 'Coming Soon',
            'Contrail+One' => 'Contrail One',
            'Convergence' => 'Convergence',
            'Cookie' => 'Cookie',
            'Copse' => 'Copse',
            'Corben' => 'Corben',
            'Cousine' => 'Cousine',
            'Coustard' => 'Coustard',
            'Covered+By+Your+Grace' => 'Covered By Your Grace',
            'Crafty+Girls' => 'Crafty Girls',
            'Creepster+Caps' => 'Creepster Caps',
            'Crimson+Text' => 'Crimson Text',
            'Crushed' => 'Crushed',
            'Cuprum' => 'Cuprum',
            'Damion' => 'Damion',
            'Dancing+Script' => 'Dancing Script',
            'Dawning+of+a+New+Day' => 'Dawning of a New Day',
            'Days+One' => 'Days One',
            'Delius+Swash+Caps' => 'Delius Swash Caps',
            'Delius+Unicase' => 'Delius Unicase',
            'Delius' => 'Delius',
            'Devonshire' => 'Devonshire',
            'Didact+Gothic' => 'Didact Gothic',
            'Dorsa' => 'Dorsa',
            'Dr+Sugiyama' => 'Dr Sugiyama',
            'Droid+Sans+Mono' => 'Droid Sans Mono',
            'Droid+Sans' => 'Droid Sans',
            'Droid+Serif' => 'Droid Serif',
            'EB+Garamond' => 'EB Garamond',
            'Eater+Caps' => 'Eater Caps',
            'Expletus+Sans' => 'Expletus Sans',
            'Fanwood+Text' => 'Fanwood Text',
            'Federant' => 'Federant',
            'Federo' => 'Federo',
            'Fjord+One' => 'Fjord One',
            'Fondamento' => 'Fondamento',
            'Fontdiner+Swanky' => 'Fontdiner Swanky',
            'Forum' => 'Forum',
            'Francois+One' => 'Francois One',
            'Gentium+Basic' => 'Gentium Basic',
            'Gentium+Book+Basic' => 'Gentium Book Basic',
            'Geo' => 'Geo',
            'Geostar+Fill' => 'Geostar Fill',
            'Geostar' => 'Geostar',
            'Give+You+Glory' => 'Give You Glory',
            'Gloria+Hallelujah' => 'Gloria Hallelujah',
            'Goblin+One' => 'Goblin One',
            'Gochi+Hand' => 'Gochi Hand',
            'Goudy+Bookletter+1911' => 'Goudy Bookletter 1911',
            'Gravitas+One' => 'Gravitas One',
            'Gruppo' => 'Gruppo',
            'Hammersmith+One' => 'Hammersmith One',
            'Herr+Von+Muellerhoff' => 'Herr Von Muellerhoff',
            'Holtwood+One+SC' => 'Holtwood One SC',
            'Homemade+Apple' => 'Homemade Apple',
            'IM+Fell+DW+Pica+SC' => 'IM Fell DW Pica SC',
            'IM+Fell+DW+Pica' => 'IM Fell DW Pica',
            'IM+Fell+Double+Pica+SC' => 'IM Fell Double Pica SC',
            'IM+Fell+Double+Pica' => 'IM Fell Double Pica',
            'IM+Fell+English+SC' => 'IM Fell English SC',
            'IM+Fell+English' => 'IM Fell English',
            'IM+Fell+French+Canon+SC' => 'IM Fell French Canon SC',
            'IM+Fell+French+Canon' => 'IM Fell French Canon',
            'IM+Fell+Great+Primer+SC' => 'IM Fell Great Primer SC',
            'IM+Fell+Great+Primer' => 'IM Fell Great Primer',
            'Iceland' => 'Iceland',
            'Inconsolata' => 'Inconsolata',
            'Indie+Flower' => 'Indie Flower',
            'Irish+Grover' => 'Irish Grover',
            'Istok+Web' => 'Istok Web',
            'Jockey+One' => 'Jockey One',
            'Josefin+Sans' => 'Josefin Sans',
            'Josefin+Slab' => 'Josefin Slab',
            'Judson' => 'Judson',
            'Julee' => 'Julee',
            'Jura' => 'Jura',
            'Just+Another+Hand' => 'Just Another Hand',
            'Just+Me+Again+Down+Here' => 'Just Me Again Down Here',
            'Kameron' => 'Kameron',
            'Kelly+Slab' => 'Kelly Slab',
            'Kenia' => 'Kenia',
            'Knewave' => 'Knewave',
            'Kranky' => 'Kranky',
            'Kreon' => 'Kreon',
            'Kristi' => 'Kristi',
            'La+Belle+Aurore' => 'La Belle Aurore',
            'Lancelot' => 'Lancelot',
            'Lato' => 'Lato',
            'League+Script' => 'League Script',
            'Leckerli+One' => 'Leckerli One',
            'Lekton' => 'Lekton',
            'Lemon' => 'Lemon',
            'Limelight' => 'Limelight',
            'Linden+Hill' => 'Linden Hill',
            'Lobster+Two' => 'Lobster Two',
            'Lobster' => 'Lobster',
            'Lora' => 'Lora',
            'Love+Ya+Like+A+Sister' => 'Love Ya Like A Sister',
            'Loved+by+the+King' => 'Loved by the King',
            'Luckiest+Guy' => 'Luckiest Guy',
            'Maiden+Orange' => 'Maiden Orange',
            'Mako' => 'Mako',
            'Marck+Script' => 'Marck Script',
            'Marvel' => 'Marvel',
            'Mate+SC' => 'Mate SC',
            'Mate' => 'Mate',
            'Maven+Pro' => 'Maven Pro',
            'Meddon' => 'Meddon',
            'MedievalSharp' => 'MedievalSharp',
            'Megrim' => 'Megrim',
            'Merienda+One' => 'Merienda One',
            'Merriweather' => 'Merriweather',
            'Metrophobic' => 'Metrophobic',
            'Michroma' => 'Michroma',
            'Miltonian+Tattoo' => 'Miltonian Tattoo',
            'Miltonian' => 'Miltonian',
            'Miss+Fajardose' => 'Miss Fajardose',
            'Miss+Saint+Delafield' => 'Miss Saint Delafield',
            'Modern+Antiqua' => 'Modern Antiqua',
            'Molengo' => 'Molengo',
            'Monofett' => 'Monofett',
            'Monoton' => 'Monoton',
            'Monsieur+La+Doulaise' => 'Monsieur La Doulaise',
            'Montez' => 'Montez',
            'Mountains+of+Christmas' => 'Mountains of Christmas',
            'Mr+Bedford' => 'Mr Bedford',
            'Mr+Dafoe' => 'Mr Dafoe',
            'Mr+De+Haviland' => 'Mr De Haviland',
            'Mrs+Sheppards' => 'Mrs Sheppards',
            'Muli' => 'Muli',
            'Neucha' => 'Neucha',
            'Neuton' => 'Neuton',
            'News+Cycle' => 'News Cycle',
            'Niconne' => 'Niconne',
            'Nixie+One' => 'Nixie One',
            'Nobile' => 'Nobile',
            'Nosifer+Caps' => 'Nosifer Caps',
            'Nothing+You+Could+Do' => 'Nothing You Could Do',
            'Nova+Cut' => 'Nova Cut',
            'Nova+Flat' => 'Nova Flat',
            'Nova+Mono' => 'Nova Mono',
            'Nova+Oval' => 'Nova Oval',
            'Nova+Round' => 'Nova Round',
            'Nova+Script' => 'Nova Script',
            'Nova+Slim' => 'Nova Slim',
            'Nova+Square' => 'Nova Square',
            'Numans' => 'Numans',
            'Nunito' => 'Nunito',
            'Old+Standard+TT' => 'Old Standard TT',
            'Open+Sans+Condensed' => 'Open Sans Condensed',
            'Open+Sans' => 'Open Sans',
            'Orbitron' => 'Orbitron',
            'Oswald' => 'Oswald',
            'Over+the+Rainbow' => 'Over the Rainbow',
            'Ovo' => 'Ovo',
            'PT+Sans+Caption' => 'PT Sans Caption',
            'PT+Sans+Narrow' => 'PT Sans Narrow',
            'PT+Sans' => 'PT Sans',
            'PT+Serif+Caption' => 'PT Serif Caption',
            'PT+Serif' => 'PT Serif',
            'Pacifico' => 'Pacifico',
            'Passero+One' => 'Passero One',
            'Patrick+Hand' => 'Patrick Hand',
            'Paytone+One' => 'Paytone One',
            'Permanent+Marker' => 'Permanent Marker',
            'Petrona' => 'Petrona',
            'Philosopher' => 'Philosopher',
            'Piedra' => 'Piedra',
            'Pinyon+Script' => 'Pinyon Script',
            'Play' => 'Play',
            'Playfair+Display' => 'Playfair Display',
            'Podkova' => 'Podkova',
            'Poller+One' => 'Poller One',
            'Poly' => 'Poly',
            'Pompiere' => 'Pompiere',
            'Prata' => 'Prata',
            'Prociono' => 'Prociono',
            'Puritan' => 'Puritan',
            'Quattrocento+Sans' => 'Quattrocento Sans',
            'Quattrocento' => 'Quattrocento',
            'Questrial' => 'Questrial',
            'Quicksand' => 'Quicksand',
            'Radley' => 'Radley',
            'Raleway' => 'Raleway',
            'Rammetto+One' => 'Rammetto One',
            'Rancho' => 'Rancho',
            'Rationale' => 'Rationale',
            'Redressed' => 'Redressed',
            'Reenie+Beanie' => 'Reenie Beanie',
            'Ribeye+Marrow' => 'Ribeye Marrow',
            'Ribeye' => 'Ribeye',
            'Righteous' => 'Righteous',
            'Rochester' => 'Rochester',
            'Rock+Salt' => 'Rock Salt',
            'Rokkitt' => 'Rokkitt',
            'Rosario' => 'Rosario',
            'Ruslan+Display' => 'Ruslan Display',
            'Salsa' => 'Salsa',
            'Sancreek' => 'Sancreek',
            'Sansita+One' => 'Sansita One',
            'Satisfy' => 'Satisfy',
            'Schoolbell' => 'Schoolbell',
            'Shadows+Into+Light' => 'Shadows Into Light',
            'Shanti' => 'Shanti',
            'Short+Stack' => 'Short Stack',
            'Sigmar+One' => 'Sigmar One',
            'Signika+Negative' => 'Signika Negative',
            'Signika' => 'Signika',
            'Six+Caps' => 'Six Caps',
            'Slackey' => 'Slackey',
            'Smokum' => 'Smokum',
            'Smythe' => 'Smythe',
            'Sniglet' => 'Sniglet',
            'Snippet' => 'Snippet',
            'Sorts+Mill+Goudy' => 'Sorts Mill Goudy',
            'Special+Elite' => 'Special Elite',
            'Spinnaker' => 'Spinnaker',
            'Spirax' => 'Spirax',
            'Stardos+Stencil' => 'Stardos Stencil',
            'Sue+Ellen+Francisco' => 'Sue Ellen Francisco',
            'Sunshiney' => 'Sunshiney',
            'Supermercado+One' => 'Supermercado One',
            'Swanky+and+Moo+Moo' => 'Swanky and Moo Moo',
            'Syncopate' => 'Syncopate',
            'Tangerine' => 'Tangerine',
            'Tenor+Sans' => 'Tenor Sans',
            'Terminal+Dosis' => 'Terminal Dosis',
            'The+Girl+Next+Door' => 'The Girl Next Door',
            'Tienne' => 'Tienne',
            'Tinos' => 'Tinos',
            'Tulpen+One' => 'Tulpen One',
            'Ubuntu+Condensed' => 'Ubuntu Condensed',
            'Ubuntu+Mono' => 'Ubuntu Mono',
            'Ubuntu' => 'Ubuntu',
            'Ultra' => 'Ultra',
            'UnifrakturCook' => 'UnifrakturCook',
            'UnifrakturMaguntia' => 'UnifrakturMaguntia',
            'Unkempt' => 'Unkempt',
            'Unlock' => 'Unlock',
            'Unna' => 'Unna',
            'VT323' => 'VT323',
            'Varela+Round' => 'Varela Round',
            'Varela' => 'Varela',
            'Vast+Shadow' => 'Vast Shadow',
            'Vibur' => 'Vibur',
            'Vidaloka' => 'Vidaloka',
            'Volkhov' => 'Volkhov',
            'Vollkorn' => 'Vollkorn',
            'Voltaire' => 'Voltaire',
            'Waiting+for+the+Sunrise' => 'Waiting for the Sunrise',
            'Wallpoet' => 'Wallpoet',
            'Walter+Turncoat' => 'Walter Turncoat',
            'Wire+One' => 'Wire One',
            'Yanone+Kaffeesatz' => 'Yanone Kaffeesatz',
            'Yellowtail' => 'Yellowtail',
            'Yeseva+One' => 'Yeseva One',
            'Zeyada' => 'Zeyada',
        );
        public function __construct() {
            add_action('admin_enqueue_scripts',array(&$this,'admin_scripts'));
            add_action('admin_menu',array(&$this, 'wpc_plugin_setup_menu'));
            add_action('admin_init',array(&$this,'plugin_admin_init'));
            add_action('admin_menu',array(&$this,'register_custom_submenu_page_image'));
            add_action('admin_menu',array(&$this,'register_custom_submenu_page_color'));
            add_action('admin_menu',array(&$this,'register_custom_submenu_page_texture'));
            add_action('admin_menu',array(&$this,'register_custom_submenu_page_embroidery'));
            add_action('admin_menu',array(&$this,'register_custom_submenu_page_images'));
            add_action('wp_ajax_wpc_save_configuration_form',array(&$this,'wpc_save_configuration_form'));
            add_action('wp_ajax_wpc_save_configuration_form_texture',array(&$this,'wpc_save_configuration_form_texture'));
            add_action('wp_ajax_wpc_save_configuration_form_color',array(&$this,'wpc_save_configuration_form_color'));
            add_action('wp_ajax_wpc_save_configuration_form_embroidery',array(&$this,'wpc_save_configuration_form_embroidery'));
            add_action('wp_ajax_wpc_save_tab_data',array(&$this,'wpc_save_tab_data'));
            add_action('wp_ajax_wpc_load_tab_data',array(&$this,'wpc_load_tab_data'));
            add_action('wp_ajax_copy_coor_or_texture',array(&$this,'copy_coor_or_texture'));
        }
        public function admin_scripts(){
            wp_register_script('wpc_sheepit_jquery',WPC_PLUGIN_ABSOLUTE_PATH.'admin/js/jquery.sheepItPlugin-1.1.1.min.js','',false,true);
            wp_enqueue_script('wpc_sheepit_jquery');
            wp_enqueue_media();
            wp_register_script('wpc_chosen_jquery',WPC_PLUGIN_ABSOLUTE_PATH.'admin/js/chosen.jquery.js','',false,true);
            wp_register_script('wpc_blockUI',WPC_PLUGIN_ABSOLUTE_PATH.'admin/js/jquery.blockUI.min.js','',false,true);
            wp_enqueue_script('wpc_fabric',WPC_PLUGIN_ABSOLUTE_PATH.'admin/js/fabric.js','',false,true);
            wp_enqueue_script('wpc_steps',WPC_PLUGIN_ABSOLUTE_PATH.'admin/js/jquery.steps.min.js','',false,true);
            wp_enqueue_script('wpc_multiselect',WPC_PLUGIN_ABSOLUTE_PATH.'admin/js/jquery.multiselect.min.js','',false,true);
            wp_enqueue_script( 'wpc_chosen_jquery' );
            wp_enqueue_script( 'wpc_blockUI' );
            wp_register_script('wpc_admin_script',WPC_PLUGIN_ABSOLUTE_PATH.'admin/js/wpc.admin.js','',false,true);
            wp_localize_script('wpc_admin_script','wpc_image_labels',array('previous'=>__('Previous','wpc'),'next'=>__('Save and Continue','wpc'),'finish'=>'Save'));
            wp_enqueue_script('wpc_admin_script');
            wp_enqueue_style('wpc_grid12',WPC_PLUGIN_ABSOLUTE_PATH.'admin/css/grid12.css');
            wp_enqueue_style('wpc_chosen_style',WPC_PLUGIN_ABSOLUTE_PATH.'admin/css/chosen.min.css');
            wp_enqueue_style('wpc_steps_style',WPC_PLUGIN_ABSOLUTE_PATH.'admin/css/jquery.steps.css');
            wp_enqueue_style('wpc_ui_style','http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/ui-darkness/jquery-ui.css');
            wp_enqueue_style('wpc_multiselect_style',WPC_PLUGIN_ABSOLUTE_PATH.'admin/css/jquery.multiselect.css');
            wp_enqueue_style('wpc_admin_style',WPC_PLUGIN_ABSOLUTE_PATH.'admin/css/wpc.admin.css');
        }
        public  function wpc_save_configuration_form_texture(){
            parse_str($_POST['formData'],$params);
            update_post_meta(intval($_POST['postId']),'_wpc_texture_config',$params);
        }
        public function wpc_plugin_setup_menu(){
            add_options_page( 'WP Product Configurator Settings', 'WP Product Configurator', 'manage_options', 'wpc-plugin', array(&$this,'wpc_settings_init'));
        }
        public function wpc_settings_init(){
            ?>
            <div class="wrap">
                <h2><?php _e('WP Product Configurator Settings','wpc'); ?></h2>

                <form action="options.php" method="post">
                    <?php settings_fields('wpc_plugin_options'); ?>
                    <?php do_settings_sections('wpc_plugin_fields'); ?>
                    <input name="Submit" class="button button-primary button-large" type="submit" value="<?php esc_attr_e('Save Changes','wpc'); ?>" />
                </form>
            </div>
        <?php }
        public function plugin_admin_init(){
            if( array_key_exists('woocommerce', $GLOBALS) == false) {
                return;
            }

            $role = get_role( 'administrator' );
            $role->add_cap(WPC_Product_Configurator::CAPABILITY);
            require_once(WPC_PLUGIN_ADMIN_DIR . '/class-admin-attributes.php' );
            require_once(WPC_PLUGIN_ADMIN_DIR . '/class-admin-product.php' );
            require_once(WPC_PLUGIN_ADMIN_DIR . '/class-admin-metabox.php' );
            register_setting( 'wpc_plugin_options', 'wpc_settings',array(&$this,'plugin_options_validate'));
            add_settings_section('wpc_plugin_main', '', array(&$this,'plugin_description'), 'wpc_plugin_fields');
            add_settings_field('plugin_text_string1', __('Available Colors','wpc'), array(&$this,'add_colors'),'wpc_plugin_fields','wpc_plugin_main');
            add_settings_field('plugin_text_string4', __('Available Textures','wpc'), array(&$this,'add_textures'),'wpc_plugin_fields','wpc_plugin_main');
            add_settings_field('plugin_text_string3', __('Rotation','wpc'), array(&$this,'emb_rotation_angle'),'wpc_plugin_fields','wpc_plugin_main');
            add_settings_field('plugin_text_string5', __('Font Sizes','wpc'), array(&$this,'font_sizes'),'wpc_plugin_fields','wpc_plugin_main');
            add_settings_field('plugin_text_string6', __('Embroidery Character Limit','wpc'), array(&$this,'emb_char_limit'),'wpc_plugin_fields','wpc_plugin_main');
        }
        public function plugin_description(){

        }
        public function add_colors(){
            $options = get_option('wpc_settings');
            //print_r($options["colors_data"]);
           $data_color=array(); if(!empty($options["colors_data"])){ foreach($options["colors_data"] as $color){
                array_push($data_color,array('wpc_#index#_color_name'=>$color['name'],'wpc_#index#_color_value'=>$color['value']));
            }} //print_r($data_color);?>
            <script type="text/javascript">
                var wpc_config_page=true;
                var inject_data=[];
               inject_data= <?php echo json_encode($data_color); ?>;
            </script>
            <div id="wpc_sheepItForm">
                <!-- Form template-->
                <div id="wpc_sheepItForm_template">
                    <div class="row">
                        <div class="col-sm-6">
                            <label for=""><?php _e('Color Name','wpc'); ?></label>
                            <input type="text" class="full-width" name="wpc_settings[colors_data][#index#][name]" id="wpc_#index#_color_name">
                        </div>
                        <div class="col-sm-6">
                            <input type="color"  class="full-width wpc_color_picker1" name="wpc_settings[colors_data][#index#][value]" id="wpc_#index#_color_value">
                        </div>
                    </div>
                    </div>
                <!-- /Form template-->
                <!-- No forms template -->
                <div id="wpc_sheepItForm_noforms_template"><?php _e('No Color','wpc'); ?></div>
                <!-- /No forms template-->
                    <!-- Controls -->
                    <div id="wpc_sheepItForm_controls" class="row">
                        <div id="wpc_sheepItForm_add" class="col-sm-3"><a class="button button-primary"><span><?php _e('Add Color','wpc'); ?></span></a></div>
                        <div id="wpc_sheepItForm_remove_last" class="col-sm-3"><a class="button button-primary"><span><?php _e('Remove','wpc'); ?> </span></a></div>
                        <div id="wpc_sheepItForm_remove_all"><a><span><?php _e('Remove all','wpc'); ?></span></a></div>
                        <div id="wpc_sheepItForm_add_n" class="col-sm-6">
                            <div class="row">
                                <div class="col-sm-2"><input id="wpc_sheepItForm_add_n_input" type="text" size="4" /></div>
                            <div class="col-sm-10" id="wpc_sheepItForm_add_n_button"><a class="button button-primary"><span><?php _e('Add','wpc'); ?> </span></a></div></div>
                             </div>
                    </div>
                    <!-- /Controls -->

                </div>

<?php
        }
        public function add_textures(){
            $options = get_option('wpc_settings');
            $data_texture=array(); if(!empty($options["texture_data"])){ foreach($options["texture_data"] as $color){

                array_push($data_texture,array('wpc_#index#_texture_name'=>$color['name'],'wpc_#index#_texture_value'=>$color['value']));
            }}

            ?>
            <script type="text/javascript">
                var wpc_config_page=true;
                var inject_data_texture=[];
                inject_data_texture= <?php echo json_encode($data_texture); ?>;
            </script>
            <div id="wpc_sheepItForm1">
                <!-- Form template-->
                <div id="wpc_sheepItForm1_template">
                    <div class="row">
                        <div class="col-sm-6">
                            <label for=""><?php _e('Texture Name','wpc'); ?></label>
                            <input type="text" class="full-width" name="wpc_settings[texture_data][#index#][name]" id="wpc_#index#_texture_name">
                        </div>
                        <div class="col-sm-6">
                            <input type="text"  class="full-width" name="wpc_settings[texture_data][#index#][value]" id="wpc_#index#_texture_value">
                            <button class="button wpc_texture_upload"><?=__('Upload','wpc')?></button>

                        </div>
                    </div>
                </div>
                <!-- /Form template-->
                <!-- No forms template -->
                <div id="wpc_sheepItForm1_noforms_template"><?php _e('No Texture','wpc'); ?></div>
                <!-- /No forms template-->
                <!-- Controls -->
                <div id="wpc_sheepItForm1_controls" class="row">
                    <div id="wpc_sheepItForm1_add" class="col-sm-3"><a class="button button-primary"><span><?php _e('Add Texture','wpc'); ?></span></a></div>
                    <div id="wpc_sheepItForm1_remove_last" class="col-sm-3"><a class="button button-primary"><span><?php _e('Remove','wpc'); ?> </span></a></div>
                    <div id="wpc_sheepItForm1_remove_all"><a><span><?php _e('Remove all','wpc'); ?></span></a></div>
                    <div id="wpc_sheepItForm1_add_n" class="col-sm-6">
                        <div class="row">
                            <div class="col-sm-2"><input id="wpc_sheepItForm1_add_n_input" type="text" size="4" /></div>
                            <div class="col-sm-10" id="wpc_sheepItForm1_add_n_button"><a class="button button-primary"><span><?php _e('Add','wpc'); ?> </span></a></div></div>
                    </div>
                </div>
                <!-- /Controls -->

            </div>
          <?php
        }
        public function font_sizes(){
            $options = get_option('wpc_settings');
            $data_size=array(); if(!empty($options["font_size_data"])){ foreach($options["font_size_data"] as $size){

                array_push($data_size,array('wpc_#index#_size_name'=>$size['name'],'wpc_#index#_size_value'=>$size['value']));
            }}

            ?>
            <script type="text/javascript">
                var wpc_config_page=true;
                var inject_data_size=[];
                inject_data_size= <?php echo json_encode($data_size); ?>;
            </script>
            <div id="wpc_sheepItForm2">
                <!-- Form template-->
                <div id="wpc_sheepItForm2_template">
                    <div class="row">
                        <div class="col-sm-6">
                            <label for=""><?php _e('Display Name','wpc'); ?></label>
                            <input type="text" class="full-width" name="wpc_settings[font_size_data][#index#][name]" id="wpc_#index#_size_name">
                        </div>
                        <div class="col-sm-6">
                            <label for=""><?php _e('Size','wpc'); ?></label>
                            <input type="text" size="2" class="" name="wpc_settings[font_size_data][#index#][value]" id="wpc_#index#_size_value">

                        </div>
                    </div>
                </div>
                <!-- /Form template-->
                <!-- No forms template -->
                <div id="wpc_sheepItForm2_noforms_template"><?php _e('No Font Size','wpc'); ?></div>
                <!-- /No forms template-->
                <!-- Controls -->
                <div id="wpc_sheepItForm2_controls" class="row">
                    <div id="wpc_sheepItForm2_add" class="col-sm-3"><a class="button button-primary"><span><?php _e('Add Size','wpc'); ?></span></a></div>
                    <div id="wpc_sheepItForm2_remove_last" class="col-sm-3"><a class="button button-primary"><span><?php _e('Remove','wpc'); ?> </span></a></div>
                    <div id="wpc_sheepItForm2_remove_all"><a><span><?php _e('Remove all','wpc'); ?></span></a></div>
                    <div id="wpc_sheepItForm2_add_n" class="col-sm-6">
                        <div class="row">
                            <div class="col-sm-2"><input id="wpc_sheepItForm2_add_n_input" type="text" size="4" /></div>
                            <div class="col-sm-10" id="wpc_sheepItForm2_add_n_button"><a class="button button-primary"><span><?php _e('Add','wpc'); ?> </span></a></div></div>
                    </div>
                </div>
                <!-- /Controls -->

            </div>
         <?php
        }
        function plugin_options_validate($input) {return $input;}
        public function pre_configured_product(){
            $options = get_option('wpc_settings');
            $checked=$options['pre_configured_product']==1?'checked':'';
            ?>
            <input type="checkbox" <?=$checked?> name="wpc_settings[pre_configured_product]" value="1" />
            <?php
        }
        public function emb_char_limit(){
            $options = get_option('wpc_settings');
            ?>
           <div class="col-sm-6">
            <label><?=__('Per Line Character Limit','wpc'); ?></label>
             <input type="text" size="2" value="<?=@$options['emb_settings']['character_limit']?>" name="wpc_settings[emb_settings][character_limit]">
           </div>
            <div class="col-sm-6">
                <label><?=__('Line Limit','wpc'); ?></label>
                <input type="text" value="<?=@$options['emb_settings']['line_limit']?>" size="2" name="wpc_settings[emb_settings][line_limit]">
            </div>
<?php
        }
        public function emb_rotation_angle(){
            $options = get_option('wpc_settings');
            ?>

            <div class="col-sm-12">
                <label><?=__("Rotation Angle","wpc")?></label>
                <input type="text" size="2" value="<?=@$options['emb_settings']['rotation_angle']?>" name="wpc_settings[emb_settings][rotation_angle]">
            </div>
<?php        }

        public function register_custom_submenu_page_image(){
            add_submenu_page(
               'options.php'
                , 'Image Configurator'
                , 'Image Configurator'
                , 'manage_options'
                , 'wpc_configurator_image'
                , array(&$this,'my_custom_submenu_page_callback_image')
            );
        }
        public function register_custom_submenu_page_color(){
            add_submenu_page(
                'options.php'
                , 'Color Configurator'
                , 'Color Configurator'
                , 'manage_options'
                , 'wpc_configurator_color'
                , array(&$this,'my_custom_submenu_page_callback_color')
            );
        }
        public function register_custom_submenu_page_texture(){
            add_submenu_page(
                'options.php'
                , 'Texture Configurator'
                , 'Texture Configurator'
                , 'manage_options'
                , 'wpc_configurator_texture'
                , array(&$this,'my_custom_submenu_page_callback_texture')
            );
        }
        public function register_custom_submenu_page_embroidery(){
            add_submenu_page(
                'options.php'
                , 'Embroidery Configurator'
                , 'Embroidery Configurator'
                , 'manage_options'
                , 'wpc_configurator_embroidery'
                , array(&$this,'my_custom_submenu_page_callback_embroidery')
            );
        }

        public  function register_custom_submenu_page_images(){
            add_submenu_page(
                'options.php'
                , 'Image Configurator'
                , 'Image Configurator'
                , 'manage_options'
                , 'wpc_configurator_images'
                , array(&$this,'my_custom_submenu_page_callback_images')
            );
        }
        public function my_custom_submenu_page_callback_images(){
            require_once(WPC_PLUGIN_ADMIN_DIR.'/all_images.php');
        }
        public function my_custom_submenu_page_callback_image(){
            require_once(WPC_PLUGIN_ADMIN_DIR.'/image_configurator.php');
        }
        public function my_custom_submenu_page_callback_color(){
            require_once(WPC_PLUGIN_ADMIN_DIR.'/color_configurator.php');
        }
        public function my_custom_submenu_page_callback_texture(){
            require_once(WPC_PLUGIN_ADMIN_DIR.'/texture_configurator.php');
        }
        public function my_custom_submenu_page_callback_embroidery(){
            require_once(WPC_PLUGIN_ADMIN_DIR.'/embroidery_configurator.php');
        }
        public function wpc_save_configuration_form(){
           parse_str($_POST['formData'],$params);
           update_post_meta(intval($_POST['postId']),'_wpc_image_config',$params);
        }
        public function wpc_save_configuration_form_color(){
            $formData=parse_str($_POST['formDataColor'],$params);
            update_post_meta(intval($_POST['postId']),'_wpc_color_config',$params);
        }
        public function wpc_save_configuration_form_embroidery(){
            parse_str($_POST['formData'],$params);
              update_post_meta(absint($_POST['postId']),'_wpc_emb_config_'.absint($_POST["termId"]),$params["wpc_emb_config"]);
            exit;
        }
        public function wpc_save_tab_data(){
            $section=esc_html($_POST["section"]);
            parse_str($_POST['formData'],$params);
            $postId=absint($_POST["postId"]);
            $termId=isset($_POST["termId"]) ? absint($_POST["termId"]) : null;
            $taxonomy=isset($_POST["termId"]) ? absint($_POST["termId"]) : null;
            switch ($section) {
                case 'wpc_base_edge' :
                    update_post_meta($postId,'_wpc_base_image_base_'.$termId,$params['wpc_base_image_base']);
                    update_post_meta($postId,'_wpc_base_image_texture_'.$termId,$params['wpc_base_image_texture']);
                    update_post_meta($postId,"_wpc_static_images_".$termId,$params["wpc_static_images"]);
                    update_post_meta($postId,"_wpc_not_require_".$termId,$params["wpc_not_require"]);
                    break;
                case 'save_all_layers':
                    update_post_meta($postId,'_wpc_color_dependency',$params['wpc_color_dependency']);
                    update_post_meta($postId,'_wpc_base_color_dependency',$params['wpc_base_color_dependency']);
                    update_post_meta($postId,'_wpc_static_layers',$params['wpc_static_layers']);
                    update_post_meta($postId,'_wpc_emb_layer',$params['wpc_emb_layer']);
                    update_post_meta($postId,'_wpc_cord_layers',$params['wpc_cord_layers']);
                    break;
                case 'save_all_cords':
                    update_post_meta($postId,'_wpc_no_cords',$params['wpc_no_cords']);
                    update_post_meta($postId,'_wpc_multicolor_cords',$params['wpc_multicolor_cords']);
                    update_post_meta($postId,'_wpc_available_models',$params['wpc_available_models']);
                    update_post_meta($postId,'_wpc_no_emb',$params['wpc_no_emb']);
                    break;
                case "save_all_others":
                    update_post_meta($postId,'_wpc_selected_base',$params['wpc_selected_base']);
                    update_post_meta($postId,'_wpc_additional_comment',$params['wpc_additional_comment']);
                    break;
                case 'wpc_cord_layers' :
                    update_post_meta($postId,'_wpc_cord_layers',$params['wpc_cord_layers']);
                    break;
                case 'wpc_multicolor_cords' :
                    update_post_meta($postId,'_wpc_multicolor_cords',$params['wpc_multicolor_cords']);
                    break;
                case 'wpc_cord_images' :
                    update_post_meta($postId,'_wpc_cord_images_'.$termId,$params['wpc_cord_images']);
                    break;
                case 'wpc_multicolor_images' :
                    update_post_meta($postId,'_wpc_multicord_images_'.$termId,$params['wpc_muticord_images']);
                    break;
                case 'wpc_colors' :
                    update_post_meta($postId,'_wpc_colors_'.$termId,$params["wpc_colors"]);
                    break;
                case 'wpc_textures':
                    update_post_meta($postId,'_wpc_textures_'.$termId,$params["wpc_textures"]);
                    break;
                default:
                    break;
            }
            exit;
        }
        public function wpc_load_tab_data(){
            $postId=absint($_POST["postId"]);
            $section=trim($_POST["section"]);
            $termId=isset($_POST["termId"]) ? absint($_POST["termId"]) : null;
            $taxonomy=isset($_POST["taxonomy"]) ? esc_html($_POST["taxonomy"]) : null;
            if($section!="wpc_cord_layers"  || $section!="wpc_base_edge") {
                switch ($section) {
                    case "wpc_multicolor_cords":
                        require_once(WPC_PLUGIN_ADMIN_DIR.'/wpc_multicolor_cords.php');
                        break;
                    case "wpc_cord_images":
                        require_once(WPC_PLUGIN_ADMIN_DIR.'/wpc_cord_images.php');
                        break;
                    case "wpc_multicolor_images":
                        require_once(WPC_PLUGIN_ADMIN_DIR.'/wpc_multicolor_images.php');
                        break;
                    case "wpc_colors":
                        require_once(WPC_PLUGIN_ADMIN_DIR.'/wpc_colors.php');
                        break;
                    case "wpc_textures" :
                        require_once(WPC_PLUGIN_ADMIN_DIR.'/wpc_textures.php');
                        break;
                    default:
                        echo 'success';
                }
            }
            exit;
        }
        public function copy_coor_or_texture(){
            $type=esc_html($_POST["type"]);
            $postId=absint($_POST["postId"]);
            $modelToCopy=absint($_POST["modeltocopy"]);
            $term=absint($_POST["termId"]);
            switch ($type){
                case "color":
                    $modelData=get_post_meta($postId,"_wpc_colors_".$modelToCopy,true);
                    if(!empty($modelData)){
                        update_post_meta($postId,"_wpc_colors_".$term,$modelData);
                    }
                    break;
                case "texture":
                    $modelData=get_post_meta($postId,"_wpc_textures_".$modelToCopy,true);
                    if(!empty($modelData)){
                        update_post_meta($postId,"_wpc_textures_".$term,$modelData);
                    }
                    break;
                case "embroidery":
                    $modelData=get_post_meta($postId,"_wpc_emb_config_".$modelToCopy,true);
                    if(!empty($modelData)){
                        update_post_meta($postId,"_wpc_emb_config_".$term,$modelData);
                    }
                    break;
            }
            exit;
        }

    }
    new WPC_Admin();
}
