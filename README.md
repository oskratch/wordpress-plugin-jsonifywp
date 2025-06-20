# JsonifyWP
JsonifyWP is a WordPress plugin that lets you manage custom API endpoints—each with its own title, language, API URL (endpoint), templates, and detail page settings—stored in a dedicated database table. You can display data from remote JSON APIs on your site using flexible templates.

## Features

- Admin interface to manage endpoints (add/edit title, language, API URL [endpoint], list template, detail template, detail page URL, detail API field)
- Stores endpoints in a dedicated table (`wp_jsonifywp`)
- Supports multiple templates for list and detail views (selectable per endpoint)
- Global setting to define the number of items per page for paginated lists
- Option to configure endpoints as "list-only" (no detail page), enabling API-driven pagination

## Configuration fields per endpoint

When you add or edit an endpoint, you can configure the following fields:

- **Title**: The name of the endpoint.
- **Language**: The language code for the endpoint.
- **API domain**: The base domain for the API (optional).
- **API URL**: The endpoint path or full URL (without `page` or `limit` parameters; these are handled automatically).
- **List template**: The PHP template file used to render the list view (from `templates/list/`).
- **Detail template**: The PHP template file used to render the detail view (from `templates/detail/`), or select **No detail page** if you only want a paginated list.
- **Detail page URL**: Only required if a detail template is selected; hidden and not required if "No detail page" is chosen.
- **Detail API field**: Only required if a detail template is selected; hidden and not required if "No detail page" is chosen.

### Items per page

You can set the global number of items per page for paginated lists in the plugin settings. This value is automatically used as the `limit` parameter in API requests for endpoints configured as "list-only" (no detail page).

## List-only Endpoints and API Pagination

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

## Usage

To display a list from an endpoint, use the shortcode:

```
[jsonifywp id="1"]
```

To display a detail view (if configured), use:

```
[jsonifywp_detail id="1"]
```

**Note:** If the endpoint is configured as “No detail page”, only the paginated list will be shown and no detail links will be generated.

## Templates

List and detail templates are PHP files located in the `templates/list/` and `templates/detail/` directories of the plugin. You can create your own templates to customize the display of API data.

---

Feel free to copy and paste this into your