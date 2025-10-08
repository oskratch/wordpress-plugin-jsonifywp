# JsonifyWP

<img alt="WordPress" src="https://img.shields.io/badge/WordPress-5.0+-blue.svg"> <img alt="PHP" src="https://img.shields.io/badge/PHP-7.4+-purple.svg"> <img alt="MySQL" src="https://img.shields.io/badge/MySQL-5.6+-orange.svg"> <img alt="License" src="https://img.shields.io/badge/License-GPL v2-green.svg">

JsonifyWP is a WordPress plugin that lets you manage custom API endpoints—each with its own title, language, API URL (endpoint), templates, and detail page settings—stored in a dedicated database table. You can display data from remote JSON APIs on your site using flexible templates.

## Features

- **Admin interface** to manage endpoints with full configuration options
- **Custom database table** (`wp_jsonifywp`) to store all endpoint configurations
- **Multiple templates** for list and detail views (selectable per endpoint)
- **Flexible shortcodes** to display API data using the selected template
- **Template system** easily extensible with your own PHP templates
- **Navigation support** between list and detail pages
- **Smart URL handling** - shortcodes automatically read parameters from the URL
- **Two operation modes**: full list-to-detail navigation or list-only with API pagination
- **Endpoint duplication** for quick setup of similar configurations
- **Multilingual support** with translation files in the `languages` folder

## Installation

1. Upload the `jsonifywp` folder to your WordPress `wp-content/plugins/` directory.
2. Ensure the `templates/list/` and `templates/detail/` subfolders exist and contain at least one template each (e.g., `default.php` and `default_detail.php`).
3. Activate the plugin from the WordPress admin panel.
4. Go to the JsonifyWP menu in the admin sidebar to add and manage endpoints.

## Configuration

### Endpoint Fields

When creating or editing an endpoint, you can configure:

- **Title**: Display name for the endpoint
- **Language**: For display purposes (language parameters should be included in the API URL if required)
- **API domain**: Optional base domain to prepend to detail URLs if they are relative
- **API URL**: Main list endpoint (do not include `page` or `limit` parameters, as these are handled automatically)
- **List template**: Choose from available templates in `templates/list/`
- **Detail template**: Choose from `templates/detail/` templates, or select **No detail page** for list-only mode
- **Detail page URL**: Required only for detail mode - relative URL to the WordPress page containing `[jsonifywp_detail]`
- **Detail API field**: Required only for detail mode - JSON field name containing the detail API URL

### Operation Modes

**Detail Mode (default):**
- Each list item can link to a detail page showing expanded information
- Requires a second API call to fetch detailed data
- Suitable for member directories, product catalogs, etc.

**List-only Mode:**
- Select "No detail page" as the detail template
- Displays only a paginated list with automatic API-driven pagination
- The plugin automatically adds `page` and `limit` parameters to API requests
- Ideal for publications, news feeds, or simple data lists

### Global Settings

Configure global options from JsonifyWP > Settings:

- **Items per page**: Number of items to display per page for list-only endpoints

### List-only API Response Format

For list-only endpoints, your API should return JSON in this format:

```json
{
  "total": 42,
  "page": 1,
  "limit": 10,
  "items": [
    "Information A...",
    "Information B...",
    "Information C...",
    "..."
  ]
}
```

- **total**: Total number of available items
- **page**: Current page number
- **limit**: Number of items per page
- **items**: Array of items for the current page

## Usage

### Shortcodes

**Display a List:**
```
[jsonifywp id="1"]
```
Or use the alias format:
```
[jsonifywp-1]
```

**Display a Detail Page:**
```
[jsonifywp_detail]
```

You can find the correct shortcode for each endpoint in the admin endpoints table, ready to copy and paste.

### Setting up Detail Pages

1. Create a WordPress page (e.g., `/detail/`) and add the `[jsonifywp_detail]` shortcode
2. In JsonifyWP admin, set the **Detail page URL** field to match your page slug (e.g., `detail`)
3. The plugin automatically handles URL parameters like `/detail/?jsonifywp_id=1&item=2`

### Navigation Flow

- List templates use `$item_obj->detail_page_url` to generate correct detail page links
- The plugin fetches the main API, extracts the detail URL from the configured JSON field
- Makes a second API call to fetch and display detailed information

## Templates

### Creating Custom Templates

- **List templates**: Place in `templates/list/` directory
- **Detail templates**: Place in `templates/detail/` directory
- Templates are standard PHP files with access to API data variables
- Select templates per endpoint in the admin interface

### Available Variables in Templates

- `$json`: Decoded API response data
- `$item_obj`: Full endpoint configuration object
- `$type_id`: Current endpoint ID

## Advanced Features

### Duplicating Endpoints

Quickly duplicate any endpoint from the admin endpoints list. Click the "Duplicate" action to create a new endpoint with the same configuration (title will have "(copy)" appended). This makes it easy to create similar endpoints without re-entering all details.

### Accessing Configuration in Code

**Get items per page setting:**
```php
$items_per_page = get_option('jsonifywp_items_per_page', 5);
```

**Access from JavaScript (if loaded):**
```js
const itemsPerPage = window.jsonifywp_vars?.itemsPerPage || 5;
```

## License

This plugin is licensed under the GPLv2 or later. See [LICENSE](LICENSE) for details.

---

**Developed by Oscar Periche. Contributions welcome!**