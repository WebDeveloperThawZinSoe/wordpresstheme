<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
   exit();
}
$njforms_gs_tools_service = new NJforms_Gsheet_Connector_Init();
?>
<div class="card">
   <textarea readonly="readonly" onclick="this.focus();this.select()" id="njforms-gs-system-info" name="njforms-gs-system-info" title="<?php echo __( 'To copy the system info, click below then press Ctrl + C (PC) or Cmd + C (Mac).', 'googlesheet' ); ?>">
<?php echo esc_textarea($njforms_gs_tools_service->get_njforms_system_info()); ?>
   </textarea>
</div>    