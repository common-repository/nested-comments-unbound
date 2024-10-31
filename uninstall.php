<?php

/* 
 * DELETE SETTINGS DATA ON FULL UNINSTALL
 */

if ( !defined('WP_UNINSTALL_PLUGIN') ) {
    
    exit;
    
}

delete_option( 'wp_ncu_settings' ) ;
delete_option( 'wp_ncu_legacy_settings' );

