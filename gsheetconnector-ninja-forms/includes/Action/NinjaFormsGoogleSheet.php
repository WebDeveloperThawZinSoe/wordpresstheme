<?php
if ( ! defined( 'ABSPATH' ) || ! class_exists( 'NF_Abstracts_Action' )) exit;


// I know this says NF_Notification_Base_Type, but the name will eventually be changed to reflect the action nomenclature.
final class NF_Action_NJGheetAction extends NF_Abstracts_Action
{

	/**
	 * Get things rolling
	 */
	protected $_name = 'google_sheet';
	protected $_tags = array();
    protected $_timing = 'normal';
    protected $_priority = '10';
	function __construct() {
		parent::__construct();
		$this->name = __( 'Google Sheet' );
		$this->_nicename = esc_html__( 'Google Sheet', 'ninja-forms' );
		
		

		$this->_settings['field_map_ninja_gsname'] = array(
            'name' => 'field_map_ninja_gsname',
            'type' => 'textbox',
            'label' => __( 'Google Sheet Name' ),
            'width' => 'full',
            'group' => 'primary',
            'tmpl_row' => 'nf-tmpl-ninjags-custom-field-map-row',
			'help' => esc_html__( 'Go to your google account and click on"Google apps" icon and than click "Sheets". Select the name of the appropriate sheet you want to link your contact form or create new sheet.', 'ninja-forms' ),
            'columns'           => array(
                'mautic_ninjags_alias1'          => array(
                    'header' => 'Ninja Field Alias',
                    'column2'    => __( 'Ninja Field Alias' ),
                    'default'   => '',
                ),
                'value'          => array(
                    'header' => 'Value',
                    'column1'    => __( 'Ninja Forms Field Key' ),
                    'default'   => '',
                ),
            ),
        );

        $this->_settings['field_map_ninja_gsid'] = array(
            'name' => 'field_map_ninja_gsid',
            'type' => 'textbox',
            'label' => __( 'Google Sheet ID' ),
            'width' => 'full',
            'group' => 'primary',
            'tmpl_row' => 'nf-tmpl-ninjags-custom-field-map-row',
			'help' => esc_html__( 'You can get sheet id from your sheet URL.', 'ninja-forms' ),
            'columns'           => array(
                'mautic_ninjags_alias'          => array(
                    'header' => 'Ninja Field Alias',
                    'column2'    => __( 'Ninja Field Alias' ),
                    'default'   => '',
                ),
                'value'          => array(
                    'header' => 'Value',
                    'column1'    => __( 'Ninja Forms Field Key' ),
                    'default'   => '',
                ),
            ),
        );

        $this->_settings['field_map_ninja_gstabname'] = array(
            'name' => 'field_map_ninja_gstabname',
            'type' => 'textbox',
            'label' => __( 'Google Sheet Tab Name' ),
            'width' => 'full',
            'group' => 'primary',
            'tmpl_row' => 'nf-tmpl-ninjags-custom-field-map-row',
			'help' => esc_html__( 'Open your Google Sheet with which you want to link your contact form . You will notice a tab names at bottom of the screen. Copy the tab name where you want to have an entry of contact form.', 'ninja-forms' ),
            'columns'           => array(
                'mautic_ninjags_alias'          => array(
                    'header' => 'Ninja Field Alias',
                    'column2'    => __( 'Ninja Field Alias' ),
                    'default'   => '',
                ),
                'value'          => array(
                    'header' => 'Value',
                    'column1'    => __( 'Ninja Forms Field Key' ),
                    'default'   => '',
                ),
            ),
        );


        $this->_settings['field_map_ninja_gstabid'] = array(
            'name' => 'field_map_ninja_gstabid',
            'type' => 'textbox',
            'label' => __( 'Google Sheet Tab ID' ),
            'width' => 'full',
            'group' => 'primary',
            'tmpl_row' => 'nf-tmpl-ninjags-custom-field-map-row',
			'help' => esc_html__( 'You can get tab id from your sheet URL.', 'ninja-forms' ),
            'columns'           => array(
                'mautic_ninjags_alias'          => array(
                    'header' => 'Ninja Field Alias',
                    'column2'    => __( 'Ninja Field Alias' ),
                    'default'   => '',
                ),
                'value'          => array(
                    'header' => 'Value',
                    'column1'    => __( 'Ninja Forms Field Key' ),
                    'default'   => '',
                ),
            ),
        );
		//$google_sheet_set = $action->get_settings();
		if(isset($form_id)){
			$actions = Ninja_Forms()->form( $form_id )->get_actions();
			foreach ( $actions as $action ) {
			  $type = $action->get_setting( 'type' );
			  if($type == 'google_sheet')
			  {
				 $google_sheet_set = $action->get_settings();
				
			  } 
			}
			$tab_id = isset($google_sheet_set['field_map_ninja_gstabid']) ? $google_sheet_set['field_map_ninja_gstabid'] : "";
			$sheet_id = isset($google_sheet_set['field_map_ninja_gsid']) ? $google_sheet_set['field_map_ninja_gsid'] : "";
			
			$link = "https://docs.google.com/spreadsheets/d/".$sheet_id."/edit#gid=".$tab_id; 
			$this->_settings['exception_fields'] =  array(
					'name'      => 'exception_fields',
					'type'      => 'option-repeater',
					'label'     => esc_html__( '', 'ninja-forms' ) . ' <a href="'.$link.'" target="_blank">' .
								   esc_html__( 'Google Sheet Link', 'ninja-forms' ) . '</a>',
					'width'     => 'full',
					'group'     => 'primary',
					'tmpl_row'  => 'nf-tmpl-save-field-repeater-row',
					'value'     => array(),
				);
		}
	}

	public function builder_templates() {
		//exit;
       
        NF_Mautic::template('custom-field-map-row.html');
    }

    public function process( $action_settings, $form_id, $data )
    {
		  $action_settings['id'];
          $sheet_name = isset($action_settings['field_map_ninja_gsname']) ? $action_settings['field_map_ninja_gsname'] : "";
          $sheet_id = isset($action_settings['field_map_ninja_gsid']) ? $action_settings['field_map_ninja_gsid'] : "";
          $sheet_tab_name = isset($action_settings['field_map_ninja_gstabname']) ? $action_settings['field_map_ninja_gstabname'] : "";
          $tab_id = isset($action_settings['field_map_ninja_gstabid']) ? $action_settings['field_map_ninja_gstabid'] : "";
          

		  if ( has_filter( 'ninja_forms_get_fields_sorted' ) ) {
            $fields_by_key = array();
            foreach( $data[ 'fields' ] as $field ){
                if( is_null( $field ) ) continue;
                if( is_array( $field ) ){
                    if( ! isset( $field[ 'key' ] ) ) continue;
                    $key = $field[ 'key' ];
                } else {
                    $key = $field->get_setting('key');
                }
                $fields_by_key[ $key ] = $field;
            }
            $data[ 'fields' ] = apply_filters( 'ninja_forms_get_fields_sorted', array(), $data[ 'fields' ], $fields_by_key, $form_id );
        }
		foreach ($data['fields'] as $form) {
			//print_r($form['value']);
			if(is_array($form['value'])){
				$data1[$form['label']] = implode(',',$form['value']);
			}else{
				$data1[$form['label']] = $form['value'];
			}
			
		}
	
	  if ((!empty($sheet_name) ) && (!empty($sheet_tab_name) )) {
		 try {
			include_once( NINJAFORMS_GOOGLESHEET_ROOT . "/lib/google-sheets.php" );
			$doc = new njfgsc_googlesheet();
			$doc->auth();
			$doc->setSpreadsheetId($sheet_id);
			$doc->setWorkTabId($tab_id);
			//$timestamp = strtotime(date("Y-m-d H:i:s"));
			// Fetched local date and time instaed of unix date and time
			$data1['date'] = date_i18n(get_option('date_format'));
			$data1['time'] = date_i18n(get_option('time_format'));           
			$doc->add_row($data1);
		 } catch (Exception $e) {
			$data1['ERROR_MSG'] = $e->getMessage();
			$data1['TRACE_STK'] = $e->getTraceAsString();
			NJForm_gs_Connector_Utility::gs_debug_log($data1);
		 }
	  }
        return $data;
    }
}

return new NF_Action_NJGheetAction();