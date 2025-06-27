<?php
if ( ! class_exists( 'GF_Field' ) ) {
    return;
}

class GF_Field_Slider extends GF_Field {

    // Field type slug
    public $type = 'slider';

    // Name shown in the editor palette
    public function get_form_editor_field_title() {
        return esc_attr__( 'Slider', 'gravityforms' );
    }

    // Place under Advanced Fields
    public function get_form_editor_button() {
        return [
            'group' => 'advanced_fields',
            'text'  => $this->get_form_editor_field_title(),
        ];
    }

    // Icon in the editor
    public function get_form_editor_icon() {
        return '<i class="dashicons dashicons-slides"></i>';
    }
    
    // Enable conditional logic support
    public function is_conditional_logic_supported() {
        return true;
    }

    // Define which settings should appear in the field editor
    public function get_form_editor_field_settings() {
        return array(
            'conditional_logic_field_setting',
            'error_message_setting',
            'label_setting',
            'admin_label_setting',
            'size_setting',
            'rules_setting',
            'visibility_setting',
            'duplicate_setting',
            'default_value_setting',
            'placeholder_setting',
            'description_setting',
            'css_class_setting',
            'label_placement_setting',
            'slider_min_setting',
            'slider_max_setting',
            'slider_step_setting',
            'slider_prefix_setting',
            'slider_suffix_setting',
            'slider_format_setting',
            'default_value_setting',
        );
    }
    // Front-end markup
    public function get_field_input( $form, $value = '', $entry = null ) {
        // Pull saved settings or use defaults
        $min  = property_exists( $this, 'sliderMin'  ) && is_numeric( $this->sliderMin  ) ? $this->sliderMin  : 0;
        $max  = property_exists( $this, 'sliderMax'  ) && is_numeric( $this->sliderMax  ) ? $this->sliderMax  : 100;
        $step = property_exists( $this, 'sliderStep' ) && is_numeric( $this->sliderStep ) ? $this->sliderStep : 1;
        $prefix = property_exists( $this, 'sliderPrefix' ) ? esc_html( $this->sliderPrefix ) : '';
        $suffix = property_exists( $this, 'sliderSuffix' ) ? esc_html( $this->sliderSuffix ) : '';
        $format = property_exists( $this, 'sliderFormat' ) ? esc_attr( $this->sliderFormat ) : 'number';

        $field_id = "input_{$form['id']}_{$this->id}";
        $name     = "input_{$this->id}";
        $val      = $value !== '' ? esc_attr( $value ) : esc_attr( $min );

        // Format initial value based on format setting
        $display_val = $val;
        if ($format === 'money') {
            $display_val = number_format((int)$val, 0, '.', ',');
        }

        // Build the slider markup
        $html = "<div id='gf-nouislider' class='gf-slider-container'>";
        $html .= "<legend class='gfield-slider-value gfield_label gform-field-label'>{$prefix}&nbsp;{$display_val}&nbsp;{$suffix}</legend>";
        
        // Hidden input for form submission
        $html .= "<input type='hidden' name='{$name}' class='gf-slider-input' value='{$val}' />";
        
        // noUiSlider div with data attributes
        $html .= "<div class='gf-slider' 
            data-min='{$min}' 
            data-max='{$max}' 
            data-step='{$step}' 
            data-value='{$val}' 
            data-prefix='{$prefix}' 
            data-suffix='{$suffix}'
            data-format='{$format}'></div>";
        
        $html .= "</div>";

        return $html;
    }

    //////////////////////////////
    // 1) Add Settings Markup  //
    //////////////////////////////

    // Hook into the Field Settings panel
    public static function add_standard_settings( $position ) {
        // 1500 places it after the Default Value box in the Standard tab
        if ( $position === 1500 ) {
            ?>
            <li class="slider_min_setting field_setting">
                <label for="slider_min"><?php esc_html_e( 'Slider Minimum', 'gravityforms' ); ?></label>
                <input type="number" id="slider_min" onkeyup="SetFieldProperty('sliderMin', this.value);" />
            </li>
            <li class="slider_max_setting field_setting">
                <label for="slider_max"><?php esc_html_e( 'Slider Maximum', 'gravityforms' ); ?></label>
                <input type="number" id="slider_max" onkeyup="SetFieldProperty('sliderMax', this.value);" />
            </li>
            <li class="slider_step_setting field_setting">
                <label for="slider_step"><?php esc_html_e( 'Slider Step', 'gravityforms' ); ?></label>
                <input type="number" id="slider_step" onkeyup="SetFieldProperty('sliderStep', this.value);" />
            </li>
            <li class="slider_format_setting field_setting">
                <label for="slider_format"><?php esc_html_e( 'Value Format', 'gravityforms' ); ?></label>
                <select id="slider_format" onchange="SetFieldProperty('sliderFormat', this.value);">
                    <option value="number"><?php esc_html_e( 'Regular Number', 'gravityforms' ); ?></option>
                    <option value="money"><?php esc_html_e( 'Money (with thousand separators)', 'gravityforms' ); ?></option>
                </select>
            </li>
            <li class="slider_prefix_setting field_setting">
                <label for="slider_prefix"><?php esc_html_e( 'Prefix (e.g. $ or months)', 'gravityforms' ); ?></label>
                <input type="text" id="slider_prefix" onkeyup="SetFieldProperty('sliderPrefix', this.value);" />
            </li>
            <li class="slider_suffix_setting field_setting">
                <label for="slider_suffix"><?php esc_html_e( 'Suffix (e.g. months)', 'gravityforms' ); ?></label>
                <input type="text" id="slider_suffix" onkeyup="SetFieldProperty('sliderSuffix', this.value);" />
            </li>
            <?php
        }
    }

    //////////////////////////////
    // 2) Editor JS for Defaults //
    //////////////////////////////

    public static function editor_js() {
        ?>
        <script type="text/javascript">
            // Insert our new settings into the Standard Settings list for Slider fields
            // fieldSettings.slider =
            //     '.admin_label_setting, .error_message_setting, .label_setting, .description_setting, .slider_min_setting, .slider_max_setting, .slider_step_setting, .slider_prefix_setting, .slider_suffix_setting, .css_class_setting, .conditional_logic_field_setting, .rules_setting';

            // Populate the inputs when a field is clicked
            jQuery(document).on('gform_load_field_settings', function(event, field, form){
                jQuery('#slider_min').val(field.sliderMin  || 0);
                jQuery('#slider_max').val(field.sliderMax  || 100);
                jQuery('#slider_step').val(field.sliderStep || 1);
                jQuery('#slider_prefix').val(field.sliderPrefix || '');
                jQuery('#slider_suffix').val(field.sliderSuffix || '');
                jQuery('#slider_format').val(field.sliderFormat || 'number');
            });
        </script>
        <?php
    }
}

// Register class & hooks
GF_Fields::register( new GF_Field_Slider() );
add_action( 'gform_field_standard_settings', [ 'GF_Field_Slider', 'add_standard_settings' ], 10, 1 );
add_action( 'gform_editor_js',              [ 'GF_Field_Slider', 'editor_js' ] );
