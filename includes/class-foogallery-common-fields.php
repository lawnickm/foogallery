<?php
/**
 * Adds all functionality related to the common gallery fields that are used in the default gallery templates
 * Date: 12/09/2017
 */
if ( ! class_exists( 'FooGallery_Common_Fields' ) ) {

	class FooGallery_Common_Fields {

		function __construct() {
            //handle some default field types that all templates can reuse
            add_filter( 'foogallery_alter_gallery_template_field', array( $this, 'alter_gallery_template_field' ), 10, 2 );

            //build up class attributes
			add_filter( 'foogallery_build_class_attribute', array( $this, 'add_common_fields_class_attributes' ), 10, 2 );

			//add our common field data attribute
			add_filter( 'foogallery_build_container_attributes', array( $this, 'add_common_fields_data_attribute' ), 10, 2 );

			//add common data options
			add_filter( 'foogallery_build_container_data_options', array( $this, 'add_caption_data_options' ), 10, 3 );

			//build up any preview arguments
			add_filter( 'foogallery_preview_arguments', array( $this, 'preview_arguments' ), 10, 3 );

            //add common fields to the templates that support it
            add_filter( 'foogallery_override_gallery_template_fields', array( $this, 'add_common_fields' ), 10, 2 );
		}

        function alter_gallery_template_field( $field, $gallery ) {
            if ( $field ) {

            	if ( isset( $field['type'] ) ) {
					switch ( $field['type'] ) {
						case 'thumb_link':
							$field['type']    = 'radio';
							$field['choices'] = foogallery_gallery_template_field_thumb_link_choices();
							break;
						case 'lightbox':
							$field['lightbox'] = true;
							$field['type']     = 'select';
							$field['choices']  = foogallery_gallery_template_field_lightbox_choices();
							break;
					}
				}

                if ( isset($field['help']) && $field['help'] ) {
                    $field['type'] = 'help';
                }
            }
            return $field;
        }

		/**
		 * Add common fields to the gallery template if supported
		 *
		 * @param $fields
		 * @param $template
		 *
		 * @return array
		 */
		function add_common_fields( $fields, $template ) {
			//check if the template supports the common fields
			if ( $template && array_key_exists( 'common_fields_support', $template ) && true === $template['common_fields_support'] ) {

				//region Appearance Fields
				$fields[] = array(
					'id'       => 'theme',
					'title'    => __( 'Theme', 'foogallery' ),
					'desc'     => __( 'The overall appearance of the items in the gallery, affecting the border, background, font and shadow colors.', 'foogallery' ),
					'section'  => __( 'Appearance', 'foogallery' ),
					'type'     => 'radio',
					'default'  => 'fg-light',
					'spacer'   => '<span class="spacer"></span>',
					'choices'  => array(
						'fg-light'  => __( 'Light', 'foogallery' ),
						'fg-dark'   => __( 'Dark', 'foogallery' ),
						'fg-custom' => __( 'Custom', 'foogallery' )
					),
					'row_data' => array(
						'data-foogallery-change-selector' => 'input:radio',
						'data-foogallery-preview'         => 'class'
					)
				);

				$fields[] = array(
					'id'       => 'border_size',
					'title'    => __( 'Border Size', 'foogallery' ),
					'desc'     => __( 'The border size applied to each thumbnail', 'foogallery' ),
					'section'  => __( 'Appearance', 'foogallery' ),
					'type'     => 'radio',
					'spacer'   => '<span class="spacer"></span>',
					'default'  => 'fg-border-thin',
					'choices'  => array(
						''                 => __( 'None', 'foogallery' ),
						'fg-border-thin'   => __( 'Thin', 'foogallery' ),
						'fg-border-medium' => __( 'Medium', 'foogallery' ),
						'fg-border-thick'  => __( 'Thick', 'foogallery' ),
					),
					'row_data' => array(
						'data-foogallery-change-selector' => 'input:radio',
						'data-foogallery-preview'         => 'class'
					)
				);

				$fields[] = array(
					'id'       => 'rounded_corners',
					'title'    => __( 'Rounded Corners', 'foogallery' ),
					'desc'     => __( 'The border radius, or rounded corners applied to each thumbnail', 'foogallery' ),
					'section'  => __( 'Appearance', 'foogallery' ),
					'type'     => 'radio',
					'spacer'   => '<span class="spacer"></span>',
					'default'  => '',
					'choices'  => array(
						''                => __( 'None', 'foogallery' ),
						'fg-round-small'  => __( 'Small', 'foogallery' ),
						'fg-round-medium' => __( 'Medium', 'foogallery' ),
						'fg-round-large'  => __( 'Large', 'foogallery' ),
						'fg-round-full'   => __( 'Full', 'foogallery' ),
					),
					'row_data' => array(
						'data-foogallery-change-selector' => 'input:radio',
						'data-foogallery-preview'         => 'class'
					)
				);

				$fields[] = array(
					'id'       => 'drop_shadow',
					'title'    => __( 'Drop Shadow', 'foogallery' ),
					'desc'     => __( 'The outer or drop shadow applied to each thumbnail', 'foogallery' ),
					'section'  => __( 'Appearance', 'foogallery' ),
					'type'     => 'radio',
					'spacer'   => '<span class="spacer"></span>',
					'default'  => 'fg-shadow-outline',
					'choices'  => array(
						''                  => __( 'None', 'foogallery' ),
						'fg-shadow-outline' => __( 'Outline', 'foogallery' ),
						'fg-shadow-small'   => __( 'Small', 'foogallery' ),
						'fg-shadow-medium'  => __( 'Medium', 'foogallery' ),
						'fg-shadow-large'   => __( 'Large', 'foogallery' ),
					),
					'row_data' => array(
						'data-foogallery-change-selector' => 'input:radio',
						'data-foogallery-preview'         => 'class'
					)
				);

				$fields[] = array(
					'id'       => 'inner_shadow',
					'title'    => __( 'Inner Shadow', 'foogallery' ),
					'desc'     => __( 'The inner shadow applied to each thumbnail', 'foogallery' ),
					'section'  => __( 'Appearance', 'foogallery' ),
					'type'     => 'radio',
					'spacer'   => '<span class="spacer"></span>',
					'default'  => '',
					'choices'  => array(
						''                       => __( 'None', 'foogallery' ),
						'fg-shadow-inset-small'  => __( 'Small', 'foogallery' ),
						'fg-shadow-inset-medium' => __( 'Medium', 'foogallery' ),
						'fg-shadow-inset-large'  => __( 'Large', 'foogallery' ),
					),
					'row_data' => array(
						'data-foogallery-change-selector' => 'input:radio',
						'data-foogallery-preview'         => 'class'
					)
				);

				$fields[] = array(
					'id'       => 'loading_icon',
					'title'    => __( 'Loading Icon', 'foogallery' ),
					'desc'     => __( 'An animated loading icon can be shown while the thumbnails are busy loading.', 'foogallery' ),
					'section'  => __( 'Appearance', 'foogallery' ),
					'default'  => 'fg-loading-default',
					'type'     => 'htmlicon',
					'choices'  => apply_filters(
						'foogallery_gallery_template_common_thumbnail_fields_loading_icon_choices', array(
						''                   => array( 'label' => __( 'None', 'foogallery' ), 'html' => '<div class="foogallery-setting-loading_icon"></div>' ),
						'fg-loading-default' => array( 'label' => __( 'Default', 'foogallery' ), 'html' => '<div class="foogallery-setting-loading_icon fg-loading-default"><div class="fg-loader"></div></div>' ),
						'fg-loading-bars'    => array( 'label' => __( 'Bars', 'foogallery' ), 'html' => '<div class="foogallery-setting-loading_icon fg-loading-bars"><div class="fg-loader"></div></div>' ),
						'fg-loading-dots'    => array( 'label' => __( 'Dots', 'foogallery' ), 'html' => '<div class="foogallery-setting-loading_icon fg-loading-dots"><div class="fg-loader"></div></div>' ),
						'fg-loading-partial' => array( 'label' => __( 'Partial', 'foogallery' ), 'html' => '<div class="foogallery-setting-loading_icon fg-loading-partial"><div class="fg-loader"></div></div>' ),
						'fg-loading-pulse'   => array( 'label' => __( 'Pulse', 'foogallery' ), 'html' => '<div class="foogallery-setting-loading_icon fg-loading-pulse"><div class="fg-loader"></div></div>' ),
						'fg-loading-trail'   => array( 'label' => __( 'Trail', 'foogallery' ), 'html' => '<div class="foogallery-setting-loading_icon fg-loading-trail"><div class="fg-loader"></div></div>' ),
					)
					),
					'row_data' => array(
						'data-foogallery-change-selector' => 'input:radio',
						'data-foogallery-preview'         => 'class'
					)
				);

				$fields[] = array(
					'id'       => 'loaded_effect',
					'title'    => __( 'Loaded Effect', 'foogallery' ),
					'desc'     => __( 'The animation effect used to display the thumbnail, once it has loaded.', 'foogallery' ),
					'section'  => __( 'Appearance', 'foogallery' ),
					'default'  => 'fg-loaded-fade-in',
					'type'     => 'select',
					'choices'  => apply_filters(
						'foogallery_gallery_template_common_thumbnail_fields_loaded_effect_choices', array(
						''                      => __( 'None', 'foogallery' ),
						'fg-loaded-fade-in'     => __( 'Fade In', 'foogallery' ),
						'fg-loaded-slide-up'    => __( 'Slide Up', 'foogallery' ),
						'fg-loaded-slide-down'  => __( 'Slide Down', 'foogallery' ),
						'fg-loaded-slide-left'  => __( 'Slide Left', 'foogallery' ),
						'fg-loaded-slide-right' => __( 'Slide Right', 'foogallery' ),
						'fg-loaded-scale-up'    => __( 'Scale Up', 'foogallery' ),
						'fg-loaded-swing-down'  => __( 'Swing Down', 'foogallery' ),
						'fg-loaded-drop'        => __( 'Drop', 'foogallery' ),
						'fg-loaded-fly'         => __( 'Fly', 'foogallery' ),
						'fg-loaded-flip'        => __( 'Flip', 'foogallery' ),
					)
					),
					'row_data' => array(
						'data-foogallery-change-selector' => 'input:radio',
						'data-foogallery-preview'         => 'class'
					)
				);
				//endregion

				//region Hover Effects Fields
				$fields[] = array(
					'id'      => 'hover_effect_help',
					'title'   => __( 'Hover Effect Help', 'foogallery' ),
					'desc'    => __( 'A preset provides a stylish and pre-defined look &amp; feel for when you hover over the thumbnails.', 'foogallery' ),
					'section' => __( 'Hover Effects', 'foogallery' ),
					'type'    => 'help'
				);

				$fields[] = array(
					'id'       => 'hover_effect_preset',
					'title'    => __( 'Preset', 'foogallery' ),
					'section'  => __( 'Hover Effects', 'foogallery' ),
					'default'  => 'fg-custom',
					'type'     => 'radio',
					'choices'  => apply_filters(
						'foogallery_gallery_template_common_thumbnail_fields_hover_effect_preset_choices', array(
						''          => __( 'None', 'foogallery' ),
						'fg-custom' => __( 'Custom', 'foogallery' ),
					)
					),
					'spacer'   => '<span class="spacer"></span>',
					'desc'     => __( 'A preset styling that is used for the captions. If you want to define your own custom captions, then choose Custom.', 'foogallery' ),
					'row_data' => array(
						'data-foogallery-change-selector' => 'input:radio',
						'data-foogallery-value-selector'  => 'input:checked',
						'data-foogallery-preview'         => 'class'
					)
				);

				$fields[] = array(
					'id'       => 'hover_effect_preset_size',
					'title'    => __( 'Preset Size', 'foogallery' ),
					'section'  => __( 'Hover Effects', 'foogallery' ),
					'default'  => 'fg-preset-small',
					'spacer'   => '<span class="spacer"></span>',
					'type'     => 'radio',
					'choices'  => apply_filters(
						'foogallery_gallery_template_common_thumbnail_fields_hover_effect_preset_size_choices', array(
						'fg-preset-small'  => __( 'Small', 'foogallery' ),
						'fg-preset-medium' => __( 'Medium', 'foogallery' ),
						'fg-preset-large'  => __( 'Large', 'foogallery' ),
					)
					),
					'desc'     => __( 'Choose an appropriate size for the preset caption effects, based on the size of your thumbs. Choose small for thumbs 150-200 wide, medium for thumbs 200-400 wide, and large for thumbs over 400 wide.', 'foogallery' ),
					'row_data' => array(
						'data-foogallery-change-selector'          => 'input:radio',
						'data-foogallery-hidden'                   => true,
						'data-foogallery-show-when-field'          => 'hover_effect_preset',
						'data-foogallery-show-when-field-operator' => 'indexOf',
						'data-foogallery-show-when-field-value'    => 'fg-preset',
						'data-foogallery-preview'                  => 'class'
					)
				);

				$fields[] = array(
					'id'       => 'hover_effect_color',
					'title'    => __( 'Color Effect', 'foogallery' ),
					'section'  => __( 'Hover Effects', 'foogallery' ),
					'default'  => '',
					'spacer'   => '<span class="spacer"></span>',
					'type'     => 'radio',
					'choices'  => apply_filters(
						'foogallery_gallery_template_common_thumbnail_fields_hover_effect_color_choices', array(
						''                   => __( 'None', 'foogallery' ),
						'fg-hover-colorize'  => __( 'Colorize', 'foogallery' ),
						'fg-hover-grayscale' => __( 'Greyscale', 'foogallery' ),
					)
					),
					'desc'     => __( 'Choose an color effect that is applied when you hover over a thumbnail.', 'foogallery' ),
					'row_data' => array(
						'data-foogallery-change-selector'       => 'input:radio',
						'data-foogallery-hidden'                => true,
						'data-foogallery-show-when-field'       => 'hover_effect_preset',
						'data-foogallery-show-when-field-value' => 'fg-custom',
						'data-foogallery-preview'               => 'class'
					)
				);

				$fields[] = array(
					'id'       => 'hover_effect_scale',
					'title'    => __( 'Scaling Effect', 'foogallery' ),
					'section'  => __( 'Hover Effects', 'foogallery' ),
					'default'  => '',
					'spacer'   => '<span class="spacer"></span>',
					'type'     => 'radio',
					'choices'  => apply_filters(
						'foogallery_gallery_template_common_thumbnail_fields_hover_effect_scale_choices', array(
						''               => __( 'None', 'foogallery' ),
						'fg-hover-scale' => __( 'Scaled', 'foogallery' ),
					)
					),
					'desc'     => __( 'Apply a slight scaling effect when hovering over a thumbnail.', 'foogallery' ),
					'row_data' => array(
						'data-foogallery-change-selector'       => 'input:radio',
						'data-foogallery-hidden'                => true,
						'data-foogallery-show-when-field'       => 'hover_effect_preset',
						'data-foogallery-show-when-field-value' => 'fg-custom',
						'data-foogallery-preview'               => 'class'
					)
				);

				$fields[] = array(
					'id'       => 'hover_effect_caption_visibility',
					'title'    => __( 'Caption Visibility', 'foogallery' ),
					'section'  => __( 'Hover Effects', 'foogallery' ),
					'default'  => 'fg-caption-hover',
					'spacer'   => '<span class="spacer"></span>',
					'type'     => 'radio',
					'choices'  => apply_filters(
						'foogallery_gallery_template_common_thumbnail_fields_hover_effect_caption_visibility_choices', array(
						''                  => __( 'None', 'foogallery' ),
						'fg-caption-hover'  => __( 'On Hover', 'foogallery' ),
						'fg-caption-always' => __( 'Always Visible', 'foogallery' ),
					)
					),
					'desc'     => __( 'Choose how the captions will be displayed.', 'foogallery' ),
					'row_data' => array(
						'data-foogallery-change-selector'       => 'input:radio',
						'data-foogallery-hidden'                => true,
						'data-foogallery-show-when-field'       => 'hover_effect_preset',
						'data-foogallery-show-when-field-value' => 'fg-custom',
						'data-foogallery-preview'               => 'class'
					)
				);

				$fields[] = array(
					'id'       => 'hover_effect_transition',
					'title'    => __( 'Transition', 'foogallery' ),
					'section'  => __( 'Hover Effects', 'foogallery' ),
					'default'  => 'fg-hover-fade',
					'type'     => 'select',
					'choices'  => apply_filters( 'foogallery_gallery_template_common_thumbnail_fields_hover_effect_transition_choices', array(
						'fg-hover-instant'     => __( 'Instant', 'foogallery' ),
						'fg-hover-fade'        => __( 'Fade', 'foogallery' ),
						'fg-hover-slide-up'    => __( 'Slide Up', 'foogallery' ),
						'fg-hover-slide-down'  => __( 'Slide Down', 'foogallery' ),
						'fg-hover-slide-left'  => __( 'Slide Left', 'foogallery' ),
						'fg-hover-slide-right' => __( 'Slide Right', 'foogallery' ),
						'fg-hover-push'        => __( 'Push', 'foogallery' ) )
					),
					'desc'     => __( 'Choose what effect is used to show the caption when you hover over a thumbnail', 'foogallery' ),
					'row_data' => array(
						'data-foogallery-change-selector'       => 'select',
						'data-foogallery-hidden'                => true,
						'data-foogallery-show-when-field'       => 'hover_effect_preset',
						'data-foogallery-show-when-field-value' => 'fg-custom',
						'data-foogallery-preview'               => 'class'
					)
				);

				$fields[] = array(
					'id'       => 'hover_effect_icon',
					'title'    => __( 'Icon', 'foogallery' ),
					'desc'     => __( 'Choose which icon is shown with the caption when you hover over a thumbnail', 'foogallery' ),
					'section'  => __( 'Hover Effects', 'foogallery' ),
					'type'     => 'htmlicon',
					'default'  => 'fg-hover-zoom',
					'choices'  => apply_filters( 'foogallery_gallery_template_common_thumbnail_fields_hover_effect_icon_choices', array(
						''                     => array( 'label' => __( 'None', 'foogallery' ), 'html' => '<div class="foogallery-setting-caption_icon"></div>' ),
						'fg-hover-zoom'        => array( 'label' => __( 'Zoom', 'foogallery' ), 'html' => '<div class="foogallery-setting-caption_icon fg-hover-zoom"></div>' ),
						'fg-hover-zoom2'       => array( 'label' => __( 'Zoom 2', 'foogallery' ), 'html' => '<div class="foogallery-setting-caption_icon fg-hover-zoom2"></div>' ),
						'fg-hover-zoom3'       => array( 'label' => __( 'Zoom 3', 'foogallery' ), 'html' => '<div class="foogallery-setting-caption_icon fg-hover-zoom3"></div>' ),
						'fg-hover-plus'        => array( 'label' => __( 'Plus', 'foogallery' ), 'html' => '<div class="foogallery-setting-caption_icon fg-hover-plus"></div>' ),
						'fg-hover-circle-plus' => array( 'label' => __( 'Circle Plus', 'foogallery' ), 'html' => '<div class="foogallery-setting-caption_icon fg-hover-circle-plus"></div>' ),
						'fg-hover-eye'         => array( 'label' => __( 'Eye', 'foogallery' ), 'html' => '<div class="foogallery-setting-caption_icon fg-hover-eye"></div>' ),
						'fg-hover-external'    => array( 'label' => __( 'External', 'foogallery' ), 'html' => '<div class="foogallery-setting-caption_icon fg-hover-external"></div>' ), )
					),
					'row_data' => array(
						'data-foogallery-change-selector'       => 'input:radio',
						'data-foogallery-hidden'                => true,
						'data-foogallery-show-when-field'       => 'hover_effect_preset',
						'data-foogallery-show-when-field-value' => 'fg-custom',
						'data-foogallery-preview'               => 'class'
					)
				);
				//endregion Hover Effects Fields

				//region Caption Fields
				$fields[] = array(
					'id'      => 'captions_help',
					'title'   => __( 'Captions Help', 'foogallery' ),
					'desc'    => __( 'You can change when captions are shown using the "Hover Effects -> Caption Visibility" setting .', 'foogallery' ),
					'section' => __( 'Captions', 'foogallery' ),
					'type'    => 'help'
				);

				$settings_link = sprintf( '<a target="blank" href="%s">%s</a>', foogallery_admin_settings_url(), __( 'settings', 'foogallery' ) );

				$fields[] = array(
					'id'       => 'caption_title_source',
					'title'    => __( 'Title', 'foogallery' ),
					'desc'     => __( 'Decide where caption titles are pulled from. By default, what is saved under general settings will be used, but it can be overridden per gallery', 'foogallery' ),
					'section'  => __( 'Captions', 'foogallery' ),
					'type'     => 'radio',
					'default'  => '',
					'choices'  => array(
						'none'    => __( 'None', 'foogallery' ),
						''        => sprintf( __( 'Default (as per %s)', 'foogallery' ), $settings_link ),
						'title'   => foogallery_get_attachment_field_friendly_name( 'title' ),
						'caption' => foogallery_get_attachment_field_friendly_name( 'caption' ),
						'alt'     => foogallery_get_attachment_field_friendly_name( 'alt' ),
						'desc'    => foogallery_get_attachment_field_friendly_name( 'desc' ),
					),
					'row_data' => array(
						'data-foogallery-change-selector'       => 'input:radio',
						'data-foogallery-preview'               => 'shortcode'
					)
				);

				$fields[] = array(
					'id'       => 'caption_desc',
					'title'    => __( 'Description', 'foogallery' ),
					'desc'     => __( 'Decide where captions descriptions are pulled from. By default, the general settings are used, but it can be overridden per gallery', 'foogallery' ),
					'section'  => __( 'Captions', 'foogallery' ),
					'type'     => 'radio',
					'default'  => '',
					'choices'  => array(
						'none'    => __( 'None', 'foogallery' ),
						''        => sprintf( __( 'Default (as per %s)', 'foogallery' ), $settings_link ),
						'title'   => foogallery_get_attachment_field_friendly_name( 'title' ),
						'caption' => foogallery_get_attachment_field_friendly_name( 'caption' ),
						'alt'     => foogallery_get_attachment_field_friendly_name( 'alt' ),
						'desc'    => foogallery_get_attachment_field_friendly_name( 'desc' ),
					),
					'row_data' => array(
						'data-foogallery-change-selector'       => 'input:radio',
						'data-foogallery-preview'               => 'shortcode'
					)
				);

				$fields[] = array(
					'id'      => 'captions_limit_length',
					'title'   => __( 'Limit Caption Length', 'foogallery' ),
					'desc'    => __( 'You can limit the length of caption title and descriptions in the thumbnails. This will NOT limit the length of captions from within the lightbox.', 'foogallery' ),
					'section' => __( 'Captions', 'foogallery' ),
					'default' => '',
					'type'    => 'radio',
					'spacer'  => '<span class="spacer"></span>',
					'choices' => array(
						'' => __( 'No', 'foogallery' ),
						'yes' => __( 'Yes', 'foogallery' ),
					),
					'row_data'=> array(
						'data-foogallery-change-selector' => 'input:radio',
						'data-foogallery-preview' => 'shortcode',
						'data-foogallery-value-selector'  => 'input:checked',
					)
				);

				$fields[] = array(
					'id'      => 'caption_title_length',
					'title'   => __( 'Max Title Length', 'foogallery' ),
					'desc'	  => __( 'A max length of zero will not apply a limit.', 'foogallery '),
					'section' => __( 'Captions', 'foogallery' ),
					'type'    => 'number',
					'class'   => 'small-text',
					'default' => 0,
					'step'    => '1',
					'min'     => '0',
					'row_data' => array(
						'data-foogallery-change-selector'       => 'input',
						'data-foogallery-hidden'                => true,
						'data-foogallery-show-when-field'       => 'captions_limit_length',
						'data-foogallery-show-when-field-value' => 'yes',
						'data-foogallery-preview'               => 'shortcode'
					)
				);

				$fields[] = array(
					'id'      => 'caption_desc_length',
					'title'   => __( 'Max Desc Length', 'foogallery' ),
					'desc'	  => __( 'A max length of zero will not apply a limit.', 'foogallery '),
					'section' => __( 'Captions', 'foogallery' ),
					'type'    => 'number',
					'class'   => 'small-text',
					'default' => 0,
					'step'    => '1',
					'min'     => '0',
					'row_data' => array(
						'data-foogallery-change-selector'       => 'input',
						'data-foogallery-hidden'                => true,
						'data-foogallery-show-when-field'       => 'captions_limit_length',
						'data-foogallery-show-when-field-value' => 'yes',
						'data-foogallery-preview'               => 'shortcode'
					)
				);
				//endregion

			}
			return $fields;
		}

		/**
		 * Build up the gallery class attribute for the common fields
		 *
		 * @param $classes array
		 * @param $gallery FooGallery
		 *
		 * @return array
		 */
		function add_common_fields_class_attributes( $classes, $gallery ) {
			$template_data = foogallery_get_gallery_template( $gallery->gallery_template );

			//check the template supports common fields
			if ( $template_data && array_key_exists( 'common_fields_support', $template_data ) && true === $template_data['common_fields_support'] ) {

				//add the gallery template core class
				$classes[] = 'fg-' . $gallery->gallery_template;

				//get some default classes from common gallery settings
				$classes[] = $gallery->get_setting( 'theme', 'fg-light' );
				$classes[] = $gallery->get_setting( 'border_size', 'fg-border-thin' );
				$classes[] = $gallery->get_setting( 'rounded_corners', '' );
				$classes[] = $gallery->get_setting( 'drop_shadow', 'fg-shadow-outline' );
				$classes[] = $gallery->get_setting( 'inner_shadow', '' );
				$classes[] = $gallery->get_setting( 'loading_icon', 'fg-loading-default' );
				$classes[] = $gallery->get_setting( 'loaded_effect', 'fg-loaded-fade-in' );

				$caption_preset = $gallery->get_setting( 'hover_effect_preset', 'fg-custom' );

				$classes[] = $caption_preset;

				if ( 'fg-custom' === $caption_preset ) {
					//only set these caption classes if custom preset is selected
					$classes[] = $gallery->get_setting( 'hover_effect_color', '' );
					$classes[] = $gallery->get_setting( 'hover_effect_scale', '' );
					$classes[] = $gallery->get_setting( 'hover_effect_caption_visibility', 'fg-caption-hover' );
					$classes[] = $gallery->get_setting( 'hover_effect_transition', 'fg-hover-fade' );
					$classes[] = $gallery->get_setting( 'hover_effect_icon', 'fg-hover-zoom' );
				} else if ( strpos( $caption_preset, 'fg-preset' ) !== false ) {
					//set a preset size
					$classes[] = $gallery->get_setting( 'hover_effect_preset_size', 'fg-preset-small' );
				}
			}

			return $classes;
		}

		/**
		 * Add the required data options for captions
		 *
		 * @param $options
		 * @param $gallery    FooGallery
		 *
		 * @param $attributes array
		 *
		 * @return array
		 */
		function add_caption_data_options($options, $gallery, $attributes) {
			$template_data = foogallery_get_gallery_template( $gallery->gallery_template );

			//check the template supports common fields
			if ( $template_data && array_key_exists( 'common_fields_support', $template_data ) && true === $template_data['common_fields_support'] ) {

				$caption_title = foogallery_gallery_template_setting( 'caption_title_source', '' );
				$caption_desc  = foogallery_gallery_template_setting( 'caption_desc_source', '' );

				$options['item']['showCaptionTitle']       = $caption_title != 'none';
				$options['item']['showCaptionDescription'] = $caption_desc != 'none';

				$captions_limit_length = foogallery_gallery_template_setting( 'captions_limit_length', '' );

				if ( 'yes' === $captions_limit_length ) {
					$caption_title_length                    = foogallery_gallery_template_setting( 'caption_title_length', '0' );
					$caption_desc_length                     = foogallery_gallery_template_setting( 'caption_desc_length', '0' );
					$options['item']['maxCaptionLength']     = intval( $caption_title_length );
					$options['item']['maxDescriptionLength'] = intval( $caption_desc_length );
				}
			}
			return $options;
		}

		/**
		 * Build up a arguments used in the preview of the gallery
		 * @param $args
		 * @param $post_data
		 * @param $template
		 *
		 * @return mixed
		 */
		function preview_arguments( $args, $post_data, $template ) {
			$args['caption_title_source'] = $post_data[FOOGALLERY_META_SETTINGS][$template . '_caption_title_source'];
			$args['caption_desc_source'] = $post_data[FOOGALLERY_META_SETTINGS][$template . '_caption_desc_source'];
			$args['captions_limit_length'] = $post_data[FOOGALLERY_META_SETTINGS][$template . '_captions_limit_length'];
			$args['caption_title_length'] = $post_data[FOOGALLERY_META_SETTINGS][$template . '_caption_title_length'];
			$args['caption_desc_length'] = $post_data[FOOGALLERY_META_SETTINGS][$template . '_caption_desc_length'];
			return $args;
		}

		/**
		 * Build up the gallery data attributes for the common fields
		 *
		 * @param $attributes array
		 * @param $gallery FooGallery
		 *
		 * @return array
		 */
		function add_common_fields_data_attribute( $attributes, $gallery ) {
			$template_data = foogallery_get_gallery_template( $gallery->gallery_template );

			//check the template supports common fields
			if ( $template_data && array_key_exists( 'common_fields_support', $template_data ) && true === $template_data['common_fields_support'] ) {
				$attributes['data-fg-common-fields'] = true;
			}

			return $attributes;
		}
	}
}