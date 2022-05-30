<?php
/**
 * Plugin Name: 	  Image Hover Effects Block
 * Plugin URI:  	  https://imagehoverblock.blocksera.com/
 * Author: 			  Blocksera
 * Author URI:		  https://blocksera.com
 * Description: 	  Collection of image hover effects for WordPress Block editor Gutenberg.
 * Requires PHP:      5.6
 * Requires at least: 5.3
 * Tested up to:      5.9.3
 * Version:     	  1.4.4
 * License: 		  GPL v3
 * Text Domain:		  iheg-lang
 * Domain Path: 	  /languages
**/

if (!defined('ABSPATH')) {
    exit;
}

define('IHEG_VERSION', 	'1.4.4');
define('IHEG_PATH', 	plugin_dir_path(__FILE__));
define("IHEG_URL", 		plugin_dir_url(__FILE__));

function create_image_hover_effects_block() {

    // automatically load dependencies and version
    $asset_file = include(IHEG_PATH . 'build/index.asset.php');

    wp_register_script('iheg-image-hover',  IHEG_URL . 'build/index.js', $asset_file['dependencies'], IHEG_VERSION);
    wp_register_style('iheg-image-hover',   IHEG_URL . 'assets/css/style.min.css', array(), IHEG_VERSION, 'all');
    wp_register_style('iheg-editor-css',    IHEG_URL . 'src/blocks/css/editor-css.css', array(), IHEG_VERSION, 'all');

    wp_enqueue_style('iheg-fontawesome',    IHEG_URL . '/assets/fontawesome-v5/css/all.min.css', array(), IHEG_VERSION, 'all');
 
    register_block_type('block/image-hover-effects-block', array(
        'attributes' => array(
            'widget_title' => array(
                'type' => 'string',
                'default' => 'Title',
            ),
            'widget_description' => array(
                'type' => 'string',
                'default' => 'Description',
            ),
            'hover_effect' => array(
                'type' => 'string',
                'default' => 'eihe-fade',
            ),
            'linktourl' => array(
                'type' => 'string',
                'default' => ''
            ),
            'addtarget' => array(
                'type' => 'boolean',
                'default' => false
            ),
            'addrel' => array(
                'type' => 'boolean',
                'default' => false
            ),
            'img_size' => array(
                'type' => 'string',
                'default' => 'full'
            ),
            'imgid' => array(
                'type' =>'number',
                'default' => ''
            ),
            'imgurl' => array(
                'type' =>'string',
                'default' => '',
            ),
            'img_size_id'=> array(
                'type' => 'array',
                'items' => [
                    'type' => 'array',
                ],
            ),
            'overlay_color' => array(
                'type' => 'string',
                'default' => '#000',
            ),
            'title_tag' => array(
                'type' => 'string',
                'default' => 'h3',
            ),
            'title_color' => array(
                'type' => 'string',
                'default' => '#fff',
            ),
            'title_font' => array(
                'type' => 'string',
                'default' => 'inherit',
            ),
            'title_font_size' => array(
                'type' => 'number',
                'default' => 40,
            ),
            'title_font_weight' => array(
                'type' => 'number',
                'default' => 400,
            ),
            'title_font_transform' => array(
                'type' => 'string',
                'default' => 'none',
            ),
            'title_font_style' => array(
                'type' => 'string',
                'default' => 'normal',
            ),
            'title_font_decoration' => array(
                'type' => 'string',
                'default' => 'none',
            ),
            'title_line_height' => array(
                'type' => 'number',
                'default' => 40,
            ),
            'title_letter_spacing' => array(
                'type' => 'number',
                'default' => 0,
            ),
            'title_style' => array(
                'source' => 'attribute',
                'attribute' => 'style',
            ),
            'desc_color' => array(
                'type' => 'string',
                'default' => '#fff',
            ),
            'desc_font' => array(
                'type' => 'string',
                'default' => 'inherit',
            ),
            'desc_font_size' => array(
                'type' => 'number',
                'default' => 20,
            ),
            'desc_font_weight' => array(
                'type' => 'number',
                'default' => 400,
            ),
            'desc_font_transform' => array(
                'type' => 'string',
                'default' => 'none',
            ),
            'desc_font_style' => array(
                'type' => 'string',
                'default' => 'normal',
            ),
            'desc_font_decoration' => array(
                'type' => 'string',
                'default' => 'none',
            ),
            'desc_line_height' => array(
                'type' => 'number',
                'default' => 20,
            ),
            'desc_letter_spacing' => array(
                'type' => 'number',
                'default' => 0,
            ),
            'desc_style' => array(
                'source' => 'attribute',
                'attribute' => 'style',
            ),
            'icon' => array(
                'type' => 'string',
                'default' => ''
            ),
            'icon_position' => array(
                'type' => 'number',
                'default' => 0,
            ),
            'icon_color' => array(
                'type' => 'string',
                'default' => '#ddd'
            ),
            'icon_size' => array(
                'type' => 'number',
                'default' => 30,
            ),
            'icon_space' => array(
                'type' => 'number',
                'default' => 15,
            ),
            'icon_margin_left' => array(
                'type' => 'number',
                'default' => 15,
            ),
            'icon_margin_right' => array(
                'type' => 'number',
                'default' => 0,
            ),
            'icon_display' => array(
                'type' => 'string',
                'default' => 'none'
            ),
            'icon_style' => array(
                'source' => 'attribute',
                'attribute' => 'style',
            ),
            'horizontal_flexalignment' => array(
                'type' => 'string',
                'default' => 'center',
            ),
            'vertical_flexalignment' => array(
                'type' => 'string',
                'default' => 'center',
            ),
            'padding_unit' => array(
                'type' => 'string',
                'default' => 'px',
            ),
            'padding_check' => array(
                'type' => 'boolean',
                'default' => true
            ),
            'padding_top' => array(
                'type' =>'string',
                'default' => '30',
            ),
            'padding_right' => array(
                'type' =>'string',
                'default' => '30',
            ),
            'padding_bottom' => array(
                'type' =>'string',
                'default' => '30',
            ),
            'padding_left' => array(
                'type' =>'string',
                'default' => '30',
            ),
            'border_radius_unit' => array(
                'type' => 'string',
                'default' => 'px',
            ),
            'border_radius_check' => array(
                'type' => 'boolean',
                'default' => true
            ),
            'border_radius_top_left' => array(
                'type' =>'string',
                'default' => '',
            ),
            'border_radius_top_right' => array(
                'type' =>'string',
                'default' => '',
            ),
            'border_radius_bottom_left' => array(
                'type' =>'string',
                'default' => '',
            ),
            'border_radius_bottom_right' => array(
                'type' =>'string',
                'default' => '',
            ),
            'horizontal_btn' => array(
                'type' => 'string',
                'default' => 'center',
            ),
            'box_style' => array(
                'source' => 'attribute',
                'attribute' => 'style',
            ),
            'caption_style' => array(
                'source' => 'attribute',
                'attribute' => 'style',
            ),
        ),
        'style'             => 'iheg-image-hover',
        'editor_style' 	    => 'iheg-editor-css',
        'editor_script'     => 'iheg-image-hover',
        'render_callback' 	=> 'iheg_render_callback',
    ));

}

function image_hover_effects_block_scripts() {
    wp_register_script('iheg-editor-js',			IHEG_URL . 'src/blocks/js/editor-script.js', array("jquery"),IHEG_VERSION, '');
    
    wp_enqueue_style('fonticonpicker-base',			IHEG_URL . 'src/blocks/css/fonticonpicker.base-theme.react.css', array(), IHEG_VERSION, 'all');
    wp_enqueue_style('fonticonpicker-material',		IHEG_URL . 'src/blocks/css/fonticonpicker.material-theme.react.css', array(), IHEG_VERSION, 'all');
    
    wp_enqueue_script( 'iheg-editor-js' );
}

function iheg_render_callback($attributes){

    //sanitize here
    $attributes['imgid'] 						= intval( $attributes['imgid'] );
    $attributes['img_size'] 					= esc_attr( $attributes['img_size'] );
    
    $attributes['widget_title'] 				= wp_kses_post($attributes['widget_title']);
    $attributes['widget_description'] 			= wp_kses_post($attributes['widget_description']);
    
    $attributes['hover_effect'] 				= esc_attr( $attributes['hover_effect'] );
    $attributes['overlay_color'] 				= esc_attr( $attributes['overlay_color'] );
    $attributes['linktourl'] 					= esc_url( $attributes['linktourl'] );
    $attributes['addtarget'] 					= esc_attr( $attributes['addtarget'] );
    $attributes['addrel'] 						= esc_attr( $attributes['addrel'] );

    $attributes['title_tag'] 					= esc_attr( $attributes['title_tag'] );
    $attributes['title_font'] 					= esc_attr( $attributes['title_font'] );
    $attributes['title_color'] 					= esc_attr( $attributes['title_color'] );
    $attributes['title_font_size'] 				= intval( $attributes['title_font_size'] );
    $attributes['title_font_weight'] 			= esc_attr( $attributes['title_font_weight'] );
    $attributes['title_font_transform'] 		= esc_attr( $attributes['title_font_transform'] );
    $attributes['title_font_style'] 			= esc_attr( $attributes['title_font_style'] );
    $attributes['title_font_decoration']		= esc_attr( $attributes['title_font_decoration'] );
    $attributes['title_line_height'] 			= intval( $attributes['title_line_height'] );
    $attributes['title_letter_spacing'] 		= intval( $attributes['title_letter_spacing'] );

    $attributes['desc_font']  					= esc_attr( $attributes['desc_font'] );
    $attributes['desc_color'] 					= esc_attr( $attributes['desc_color'] );
    $attributes['desc_font_size'] 				= intval( $attributes['desc_font_size'] );
    $attributes['desc_font_weight'] 			= esc_attr( $attributes['desc_font_weight'] );
    $attributes['desc_font_transform'] 			= esc_attr( $attributes['desc_font_transform'] );
    $attributes['desc_font_style'] 				= esc_attr( $attributes['desc_font_style'] );
    $attributes['desc_font_decoration'] 		= esc_attr( $attributes['desc_font_decoration'] );
    $attributes['desc_line_height'] 			= intval( $attributes['desc_line_height'] );
    $attributes['desc_letter_spacing'] 			= intval( $attributes['desc_letter_spacing'] );

    $attributes['icon'] 						= esc_attr( $attributes['icon'] );
    $attributes['icon_position'] 				= esc_attr( $attributes['icon_position'] );
    $attributes['icon_display'] 				= esc_attr( $attributes['icon_display'] );
    $attributes['icon_color'] 					= esc_attr( $attributes['icon_color'] );
    $attributes['icon_size'] 					= intval( $attributes['icon_size'] );
    $attributes['icon_margin_left'] 			= intval( $attributes['icon_margin_left'] );
    $attributes['icon_margin_right'] 			= intval( $attributes['icon_margin_right'] );

    $attributes['horizontal_flexalignment'] 	= esc_attr( $attributes['horizontal_flexalignment'] );
    $attributes['vertical_flexalignment'] 		= esc_attr( $attributes['vertical_flexalignment'] );

    $attributes['border_radius_unit'] 			= esc_attr( $attributes['border_radius_unit'] );
    $attributes['border_radius_top_left'] 		= intval( $attributes['border_radius_top_left'] );
    $attributes['border_radius_top_right'] 		= intval( $attributes['border_radius_top_right'] );
    $attributes['border_radius_bottom_left'] 	= intval( $attributes['border_radius_bottom_left'] );
    $attributes['border_radius_bottom_right'] 	= intval( $attributes['border_radius_bottom_right'] );

    $attributes['padding_unit'] 				= esc_attr( $attributes['padding_unit'] );
    $attributes['padding_top'] 					= intval( $attributes['padding_top'] );
    $attributes['padding_right'] 				= intval( $attributes['padding_right'] );
    $attributes['padding_bottom'] 				= intval( $attributes['padding_bottom'] );
    $attributes['padding_left'] 				= intval( $attributes['padding_left'] );

    $addlinkto = !empty($attributes['addlinkto']) ? $attributes['addlinkto'] : 'image';

    // Load font on Fronend...
    $font_handle = $attributes['title_font'];

    if($font_handle && $font_handle != 'inherit'){
        $font_family = esc_attr( str_replace( ' ', '+', $font_handle ));
        $font_handle = str_replace( ' ', '-', strtolower( $font_handle ));
        wp_enqueue_style( 'iheg-google-font-' . $font_handle, 'https://fonts.googleapis.com/css?family=' . $font_family . ':wght@' . $attributes['title_font_weight'] . '&display=swap', array(), false );
    }

    $font_handle = $attributes['desc_font'];
    if($font_handle && $font_handle != 'inherit'){
        $font_family = esc_attr( str_replace( ' ', '+', $font_handle ));
        $font_handle = str_replace( ' ', '-', strtolower( $font_handle ));
        wp_enqueue_style( 'iheg-google-font-' . $font_handle, 'https://fonts.googleapis.com/css?family=' . $font_family . ':wght@' . $attributes['desc_font_weight'] . '&display=swap', array(), false );
    }

    if($attributes['imgid'] != '0'){
        $widgetimg =  wp_get_attachment_image_src($attributes['imgid'], $attributes['img_size'])[0];        
		$imgalt = get_post_meta($attributes['imgid'] , '_wp_attachment_image_alt', true);
    } else {
        $widgetimg = '';
    }

    //  Apply style attribute values...
    $title_style  = '';
    $title_style .= ($attributes['title_color'] != '#fff') ? "color: ". $attributes['title_color'] .";" : '';
    $title_style .= ($attributes['title_font'] != 'inherit') ? "font-family: ". $attributes['title_font'] .";" : '';
    $title_style .= ($attributes['title_font_size'] != '40') ? "font-size: ". $attributes['title_font_size'] ."px;" : '';
    $title_style .= ($attributes['title_font_weight'] != '400') ? "font-weight: ". $attributes['title_font_weight'] .";" : '';
    $title_style .= ($attributes['title_font_transform'] != 'none') ? "text-transform: ". $attributes['title_font_transform'] .";" : '';
    $title_style .= ($attributes['title_font_style'] != 'normal') ? "font-style: ". $attributes['title_font_style'] .";" : '';
    $title_style .= ($attributes['title_font_decoration'] != 'none') ? "text-decoration: ". $attributes['title_font_decoration'] .";" : '';
    $title_style .= ($attributes['title_line_height'] != '40') ? "line-height: ". $attributes['title_line_height'] ."px;" : '';
    $title_style .= ($attributes['title_letter_spacing'] != '0') ? "letter-spacing: ". $attributes['title_letter_spacing'] ."px;" : '';

    $desc_style  = '';
    $desc_style .= ($attributes['desc_color'] != '#fff') ? "color: ". $attributes['desc_color'] .";" : '';
    $desc_style .= ($attributes['desc_font'] != 'inherit') ? "font-family: ". $attributes['desc_font'] .";" : '';
    $desc_style .= ($attributes['desc_font_size'] != '20') ? "font-size: ". $attributes['desc_font_size'] ."px;" : '';
    $desc_style .= ($attributes['desc_font_weight'] != '400') ? "font-weight: ". $attributes['desc_font_weight'] .";" : '';
    $desc_style .= ($attributes['desc_font_transform'] != 'none') ? "text-transform: ". $attributes['desc_font_transform'] .";" : '';
    $desc_style .= ($attributes['desc_font_style'] != 'normal') ? "font-style: ". $attributes['desc_font_style'] .";" : '';
    $desc_style .= ($attributes['desc_font_decoration'] != 'none') ? "text-decoration: ". $attributes['desc_font_decoration'] .";" : '';
    $desc_style .= ($attributes['desc_line_height'] != '20') ? "line-height: ". $attributes['desc_line_height'] ."px;" : '';
    $desc_style .= ($attributes['desc_letter_spacing'] != '0') ? "letter-spacing: ". $attributes['desc_letter_spacing'] ."px;" : '';

    $box_style  = "background: ".$attributes['overlay_color'].";";
    if($attributes['border_radius_top_left'] != '') {
    $box_style .= "border-radius:";
    $box_style .= $attributes['border_radius_top_left'].''.$attributes['border_radius_unit']." ";
    $box_style .= $attributes['border_radius_top_right'].''.$attributes['border_radius_unit']." ";
    $box_style .= $attributes['border_radius_bottom_left'].''.$attributes['border_radius_unit']." ";
    $box_style .= $attributes['border_radius_bottom_right'].''.$attributes['border_radius_unit'].";";
    }

    $caption_style  = "background: ".$attributes['overlay_color'].";";
    $caption_style .= ($attributes['horizontal_flexalignment'] != 'center') ? "align-items: ". $attributes['horizontal_flexalignment'] .";" : '';
    $caption_style .= ($attributes['vertical_flexalignment'] != 'center') ? "justify-content: ". $attributes['vertical_flexalignment'] .";" : '';
    if($attributes['padding_top'] != '' && $attributes['padding_top'] != '30' && $attributes['padding_right'] != '30' && $attributes['padding_bottom'] != '30' && $attributes['padding_left'] != '30') {
    $caption_style .= "padding:";
    $caption_style .= $attributes['padding_top'].''.$attributes['padding_unit']." ";
    $caption_style .= $attributes['padding_right'].''.$attributes['padding_unit']." ";
    $caption_style .= $attributes['padding_bottom'].''.$attributes['padding_unit']." ";
    $caption_style .= $attributes['padding_left'].''.$attributes['padding_unit'].";";
    }

    $icon_style = '';
    $icon_style .= ($attributes['icon_display'] != 'none') ? "display:".$attributes['icon_display'].";" : '';
    $icon_style .= ($attributes['icon_color'] != '#ddd') ? "color:". $attributes['icon_color'].";" : '';
    $icon_style .= ($attributes['icon_size'] != '30') ? "width:".$attributes['icon_size']."px;height:".$attributes['icon_size']."px;font-size: ".$attributes['icon_size']."px;" : '';
    $icon_style .= ($attributes['icon_margin_left'] != '15') ? "margin-left: ".$attributes['icon_margin_left']."px;" : '';
    $icon_style .= ($attributes['icon_margin_right'] != '0') ? "margin-right: ".$attributes['icon_margin_right']."px;" : '';
    $icon_style .= ($attributes['icon_position'] != '0') ? "order: ".$attributes['icon_position'].";" : '';
    
    $box_style 		= ' style="'.$box_style . '"';
    $caption_style 	= ' style="'.$caption_style . '"';
    $icon_style 	= ' style="'.$icon_style . '"';
    $title_style 	= (($title_style != '') ? ' style="'.$title_style . '"' : '');
    $desc_style 	= (($desc_style != '') ? ' style="'.$desc_style . '"' : '');

    // Render Output...
    $iheg_block = "";
    $iheg_block .= ($attributes['linktourl'] != '' && $addlinkto == 'image' ) ? '<a class="iheg-wrapper" href="'. $attributes['linktourl'] .'" '. (($attributes['addtarget'] == true)? 'target="_blank"': '') .' '. (($attributes['addrel'] == true) ? 'rel="nofollow"':'') .'>' : '<div class="iheg-wrapper">';
    $iheg_block	.= '<div class="eihe-box '.$attributes['hover_effect'].'"'. $box_style .'>';
    $iheg_block	.= '<img src="' . $widgetimg . '" alt="'.$imgalt.'"/>';
    $iheg_block	.= '<div class="eihe-caption"'. $caption_style .'>';
    $iheg_block	.= '<div class="eihe-title-cover">';

    $iheg_block	.= '<'.$attributes['title_tag']. $title_style .' class="eihe-title">';
    $iheg_block	.= ($attributes['linktourl'] != '' && $addlinkto == 'title' ) ? '<a href="'. $attributes['linktourl'] .'" '. (($attributes['addtarget'] == true)? 'target="_blank"': '') .' '. (($attributes['addrel'] == true) ? 'rel="nofollow"':'') .'>' : '';
    $iheg_block	.= $attributes['widget_title'];
    $iheg_block	.= ($attributes['linktourl'] != '' && $addlinkto == 'title' ) ? '</a>' : '';
    $iheg_block	.= '</'.$attributes['title_tag'].'>';
    
    $iheg_block .=	($attributes['icon'] != '') ? '<i'. $icon_style .' class="'. $attributes['icon'] .'"></i>' :'';
    $iheg_block .='	</div>';
    
    $iheg_block	.= '<p'. $desc_style .'>';
    $iheg_block	.= ($attributes['linktourl'] != '' && $addlinkto == 'desc' ) ? '<a href="'. $attributes['linktourl'] .'" '. (($attributes['addtarget'] == true)? 'target="_blank"': '') .' '. (($attributes['addrel'] == true) ? 'rel="nofollow"':'') .'>' : '';
    $iheg_block	.= $attributes['widget_description'];
    $iheg_block	.= ($attributes['linktourl'] != '' && $addlinkto == 'desc' ) ? '</a>' : '';
    $iheg_block	.= '</p>';
    $iheg_block	.= '</div></div>';
    $iheg_block .= ($attributes['linktourl'] != '' && $addlinkto == 'image') ? '</a>' : '</div>';

    return $iheg_block;
}

add_action('init', 'create_image_hover_effects_block');
add_action('enqueue_block_editor_assets', 'image_hover_effects_block_scripts');