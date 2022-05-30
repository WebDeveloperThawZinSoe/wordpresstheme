<?php if ( ! defined( 'ABSPATH' ) ) exit;

class NF_Views_Field_Values {
	private static $instance;

	public static function instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof NF_Views_Field_Values ) ) {
			self::$instance = new NF_Views_Field_Values();
		}
		return self::$instance;
	}

	public function get_repeater_field_html($form_field_id, $field_value, $sub){
		$fieldSettings = Ninja_Forms()->form()->field($form_field_id)->get_settings();
        $extractedSubmissionData = Ninja_Forms()->fieldsetRepeater->extractSubmissions($form_field_id,$field_value,$fieldSettings);

        $return ='';
        foreach($extractedSubmissionData as $index=> $indexedSubmission){
            $return .= '<div class="nf-view-repeatable-section">';
			//$return .= '<span style="font-weight:bold;">Repeated Fieldset #'.$index.'</span><br />';
            foreach($indexedSubmission as $submissionValueArray){
                $fieldsetFieldSubmissionValue = $submissionValueArray['value'];

                if(is_array($fieldsetFieldSubmissionValue)){
                    $fieldsetFieldSubmissionValue=implode(', ',$fieldsetFieldSubmissionValue);
                }
                $return.='<div class="nf-view-repeatable-field-label">'.$submissionValueArray['label'].' </div><div class="nf-view-repeatable-field-value">'. $fieldsetFieldSubmissionValue.'</div>';
            }
			$return.='</div>';
        }
        return $return;
	}

}

function NF_Views_Field_Values() {
	return NF_Views_Field_Values::instance();
}

NF_Views_Field_Values();