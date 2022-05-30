<?php

class NF_Views_Posttype {
	private $forms = array();
	function __construct() {
		add_action( 'init', array( $this, 'create_posttype' ) );
		add_filter( 'manage_nf-views_posts_columns' , array( $this, 'add_extra_columns' ) );
		add_action( 'manage_nf-views_posts_custom_column' , array( $this, 'extra_column_detail' ), 10, 2 );
	}

	function create_posttype() {

		$labels = array(
			'name'               => _x( 'NF Views', 'post type general name', 'views-for-ninja-forms' ),
			'singular_name'      => _x( 'NF View', 'post type singular name', 'views-for-ninja-forms' ),
			'menu_name'          => _x( 'NF Views', 'admin menu', 'views-for-ninja-forms' ),
			'name_admin_bar'     => _x( 'NF Views', 'add new on admin bar', 'views-for-ninja-forms' ),
			'add_new'            => _x( 'Add NF View', 'book', 'views-for-ninja-forms' ),
			'add_new_item'       => __( 'Add New NF View', 'views-for-ninja-forms' ),
			'new_item'           => __( 'New NF View', 'views-for-ninja-forms' ),
			'edit_item'          => __( 'Edit NF View', 'views-for-ninja-forms' ),
			'view_item'          => __( 'View NF View', 'views-for-ninja-forms' ),
			'all_items'          => __( 'All NF Views', 'views-for-ninja-forms' ),
			'search_items'       => __( 'Search Views', 'views-for-ninja-forms' ),
			'parent_item_colon'  => __( 'Parent Views:', 'views-for-ninja-forms' ),
			'not_found'          => __( 'No view found.', 'views-for-ninja-forms' ),
			'not_found_in_trash' => __( 'No view found in Trash.', 'views-for-ninja-forms' )
		);

		$args = array(
			'labels'             => $labels,
			'description'        => __( 'Description.', 'views-for-ninja-forms' ),
			'public'             => false,
			'exclude_from_search'=> true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'nf-views' ),
			'capability_type'    => 'post',
			'has_archive'        => false,
			'menu_icon'   => 'dashicons-format-gallery',
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title', 'false' )
		);

		register_post_type( 'nf-views', $args );
	}


	function add_extra_columns( $columns ) {
		$columns = array_slice( $columns, 0, 2, true ) + array( "shortcode" =>__( 'Shortcode', 'nf-views' ) ) + array_slice( $columns, 2, count( $columns )-2, true );
		$columns = array_slice( $columns, 0, 2, true ) + array( "view_format" =>__( 'View Format', 'nf-views' ) ) + array_slice( $columns, 2, count( $columns )-2, true );
		$columns = array_slice( $columns, 0, 2, true ) + array( "view_source" =>__( 'View Source', 'nf-views' ) ) + array_slice( $columns, 2, count( $columns )-2, true );
		return $columns;
	}

	function extra_column_detail( $column, $post_id ) {
		switch ( $column ) {

		case 'shortcode' :
			echo '<code>[nf-views id=' . $post_id . ']</code>';
			break;

		case 'view_format' :
			$view_settings_json = get_post_meta( $post_id, 'view_settings', true );
			if ( ! empty( $view_settings_json ) ) {
				$view_settings =  json_decode( $view_settings_json );
				$view_type = $view_settings->viewType;
				echo '<span>' . ucfirst( $view_type ) . '</span>';
			}
			break;
		case 'view_source' :
			if ( empty( $this->forms ) && function_exists( 'Ninja_Forms' ) ) {
				$this->forms = Ninja_Forms()->form()->get_forms();
			}
			$view_settings_json = get_post_meta( $post_id, 'view_settings', true );
			if ( ! empty( $view_settings_json ) ) {
				$view_settings =  json_decode( $view_settings_json );
				$form_id = $view_settings->formId;
				if ( ! empty( $this->forms ) ) {
					foreach ( $this->forms as $form ) {
						if ( $form->get_id() == $form_id ) {
							printf( '<a href="%s">' . $form->get_setting( 'title' ) . '</a>',
								admin_url( 'admin.php?page=ninja-forms&form_id=' . $form_id )
							);
						}
					}
				}

			}
			break;

		}
	}

}

new NF_Views_Posttype();
