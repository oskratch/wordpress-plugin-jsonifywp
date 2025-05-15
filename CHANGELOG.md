# Changelog

All notable changes to this project will be documented in this file.

## [Unreleased]
- Work in progress.

## [1.2.0] - 2025-05-15
### Added
- Multi-language support with English as the default language.
- Translations for Catalan and Spanish.

## [1.1.0] - 2025-05-14
### Added
- Support for separate list and detail templates (folders: `templates/list/` and `templates/detail/`).
- Admin fields for detail page URL and detail API field.
- Navigation from list to detail page using configurable URL and JSON field.
- Shortcode `[jsonifywp_detail]` now reads parameters from the URL automatically.
- Improved shortcode logic for robust parameter handling.
- Documentation and README updates.

### Changed
- List and detail templates are now selectable per entry.
- Shortcode `[jsonifywp-1]` is supported via content filter.

## [1.0.0] - 2025-05-13
### Added
- Initial release.
- Custom admin interface for managing entries (title, language, API URL, template).
- Entries stored in custom database table (`wp_jsonifywp`).
- Shortcode `[jsonifywp id="X"]` to display API data using a template.
- Basic template support.

---

*This changelog follows [Keep a Changelog](https://keepachangelog.com/en/1.0.0/) and [Semantic Versioning](https://semver.org/spec/v2.0.0.html).*