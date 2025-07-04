# Gravity Forms Slider Field

A WordPress plugin that adds a customizable slider field to Gravity Forms.

## Description

This plugin extends Gravity Forms by adding a new "Slider" field type under Advanced Fields. It provides a modern, interactive slider input that's perfect for collecting numeric values like prices, quantities, ratings, or any other numeric input where a slider interface would enhance user experience.

## Features

- Modern, responsive slider interface using noUiSlider
- Configurable minimum and maximum values
- Adjustable step size
- Customizable prefix and suffix labels
- Support for money formatting with thousand separators
- Conditional logic support
- Real-time value updates
- Fully integrated with Gravity Forms

## Installation

1. Upload the plugin files to `/wp-content/plugins/gf-range-slider`
2. Ensure Gravity Forms is installed and activated
3. Activate the plugin through the WordPress 'Plugins' screen
4. The slider field will appear under "Advanced Fields" when editing a form

## Usage

1. Edit or create a form in Gravity Forms
2. Look for "Slider" under the "Advanced Fields" section
3. Drag the Slider field onto your form
4. Configure the field settings:
   - Set minimum and maximum values
   - Adjust the step size
   - Choose the value format (Regular Number or Money)
   - Add prefix (e.g., $, £, €) and/or suffix (e.g., points, days)
   - Configure other standard field settings like label, description, etc.

### Field Settings

- **Slider Minimum**: The lowest value the slider can select
- **Slider Maximum**: The highest value the slider can select
- **Slider Step**: The increment between values
- **Value Format**: Choose between:
  - Regular Number: Displays numbers without formatting
  - Money: Adds thousand separators (e.g., 1,000,000)
- **Prefix**: Text or symbol to display before the value
- **Suffix**: Text to display after the value

## Requirements

- WordPress 5.0 or higher
- Gravity Forms 2.5 or higher
- Modern web browser with JavaScript enabled

## Support

For support, bug reports, or feature requests, please use the GitHub issue tracker.

## Credits

- Developed by Digital Roo
- Uses [noUiSlider](https://refreshless.com/nouislider/) for the slider interface

## License

This plugin is licensed under the GPL v2 or later.

```
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.
