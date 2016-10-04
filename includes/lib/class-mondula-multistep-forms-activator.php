<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Description of class-mondula-multistep-forms-activator
 *
 * @author alex
 */
class Mondula_Form_Wizard_Activator {
    
    private static $name = "mondula_form_wizards";

    public static function activate() {
        self::setup_db();
    }
    
    private static function setup_db() {
        global $wpdb, $charset_collate;
        
        $table_name = $wpdb->prefix . self::$name;
        
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            title TEXT NOT NULL,
            wizard BLOB NOT NULL,
            date DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";
        
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        
        dbDelta( $sql );
    }
}
