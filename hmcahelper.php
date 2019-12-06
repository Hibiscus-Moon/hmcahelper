<?php
/*
Plugin Name: Hibiscus Moon Helper
Plugin URI:  https://www.coreymwinter.com
Description: Custom Wordpress Functions for Hibiscus Moon
Version:     1.9
Author:      Corey Winter
Author URI:  https://coreymwinter.com
License:     GPLv2
*/

/**
 * Copyright (c) 2019 Corey Winter (email : hello@coreymwinter.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */



if(!class_exists('HMCA_Custom_Plugin'))
{
    class HMCA_Custom_Plugin
    {
        /**
         * Construct the plugin object
         */
        public function __construct()
        {
            // Initialize Settings
            //require_once(sprintf("%s/settings.php", dirname(__FILE__)));
            //$HMCA_Custom_Plugin_Settings = new WP_Plugin_Template_Settings();

            // Require included files
            if ( file_exists( __DIR__ . '/cmb2/init.php' ) ) {
              require_once __DIR__ . '/cmb2/init.php';
            } elseif ( file_exists(  __DIR__ . '/CMB2/init.php' ) ) {
              require_once __DIR__ . '/CMB2/init.php';
            }

            require( 'includes/functions.php' );
            require( 'includes/settings.php' );
            require( 'includes/blocks.php' );
            require( 'includes/optinbar.php' );

            //$plugin = plugin_basename(__FILE__);
            //add_filter("plugin_action_links_$plugin", array( $this, 'plugin_settings_link' ));
        } // END public function __construct

        /**
         * Activate the plugin
         */
        public static function activate()
        {
            // Do nothing
        } // END public static function activate

        /**
         * Deactivate the plugin
         */
        public static function deactivate()
        {
            // Do nothing
        } // END public static function deactivate

        // Add the settings link to the plugins page
        //function plugin_settings_link($links)
        //{
            //$settings_link = '<a href="options-general.php?page=wp_plugin_template">Settings</a>';
            //array_unshift($links, $settings_link);
            //return $links;
        //}

    } // END class HMCA_Custom_Plugin
} // END if(!class_exists('HMCA_Custom_Plugin'))

if(class_exists('HMCA_Custom_Plugin'))
{
    // Installation and uninstallation hooks
    register_activation_hook(__FILE__, array('HMCA_Custom_Plugin', 'activate'));
    register_deactivation_hook(__FILE__, array('HMCA_Custom_Plugin', 'deactivate'));

    // instantiate the plugin class
    $hmca_custom_plugin = new HMCA_Custom_Plugin();
}


