<?php
class NF_Views_Shortcode {
	public $view_id;
	public $submissions_count;
	public $table_heading_added;
	public $form_fields;
		private $seq_no = 1;
	function __construct() {
		add_shortcode( 'nf-views', array( $this, 'shortcode' ), 10 );
	}

	public function shortcode( $atts ) {
		$this->seq_no = 1;
		$atts = shortcode_atts(
			array(
				'id' => '',
			), $atts );

		if ( empty( $atts['id'] ) ) {
			return;
		}
		$view_id = $atts['id'];
		$this->view_id = $view_id;
		$this->table_heading_added = false;
		$view_settings_json = get_post_meta( $view_id, 'view_settings', true );
		if ( empty( $view_settings_json ) ) {
			return;
		}

		$view_settings =  json_decode( $view_settings_json );
		$view_type = $view_settings->viewType;
		$method_name = 'get_view';
		$view  = $this->$method_name( $view_settings );
		return $view;

	}

	function get_view( $view_settings ) {
		global $wpdb;
		$before_loop_rows = $view_settings->sections->beforeloop->rows;
		$loop_rows = $view_settings->sections->loop->rows;
		$after_loop_rows = $view_settings->sections->afterloop->rows;

		$submissions_count = 0;
		$subs = $wpdb->get_results( "SELECT post_id FROM " . $wpdb->postmeta . " WHERE `meta_key` = '_form_id' AND `meta_value` = $view_settings->formId" );
		foreach ( $subs as $sub ) {
			if ( 'publish' == get_post_status( $sub->post_id ) ) $submissions_count++;
		}
		$this->submissions_count = $submissions_count;
		$this->form_fields = nf_views_get_ninja_form_fields( $view_settings->formId, false );

		$per_page = $view_settings->viewSettings->multipleentries->perPage;
		$args = array(
			'form_id' => $view_settings->formId,
			'posts_per_page' =>$per_page,
		);

		if ( ! empty( $_GET['pagenum'] ) && ! empty( $_GET['view_id'] ) && ( $this->view_id === $_GET['view_id'] ) ) {
			$page_no = sanitize_text_field( $_GET['pagenum'] );
			$offset = $per_page * ( $page_no-1 );
			$args['offset'] = $offset;
			$this->seq_no = $offset+1;
		}

		$submissions = nf_views_get_submissions( $args );

		$view_content = '';
		if ( ! empty ( $before_loop_rows ) ) {
			$view_content .= $this->get_sections_content( 'beforeloop', $view_settings, $submissions );
		}

		if ( ! empty ( $loop_rows ) ) {
			$view_content .= $this->get_table_content( 'loop', $view_settings, $submissions );
		}

		if ( ! empty ( $after_loop_rows ) ) {
			$view_content .= $this->get_sections_content( 'afterloop', $view_settings, $submissions );
		}
		return $view_content;

	}



	function get_sections_content( $section_type, $view_settings, $submissions ) {
		$content = '';
		$section_rows = $view_settings->sections->{$section_type}->rows;
		if ( $section_type == 'loop' ) {
			foreach ( $submissions as $sub ) {
				foreach ( $section_rows as $row_id ) {
					$content .= $this->get_table_content( $row_id, $view_settings, $sub );
				}
			}
		} else {
			foreach ( $section_rows as $row_id ) {
				$content .= $this->get_grid_row_html( $row_id, $view_settings );
			}
		}
		return $content;
	}



	function get_table_content( $section_type, $view_settings, $submissions ) {
		$content = '';
		$section_rows = $view_settings->sections->{$section_type}->rows;
		$content = '<div class="nf-views-cont nf-views-' . $this->view_id . '-cont"><table class="nf-views-table nf-view-' . $this->view_id . '-table pure-table pure-table-bordered">';
		$content .= '<thead>';
		foreach ( $submissions as $sub ) {
			$content .= '<tr>';
			foreach ( $section_rows as $row_id ) {

				$content .= $this->get_table_row_html( $row_id, $view_settings, $sub );
			}
			$content .= '</tr>';
			$this->seq_no++;

		}
		$content .= '</tbody></table></div>';

		return $content;
	}

	function get_table_row_html( $row_id, $view_settings, $sub = false ) {
		$row_content = '';
		$row_columns = $view_settings->rows->{$row_id}->cols;
		foreach ( $row_columns as $column_id ) {
			$row_content .= $this->get_table_column_html( $column_id, $view_settings, $sub );
		}
		//$row_content .= '</table>'; // row ends
		return $row_content;
	}

	function get_table_column_html( $column_id, $view_settings, $sub ) {
		$column_size = $view_settings->columns->{$column_id}->size;
		$column_fields = $view_settings->columns->{$column_id}->fields;

		$column_content = '';

		if ( ! ( $this->table_heading_added ) ) {

			foreach ( $column_fields as $field_id ) {
				$column_content .= $this->get_table_headers( $field_id, $view_settings, $sub );
			}
			$this->table_heading_added = true;
			$column_content .= '</tr></thead><tbody><tr>';
		}
		foreach ( $column_fields as $field_id ) {

			$column_content .= $this->get_field_html( $field_id, $view_settings, $sub );
		}

		return $column_content;
	}



	function get_grid_row_html( $row_id, $view_settings, $sub = false ) {
		$row_columns = $view_settings->rows->{$row_id}->cols;

		$row_content = '<div class="pure-g">';
		foreach ( $row_columns as $column_id ) {
			$row_content .= $this->get_grid_column_html( $column_id, $view_settings, $sub );
		}
		$row_content .= '</div>'; // row ends
		return $row_content;
	}

	function get_grid_column_html( $column_id, $view_settings, $sub ) {
		$column_size = $view_settings->columns->{$column_id}->size;
		$column_fields = $view_settings->columns->{$column_id}->fields;

		$column_content = '<div class="pure-u-1 pure-u-md-' . $column_size . '">';

		foreach ( $column_fields as $field_id ) {

			$column_content .= $this->get_field_html( $field_id, $view_settings, $sub );

		}
		$column_content .= '</div>'; // column ends
		return $column_content;
	}

	function get_field_html( $field_id, $view_settings, $sub ) {
		$field = $view_settings->fields->{$field_id};
		$form_field_id = $field->formFieldId;
		$fieldSettings = $field->fieldSettings;
		$label = $fieldSettings->useCustomLabel ? $fieldSettings->label : $field->label;
		$class = $fieldSettings->customClass;
		$view_type = $view_settings->viewType;
		$field_html = '';
		if ( $view_type == 'table' ) {
			$field_html .= '<td>';
		}

		$field_html .= '<div class="field-' . $form_field_id . ' ' . $class . '">';
		// check if it's a form field
		if ( ! empty( $sub ) && is_object( $sub ) ) {
			//  if view type is table then don't send label
			if (  $view_type != 'table' ) {
				if ( ! empty( $label ) ) {
					$field_html .= '<div class="field-label">' . $label . '</div>';
				}
			}
			$field = '_field_' . $form_field_id;
			$field_value = get_post_meta( $sub->ID, $field, TRUE );
			if ( isset( $this->form_fields->{$form_field_id} ) ) {
				$form_field_type = $this->form_fields->{$form_field_id}->type;

				if ( is_array( $field_value ) ) {

					if ( $form_field_type != 'file_upload' ) {
						if ( $form_field_type == 'repeater' ) {
							$field_value = NF_Views_Field_Values()->get_repeater_field_html( $form_field_id, $field_value, $sub );
						}else {
							if (  $form_field_type === 'date' ) {
								$field_value = $this->date_field_display_value( $field_value, $form_field_id );
							}else if ( in_array( $form_field_type, array(  'liststate', 'listselect', 'listradio', 'listcheckbox' ) ) ) {
								// $field_value = isset( $this->form_fields->{$form_field_id}->values[$field_value] )? $this->form_fields->{$form_field_id}->values[$field_value] : '';
								$field_value_labes_array = [];
								foreach ( $field_value as $option_value ) {
									$field_value_labes_array[] = isset( $this->form_fields->{$form_field_id}->values[$option_value] )? $this->form_fields->{$form_field_id}->values[$option_value] : '';
								}
								$field_value = implode( ', ', $field_value_labes_array );
							}else {
								$field_value = implode( ', ', $field_value );
							}

						}
					} else {
						$field_value = '<img src="' . reset( $field_value ) . '">';
					}

				} else if ( $form_field_type == 'html' ) {
					$field_value = $this->get_calculatons_for_html_field( $sub, $form_field_id );
				}else if ( $form_field_type == 'starrating' ) {
					$rating_html = '<div class="stars">';
					for ( $i = 1; $i <= $field_value; $i++ ) {
						$rating_html .= '<span class="star fullStar"></span>';
					}
					$rating_html .= '</div>';
					$field_value = $rating_html;

				}else if ( in_array( $form_field_type, array(  'liststate', 'listselect', 'listradio', 'listcheckbox' ) ) ) {
					$field_value = isset( $this->form_fields->{$form_field_id}->values[$field_value] )? $this->form_fields->{$form_field_id}->values[$field_value] : '';

				}
			}else {
				if ( $form_field_id == 'entryId' ) {
					$field_value = $sub->ID;
				}else if ( $form_field_id == 'sequenceNumber' ) {
					$field_value = '<div class="nf-view-field-value nf-view-field-type-sequenceNumber-value">';
					$field_value .= $this->seq_no;
					$field_value .= '</div>';
				}
			}
			$field_value = apply_filters( 'nfviews-field-value', $field_value, $view_settings, $sub );
			$field_html .=  $field_value;
		} else {
			switch ( $field->formFieldId ) {
			case 'pagination':
				$field_html .= $this->get_pagination_links( $view_settings, $sub );
				break;
			case 'paginationInfo':
				$field_html .= $this->get_pagination_info( $view_settings, $sub );
				break;
			}
		}

		$field_html .= '</div>';
		if ( $view_type == 'table' ) {
			$field_html .= '</td>';
		}


		return $field_html;
	}

	function get_table_headers( $field_id, $view_settings, $sub ) {
		$field = $view_settings->fields->{$field_id};
		$fieldSettings = $field->fieldSettings;
		$label = $fieldSettings->useCustomLabel ? $fieldSettings->label : $field->label;
		return '<th>' . $label . '</th>';
	}


	function get_pagination_links( $view_settings, $sub ) {
		global $wp;
		$entries_count = $this->submissions_count;
		$per_page = $view_settings->viewSettings->multipleentries->perPage;
		$pages = new View_Paginator( $per_page, 'pagenum' );
		$pages->set_total( $entries_count ); //or a number of records
		$current_url = site_url( remove_query_arg( array( 'pagenum', 'view_id' ) ) );
		$current_url = add_query_arg( 'view_id', $this->view_id, $current_url );

		return $pages->page_links( $current_url . '&' );

	}

	function get_pagination_info( $view_settings, $sub ) {
		$page_no = empty(  $_GET['pagenum'] ) ? 1 : sanitize_text_field( $_GET['pagenum'] );
		$submissions_count = $this->submissions_count;
		$per_page = $view_settings->viewSettings->multipleentries->perPage;
		$from = ( $page_no-1 ) * $per_page;
		$of = $per_page * $page_no;
		if ( $of > $submissions_count ) {
			$of = $submissions_count;
		}
		if ( $from == 0 ) {
			$from = 1;
		}

		return sprintf(
			__( 'Displaying %1$s - %2$s of %3$s', 'views-for-ninja-forms' ),
			$from,
			$of,
			$submissions_count
		);
	}

	public function get_calculatons_for_html_field( $sub, $form_field_id ) {
		$field_model = Ninja_Forms()->form()->field( $form_field_id )->get();
		$field_html = $field_model->get_setting( 'default' );
		$sub_model = Ninja_Forms()->form()->sub( $sub->ID )->get();
		$data = $sub_model->get_extra_values( array( 'calculations' ) );
		if ( ! empty( $data['calculations'] ) ) {
			foreach ( $data['calculations'] as $cal_name => $cal ) {
				$field_html = str_replace( '{calc:' . $cal_name . '}', $cal['value'], $field_html );
			}
		}
		return $field_html;
	}
	private function date_field_display_value( $field_value, $field_id ) {
		if ( ! is_array( $field_value ) ) {
			return $field_value;
		}
		$field = Ninja_Forms()->form()->field( $field_id )->get();
		// Get our date and time, the combine them into a string.
		$date = isset ( $field_value[ 'date' ] ) ? $field_value[ 'date' ] : '';
		$hour = isset ( $field_value[ 'hour' ] ) ? $field_value[ 'hour' ] : '';
		$minute = isset ( $field_value[ 'minute' ] ) ? $field_value[ 'minute' ] : '';
		$ampm = isset ( $field_value[ 'ampm' ] ) ? $field_value[ 'ampm' ] : '';
		$time = '';

		if ( ! empty ( $hour ) && ! empty ( $minute ) ) {
			$time = ' ' . $hour . ':' . $minute;
			// Display an edit for am/pm if necessary
			if ( 1 != $field->get_setting( 'hours_24' ) ) {
				$time .= ' ' . $ampm;
			}
		}

		return $date . $time;
	}
}
new NF_Views_Shortcode();
