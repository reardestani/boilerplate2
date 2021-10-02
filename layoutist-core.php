<?php
/**
 * Plugin Name: Layoutist Core
 * Description: Core functionalities for layoutist.com
 * Version: 1.0.0
 * Author: WPizard
 * Text Domain: layoutist-core
 */

defined( 'ABSPATH' ) or die();

if ( ! class_exists( 'Layoutist_Core' ) ) {

    class Layoutist_Core {

        private static $version;

        private static $plugin_basename;

        private static $plugin_name;

        private static $plugin_dir;

        private static $plugin_url;

        public function __construct() {
            $this->define_constants();
            $this->add_actions();
        }

        protected function define_constants() {
            $plugin_data = get_file_data( __FILE__, [ 'Plugin Name', 'Version' ], 'layoutist-core' );

            self::$plugin_basename = plugin_basename( __FILE__ );
            self::$plugin_name = array_shift( $plugin_data );
            self::$version = array_shift( $plugin_data );
            self::$plugin_dir = trailingslashit( plugin_dir_path( __FILE__ ) );
            self::$plugin_url = trailingslashit( plugin_dir_url( __FILE__ ) );
        }

        protected function add_actions() {
            add_action( 'plugins_loaded', [ $this, 'init' ] );
        }

        public function init() {
            load_plugin_textdomain( 'layoutist-core', false, $this->plugin_dir() . '/languages' );

            $this->load_files( [
                'sample/class',
            ] );

            do_action( 'layoutist_core_init', $this );
        }

        public function version() {
            return self::$version;
        }

        public function plugin_basename() {
            return self::$plugin_basename;
        }

        public function plugin_name() {
            return self::$plugin_name;
        }

        public function plugin_dir() {
            $plugin_dir = apply_filters( 'layoutist_core_plugin_dir', self::$plugin_dir );

            return $plugin_dir;
        }

        public function plugin_url() {
            $plugin_url = apply_filters( 'layoutist_core_plugin_url', self::$plugin_url );

            return $plugin_url;
        }

        public function load_directory( $directory_name ) {
            $path = trailingslashit( $this->plugin_dir() . 'includes/' . $directory_name );
            $file_names = glob( $path . '*.php' );

            foreach ( $file_names as $filename ) {
                if ( file_exists( $filename ) ) {
                    require_once $filename;
                }
            }
        }

        public function load_files( $file_names = array() ) {
            foreach ( $file_names as $file_name ) {
                $this->load_file( $file_name );
            }
        }

        public function load_file( $file_name = '' ) {
            if ( file_exists( $path = $this->plugin_dir() . 'includes/' . $file_name . '.php' ) ) {
                require_once $path;
            }
        }
    }
}

function layoutist_core() {
    return new Layoutist_Core();
}

layoutist_core();
