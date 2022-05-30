<?php

class NF_Views_Ajax{

	function __construct() {
		add_action( 'wp_ajax_nf_views_get_form_fields', array($this, 'get_form_fields'));

	}

	public function get_form_fields(){
		//var_dump($_POST['form_id']); die;
		if( empty($_POST['form_id']) ) return ;

		echo nf_views_get_ninja_form_fields($_POST['form_id']);
		wp_die();
	}

}
new NF_Views_Ajax();