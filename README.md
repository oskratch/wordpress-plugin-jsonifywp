# JsonifyWP

JsonifyWP is a WordPress plugin that allows you to manage custom entries stored in a dedicated database table and display remote JSON API data on your site using flexible templates.

## Features

- Custom admin interface for managing entries (title, language, API URL, template)
- Stores entries in a custom database table (`wp_jsonifywp`)
- Supports multiple display templates (selectable per entry)
- Shortcode to display API data using the selected template
- Easily extendable with your own templates

## Installation

1. Upload the `jsonifywp` folder to your WordPress `wp-content/plugins/` directory.
2. Ensure the `templates` subfolder exists and contains at least one template (e.g., `default.php`).
3. Activate the plugin from the WordPress admin panel.
4. Go to the JsonifyWP menu in the admin sidebar to add and manage entries.

## Usage

Add the following shortcode to any page or post to display data using a specific entry:

```
[jsonifywp id="1"]
```


## Creating Templates

- Place your template files in the `templates` folder inside the plugin directory.
- Templates are standard PHP files and receive a `$json` variable containing the decoded JSON data from the API.
- Select the desired template when creating or editing an entry in the admin.

## License

This plugin is licensed under the GPLv2 or later. See [LICENSE](LICENSE) for details.

---

**Developed by Oscar Periche. Contributions welcome!**