# JsonifyWP
JsonifyWP is a WordPress plugin that lets you manage custom API endpoints—each with its own title, language, API URL (endpoint), templates, and detail page settings—stored in a dedicated database table. You can display data from remote JSON APIs on your site using flexible templates.

## Features

- Admin interface to manage endpoints (add/edit title, language, API URL [endpoint], list template, detail template, detail page URL, detail API field)
- Stores endpoints in a dedicated table (`wp_jsonifywp`)
- Supports multiple templates for list and detail views (selectable per endpoint)
- Shortcode to display API data using the selected template
- Easily extensible with your own templates (separate folders for list and detail)
- Each list entry can link to its detail page
- Fully configurable: you can set which JSON field contains the detail API URL for each endpoint
- Shortcodes automatically read parameters from the URL for seamless navigation

## Multilingual Support

JsonifyWP now supports multiple languages. If your desired language file does not exist, you can create it in the `languages` folder.

## Installation

1. Upload the `jsonifywp` folder to your WordPress `wp-content/plugins/` directory.
2. Ensure the `templates/list/` and `templates/detail/` subfolders exist and contain at least one template each (e.g., `default.php` and `default_detail.php`).
3. Activate the plugin from the WordPress admin panel.
4. Go to the JsonifyWP menu in the admin sidebar to add and manage endpoints.

## Usage

### Display a List

Add the following shortcode to any page or post to display data from a specific endpoint. The example below uses `1` as the endpoint ID—replace it with your actual endpoint's ID. You can find the correct shortcode for each endpoint in the endpoints table in the admin area, ready to copy and paste.


```
[jsonifywp id="1"]
```

Or, as an alias (also supported):

```
[jsonifywp-1]
```

### Display a Detail Page

1. Create a WordPress page (e.g., `/detail/` or `/employees/`) and add this shortcode:
    ```
    [jsonifywp_detail]
    ```
2. In the JsonifyWP admin, make sure to set the **Detail page URL** field for each API endpoint to match the slug of this detail page (e.g., `detail` or `employees`). This tells the plugin where to link for detail views.
3. JsonifyWP will automatically use the `jsonifywp_id` and `item` parameters from the URL, for example:
    ```
    /employees/?jsonifywp_id=1&item=2
    ```

### How navigation works

- In your list template, use the `$item_obj->detail_page_url` property to generate the correct link to the detail page for each entry, passing the required parameters.
- The plugin will fetch the main API, extract the detail API URL from the configured field (e.g., `employee_profile`), and fetch the detail JSON for display.

## Creating Templates

- Place your list templates in `templates/list/` and your detail templates in `templates/detail/` inside the plugin directory.
- You can create as many templates as you want for both list and detail views, and select them per endpoint in the admin interface.

## Configuration fields per endpoint

When creating or editing an endpoint, you can configure:
- **Title**
- **Language** (for display purposes only; currently, language selection does not affect API requests. The API URL itself should include any language parameters required by your API, if supported.)
- **API domain** (optional base domain to prepend to detail URLs if the URLs returned in the list are relative or missing the domain)
- **API URL** (main list)
- **List template** (from `templates/list/`)
- **Detail template** (from `templates/detail/`)
- **Detail page URL** (relative URL to the WordPress page with `[jsonifywp_detail]`)
- **Detail API field** (the JSON field in the list that contains the detail API URL, e.g. `employee_profile`)

## License

This plugin is licensed under the GPLv2 or later. See [LICENSE](LICENSE) for details.

---

**Developed by Oscar Periche. Contributions welcome!**