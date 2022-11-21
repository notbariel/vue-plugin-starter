<?php
/*
Plugin Name: Vue Starter Plugin
Plugin URI: https://example.com/
Description: A WordPress Vue.js starter plugin
Version: 0.1
Author: Your Name
Author URI: https://example.com/
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: baseplugin
Domain Path: /languages
*/

/**
 * Copyright (c) YEAR Your Name (email: Email). All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * **********************************************************************
 */

// don't call the file directly
if (!defined('ABSPATH')) exit;

/**
 * Vue_Base_Plugin class
 *
 * @class Vue_Base_Plugin The class that holds the entire Vue_Base_Plugin plugin
 */
final class Vue_Base_Plugin
{

    /**
     * Plugin version
     *
     * @var string
     */
    public $version = '0.1.0';

    /**
     * Holds various class instances
     *
     * @var array
     */
    private $container = array();

    /**
     * Constructor for the Vue_Base_Plugin class
     *
     * Sets up all the appropriate hooks and actions
     * within our plugin.
     */
    public function __construct()
    {

        $this->define_constants();

        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));

        add_action('plugins_loaded', array($this, 'init_plugin'));
    }

    /**
     * Initializes the Vue_Base_Plugin() class
     *
     * Checks for an existing Vue_Base_Plugin() instance
     * and if it doesn't find one, creates it.
     */
    public static function init()
    {
        static $instance = false;

        if (!$instance) {
            $instance = new Vue_Base_Plugin();
        }

        return $instance;
    }

    /**
     * Magic getter to bypass referencing plugin.
     *
     * @param $prop
     *
     * @return mixed
     */
    public function __get($prop)
    {
        if (array_key_exists($prop, $this->container)) {
            return $this->container[$prop];
        }

        return $this->{$prop};
    }

    /**
     * Magic isset to bypass referencing plugin.
     *
     * @param $prop
     *
     * @return mixed
     */
    public function __isset($prop)
    {
        return isset($this->{$prop}) || isset($this->container[$prop]);
    }

    /**
     * Define the constants
     *
     * @return void
     */
    public function define_constants()
    {
        define('VUEBASEPLUGIN_VERSION', $this->version);
        define('VUEBASEPLUGIN_FILE', __FILE__);
        define('VUEBASEPLUGIN_PATH', dirname(VUEBASEPLUGIN_FILE));
        define('VUEBASEPLUGIN_INCLUDES', VUEBASEPLUGIN_PATH . '/includes');
        define('VUEBASEPLUGIN_URL', plugins_url('', VUEBASEPLUGIN_FILE));
        define('VUEBASEPLUGIN_ASSETS', VUEBASEPLUGIN_URL . '/assets');
    }

    /**
     * Load the plugin after all plugis are loaded
     *
     * @return void
     */
    public function init_plugin()
    {
        $this->includes();
        $this->init_hooks();
    }

    /**
     * Placeholder for activation function
     *
     * Nothing being called here yet.
     */
    public function activate()
    {

        $installed = get_option('vuebaseplugin_installed');

        if (!$installed) {
            update_option('vuebaseplugin_installed', time());
        }

        update_option('vuebaseplugin_version', VUEBASEPLUGIN_VERSION);
    }

    /**
     * Placeholder for deactivation function
     *
     * Nothing being called here yet.
     */
    public function deactivate()
    {
    }

    /**
     * Include the required files
     *
     * @return void
     */
    public function includes()
    {

        require_once VUEBASEPLUGIN_INCLUDES . '/Assets.php';

        if ($this->is_request('admin')) {
            require_once VUEBASEPLUGIN_INCLUDES . '/Admin.php';
        }

        if ($this->is_request('frontend')) {
            require_once VUEBASEPLUGIN_INCLUDES . '/Frontend.php';
        }

        if ($this->is_request('ajax')) {
            // require_once VUEBASEPLUGIN_INCLUDES . '/class-ajax.php';
        }

        require_once VUEBASEPLUGIN_INCLUDES . '/Api.php';
    }

    /**
     * Initialize the hooks
     *
     * @return void
     */
    public function init_hooks()
    {

        add_action('init', array($this, 'init_classes'));

        // Localize our plugin
        add_action('init', array($this, 'localization_setup'));
    }

    /**
     * Instantiate the required classes
     *
     * @return void
     */
    public function init_classes()
    {

        if ($this->is_request('admin')) {
            $this->container['admin'] = new VueBasePlugin\Admin();
        }

        if ($this->is_request('frontend')) {
            $this->container['frontend'] = new VueBasePlugin\Frontend();
        }

        if ($this->is_request('ajax')) {
            // $this->container['ajax'] =  new VueBasePlugin\Ajax();
        }

        $this->container['api'] = new VueBasePlugin\Api();
        $this->container['assets'] = new VueBasePlugin\Assets();
    }

    /**
     * Initialize plugin for localization
     *
     * @uses load_plugin_textdomain()
     */
    public function localization_setup()
    {
        load_plugin_textdomain('vuebaseplugin', false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }

    /**
     * What type of request is this?
     *
     * @param  string $type admin, ajax, cron or frontend.
     *
     * @return bool
     */
    private function is_request($type)
    {
        switch ($type) {
            case 'admin':
                return is_admin();

            case 'ajax':
                return defined('DOING_AJAX');

            case 'rest':
                return defined('REST_REQUEST');

            case 'cron':
                return defined('DOING_CRON');

            case 'frontend':
                return (!is_admin() || defined('DOING_AJAX')) && !defined('DOING_CRON');
        }
    }
} // Vue_Base_Plugin

$vuebaseplugin = Vue_Base_Plugin::init();
