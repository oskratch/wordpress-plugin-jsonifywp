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
- Option to configure endpoints as "list-only" (no detail page), enabling API-driven pagination

## Multilingual Support

JsonifyWP now supports multiple languages. If your desired language file does not exist, you can create it in the `languages` folder.

## Installation

1. Upload the `jsonifywp` folder to your WordPress `wp-content/plugins/` directory.
2. Ensure the `templates/list/` and `templates/detail/` subfolders exist and contain at least one template each (e.g., `default.php` and `default_detail.php`).
3. Activate the plugin from the WordPress admin panel.
4. Go to the JsonifyWP menu in the admin sidebar to add and manage endpoints.

## Usage

### Display a List

Add the following shortcode to any page or post to display data from a specific endpoint. For example, if your listing page is called `members`, you can use the shortcode below (replace `1` with your actual endpoint's ID). You can find the correct shortcode for each endpoint in the endpoints table in the admin area, ready to copy and paste.

```
[jsonifywp id="1"]
```

Or, as an alias (also supported):

```
[jsonifywp-1]
```

### Display a Detail Page

1. Create a WordPress page (e.g., `/detail/`) and add this shortcode:
    ```
    [jsonifywp_detail]
    ```
2. In the JsonifyWP admin, set the **Detail page URL** field for each API endpoint to match the slug of this detail page (e.g., `detail`). This tells the plugin where to link for detail views.
3. JsonifyWP will automatically use the `jsonifywp_id` and `item` parameters from the URL, for example:
    ```
    /detail/?jsonifywp_id=1&item=2
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
- **API URL** (main list; do not include `page` or `limit` parameters, as these are handled automatically by the plugin)
- **List template** (from `templates/list/`)
- **Detail template** (from `templates/detail/`, or select **No detail page** if you only want a paginated list)
- **Detail page URL** (only required if a detail template is selected; hidden and not required if "No detail page" is chosen)
- **Detail API field** (only required if a detail template is selected; hidden and not required if "No detail page" is chosen)

### List-only Endpoints and API Pagination

If you select **No detail page** as the detail template when creating or editing an endpoint, the fields **Detail page URL** and **Detail API field** will be hidden and are not required. In this mode, the endpoint will only display a paginated list, and the plugin will automatically add `page` and `limit` parameters to the API request for pagination.

The API should return a JSON response like:

```json
{
  "total": 42,
  "page": 1,
  "limit": 10,
  "items": [
    "Information A...",
    "Information B...",
    "Information C...",
    "Information D...",
    "Information E...",
    "Information F...",
    "Information G...",
    "Information H...",
    "Information I...",
    "Information J..."
  ]
}
```

- **total**: total number of available items.
- **page**: current page number.
- **limit**: number of items per page (taken from the plugin’s global setting).
- **items**: array of items to display for the current page.

The plugin will automatically display pagination controls and handle navigation, passing the correct parameters to the API.

> This feature is ideal for simple lists, such as publications or news, where no detail page is required for each item.

### Configuration: Items per page

The number of items per page can be configured from the JsonifyWP > Settings menu.

To access this value from PHP:
```php
$items_per_page = get_option('jsonifywp_items_per_page', 5);
```

To access this value from JS (if the plugin loads the variable):
```js
const itemsPerPage = window.jsonifywp_vars?.itemsPerPage || 5;
```

## License

This plugin is licensed under the GPLv2 or later. See [LICENSE](LICENSE) for details.

---

**Developed