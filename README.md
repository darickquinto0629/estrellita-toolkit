# Estrellita Toolkit

Private maintenance repository for the **Estrellita Toolkit** custom WordPress plugin.

## Overview

This repository tracks bug fixes, enhancements, and version history for the Estrellita Toolkit WordPress plugin. It serves as the central location for managing and deploying updates to the plugin codebase.

## Repository Purpose

- **Bug Fixes**: Track and resolve issues reported in production
- **Enhancements**: Develop and test new features and improvements
- **Version Control**: Maintain complete version history and release management
- **Collaboration**: Enable organized development workflow and code reviews

## Current Work

### Active Issues

- **CSV Order Imports - Date Formatting Issue**: Resolving the `_last_completed` date formatting issue that affects CSV order imports. This issue impacts data consistency and import reliability.

## Repository Structure

```
estrellita-toolkit/
├── class-gtt-api.php                    # GTT API integration
├── class-gtt-oauth2.php                 # GTT OAuth2 authentication
├── class-gtw-oauth2.php                 # GTW OAuth2 authentication
├── class-zoom-api.php                   # Zoom API integration
├── estrellita-toolkit.php                # Main plugin file
├── estrellita-gtt-list.php              # GTT list functionality
├── estrellita-meta-fields.php           # Custom meta fields
├── estrellita-notifications.php         # Notification system
├── estrellita-redux.php                 # Redux integration
├── estrellita-shopmanager.php           # Shop manager functionality
├── functions.php                        # General functions
├── gtt3.php                             # GTT v3 integration
├── gtw-bulk.php                         # GTW bulk operations
├── membership.php                       # Membership functionality
├── post_types.php                       # Custom post types
├── school-districts.php                 # School districts management
├── shortcodes.php                       # Custom shortcodes
├── woocommerce.php                      # WooCommerce integration
├── woocommerce-forms.php                # WooCommerce forms
├── woocommerce-invoiced.php             # WooCommerce invoicing
├── zoom-bulk.php                        # Zoom bulk operations
├── img/                                 # Image assets
├── includes/                            # Additional includes
├── js/                                  # JavaScript files
└── templates/                           # Email and template files
```

## Installation

This is a WordPress plugin. To use:

1. Clone this repository into your WordPress `wp-content/plugins/` directory
2. Activate the plugin through the WordPress admin dashboard
3. Configure plugin settings as needed

## License & Visibility

This repository is **private** and intended for internal maintenance only.

## Contact

For questions or to report issues, please use the repository issues tracker.
