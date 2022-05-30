<?php
/**
 * service class for njform Google Sheet Connector
 * @since 1.0
 */
if (!defined('ABSPATH')) {
   exit; // Exit if accessed directly
}
/**
 * NJforms_Googlesheet_Services Class
 *
 * @since 1.0
 */
class NJforms_Googlesheet_Services {

   public function __construct() {
      // activation n deactivation ajax call
      add_action('wp_ajax_deactivate_wp_integation', array($this, 'deactivate_wp_integation'));
      
      // display for upgrade notice
      add_action( 'admin_notices', array( $this, 'display_upgrade_notice' ) );
      
      add_action( 'wp_ajax_set_upgrade_notification_interval', array( $this, 'set_upgrade_notification_interval' ) );
      add_action( 'wp_ajax_close_upgrade_notification_interval', array( $this, 'close_upgrade_notification_interval' ) );

   }

   /**
    * Function - fetch njform list that is connected with google sheet
    * @since 1.0
    */
   public function get_forms_connected_to_sheet() {
      global $wpdb;
      //$query = $wpdb->get_results("SELECT ID,post_title,meta_value from " . $wpdb->prefix . "posts as p JOIN " . $wpdb->prefix . "postmeta as pm on p.ID = pm.post_id where pm.meta_key='njforms_gs_settings' AND p.post_type='njforms' ORDER BY p.ID");
      $query = $wpdb->get_results("SELECT DISTINCT(naction.parent_id) AS ID, nform.title AS title  FROM ".$wpdb->prefix."nf3_actions AS naction JOIN ". $wpdb->prefix ."nf3_forms AS nform ON nform.id = naction.parent_id
WHERE type='google_sheet'");
      return $query;
   }

   /**
    * function to save the setting data of google sheet
    *
    * @since 1.0
    */
   public function add_integration() {
      ?>
      <div class="card-wp">
         <span class="njforms-setting-field log-setting">

            <h2 class="title"><?php echo __('Google Sheet Integration - Ninja Forms'); ?></h2>
            <hr>
            <p class="njform-gs-alert-kk"> <?php echo __('Click "Get code" to retrieve your code from Google Drive to allow us to access your spreadsheets. And paste the code in the below textbox. ', 'gsheetconnector-njforms'); ?></p>
            <p>
               <label><?php echo __('Google Access Code', 'gsheetconnector-njforms'); ?></label>

      <?php if (!empty(get_option('njforms_gs_token')) && get_option('njforms_gs_token') !== "") { ?>
                  <input type="text" name="google-access-code" id="njforms-setting-google-access-code" value="" disabled placeholder="<?php echo __('Currently Active', 'gsheetconnector-njforms'); ?>"/>
                  <input type="button" name="wp-deactivate-log" id="wp-deactivate-log" value="<?php echo __('Deactivate', 'gsheetconnector-njforms'); ?>" class="button button-primary" />
                  <span class="tooltip"> <img src="<?php echo NINJAFORMS_GOOGLESHEET_URL; ?>assets/img/help.png" class="help-icon"> <span class="tooltiptext tooltip-right"><?php _e('On deactivation, all your data saved with authentication will be removed and you need to reauthenticate with your google account and configure sheet name and tab.', 'gsheetconnector-NJforms'); ?></span></span>                 
      <?php } else { ?>
                  <input type="text" name="google-access-code" id="njforms-setting-google-access-code" value="" placeholder="<?php echo __('Enter Code', 'gsheetconnector-njforms'); ?>"/>
                  <a href="https://accounts.google.com/o/oauth2/auth?access_type=offline&approval_prompt=force&client_id=1075324102277-drjc21uouvq2d0l7hlgv3bmm67er90mc.apps.googleusercontent.com&redirect_uri=urn:ietf:wg:oauth:2.0:oob&response_type=code&scope=https%3A%2F%2Fspreadsheets.google.com%2Ffeeds%2F+https://www.googleapis.com/auth/userinfo.email+https://www.googleapis.com/auth/drive.metadata.readonly" target="_blank" class="njforms-btn njforms-btn-md njforms-btn-light-grey"><?php echo __('Get Code', 'gsheetconnector-njforms'); ?></a>
      <?php } ?>

      <?php 
            //resolved - google sheet permission issues - START
            if(!empty(get_option('njforms_gs_verify')) && (get_option('njforms_gs_verify') !="valid") && (get_option('njforms_gs_verify') !="invalid")){
              ?>
              <p style="color:red"> <?php echo get_option('njforms_gs_verify'); ?></p>  
              <?php
            }
            //resolved - google sheet permission issues - END
        ?>


               <!-- set nonce -->
               <input type="hidden" name="gs-ajax-nonce" id="gs-ajax-nonce" value="<?php echo wp_create_nonce('gs-ajax-nonce'); ?>" />
      <?php if (empty(get_option('njforms_gs_token'))) { ?>
                  <input type="submit" name="save-gs" class="njforms-btn njforms-btn-md njforms-btn-orange" id="save-njform-gs-code" value="Save & Authenticate">
               <?php } ?>
               </br>
               </br>
               <span class="njforms-setting-field">
                  <label><?php echo __('Debug Log ->', 'gsheetconnector-njforms'); ?></label>
                  <label><a href="<?php echo plugins_url('logs/log.txt', __FILE__); ?>" target="_blank" class="njform-debug-view" ><?php echo __('View', 'gsheetconnector-njforms'); ?></a></label>
                  <label><a class="debug-clear-kk" ><?php echo __('Clear', 'gsheetconnector-njforms'); ?></a></label>
                  <span class="clear-loading-sign">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                  <p id="njgs-validation-message"></p>

                  <?php 
                    if (!empty(get_option('njforms_gs_token')) && get_option('njforms_gs_token') !== "") {
                    $google_sheet = new njfgsc_googlesheet();
                    $email_account = $google_sheet->gsheet_print_google_account_email(); 
                    if( $email_account ) { ?>
                      <span class="connected-account"><?php printf( __( 'Connected email account: <p>%s</p>', 'gsheetconnector-ninjaforms' ), $email_account ); ?></span>
                    <?php }else{
                      ?>
                      <p style="color:red" ><?php echo esc_html(__('Something wrong ! Your Auth code may be wrong or expired Please Deactivate and Do Re-Auth Code ', 'gsheetconnector-ninjaforms')); ?></p>
                      <?php
                        }
                    }         ?>


                  <span class="loading-sign-deactive">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                  <span id="deactivate-message"></span>
               </span>
               <span class="loading-sign">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
            </p>
      </div>
      <?php
   }

   /**
    * AJAX function - deactivate activation
    * @since 1.0
    */
   public function deactivate_wp_integation() {
      // nonce check
      check_ajax_referer('gs-ajax-nonce', 'security');

      if (get_option('njforms_gs_token') != '') {
         delete_option('njforms_gs_token');
         delete_option('njforms_gs_access_code');
         delete_option('njforms_gs_verify');
         wp_send_json_success();
      } else {
         wp_send_json_error();
      }
   }

   /**
    * Function - Display Upgrade Notice
    * @since 1.0
    */
   
   public function display_upgrade_notice() {
      $get_notification_display_interval = get_option( 'njforms_gs_upgrade_notice_interval' );
      $close_notification_interval = get_option( 'njforms_gs_close_upgrade_notice' );
      
      if( $close_notification_interval === "off" ) {
         return;
      }
      
      if ( ! empty( $get_notification_display_interval ) ) {
         $adds_interval_date_object = DateTime::createFromFormat( "Y-m-d", $get_notification_display_interval );
         $notice_interval_timestamp = $adds_interval_date_object->getTimestamp();
      }
   }
   
   public function set_upgrade_notification_interval() {
      // check nonce
      check_ajax_referer( 'njforms_gs_upgrade_ajax_nonce', 'security' );
      $time_interval = date( 'Y-m-d', strtotime( '+10 day' ) );
      update_option( 'njforms_gs_upgrade_notice_interval', $time_interval );
      wp_send_json_success();
   }
   
   public function close_upgrade_notification_interval() {
      // check nonce
      check_ajax_referer( 'njforms_gs_upgrade_ajax_nonce', 'security' );
      update_option( 'njforms_gs_close_upgrade_notice', 'off' );
      wp_send_json_success();
   }

}

$njforms_service = new NJforms_Googlesheet_Services();