# JsonifyWP

![WordPress](https://img.shields.io/badge/WordPress-6.3%2B-blue.svg) ![PHP](https://img.shields.io/badge/PHP-8%2B-purple.svg) ![MySQL](https://img.shields.io/badge/MySQL-5.6%2B-orange.svg) ![License](https://img.shields.io/badge/License-GPL%20v2-green.svg)

JsonifyWP is a WordPress plugin that lets you manage custom API endpoints—each with its own title, language, API URL, templates, and detail page settings—stored in a dedicated database table. You can display data from remote JSON APIs on your site using flexible PHP templates.

## Features

- **Admin interface** to manage endpoints with full CRUD and duplication support
- **Custom database table** (`wp_jsonifywp`) for all endpoint configurations
- **Separate list and detail templates**, selectable per endpoint
- **Flexible shortcodes** to embed API data anywhere in your content
- **Extensible template system** — drop a `.php` file in the templates folder and it appears in the selector
- **Two operation modes**: list-with-detail navigation or list-only with server-side pagination
- **Smart URL handling** — relative detail URLs are automatically prefixed with the configured API domain
- **Endpoint duplication** for quick setup of similar configurations
- **Multilingual support** with Catalan and Spanish translations included

## Installation

1. Upload the `jsonifywp` folder to `wp-content/plugins/`.
2. Activate the plugin from the WordPress admin panel.
3. Go to **JsonifyWP** in the admin sidebar to add and manage endpoints.

The `templates/list/` and `templates/detail/` directories already contain default templates. Custom templates you add there will be automatically available in the endpoint editor.

## Configuration

### Endpoint fields

| Field | Description |
|---|---|
| **Title** | Display name for this endpoint configuration |
| **Language** | For organisational purposes (`ca`, `es`, `en`) — does not affect the API call |
| **API Domain** | Base domain prepended to relative detail URLs (e.g. `https://api.example.com`) |
| **API URL** | Full URL of the list endpoint — do not include `page` or `limit` params |
| **List Template** | Template file from `templates/list/` |
| **Detail Template** | Template file from `templates/detail/`, or **No detail page** for list-only mode |
| **Detail Page URL** | Relative URL of the WordPress page containing `[jsonifywp_detail]` (detail mode only) |
| **Detail API Field** | Name of the JSON field in each list item that holds the detail API URL (detail mode only) |

### Operation modes

#### Detail mode
Each list item links to a WordPress detail page that renders expanded information fetched from a second API call.

Setup:
1. Create a WordPress page (e.g. `/person/`) and add `[jsonifywp_detail]` to its content.
2. Set **Detail Page URL** to that page's relative path (e.g. `/person/`).
3. Set **Detail API Field** to the JSON key in each list item that contains the detail URL (e.g. `profile_url`).

Flow: `[jsonifywp id="1"]` → list API call → each item links to `/person/?jsonifywp_id=1&item=N` → `[jsonifywp_detail]` fetches item N's detail URL → renders with the detail template.

#### List-only mode
Select **No detail page** as the detail template. The plugin automatically appends `?page=N&limit=X` to the API URL on each page load, and the template handles rendering the paginator.

Your API must return:

```json
{
  "total": 42,
  "page": 1,
  "limit": 10,
  "items": ["Item A", "Item B", "..."]
}
```

### Global settings

| Option | Description | Default |
|---|---|---|
| **Items per page** | Items requested per page for list-only endpoints | `5` |
| **API cache duration (minutes)** | Cache API responses using WordPress transients. Set to `0` to disable | `0` |

## Shortcodes

**List:**
```
[jsonifywp id="1"]
```
Short alias (equivalent):
```
[jsonifywp-1]
```

**Detail page:**
```
[jsonifywp_detail]
```
Reads `jsonifywp_id` and `item` from the URL query string automatically.

The correct shortcode for each endpoint is shown in the admin endpoints table, ready to copy.

## Templates

### File locations

| Type | Directory |
|---|---|
| List templates | `templates/list/` |
| Detail templates | `templates/detail/` |

Any `.php` file placed in these directories will appear automatically in the endpoint editor dropdown.

### Variables available in templates

| Variable | Type | Contents |
|---|---|---|
| `$json` | `array` | Decoded JSON response from the API |
| `$item_obj` | `object` | Full endpoint configuration row from the database |
| `$type_id` | `int` | ID of the current endpoint |

Example — accessing endpoint configuration inside a list template:

```php
// $item_obj fields
$item_obj->title
$item_obj->api_domain
$item_obj->api_url
$item_obj->list_template
$item_obj->detail_template
$item_obj->detail_page_url
$item_obj->detail_api_field
```

### Generating detail page links

```php
foreach ($json as $index => $item) {
    $detail_field = $item_obj->detail_api_field;
    if (isset($item[$detail_field])) {
        $url = add_query_arg(
            ['jsonifywp_id' => $type_id, 'item' => $index],
            $item_obj->detail_page_url
        );
        echo '<a href="' . esc_url($url) . '">View detail</a>';
    }
}
```

### JavaScript in templates

Templates that include a paginator can enqueue a script and pass data via `wp_localize_script`. Use `plugin_dir_url(__FILE__)` to resolve paths relative to the template file:

```php
wp_enqueue_script(
    'my-template-js',
    plugin_dir_url(__FILE__) . 'assets/js/my-template.js',
    [],
    '1.0',
    true
);
wp_localize_script('my-template-js', 'my_vars', [
    'itemsPerPage' => get_option('jsonifywp_items_per_page', 5),
]);
```

Place the `.js` file at `templates/list/assets/js/my-template.js`.

### Included templates

#### `templates/list/default.php`
Generic list — renders every JSON field as `key: value`. Suitable for quick testing.

#### `templates/list/members.php`
People directory with client-side pagination. All items are loaded from the API in one call; JavaScript handles showing/hiding rows. Expects fields: `fullname`, `office`, `direct_phone`, `extension`, and the configured detail field.

#### `templates/list/publications.php`
Publication list with server-side pagination. Uses the `total`/`page`/`limit`/`items` API format. Each page reload fetches only the requested page.

#### `templates/detail/default_detail.php`
Generic detail — renders every JSON field as `key: value`.

#### `templates/detail/employee_detail.php`
Structured employee profile. Renders `fullname`, contact data, `research_lines`, `research_description`, and `publications`. Allows basic HTML tags in text fields via `wp_kses`.

## Advanced

### Accessing settings from PHP

```php
$items_per_page = get_option('jsonifywp_items_per_page', 5);
```

### Accessing settings from JavaScript

```js
// members.php
const itemsPerPage = window.jsonifywp_members_vars?.itemsPerPage || 5;

// publications.php
const { totalPages, currentPage, limit } = window.jsonifywp_publications_vars;
```

### Duplicating endpoints

Click **Duplicate** on any row in the endpoints list to create a copy with `(copy)` appended to the title. Useful for setting up similar endpoints without re-entering all fields.

## License

GPL v2 or later. See [LICENSE](LICENSE) for details.

---

Developed by [Oscar Periche](https://metalinked.net/).
