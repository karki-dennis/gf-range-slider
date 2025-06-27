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

        // Add CSS styles for the slider progress
        $html = "
        <style>
            .gf-slider {
                -webkit-appearance: none;
                width: 100%;
                height: 8px !important;
                border-radius: 4px;
                background: #ddd;
                outline: none;
                position: relative;
            }
            

            .gf-slider-progress {
                width: 0%;
                height: 8px !important;
                border-radius: 4px;
                position: absolute;
                background: #0271c2;
                pointer-events: none;
                z-index: 1;
                left: 0;
                top: 0;
            }
        </style>";

        $field_id = "input_{$form['id']}_{$this->id}";
        $name     = "input_{$this->id}";
        $val      = $value !== '' ? esc_attr( $value ) : esc_attr( $min );

        // Format initial value if prefix is GBP
        $display_val = $val;
        if ($prefix === '£' || strtoupper($prefix) === 'GBP') {
            $display_val = number_format((int)$val, 0, '.', ',');
        }

        // Calculate initial progress percentage
        $initial_progress = (((float)$val - (float)$min) / ((float)$max - (float)$min)) * 100;

        // Build the slider + live value display
        $html .= "<legend id='slider_value_{$field_id}' class='gfield-slider-value gfield_label gform-field-label'>{$prefix}&nbsp;{$display_val}&nbsp;{$suffix}</legend>";
        $html .= "<div style='position: relative; width: 100%;'>";
        $html .= "<div id='progress_{$field_id}' class='gf-slider-progress' style='width: {$initial_progress}%;'></div>";
        $html .= "<input type='range' class='gf-slider' id='{$field_id}' name='{$name}' value='{$val}' min='{$min}' max='{$max}' step='{$step}' />";
        $html .= "</div>";
        $html .= "
                <script>
                    jQuery(function($){
                        var slider = $('#{$field_id}');
                        var progress = $('#progress_{$field_id}');
                        
                        // Function to update progress
                        function updateProgress(value) {
                            var min = parseFloat(slider.attr('min'));
                            var max = parseFloat(slider.attr('max'));
                            var percentage = ((value - min) / (max - min)) * 100;
                            requestAnimationFrame(function() {
                                progress.css('width', percentage + '%');
                            });
                        }

                        slider.on('input change', function(){
                            var prefix = '" . addslashes($prefix) . "';
                            var suffix = '" . addslashes($suffix) . "';
                            var value = this.value;
                            
                            // Update progress bar
                            updateProgress(value);

                            // GBP formatting if prefix is £ or GBP
                            if (prefix === '£' || prefix.toUpperCase() === 'GBP') {
                                value = new Intl.NumberFormat('en-GB', { 
                                    minimumFractionDigits: 0,
                                    maximumFractionDigits: 0,
                                    useGrouping: true
                                }).format(value);
                            }
                            
                            $('#slider_value_{$field_id}').text(prefix + ' ' + value + ' ' + suffix);

                            // Trigger the gform_input_change event that conditional logic depends on
                            if (typeof gf_input_change === 'function') {
                                gf_input_change(this, {$form['id']}, {$this->id});
                            }
                        });

                        // Initialize progress on load
                        updateProgress(slider.val());
                    });
                </script>";

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
            });
        </script>
        <?php
    }
}

// Register class & hooks
GF_Fields::register( new GF_Field_Slider() );
add_action( 'gform_field_standard_settings', [ 'GF_Field_Slider', 'add_standard_settings' ], 10, 1 );
add_action( 'gform_editor_js',              [ 'GF_Field_Slider', 'editor_js' ] );
