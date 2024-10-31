<?php

/**
 * Plugin Name: Nested Comments Unbound
 * Description: Allows Nested Comments to "Snake" Unboundedly Instead of Jamming Up at "Max Depth"
 * Plugin URI:  http://ckmacleod.com/word-press-plugins/wordpress-nested-comments-unbound/
 * Version:     1.0
 * Author:      CK MacLeod
 * Author URI:  http://www.ckmacleod.com/
 * License:     GPL
 * Text Domain: wp_ncu
 * Domain Path: /languages
 * 
 */

defined( 'ABSPATH' ) or die( 'Plugin file cannot be accessed directly.' ) ;

if ( ! defined( 'WP_NCU_VERSION' ) ) {
    
    define( 'WP_NCU_VERSION', '1.0' ) ; //  testing version
    
}

register_activation_hook( __FILE__, 'wp_ncu_set_default_options' );
register_deactivation_hook( __FILE__, 'wp_ncu_set_deactivation_options' );

//set default options or merge on re-activation
function wp_ncu_set_default_options() {
        
    $default_options = array(
	
        'apply'                 => 0,              //whether to change discussion settings and apply the plug-in
        'breakpoint'            => 5,              //where threads should turn or begin to snake
        'turnpoint'             => 3,              //how deep should snaking go
        'left_margin'           => '-4em',         //how far indented the left-snaking comments should be (suits 2017)
        'right_margin'          => '',             //how far indented the right-snaking comments should be
        'width'                 => '',             //width of snaking comment
        'breakpointtwo'         => '',             //the maximum depth to which special formatting, if any, will be applied
        'version'               => WP_NCU_VERSION, //plugin version
        'add_credit'            => 0,              //add the plugin credit-link
        'add_css'               => '',             //add inline-css
        'remove_turn_margins'   => 1,              //remove left margin for <640px screens
        
    );
    
    //merge rather than overwrite existing settings if any
    if ( get_option( 'wp_ncu_settings' ) ) {
        
        $default_options = array_merge( $default_options, get_option( 'wp_ncu_settings' ) ) ;
        
    }
           
    update_option( 'wp_ncu_settings', $default_options );
    
    //enable separate retention of "regular" thread comment settings
    $leg_thread = get_option( 'thread_comments' ) ? get_option( 'thread_comments' ) : 0 ;
    $leg_depth  = get_option( 'thread_comments_depth' ) ? get_option( 'thread_comments_depth' ) : 0 ;
    
    $legacy_options = array(
        
        'leg_thread'            => $leg_thread,     //legacy threaded setting
        'leg_depth'             => $leg_depth,      //legacy max depth setting
        
    ) ;
    
    update_option( 'wp_ncu_legacy_settings', $legacy_options ) ;
        
}

/**
 * DE-ACTIVATION
 * Set threading options however the user last set them apart from NCU
 */
function wp_ncu_set_deactivation_options() {
    
    $options = get_option( 'wp_ncu_legacy_settings') ;
    
    $leg_thread = isset( $options['leg_thread'] ) ? $options['leg_thread'] : 0 ;
    $leg_depth = isset( $options['leg_depth'] ) ? $options['leg_depth'] : 0 ;
    
    update_option( 'thread_comments', $leg_thread ) ;
    update_option( 'thread_comments_depth', $leg_depth ) ;
    
}
/**
 * INITIATE AND INSTALL
 */
/* MAIN ACTIONS */
add_action( 'init', 'wp_ncu_load_translation_file' ) ;
add_action( 'admin_init', 'wp_ncu_init_settings' ) ;
add_action( 'admin_menu', 'wp_ncu_add_menu' ) ;
add_action( 'comment_form_before', 'wp_ncu_add_credit_html') ;

/* MAIN FILTER FUNCTIONS */
add_filter('thread_comments_depth_max', 'wp_ncu_max_depth' );
add_filter('comment_class', 'wp_ncu_comment_class');

/*ENQUEUE STYLESHEETS */
add_action('wp_enqueue_scripts', 'wp_ncu_stylesheet');
add_action( 'admin_print_styles', 'wp_ncu_enqueue_admin_style' ) ;

/* SETTINGS LINK FOR PLUGINS PAGE */
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'add_wp_ncu_settings_link' );
/**
* INITIALIZE 
* Save threading options prior to completed activation and application of NCU settings
* If NCU un-applied, restore old settings
*/     
function wp_ncu_init_settings() {
    
    register_setting( 'wp_ncu', 'wp_ncu_settings', 'sanitize_ncu_options') ;
    
    $current_thread = intval( get_option( 'thread_comments' ) ) ;
    $current_depth = intval(get_option( 'thread_comments_depth' ) ) ;
    
    $options = get_option( 'wp_ncu_settings' ) ;
    
    $apply = isset( $options['apply'] ) ? $options['apply'] : 0 ;
    
    //don't bother with any of this if applied and depth at max
    if ( $apply && 10000 === $current_depth ) {
        
        return;
        
    }
    
    $leg_options = get_option( 'wp_ncu_legacy_settings' ) ;
    $leg_thread = isset( $leg_options['leg_thread'] ) ? $leg_options['leg_thread'] : 0 ;
    $leg_depth = isset( $leg_options['leg_depth'] ) ? $leg_options['leg_depth'] : 0 ;
  
    //in "applied" state, but options not yet set for NCU
    if ( $apply && 10000 !== $current_depth ) {
        
        //update latest thread settings before apply
        $leg_options['leg_thread'] = $current_thread ;
        $leg_options['leg_depth']  = $current_depth;
                
        update_option( 'wp_ncu_legacy_settings', $leg_options ) ;
        
        //apply NCU settings
        update_option( 'thread_comments', 1 ) ;

        update_option('thread_comments_depth', 10000 ) ;
           
    } else {
        
        if ( ! $apply && 10000 === $current_depth ) {
            
        //not in applied state, but options still set at 10000, so restore "legacy settings"
        update_option( 'thread_comments', $leg_thread ) ;
        update_option( 'thread_comments_depth', $leg_depth ) ;
        
        }
       
    }
    
} 
/**
 * MAIN FUNCTION 1: SET BIG DEPTH
 */
function wp_ncu_max_depth( $depth ) {
    
    $options = get_option( 'wp_ncu_settings' ) ;
    
    $apply = isset( $options['apply'] ) ? $options['apply'] : 0 ;
    
    if ( $apply ) {
    
        $depth = 10000 ;
        
    }
    
    return $depth;

}
/**
 * MAIN FUNCTION 2: ADD A SET OF NEW CLASSES TO COMMENTS DEPENDING ON SETTINGS
 * @global int $comment_depth
 * @param array $classes
 * @return array
 */
function wp_ncu_comment_class( $classes ) {
    
    $options    =   get_option( 'wp_ncu_settings' ) ;
    $apply      =   isset( $options['apply'] )  ?   $options['apply'] : 0 ;
    
    if ( $apply ) {
    
        global $comment_depth;

        $breakpoint =   isset( $options['breakpoint'] )     ?   $options['breakpoint'] : '' ;
        $turnpoint  =   isset( $options['turnpoint'] )      ?   $options['turnpoint'] : '' ;
        $bptwo      =   isset( $options['breakpointtwo'] )  ?   $options['breakpointtwo'] : '' ;

        if ($comment_depth > $breakpoint ) {

            $classes[] = 'ncu_super-max';

        }

        if ( $comment_depth === $breakpoint ) {

            $classes[] = 'ncu_breakpoint';

        }

        if ( $bptwo && $comment_depth > $bptwo ) {

            $classes[] = 'ncu_super-super-max' ;

        }

        //no dividing by zero
        if ( $turnpoint === 0 ) {

            return $classes ;

        } 

        if ( ( $comment_depth - $breakpoint ) % $turnpoint === 0 )  {

            $classes[] = 'ncu_turnpoint';

        }

        if ( $comment_depth > $breakpoint ) {

            //avoid even numbered results
            $turn_no = ceil( ( $comment_depth - $breakpoint ) / ( $turnpoint + .000001 ) / 2 );
 
            if ( ceil( ( $comment_depth - $breakpoint ) / $turnpoint ) % 2 === 0 ) {  

                $classes[] = 'ncu_return'; 

                $classes[] = 'ncu_return-' . $turn_no ;

            } else {

                $classes[] = 'ncu_turn';

                $classes[] = 'ncu_turn-' . $turn_no ;

            }

         }
         
    }
	
    return $classes;
        
}
/***
 * FOR POSSIBLE TRANSLATION
 */
function wp_ncu_load_translation_file() {
    // relative path to WP_PLUGIN_DIR where the translation files will sit:
    $plugin_path = plugin_basename( dirname( __FILE__ ) .'/languages' ) ;
    load_plugin_textdomain( 'wp_ncu', '', $plugin_path ) ;
}
/***
 * CREDIT LINK HTML
 */
function wp_ncu_add_credit_html() {
    
    $options = get_option( 'wp_ncu_settings' ) ;
    
    $option = isset( $options['add_credit'] ) ? $options['add_credit'] : 0 ;
    
    if ( $option && is_singular() && comments_open() ) {
        
        echo get_cks_ncu_credit() ;
        
    }
    
}
/**
 * GET CKS CREDIT HTML
 * @return html
 */
function get_cks_ncu_credit() {
    
    $html = '<div id="cks_cib-credit" '
            . 'style="width: 100%; '
            . 'text-align: right; '
            . 'margin-top: 1em; '
            . '" >'
            . '<a style="background-color: whitesmoke;'
            . 'border: 1px dotted gray; '
            . 'opacity:.5;text-align:center; '
            . 'padding: 4px; '
            . 'font-family: Arial,Helvetica,sans-serif;font-size:.5em;" '
            . 'href="http://ckmacleod.com/wordpress-plugins/nested-comments-unbound">'
            . '<img alt="' 
            . __( 'Nested Comments Unbound by CK\'s Plug-Ins', 'wp_ncu') 
            . '" title="' 
            . __( 'Nested Comments Unbound by CK\'s Plug-Ins', 'wp_ncu') 
            . '" style="vertical-align:middle" src="' 
            .  plugin_dir_url( __FILE__ ) 
            . 'images/cks_wp_plugins_80x16.jpg'  . '"></a></div>' ;

    return apply_filters( 'wp_ncu_credit', $html ) ;

}
/************************
 * SETTINGS PAGE SET-UP *
 ************************/

/**
 * Add a Settings Page
 */
function wp_ncu_add_menu() {

    add_options_page( 
        'Nested Comments Unbound', 
        'Nested Comments Unbound', 
        'manage_options', 
        'wp_ncu', 
        //?
        'wp_ncu_plugin_settings_page' 
    );

}
/***
 * Settings link on plugin page
 */
function add_wp_ncu_settings_link(  $links  ) { 
    
  $settings_link = '<a href="options-general.php?page=wp_ncu">Settings</a>' ; 
  
  array_unshift( $links, $settings_link ); 
  
  return $links; 
}
/**
* Settings Page Menu Callback
*/     
function wp_ncu_plugin_settings_page() {

    if ( ! current_user_can( 'manage_options' ) ) {
            wp_die(__( 'You do not have sufficient permissions '
                    . 'to access this page.', 'wp_ncu' ) );
    }

    wp_ncu_options_page() ;
    
} 
/**
 * CSS Stylesheet for Settings Page
 */
function wp_ncu_enqueue_admin_style() {

    wp_register_style( 
           'ncu-admin-styles', 
           plugins_url( 'css/ncu-admin-styles.css'. '?v=' . 
                   WP_NCU_VERSION, __FILE__ ) 
           );
    
    $page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING ) ;

    if ( isset( $page ) && $page === "wp_ncu" )  {    

        wp_enqueue_style( 'ncu-admin-styles' ) ;
    
    }
    
    wp_register_style( 
           'ncu-discussion', 
           plugins_url( 'css/ncu-discussion-style.css'. '?v=' . 
                   WP_NCU_VERSION, __FILE__ ) 
           );
    
    $options    =   get_option( 'wp_ncu_settings' ) ;
    $apply      =   isset( $options['apply'] )  ?   $options['apply'] : 0 ;
    
    if ( $apply ) {
    
        $screen = get_current_screen() ;

        if ( $screen->base === 'options-discussion' )  {    

            wp_enqueue_style( 'ncu-discussion' ) ;

        }
    }

}
/* CREATE EDITABLE INLINE STYLESHEET */
function wp_ncu_stylesheet() {
    
    wp_register_style( 
           'ncu-styles', 
           plugins_url('css/wp-ncu-style.css' . '?v=' . 
                   WP_NCU_VERSION, __FILE__ ) 
           );
    
    $options = get_option( 'wp_ncu_settings' ) ;
    
    $width          =   isset( $options['width']) ? $options['width'] : '' ;
    $marginLeft     =   isset( $options['left_margin']) ? $options['left_margin'] : '' ;
    $marginRight    =   isset( $options['right_margin']) ? $options['right_margin'] : '' ;
    $add_css        =   isset( $options['add_css']) ? $options['add_css'] : '' ;
    $remove_turn_margins     =   isset( $options['remove_turn_margins']) ? $options['remove_turn_margins'] : '' ;

    //don't enqueue unless needed
    if ( is_singular() && comments_open() && ( $width | $marginLeft | $marginRight | $add_css | $remove_turn_margins ) ) {

        wp_enqueue_style( 'ncu-styles' );

        $custom_css = '' ;

        if ( $width ) {

            $custom_css .= " .ncu_return, .ncu_turn {width: {$width}; } " ;

        }  

        if ( $marginLeft ) {

            $custom_css .= " .ncu_turn { margin-left: {$marginLeft}; }  " ;

        }

        if ( $marginRight ) {

            $custom_css .= " .ncu_turn { margin-right: {$marginRight}; } " ;

        }
        
        if ( $add_css ) {
            
            $custom_css .= $add_css ;
            
        }
        
        if ( $remove_turn_margins ) {
            
            $custom_css .= ' @media only screen and (max-width: 640px) { '
                    . '.ncu_turn { margin-left: 0; } '
                    . '.ncu_return { margin-right: 0; } }' ;
        }

        wp_add_inline_style('ncu-styles', $custom_css ) ;
        
    }
    
}
/**
 * SANITIZE MAIN PAGE OPTIONS
 * @param array $options
 * @return array
 */
function sanitize_ncu_options( $options ) {
    
    $options['width']               =   esc_attr( 
            $options['width'] ) ;  //css width
    $options['left_margin']         =   esc_attr( 
            $options['left_margin'] ) ;  //css width
    $options['right_margin']        =   esc_attr( 
            $options['right_margin'] ) ;  //css width
    $options['breakpoint']          =   isset( $options['breakpoint'] ) ?  
            intval( $options['breakpoint']) : 0 ;
    $options['turnpoint']           =   isset( $options['turnpoint'] ) ?  
            intval( $options['turnpoint']) : 0 ;
    $options['breakpointtwo'] =   isset( $options['breakpointtwo'] ) ?  
            intval( $options['breakpointtwo']) : 0 ;
    $options['add_credit']          =   isset( $options['add_credit'] ) ?  
            intval( $options['add_credit']) : 0 ;
    $options['add_css']             =   isset( $options['add_css'] ) ?  
            esc_html( $options['add_css']) : '' ;
    $options['apply']          =   isset( $options['apply'] ) ?  
            intval( $options['apply']) : 0 ;
    
    return $options ;
    
}

include_once(dirname(__FILE__).'/ncu-settings.php');