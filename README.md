# JsonifyWP

JsonifyWP is a WordPress plugin that allows you to manage custom entries stored in a dedicated database table and display remote JSON API data on your site using flexible templates.

## Features

- Custom admin interface for managing entries (title, language, API URL, list template, detail template, detail page URL, detail API field)
- Stores entries in a custom database table (`wp_jsonifywp`)
- Supports multiple display templates for both list and detail views (selectable per entry)
- Shortcode to display API data using the selected template
- Easily extendable with your own templates (separate folders for list and detail)
- Each list entry can link to a detail page, showing expanded info from a secondary API
- Fully configurable: you can set which JSON field contains the detail API URL for each entry
- Shortcodes automatically read parameters from the URL for seamless navigation

## Installation

1. Upload the `jsonifywp` folder to your WordPress `wp-content/plugins/` directory.
2. Ensure the `templates/list/` and `templates/detail/` subfolders exist and contain at least one template each (e.g., `default.php` and `default_detail.php`).
3. Activate the plugin from the WordPress admin panel.
4. Go to the JsonifyWP menu in the admin sidebar to add and manage entries.

## Usage

### Display a List

Add the following shortcode to any page or post to display data using a specific entry:

```
[jsonifywp id="1"]
```

Or, as an alias (also supported):

```
[jsonifywp-1]
```

### Display a Detail Page

1. Create a WordPress page (e.g., `/detail/` or `/empleats/`) and add this shortcode:
    ```
    [jsonifywp_detail]
    ```
2. JsonifyWP will automatically use the `jsonifywp_id` and `item` parameters from the URL, e.g.:
    ```
    /empleats/?jsonifywp_id=1&item=2
    ```

### How navigation works

- In your list template, use the `$item_obj->detail_page_url` property to generate the correct link to the detail page for each entry, passing the required parameters.
- The plugin will fetch the main API, extract the detail API URL from the configured field (e.g., `employee_profile`), and fetch the detail JSON for display.

## Creating Templates

- Place your list templates in `templates/list/` and your detail templates in `templates/detail/` inside the plugin directory.
- You can create as many templates as you want for both list and detail views, and select them per entry in the admin interface.

## Configuration fields per entry

When creating or editing an entry, you can configure:
- **Title**
- **Language**
- **API URL** (main list)
- **List template** (from `templates/list/`)
- **Detail template** (from `templates/detail/`)
- **Detail page URL** (relative URL to the WordPress page with `[jsonifywp_detail]`)
- **Detail API field** (the JSON field in the list that contains the detail API URL, e.g. `employee_profile`)

## License

This plugin is licensed under the GPLv2 or later. See [LICENSE](LICENSE) for details.

---

**Developed by Oscar Periche. Contributions welcome!**