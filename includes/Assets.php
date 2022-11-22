<?php

namespace VueBasePlugin;

/**
 * Scripts and Styles Class
 */
class Assets
{

    function __construct()
    {

        if (is_admin()) {
            add_action('admin_enqueue_scripts', [$this, 'register'], 5);
        } else {
            add_action('wp_enqueue_scripts', [$this, 'register'], 5);
        }
    }

    /**
     * Register our app scripts and styles
     *
     * @return void
     */
    public function register()
    {
        $this->register_scripts($this->get_scripts());
        $this->register_styles($this->get_styles());
    }

    /**
     * Register scripts
     *
     * @param  array $scripts
     *
     * @return void
     */
    private function register_scripts($scripts)
    {
        foreach ($scripts as $handle => $script) {
            $deps      = isset($script['deps']) ? $script['deps'] : false;
            $in_footer = isset($script['in_footer']) ? $script['in_footer'] : false;
            $version   = isset($script['version']) ? $script['version'] : VUEBASEPLUGIN_VERSION;

            wp_register_script($handle, $script['src'], $deps, $version, $in_footer);
        }
    }

    /**
     * Register styles
     *
     * @param  array $styles
     *
     * @return void
     */
    public function register_styles($styles)
    {
        foreach ($styles as $handle => $style) {
            $deps = isset($style['deps']) ? $style['deps'] : false;

            wp_register_style($handle, $style['src'], $deps, VUEBASEPLUGIN_VERSION);
        }
    }

    /**
     * Get all registered scripts
     *
     * @return array
     */
    public function get_scripts()
    {
        $prefix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '.min' : '';

        $scripts = [
            'vuebaseplugin-frontend' => [
                'src'       => VUEBASEPLUGIN_ASSETS . '/frontend/main.js',
                'deps'      => ['jquery'],
                'version'   => filemtime(VUEBASEPLUGIN_PATH . '/assets/frontend/main.js'),
                'in_footer' => true
            ],
            'vuebaseplugin-admin' => [
                'src'       => VUEBASEPLUGIN_ASSETS . '/admin/main.js',
                'deps'      => ['jquery'],
                'version'   => filemtime(VUEBASEPLUGIN_PATH . '/assets/admin/main.js'),
                'in_footer' => true
            ],
        ];

        return $scripts;
    }

    /**
     * Get registered styles
     *
     * @return array
     */
    public function get_styles()
    {

        $styles = [
            'vuebaseplugin-frontend' => [
                'src' =>  VUEBASEPLUGIN_ASSETS . '/frontend/main.css'
            ],
            'vuebaseplugin-admin' => [
                'src' =>  VUEBASEPLUGIN_ASSETS . '/admin/main.css'
            ],
        ];

        return $styles;
    }
}
