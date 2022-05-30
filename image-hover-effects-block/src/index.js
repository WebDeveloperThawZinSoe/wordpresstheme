import { Panel, PanelBody, SelectControl, Dashicon, TextControl, Dropdown, RangeControl, RadioControl, CheckboxControl, ColorPicker, Button } from '@wordpress/components';
import FontIconPicker from '@fonticonpicker/react-fonticonpicker';
import { Logo, Effects, Icons, GoogleFonts } from './blocks/js/iheg-assets.js';
const { Fragment }	= wp.element;
const { registerBlockType }	= wp.blocks;
const { RichText, InspectorControls, MediaUpload, AlignmentToolbar } = wp.blockEditor;

registerBlockType('block/image-hover-effects-block', {
	title: 'Image Hover Effects',
	icon: Logo(),
	category: 'common',
	attributes: {
		widget_title: {
            type: 'string',
            default: 'Title'
		},
		widget_description: {
			type: 'string',
			default: 'Description',
		},
		hover_effect: {
			type: 'string',
			default: 'eihe-fade',
		},
		linktourl: {
			type: 'string'
		},
		addtarget: {
			type: 'boolean',
			default: false
		},
		addlinkto: {
			type: 'string',
			default: 'image'
		},		
		addrel: {
			type: 'boolean',
			default: false
		},
		img_size: {
			type: 'string',
			default: 'full'
		},
		imgid: {
			type: 'number'
		},
		imgurl: {
			type: 'string'
		},
		img_size_id: {
			type: 'array',
			items: {
				type: 'array',
			}
		},
		overlay_color: {
			type: 'string',
			default: '#000',
		},
		title_tag: {
			type: 'string',
			default: 'h3',
		},
		title_color: {
			type: 'string',
			default: '#fff',
		},
		title_font: {
			type: 'string',
			default: 'inherit',
		},
		title_font_size: {
			type: 'number',
			default: 40,
		},
		title_font_weight: {
			type: 'number',
			default: 700,
		},
		title_font_transform: {
			type: 'string',
			default: 'none',
		},
		title_font_style: {
			type: 'string',
			default: 'normal',
		},
		title_font_decoration: {
			type: 'string',
			default: 'none',
		},
		title_line_height: {
			type: 'number',
			default: 40,
		},
		title_letter_spacing: {
			type: 'number',
			default: 0,
		},
		title_style: {
			source: 'attribute',
			attribute: 'style',
		},
		desc_color: {
			type: 'string',
			default: '#fff',
		},
		desc_font: {
			type: 'string',
			default: 'inherit',
		},
		desc_font_size: {
			type: 'number',
			default: 20,
		},
		desc_font_weight: {
			type: 'number',
			default: 400,
		},
		desc_font_transform: {
			type: 'string',
			default: 'none',
		},
		desc_font_style: {
			type: 'string',
			default: 'normal',
		},
		desc_font_decoration: {
			type: 'string',
			default: 'none',
		},
		desc_line_height: {
			type: 'number',
			default: 20,
		},
		desc_letter_spacing: {
			type: 'number',
			default: 0,
		},
		desc_style: {
			source: 'attribute',
			attribute: 'style',
		},
		icon: {
			type: 'string'
		},
		icon_position: {
			type: 'number',
			default: 0,
		},
		icon_color: {
			type: 'string',
			default: '#ddd'
		},
		icon_size: {
			type: 'number',
			default: 30,
		},
		icon_space: {
			type: 'number',
			default: 15,
		},
		icon_margin_left: {
			type: 'number',
			default: 15,
		},
		icon_margin_right: {
			type: 'number',
			default: 0,
		},
		icon_display: {
			type: 'string',
			default: 'none'
		},
		icon_style: {
			source: 'attribute',
			attribute: 'style',
		},
		horizontal_flexalignment: {
			type: 'string',
			default: 'center',
		},
		vertical_flexalignment: {
			type: 'string',
			default: 'center',
		},
		padding_unit: {
			type: 'string',
			default: 'px',
		},
		padding_check: {
			type: 'boolean',
			default: true
		},
		padding_top: {
			type: 'string',
			default: '30',
		},
		padding_right: {
			type: 'string',
			default: '30',
		},
		padding_bottom: {
			type: 'string',
			default: '30',
		},
		padding_left: {
			type: 'string',
			default: '30',
		},
		border_radius_unit: {
			type: 'string',
			default: 'px',
		},
		border_radius_check: {
			type: 'boolean',
			default: true
		},
		border_radius_top_left: {
			type: 'string'
		},
		border_radius_top_right: {
			type: 'string'
		},
		border_radius_bottom_left: {
			type: 'string'
		},
		border_radius_bottom_right: {
			type: 'string'
		},
		horizontal_btn: {
			type: 'string',
			default: 'center',
		},
		box_style: {
			source: 'attribute',
			attribute: 'style',
		},
		caption_style: {
			source: 'attribute',
			attribute: 'style',
		}
	},
	edit(props) {
		let atts = props.attributes;
		const setAttributes = props.setAttributes;

		let styles = {
			box: {
				background: atts.overlay_color,
				borderRadius: atts.border_radius_top_left + atts.border_radius_unit + " " + atts.border_radius_top_right + atts.border_radius_unit + " " + atts.border_radius_bottom_left + atts.border_radius_unit + " " + atts.border_radius_bottom_right + atts.border_radius_unit,
			},
			caption: {
				background: atts.overlay_color,
				alignItems: atts.horizontal_flexalignment,
				justifyContent: atts.vertical_flexalignment,
				padding: atts.padding_top + atts.padding_unit + " " + atts.padding_right + atts.padding_unit + " " + atts.padding_bottom + atts.padding_unit + " " + atts.padding_left + atts.padding_unit,
			},
			title: {
				color: atts.title_color,
				fontFamily: atts.title_font,
				fontSize: atts.title_font_size + 'px',
				fontWeight: atts.title_font_weight,
				fontStyle: atts.title_font_style,
				textTransform: atts.title_font_transform,
				textDecoration: atts.title_font_decoration,
				lineHeight: atts.title_line_height + 'px',
				letterSpacing: atts.title_letter_spacing + 'px',
			},
			desc: {
				color: atts.desc_color,
				fontFamily: atts.desc_font,
				fontSize: atts.desc_font_size + 'px',
				fontWeight: atts.desc_font_weight,
				fontStyle: atts.desc_font_style,
				textTransform: atts.desc_font_transform,
				textDecoration: atts.desc_font_decoration,
				lineHeight: atts.desc_line_height + 'px',
				letterSpacing: atts.desc_letter_spacing + 'px',
			},
			icon: {
				display: atts.icon_display,
				color: atts.icon_color,
				width: atts.icon_size + 'px',
				height: atts.icon_size + 'px',
				fontSize: atts.icon_size + 'px',
				marginLeft: atts.icon_margin_left + 'px',
				marginRight: atts.icon_margin_right + 'px',
				order: atts.icon_position,
			}
        }

		function chooseImage(value) {
			setAttributes({imgid: value.id});
			var sizes = [];
			for (var key in value.sizes) {
				sizes.push({ label: key.charAt(0).toUpperCase() + key.slice(1)+'- ('+value.sizes[key].width+' x '+value.sizes[key].height+')', value: key });
			}
			setAttributes({ img_size_id: sizes });
			setAttributes({ imgurl: value.url });
		}

		function changeImageSize(content){
			setAttributes({img_size: content});
			if (atts.imgid) {
				wp.media.attachment(atts.imgid).fetch().then(function (data) {
					var newurl = data.sizes[content].url;
					setAttributes({imgurl: newurl});
				});
			}
		}
		
		// ICON...
		function changeIcon(content) {
			if(content != ''){
				setAttributes({ icon_display: "block" });
			} else {
				setAttributes({ icon_display: "none" });
			}
			setAttributes({ icon: content });
		}

		function changeIconPosition(content) {
			setAttributes({ icon_position: content });
			if(content == "0"){
				setAttributes({ icon_margin_left: atts.icon_space });
				setAttributes({ icon_margin_right: 0 });
			}else if(content == "-1"){
				setAttributes({ icon_margin_right: atts.icon_space });
				setAttributes({ icon_margin_left: 0 });
			}
		}

		function iconSize(content) {
			setAttributes({ icon_size: content });
		}

		function iconSpace(content) {
			setAttributes({ icon_space: content });
			if(atts.icon_position == "0"){
				setAttributes({ icon_margin_left: content });
				setAttributes({ icon_margin_right: 0 });
			}else if(atts.icon_position == "-1"){
				setAttributes({ icon_margin_right: content });
				setAttributes({ icon_margin_left: 0 });
			}
		}

		// Horizontal Alignment of Content...
		function horizontal(content) {
			setAttributes({ horizontal_btn: content})
			var flexcontent = content;
			if (content == 'right') {
				flexcontent = 'flex-end';
			} else if (content == 'left') {
				flexcontent = 'flex-start';
			}
			setAttributes({ horizontal_flexalignment: flexcontent });
		}
		// Padding...
		function commonPaddingDimension(value, check = false) {
			if (check || atts.padding_check) {
				setAttributes({padding_top: value});
				setAttributes({padding_bottom: value});
				setAttributes({padding_left: value});
				setAttributes({padding_right: value});
			}
		}
		
		// Border Radius...
		function commonBorderRadiusDimension(value, check = false) {
			if (check || atts.border_radius_check) {
				setAttributes({border_radius_top_left: value});
				setAttributes({border_radius_top_right: value});
				setAttributes({border_radius_bottom_left: value});
				setAttributes({border_radius_bottom_right: value});
			}
		}

		function getFonts() {
			var fonts = [];
			fonts.push({ label: 'Default', value: 'inherit' });
			for (var i = 0; i < GoogleFonts.length; i++) {
				fonts.push({ label: GoogleFonts[i], value: GoogleFonts[i] });
			}
			return fonts;
		}

		// Add Google Font To Backend...
		function loadGoogleFont(font, weight) {
			if (font === 'inherit') {
				return;
			}
			const head = document.head;
			const link = document.createElement('link');
			link.type = 'text/css';
			link.rel = 'stylesheet';
			link.href = 'https://fonts.googleapis.com/css?family=' + font.replace( /\s+/g, '+' ) + ':wght@' + weight + '&display=swap';
			head.appendChild( link );
        }

        const onRemoveImage = () => {
            setAttributes( {
                imgurl: undefined,
            } );
        };

		return (
			<Fragment>
				<div className="widget_wrapper">
                    { !atts.imgurl &&
                        <div className="eihe-box">
                            <MediaUpload onSelect = {chooseImage} allowedTypes={ ['image'] } value = {atts.imgurl} render = {({ open }) => { return <Button className="components-placeholder block-editor-media-placeholder is-large" onClick={open}><div className="no-image"></div></Button> } } />
                        </div>
                    }
                    { atts.imgurl &&
                        <div className = {"eihe-box " + atts.hover_effect} style = {styles.box}>
                            <img src = {atts.imgurl} />
                            <div className="eihe-caption" style = {styles.caption}>
                                <div className="eihe-title-cover">
                                    <atts.title_tag className="eihe-title"><RichText style = {styles.title} className="eihe-title" value = {atts.widget_title} onChange = {(value)=> setAttributes({widget_title: value})}  placeholder="Enter Title" /></atts.title_tag>
                                    <i style={styles.icon} className = {atts.icon}></i>
                                </div>
                                <RichText style = {styles.desc} className="" value = {atts.widget_description} onChange = {(value)=> setAttributes({widget_description: value})} placeholder="Enter Description"/>
                            </div>
                        </div>
                    }
				</div>				
				<InspectorControls>
					<Panel className="ihe-panel">
						<PanelBody title="Image" initialOpen={ true }>
                            <div className="wp-block-eihe-image">
                                { !atts.imgurl &&
                                    <MediaUpload
                                        onSelect={ chooseImage }
                                        allowedTypes={ ['image'] }
                                        value={ atts.imgurl }
                                        render={ ( { open } ) => (
                                            <Button
                                                className={ 'image__toggle' }
                                                onClick={ open }>
                                                Choose image
                                            </Button>
                                        ) }
                                    />
                                }
                                { atts.imgurl &&
                                    <div>
                                        <img src={atts.imgurl} />
                                        <Button onClick={ onRemoveImage } isSecondary>Remove</Button>
                                    </div>
                                }
                            </div>
							<div className="ihe-block-control">
								<SelectControl
									label="Hover Effect"
									value = {atts.hover_effect}
									options = {Effects}
									onChange = {(value) => setAttributes({hover_effect: value})}
								/>
							</div>
							<div className="ihe-block-control">
								<SelectControl label="Size"
									value = {atts.img_size}
									options = {atts.img_size_id}
									onChange = {changeImageSize}
								/>
							</div>
							<div className="ihe-block-control">
								<div className="ihe-flexbox-control">
									<label className="block_input_label">Overlay</label>
									<Dropdown
										className = "ihe-popup-control"
										contentClassName = "ihe-control-container"
										position = "bottom right"
										renderToggle = {({ isOpen, onToggle }) => (
											<div style={{background: atts.overlay_color}} className="ihe-colorbox" onClick = { onToggle } aria-expanded = { isOpen }></div>
										)}
										renderContent = {() => (
											<div className="ihe-group-controls ihe-colorpicker">
												<ColorPicker
													value = {atts.overlay_color}
													onChangeComplete = {(value)=> setAttributes({overlay_color: "rgba("+value.rgb.r+","+ value.rgb.g+","+ value.rgb.b+","+ value.rgb.a+")"})}
												/>
											</div>
										)}
									/>
								</div>
								<div className="ihe-block-control iheg-linkto">
									<label htmlFor="ihe-addlink" className="block_input_label">Link To</label>
									<div className="input_inline">
										<TextControl id="ihe-addlink" value = {atts.linktourl} onChange = {(value)=> setAttributes({linktourl: value})} placeholder="https://your-link.com" />
										<Dashicon className="span_icon" icon="admin-generic" />
									</div>
									<div className="link-attributes">
										<CheckboxControl
											label="Open in new window"
											checked = {atts.addtarget}
											onChange = {(value)=> setAttributes({addtarget: value})}
										/>
										<CheckboxControl
											label="Add nofollow"
											checked = {atts.addrel}
											onChange = {(value)=> setAttributes({addrel: value})}
										/>
									</div>
									<br/>
									<label htmlFor="" className="block_input_label">Add Link to</label>
									<SelectControl label="" value = {atts.addlinkto}
										options = {[
											{ value: 'title', label: 'Title' },
											{ value: 'desc', label: 'Description' },
											{ value: 'image', label: 'Image' },
										]}
										onChange = {(value)=> setAttributes({addlinkto: value})}
									/>
								</div>
							</div>
						</PanelBody>
						<PanelBody title="Title" initialOpen={ false }>
							<div className="ihe-block-control">
								<SelectControl label="Tag" value = {atts.title_tag}
									options = {[
										{ value: 'h1', label: 'H1' },
										{ value: 'h2', label: 'H2' },
										{ value: 'h3', label: 'H3' },
										{ value: 'h4', label: 'H4' },
										{ value: 'h5', label: 'H5' },
										{ value: 'h6', label: 'H6' },
										{ value: 'p', label: 'Paragraph' },
										{ value: 'span', label: 'Span' },
									]}
									onChange = {(value)=> setAttributes({title_tag: value})}
								/>
							</div>
							<div className="ihe-block-control">
								<div className="ihe-flexbox-control">
									<label className="block_input_label">Color</label>
									<Dropdown
										className="ihe-popup-control"
										contentClassName="ihe-control-container"
										position="bottom right"
										renderToggle = {({ isOpen, onToggle }) => (
											<div style={{background: atts.title_color}} className="ihe-colorbox" onClick = { onToggle } aria-expanded = { isOpen }></div>
										)}
										renderContent = {() => (
											<div className="ihe-group-controls ihe-colorpicker">
												<ColorPicker
													value = {atts.title_color}
													onChangeComplete = {(value)=> setAttributes({title_color: "rgba("+value.rgb.r+","+ value.rgb.g+","+ value.rgb.b+","+ value.rgb.a+")"})}
												/>
											</div>
										)}
									/>
								</div>
							</div>
							<div className="ihe-block-control">
								<div className="ihe-flexbox-control">
									<label className="block_input_label">Typography</label>
									<Dropdown
										className="ihe-popup-control typography-group"
										contentClassName="ihe-control-container"
										position="bottom right"
										renderToggle = {({ isOpen, onToggle }) => (
											<div onClick = { onToggle } aria-expanded = { isOpen }><Dashicon icon="edit"></Dashicon></div>
										)}
										renderContent = {() => (
											<div className="ihe-group-controls ihe-title-typo">
												<div className="ihe-block-control select-default">
													<SelectControl label="Family"
														value = {atts.title_font}
														options = {getFonts()}
														onChange = {(value) => { setAttributes({ title_font: value }); loadGoogleFont(value, atts.title_font_weight); }}
													/>
												</div>
												<div className="ihe-block-control">
													<RangeControl
														label="Size"
														value = { atts.title_font_size }
														onChange = {(value)=>setAttributes({ title_font_size: value})}
														min = { 0 }
														max = { 100 }
													/>
												</div>
												<div className="ihe-block-control select-default">
													<SelectControl label="Weight" 
														value = {atts.title_font_weight}
                                                        options = {[
															{ value: '300', label: 'Light' },
															{ value: '400', label: 'Regular' },
															{ value: '500', label: 'Medium' },
															{ value: '700', label: 'Bold' },
															{ value: '900', label: 'Extra Bold' },
														]}
														onChange = {(value)=>setAttributes({title_font_weight: value})}
													/>
												</div>
												<div className="ihe-block-control select-default">
													<SelectControl label="Transform" 
														value = {atts.title_font_transform}
														options = {[
															{ value: 'none', label: 'None' },
															{ value: 'capitalize', label: 'Capitalize' },
															{ value: 'uppercase', label: 'Uppercase' },
															{ value: 'lowercase', label: 'Lowercase' },
															{ value: 'initial', label: 'Initial' },
															{ value: 'inherit', label: 'Inherit' },
														]}
														onChange = {(value)=>setAttributes({title_font_transform: value})}
													/>
												</div>
												<div className="ihe-block-control select-default">
													<SelectControl label="Style" 
														value = {atts.title_font_style}
														options = {[
															{ value: 'normal', label: 'Normal' },
															{ value: 'italic', label: 'Italic' },
															{ value: 'oblique', label: 'Oblique' },
														]}
														onChange = {(value)=>setAttributes({title_font_style: value})}
													/>
												</div>
												<div className="ihe-block-control select-default">
													<SelectControl label="Decoration" 
														value = {atts.title_font_decoration}
														options = {[
															{ value: 'none', label: 'None' },
															{ value: 'underline', label: 'Underline' },
															{ value: 'overline', label: 'Overline' },
															{ value: 'line-through', label: 'Line Through' },
														]}
														onChange = {(value)=>setAttributes({title_font_decoration: value})}
													/>
												</div>
												<div className="ihe-block-control">
													<RangeControl
														label="Line Height"
														value = {atts.title_line_height}
														onChange = {(value)=>setAttributes({title_line_height: value})}
														min = { 0 }
														max = { 100 }
													/>
												</div>
												<div className="ihe-block-control">
													<RangeControl
														label="Letter Spacing"
														value = {atts.title_letter_spacing}
														onChange = {(value)=>setAttributes({title_letter_spacing: value})}
														min = { -5 }
														max = { 10 }
													/>
												</div>
											</div>
										)}
									/>
								</div>
							</div>
						</PanelBody>
						<PanelBody title="Description" initialOpen={ false }>
							<div className="ihe-block-control">
								<div className="ihe-flexbox-control">
									<label className="block_input_label">Color</label>
									<Dropdown
										className="ihe-popup-control"
										contentClassName="ihe-control-container"
										position="bottom right"
										renderToggle = {({ isOpen, onToggle }) => (
											<div style={{background: atts.desc_color}} className="ihe-colorbox" onClick = { onToggle } aria-expanded = { isOpen }></div>
										)}
										renderContent = {() => (
											<div className="ihe-group-controls ihe-colorpicker">
												<ColorPicker
													value = {atts.desc_color}
													onChangeComplete = {(value)=> setAttributes({desc_color: "rgba("+value.rgb.r+","+ value.rgb.g+","+ value.rgb.b+","+ value.rgb.a+")"})}
												/>
											</div>
										)}
									/>
								</div>
							</div>
							<div className="ihe-block-control">
								<div className="ihe-flexbox-control">
									<label className="block_input_label">Typography</label>
									<Dropdown
										className="ihe-popup-control typography-group"
										contentClassName="ihe-control-container"
										position="bottom right"
										renderToggle = {({ isOpen, onToggle }) => (
											<div onClick = {onToggle} aria-expanded = {isOpen}><Dashicon  icon="edit"></Dashicon></div>
										)}
										renderContent = {() => (
											<div className="ihe-group-controls">
												<div className="ihe-block-control select-default">
													<SelectControl label="Family"
														value = {atts.desc_font}
														options = {getFonts()}
														onChange = {(value) => { setAttributes({ desc_font: value }); loadGoogleFont(value, atts.desc_font_weight); }}
													/>
												</div>
												<div className="ihe-block-control select-default">
													<SelectControl label="Weight" 
														value = {atts.desc_font_weight}
														options = {[
															{ value: '300', label: 'Light' },
															{ value: '400', label: 'Regular' },
															{ value: '500', label: 'Medium' },
															{ value: '700', label: 'Bold' },
															{ value: '900', label: 'Extra Bold' },
														]}
														onChange = {(value)=>setAttributes({desc_font_weight: value})}
													/>
												</div>
												<div className="ihe-block-control">
													<RangeControl
														label="Size"
														value = {atts.desc_font_size}
														onChange = {(value)=>setAttributes({desc_font_size: value})}
														min = { 0 }
														max = { 100 }
													/>
												</div>
												<div className="ihe-block-control select-default">
													<SelectControl label="Transform" 
														value = {atts.desc_font_transform}
														options = {[
															{ value: 'none', label: 'None' },
															{ value: 'capitalize', label: 'Capitalize' },
															{ value: 'uppercase', label: 'Uppercase' },
															{ value: 'lowercase', label: 'Lowercase' },
															{ value: 'initial', label: 'Initial' },
															{ value: 'inherit', label: 'Inherit' },
														]}
														onChange = {(value)=>setAttributes({desc_font_transform: value})}
													/>
												</div>
												<div className="ihe-block-control select-default">
													<SelectControl label="Style"
														value = {atts.desc_font_style}
														options = {[
															{ value: 'normal', label: 'Normal' },
															{ value: 'italic', label: 'Italic' },
															{ value: 'oblique', label: 'Oblique' },
														]}
														onChange = {(value)=>setAttributes({desc_font_style: value})}
													/>
												</div>
												<div className="ihe-block-control select-default">
													<SelectControl label="Decoration" 
														value = {atts.desc_font_decoration}
														options = {[
															{ value: 'none', label: 'None' },
															{ value: 'underline', label: 'Underline' },
															{ value: 'overline', label: 'Overline' },
															{ value: 'line-through', label: 'Line Through' },
														]}
														onChange = {(value)=>setAttributes({desc_font_decoration: value})}
													/>
												</div>
												<div className="ihe-block-control">
													<RangeControl
														label="Line Height"
														value = {atts.desc_line_height}
														onChange = {(value)=>setAttributes({desc_line_height: value})}
														min = { 0 }
														max = { 100 }
													/>
												</div>
												<div className="ihe-block-control">
													<RangeControl
														label="Letter Spacing"
														value = {atts.desc_letter_spacing}
														onChange = {(value)=>setAttributes({desc_letter_spacing: value})}
														min = { -5 }
														max = { 10 }
													/>
												</div>
											</div>
										)}
									/>
								</div>
							</div>
						</PanelBody>
						<PanelBody title="Icon" initialOpen={ false }>
							<div className="ihe-block-control iheg-icon-selector">
								<FontIconPicker
									icons = {Icons()}
									value = {atts.icon}
									onChange = {changeIcon}
									isMulti = {false}
									appendTo = "body"
								/>
							</div>
							<div className="ihe-block-control">
								<SelectControl label="Position" value = {atts.icon_position}
									options = {[
										{ value: '-1', label: 'Before' },
										{ value: '0', label: 'After' },
									]}
									onChange = {changeIconPosition}
								/>
							</div>
							<div className="ihe-block-control">
								<div className="ihe-flexbox-control">
									<label className="block_input_label">Color</label>
									<Dropdown
										className="ihe-popup-control"
										contentClassName="ihe-control-container"
										position="bottom right"
										renderToggle = {({ isOpen, onToggle }) => (
											<div style={{background: atts.icon_color}} className="ihe-colorbox" onClick = { onToggle } aria-expanded = { isOpen }></div>
										)}
										renderContent = {() => (
											<div className="ihe-group-controls ihe-colorpicker">
												<ColorPicker
													value = {atts.icon_color}
													onChangeComplete = {(value)=> setAttributes({icon_color: "rgba("+value.rgb.r+","+ value.rgb.g+","+ value.rgb.b+","+ value.rgb.a+")"})}
												/>
											</div>
										)}
									/>
								</div>
							</div>
							<div className="ihe-block-control">
								<RangeControl
									label="Size"
									value = { atts.icon_size }
									onChange = {iconSize}
									min = { 5 }
									max = { 200 }
								/>
								<RangeControl
									label="Space"
									value = { atts.icon_space }
									onChange = {iconSpace}
									min = { 0 }
									max = { 150 }
								/>
							</div>
						</PanelBody>
						<PanelBody title="Alignment & Styles" initialOpen={ false }>
							<div className="ihe-block-control alignment">
								<div className="ihe-block-control ihe-horizontal-align">
									<label className="block_input_label">Horizontal Alignment</label>
									<AlignmentToolbar onChange = {horizontal} value = {atts.horizontal_btn} />
								</div>
								<div className="ihe-block-control">
									<label className="block_input_label">Vertical Alignment</label>
									<RadioControl
										className = "ihe-vertical-align"
										selected={ atts.vertical_flexalignment }
										options={ [
											{ value: 'flex-start', label: 'top' },
											{ value: 'center', label: 'middle' },
											{ value: 'flex-end', label: 'bottom' },
										] }
										onChange = {(value)=> setAttributes({vertical_flexalignment: value})}
									/>
								</div>
							</div>
							<div className="ihe-block-control dnlslider">
								<div className="css-dnl">
									<div className="css-col-1">
										<div className="ihe-block-control">
											<div className="ihe-flexbox-control">
												<label className="block_input_label">Padding</label>
												<RadioControl
													selected={ atts.padding_unit }
													options={ [
														{ label: 'px', value: 'px' },
														{ label: '%',  value: '%' },
													] }
													onChange = {(value)=> setAttributes({padding_unit: value})}
												/>
											</div>
										</div>
									</div>
									<div className="css-col-2">
										<div className="css-dnl-indvl">
											<TextControl type="number" value = {atts.padding_top} onChange = {value => { setAttributes({ padding_top: value }); commonPaddingDimension(value); }} />
											<TextControl type="number" value = {atts.padding_right} onChange = {value => { setAttributes({ padding_right: value }); commonPaddingDimension(value); }} />
											<TextControl type="number" value = {atts.padding_bottom} onChange = {value => { setAttributes({ padding_bottom: value }); commonPaddingDimension(value); }} />
											<TextControl type="number" value = {atts.padding_left} onChange = {value => { setAttributes({ padding_left: value }); commonPaddingDimension(value); }} />
										</div>
										<div className="css-switch">
											<div className="">
												<CheckboxControl
													label = "click"
													className = "css-link checked"
													checked = {atts.padding_check}
													onChange = {value => { setAttributes({ padding_check: value }); commonPaddingDimension(atts.padding_top, value) }}
												/>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div className="ihe-block-control dnlslider">
								<div className="css-dnl">
									<div className="css-col-1">
										<div className="ihe-block-control">
											<div className="ihe-flexbox-control">
												<label className="block_input_label">Border Radius</label>
												<RadioControl
													selected={ atts.border_radius_unit }
													options={ [
														{ label: 'px', value: 'px' },
														{ label: '%',  value: '%' },
													] }
													onChange = {(value)=> setAttributes({border_radius_unit: value})}
												/>
											</div>
										</div>
									</div>
									<div className="css-col-2">
										<div className="css-dnl-indvl">
											<TextControl type="number" value = {atts.border_radius_top_left} onChange = {value => { setAttributes({ border_radius_top_left: value }); commonBorderRadiusDimension(value); }} />
											<TextControl type="number" value = {atts.border_radius_top_right} onChange = {value => { setAttributes({ border_radius_top_right: value }); commonBorderRadiusDimension(value); }} />
											<TextControl type="number" value = {atts.border_radius_bottom_left} onChange = {value => { setAttributes({ border_radius_bottom_left: value }); commonBorderRadiusDimension(value); }} />
											<TextControl type="number" value = {atts.border_radius_bottom_right} onChange = {value => { setAttributes({ border_radius_bottom_right: value }); commonBorderRadiusDimension(value); }} />
										</div>
										<div className="css-switch">
											<div className="">
												<CheckboxControl
													label = "click"
													className = "css-link checked"
													checked = {atts.border_radius_check}
													onChange = {value => { setAttributes({ border_radius_check: value }); commonBorderRadiusDimension(atts.border_radius_top_left, value) }}
												/>
											</div>
										</div>
									</div>
								</div>
							</div>
						</PanelBody>
					</Panel>
				</InspectorControls>
			</Fragment>
		);
	},
	save(props) {
		return (null);
	}
});