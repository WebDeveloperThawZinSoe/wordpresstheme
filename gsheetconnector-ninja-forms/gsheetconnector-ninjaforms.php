<?php

/**
 * Plugin Name:  Ninja Forms GSheetConnector
 * Description:  Send your Ninja Forms data to your Google Sheets spreadsheet.
 * Author:       GSheetConnector
 * Author URI:   https://www.gsheetconnector.com/
 * Version:      1.2.3
 * Text Domain:  gsheetconnector-ninjaforms
 * Domain Path:  languages
 */
// Exit if accessed directly.

/*freemius*/
if ( ! function_exists( 'gs_ninjafree' ) ) {
    // Create a helper function for easy SDK access.
    function gs_ninjafree() {
        global $gs_ninjafree;

        if ( ! isset( $gs_ninjafree ) ) {
            // Activate multisite network integration.
            if ( ! defined( 'WP_FS__PRODUCT_9034_MULTISITE' ) ) {
                define( 'WP_FS__PRODUCT_9034_MULTISITE', true );
            }

            // Include Freemius SDK.
            require_once dirname(__FILE__) . '/freemius/start.php';

            $gs_ninjafree = fs_dynamic_init( array(
                'id'                  => '9034',
                'slug'                => 'gsheetconnector-ninja-forms',
                'type'                => 'plugin',
                'public_key'          => 'pk_90fceba6082f2f976d4c7e2128455',
                'is_premium'          => false,
                'has_addons'          => false,
                'has_paid_plans'      => false,
                'menu'                => array(
                    'slug'           => 'gsheetconnector-ninja-forms',
                    'first-path'     => 'admin.php?page=njform-google-sheet-config',
                    'account'        => false,
                ),
            ) );
        }

        return $gs_ninjafree;
    }

    // Init Freemius.
    gs_ninjafree();
    // Signal that SDK was initiated.
    do_action( 'gs_ninjafree_loaded' );
}
/*freemius */
/* Customizing the Opt Message Freemius  */
    function gs_ninjafree_custom_connect_message_on_update(
        $message,
        $user_first_name,
        $plugin_title,
        $user_login,
        $site_link,
        $freemius_link
    ) {
        return sprintf(
            __( 'Hey %1$s' ) . ',<br>' .
            __( 'Please help us improve %2$s! If you opt-in, some data about your usage of %2$s will be sent to %5$s. If you skip this, that\'s okay! %2$s will still work just fine.', 'gsheetconnector-ninja-forms' ),
            $user_first_name,
            '<b>' . $plugin_title . '</b>',
            '<b>' . $user_login . '</b>',
            $site_link,
            $freemius_link
        );
    }

    gs_ninjafree()->add_filter('connect_message_on_update', 'gs_ninjafree_custom_connect_message_on_update', 10, 6);
/* End Customizing the Opt Message Freemius  */
if (!defined('ABSPATH')) {
   exit;
}

define('NINJAFORMS_GOOGLESHEET_VERSION', '1.2.3');
define('NINJAFORMS_GOOGLESHEET_DB_VERSION', '1.2.3');
define('NINJAFORMS_GOOGLESHEET_ROOT', dirname(__FILE__));
define('NINJAFORMS_GOOGLESHEET_URL', plugins_url('/', __FILE__));
define('NINJAFORMS_GOOGLESHEET_BASE_FILE', basename(dirname(__FILE__)) . '/gsheetconnector-ninjaforms.php');
define('NINJAFORMS_GOOGLESHEET_BASE_NAME', plugin_basename(__FILE__));
define('NINJAFORMS_GOOGLESHEET_PATH', plugin_dir_path(__FILE__)); //use for include files to other files
define('NINJAFORMS_GOOGLESHEET_PRODUCT_NAME', 'Njforms Google Sheet Connector');
define('NINJAFORMS_GOOGLESHEET_CURRENT_THEME', get_stylesheet_directory());
load_plugin_textdomain( 'gsheetconnector-ninjaforms', false, basename( dirname( __FILE__ ) ) . '/languages' );


/*
 * include utility classes
 */
if (!class_exists('NJForm_gs_Connector_Utility')) {
   include( NINJAFORMS_GOOGLESHEET_ROOT . '/includes/class-njform-utility.php' );
   $GoogleSheetConnector = new NJForm_gs_Connector_Utility();
}
/*
 * Add Sub Menu 
 */
if (isset($GoogleSheetConnector)) {
    add_action( 'admin_menu', array(&$GoogleSheetConnector, 'admin_page'), 20);
}

/*
 * Setting Page
 */
add_action('ninja_forms_loaded', 'ninjaform_Googlesheet_integration');

function ninjaform_Googlesheet_integration() {
  require_once plugin_dir_path(__FILE__) . 'includes/class-njforms-integration.php';
}

//Include Library Files
require_once NINJAFORMS_GOOGLESHEET_ROOT . '/lib/vendor/autoload.php';
include_once( NINJAFORMS_GOOGLESHEET_ROOT . '/lib/google-sheets.php');

class NJforms_Gsheet_Connector_Init {

   public function __construct() {

      //run on activation of plugin
      register_activation_hook(__FILE__, array($this, 'njforms_gs_connector_activate'));

      //run on deactivation of plugin
      register_deactivation_hook(__FILE__, array($this, 'njforms_gs_connector_deactivate'));

      //run on uninstall
      register_uninstall_hook(__FILE__, array('NJforms_Gsheet_Connector_Init', 'njforms_gs_connector_uninstall'));

      // validate is Gravityforms plugin exist
      add_action('admin_init', array($this, 'validate_parent_plugin_exists'));

      // Display widget to dashboard
      add_action('wp_dashboard_setup', array($this, 'add_njform_gs_connector_summary_widget'));

      // clear debug log data
      add_action('wp_ajax_gs_clear_log', array($this, 'gs_clear_logs'));

      // load the js and css files
      add_action('init', array($this, 'load_css_and_js_files'));

      // Add custom link for our plugin
      add_filter('plugin_action_links_' . NINJAFORMS_GOOGLESHEET_BASE_NAME, array($this, 'njforms_gs_connector_plugin_action_links'));

      // verify the spreadsheet connection
      add_action('wp_ajax_verify_njforms_gs_integation', array($this, 'verify_njforms_gs_integation'));
      
      //For register action in ninja form
      add_filter( 'ninja_forms_register_actions', array( $this, 'register_actions' ) );
   }


  /**
    * AJAX function - verifies the token
    *
    * @since 1.0
    */
   public function verify_njforms_gs_integation() {
	   try{
			  // nonce checksave_gs_settings
			  check_ajax_referer('gs-ajax-nonce', 'security');
			  /* sanitize incoming data */
			  $Code = sanitize_text_field($_POST["code"]);

			  if (!empty($Code)) {
				 update_option('njforms_gs_access_code', $Code);
			  } else {
				 return;
			  }
			  if (get_option('njforms_gs_access_code') != '') {
				 include_once( NINJAFORMS_GOOGLESHEET_ROOT . '/lib/google-sheets.php');
				 njfgsc_googlesheet::preauth(get_option('njforms_gs_access_code'));
				 //update_option('njforms_gs_verify', 'valid');
				 wp_send_json_success();
			  } else {
				 update_option('njforms_gs_verify', 'invalid');
				 wp_send_json_error();
			  }
	   } catch (Exception $e) {
         NJForm_gs_Connector_Utility::gs_debug_log("Something Wrong : - " . $e->getMessage());
      }	
   }
/**
    * AJAX function - Process for Google Sheet
    *
    * @since 1.0
    */
  public function process_googlesheets( $fields, $entry, $form_data, $entry_id, $fixed_wpgs_feed_id = false ) {
    try{
		if (
		  empty( $form_data['settings']['gsheetconnector-njforms'] ) ||
		  ! isset( $form_data['settings']['gsheetconnector-njforms'] ) ||
		  ! wp_validate_boolean( $form_data['settings']['gsheetconnector-njforms'] )
		) {
		  return false;
		}
		
		$is_processed = false;
		$form_id = $form_data['id'];
		
		$gsheetconnector_njforms = $form_data['settings']['gsheetconnector-njforms'];
		if( $gsheetconnector_njforms != 1 ) {
		  return false;
		}
		
		foreach ( $form_data['settings']['wpgs_spreadsheets'] as $wpgs_feed_id => $googlesheet ) {
		  
		  if( $fixed_wpgs_feed_id !== false && $fixed_wpgs_feed_id != $wpgs_feed_id ) {
			continue;
		  }
		  
		  if ( ! $this->is_conditionals_passed( $googlesheet, $fields, $entry, $form_data, $entry_id ) ) {
			continue;
		  }
		  
		  $this->wfgs_process_entry( $fields, $entry, $entry_id, $wpgs_feed_id, $googlesheet, $form_id, $form_data, get_current_user_id() );
		  $is_processed = true;
		}
		
		return $is_processed;
	} catch (Exception $e) {
         NJForm_gs_Connector_Utility::gs_debug_log("Something Wrong : - " . $e->getMessage());
      }
  }

   /**
    * Do things on plugin activation
    * @since 1.0
    */
   public function njforms_gs_connector_activate() {
	   try {
		 global $wpdb;
		  $this->run_on_activation();
		  if (function_exists('is_multisite') && is_multisite()) {
			 // check if it is a network activation - if so, run the activation function for each blog id
			 if ($network_wide) {
				// Get all blog ids
				$blogids = $wpdb->get_col("SELECT blog_id FROM {$wpdb->base_prefix}blogs");
				foreach ($blogids as $blog_id) {
				   switch_to_blog($blog_id);
				   $this->run_for_site();
				   restore_current_blog();
				}
				return;
			 }
		  }
		  // for non-network sites only
		  $this->run_for_site();
	  } catch (Exception $e) {
         NJForm_gs_Connector_Utility::gs_debug_log("Something Wrong : - " . $e->getMessage());
      }
      
   }


   /**
    * AJAX function - clear log file
    * @since 1.0
    */
   public function gs_clear_logs() {
      // nonce check
      check_ajax_referer('gs-ajax-nonce', 'security');

      $handle = fopen(NINJAFORMS_GOOGLESHEET_PATH . 'includes/logs/log.txt', 'w');
      fclose($handle);
      wp_send_json_success();
   }


   /**
    * deactivate the plugin
    * @since 1.0
    */
   public function njforms_gs_connector_deactivate() {
      
   }

   /**
    *  Runs on plugin uninstall.
    *  a static class method or function can be used in an uninstall hook
    *
    *  @since 1.0
    */
   public static function njforms_gs_connector_uninstall() {
    // Not like register_uninstall_hook(), you do NOT have to use a static function.
    gs_ninjafree()->add_action('after_uninstall', 'gs_ninjafree_uninstall_cleanup');
   }

   /**
    * Validate parent Plugin Ninja Form exist and activated
    * @access public
    * @since 1.0
    */
   public function validate_parent_plugin_exists() {
     
      $plugin = plugin_basename(__FILE__);
      if ((!is_plugin_active('ninja-forms/ninja-forms.php'))) {
         add_action('admin_notices', array($this, 'ninjaform_missing_notice'));
         add_action('network_admin_notices', array($this, 'ninjaform_missing_notice'));
         deactivate_plugins($plugin);
         if (isset($_GET['activate'])) {
            // Do not sanitize it because we are destroying the variables from URL
            unset($_GET['activate']);
            unset($GLOBALS['gs_ninjafree']);//unset global variable after deactivate plugins
         }
      }
   }


    /**
    * If Ninja Form plugin is not installed or activated then throw the error
    *
    * @access public
    * @return mixed error_message, an array containing the error message
    *
    * @since 1.0 initial version
    */
   public function ninjaform_missing_notice() {
      $plugin_error = NJForm_gs_Connector_Utility::instance()->admin_notice(array(
         'type' => 'error',
         'message' => 'Ninja Form Add-on requires Ninja-Forms plugin to be installed and activated.'
      ));
      echo esc_html($plugin_error);
   }

    public function load_css_and_js_files() {
      add_action('admin_print_styles', array($this, 'add_css_files'));
      add_action('admin_print_scripts', array($this, 'add_js_files'));
   }

   /**
    * enqueue CSS files
    * @since 1.0
    */
   public function add_css_files() {
      if (is_admin() && ( isset($_GET['page']) && ( $_GET['page'] == 'njform-google-sheet-config' ) )) {
         wp_enqueue_style('njform-gs-connector-css', NINJAFORMS_GOOGLESHEET_URL . 'assets/css/njform-gs-connector.css', NINJAFORMS_GOOGLESHEET_VERSION, true);
         wp_enqueue_style('njform-gs-connector-font', NINJAFORMS_GOOGLESHEET_URL . 'assets/css/font-awesome.min.css', NINJAFORMS_GOOGLESHEET_VERSION, true);
      }              
   }

   /**
    * enqueue JS files
    * @since 1.0
    */
   public function add_js_files() {
      if (is_admin() && ( isset($_GET['page']) && ( $_GET['page'] == 'njform-google-sheet-config' ) )) {
         wp_enqueue_script('njform-gs-connector-js', NINJAFORMS_GOOGLESHEET_URL . 'assets/js/njform-gs-connector.js', NINJAFORMS_GOOGLESHEET_VERSION, true);
      }
      
      if ( is_admin() ) {
         wp_enqueue_script('njform-gs-connector-notice-css', NINJAFORMS_GOOGLESHEET_URL . 'assets/js/njforms-gs-connector-notice.js', NINJAFORMS_GOOGLESHEET_VERSION, true);
      }
   }

    /**
    * Add custom link for the plugin beside activate/deactivate links
    * @param array $links Array of links to display below our plugin listing.
    * @return array Amended array of links.    * 
    * @since 1.0
    */
   public function njforms_gs_connector_plugin_action_links($links) {
	   try{
		  // We shouldn't encourage editing our plugin directly.
		  unset($links['edit']);

		  // Add our custom links to the returned array value.
		  return array_merge(array(
			 '<a href="' . admin_url('admin.php?page=njform-google-sheet-config&tab=integration') . '">' . __('Settings', 'gsheetconnector-wpforms') . '</a>'
				  ), $links);
		} catch (Exception $e) {
         NJForm_gs_Connector_Utility::gs_debug_log("Something Wrong : - " . $e->getMessage());
      }
   }

   /**
    * Called on activation.
    * Creates the site_options (required for all the sites in a multi-site setup)
    * If the current version doesn't match the new version, runs the upgrade
    * @since 1.0
    */
   private function run_on_activation() {
	   try{
		  $plugin_options = get_site_option('njforms_GS_info');
		  if (false === $plugin_options) {
			 $njforms_GS_info = array(
				'version' => NINJAFORMS_GOOGLESHEET_VERSION,
				'db_version' => NINJAFORMS_GOOGLESHEET_DB_VERSION
			 );
			 update_site_option('njforms_GS_info', $njforms_GS_info);
		  } else if (NINJAFORMS_GOOGLESHEET_DB_VERSION != $plugin_options['version']) {
			 $this->run_on_upgrade();
		  }
      //echo "activate";
      //exit;
      wp_redirect( admin_url( '/admin.php?page=njform-google-sheet-config&tab=integration' ));
		} catch (Exception $e) {
         NJForm_gs_Connector_Utility::gs_debug_log("Something Wrong : - " . $e->getMessage());
      }
   }

    /**
    * called on upgrade. 
    * checks the current version and applies the necessary upgrades from that version onwards
    * @since 1.0
    */
   public function run_on_upgrade() {
      $plugin_options = get_site_option('njforms_GS_info');

      // update the version value
      $google_sheet_info = array(
         'version' => NINJAFORMS_GOOGLESHEET_ROOT,
         'db_version' => NINJAFORMS_GOOGLESHEET_DB_VERSION
      );
      update_site_option('njforms_GS_info', $google_sheet_info);
   }


    /**
    * Called on activation.
    * Creates the options and DB (required by per site)
    * @since 1.0
    */
   private function run_for_site() {
	   try{
		  if (!get_option('njforms_gs_access_code')) {
			 update_option('njforms_gs_access_code', '');
		  }
		  if (!get_option('njforms_gs_verify')) {
			 update_option('njforms_gs_verify', 'invalid');
		  }
		  if (!get_option('njforms_gs_verify')) {
			 update_option('njforms_gs_verify', '');
		  }
		  if (!get_option('njforms_gs_verify')) {
			 update_option('njforms_gs_verify', 'false');
		  }
		} catch (Exception $e) {
         NJForm_gs_Connector_Utility::gs_debug_log("Something Wrong : - " . $e->getMessage());
      } 
   }

   /**
    * Called on uninstall - deletes site specific options
    *
    * @since 1.0
    */
   private static function delete_for_site() {
	   try{
		  delete_option('njforms_gs_access_code');
		  delete_option('njforms_gs_verify');
		  delete_option('njforms_gs_token');
		  delete_post_meta_by_key('njforms_gs_settings');
		} catch (Exception $e) {
         NJForm_gs_Connector_Utility::gs_debug_log("Something Wrong : - " . $e->getMessage());
      }  
   }

   /**
    * register action in ninja Email & Action tab 
    */
   public function register_actions($actions)
    {
      require_once plugin_dir_path(__FILE__) . 'includes/Action/NinjaFormsGoogleSheet.php';
      $actions[ 'google_sheet' ] = new NF_Action_NJGheetAction();
       return $actions;
    }

    /**
    * register action in ninja Email & Action tab 
    */
    public static function template($file_name = '', array $data = array()) {

        if (!$file_name) {
            return;
        }
        extract($data);

        include NINJAFORMS_GOOGLESHEET_PATH . 'includes/Templates/' . $file_name;
    }

    /**
    * Add widget to the dashboard
    * @since 1.0
    */
     public function add_njform_gs_connector_summary_widget() {
        wp_add_dashboard_widget('njform_gs_dashboard', __('GSheetConnector Ninja Forms', 'gsheetconnector-wpforms'), array($this, 'njform_gs_connector_summary_dashboard'));
     }

     /**
    * Display widget conetents
    * @since 1.0
    */
     public function njform_gs_connector_summary_dashboard() {
        include_once( NINJAFORMS_GOOGLESHEET_ROOT . '/includes/pages/njform-dashboard-widget.php' );
     }

    /**
    * Build System Information String
    * @global object $wpdb
    * @return string
    * @since 1.2
    */
   public function get_njforms_system_info() {
      $return = "System Info";
      return $return;
   }

}

// Initialize the njform google sheet connector class
$init = new NJforms_Gsheet_Connector_Init();