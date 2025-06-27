document.addEventListener('DOMContentLoaded', function() {
    // Find all slider containers
    document.querySelectorAll('.gf-slider-container').forEach(function(container) {
        const slider = container.querySelector('.gf-slider');
        const valueDisplay = container.querySelector('.gfield-slider-value');
        const hiddenInput = container.querySelector('.gf-slider-input');
        
        if (!slider || !valueDisplay || !hiddenInput) return;

        // Get slider settings
        const min = parseFloat(slider.getAttribute('data-min'));
        const max = parseFloat(slider.getAttribute('data-max'));
        const step = parseFloat(slider.getAttribute('data-step'));
        const prefix = slider.getAttribute('data-prefix') || '';
        const suffix = slider.getAttribute('data-suffix') || '';
        const format = slider.getAttribute('data-format') || 'number';
        const value = parseFloat(slider.getAttribute('data-value')) || min;

        // Create noUiSlider
        noUiSlider.create(slider, {
            start: value,
            connect: 'lower',
            step: step,
            range: {
                'min': min,
                'max': max
            },
            format: {
                to: function(value) {
                    return Math.round(value);
                },
                from: function(value) {
                    return parseFloat(value);
                }
            }
        });

        // Update the display and hidden input when slider changes
        slider.noUiSlider.on('update', function(values, handle) {
            let displayValue = Math.round(values[handle]);
            
            // Format based on the format setting
            if (format === 'money') {
                displayValue = new Intl.NumberFormat('en-GB', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0,
                    useGrouping: true
                }).format(displayValue);
            }

            // Update display and hidden input
            valueDisplay.textContent = `${prefix} ${displayValue} ${suffix}`.trim();
            hiddenInput.value = displayValue;

            // Trigger change event for Gravity Forms
            const event = new Event('change', { bubbles: true });
            hiddenInput.dispatchEvent(event);
        });
    });
}); 