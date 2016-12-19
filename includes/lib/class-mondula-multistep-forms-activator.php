<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Description of class-mondula-multistep-forms-activator
 *
 * @author alex
 */
class Mondula_Form_Wizard_Activator {

    private static $name = "mondula_form_wizards";

    public static function activate( $network_wide ) {
        self::setup_db( $network_wide );
    }

    private static function setup_db( $network_wide ) {
      global $wpdb;

      if ( is_multisite() && $network_wide ) {
        $blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
        foreach ( $blog_ids as $blog_id ) {
          self::activate_for_blog( $blog_id );
        }
      } else {
        self::create_table();
      }
    }

    public static function activate_for_blog( $blog_id ) {
      switch_to_blog( $blog_id );
      self::create_table();
      restore_current_blog();
    }

    private static function create_table( ) {
      global $wpdb, $charset_collate;

      $table_name = $wpdb->prefix . self::$name;

      $sql = "CREATE TABLE $table_name (
          id mediumint(9) NOT NULL AUTO_INCREMENT,
          title TEXT NOT NULL,
          json TEXT NOT NULL,
          version VARCHAR(11) NOT NULL,
          date DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL,
          PRIMARY KEY  (id)
      ) $charset_collate;";

      require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

      dbDelta( $sql );
    }

    public static function drop_table( $tables, $blog_id ) {
      if ( empty( $blog_id ) || 1 == $blog_id || $blog_id != $GLOBALS['blog_id'] ) {
        return $tables;
      }

      global $wpdb;
      $blog_prefix = $wpdb->get_blog_prefix( $blog_id );
      $table = $blog_prefix . self::$name;
      $tables[] = $table;

      return $tables;
    }
}
