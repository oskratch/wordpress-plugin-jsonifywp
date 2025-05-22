# Changelog

All notable changes to this project will be documented in this file.

## 1.0.0 (2025-05-22)


### Features

* add api_domain field to database for full endpoint URLs ([10ac6bc](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/10ac6bc78cfa150dfb98573decdb3a6b492b7cd1))
* add members list template and update translations ([2d86751](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/2d86751a9d4d324c798f40ecfff399a7dc5f7f66))
* add support for separate list/detail templates and detail page navigation ([db9a8b3](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/db9a8b3c147028b821aac668f06d2635490c8c92))
* Configure release-please and move manifest ([310458f](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/310458f3579a12713dc8da6fcd5962b201fca81c))
* **i18n:** add multi-language support with Catalan and Spanish translations ([1a3733a](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/1a3733a8d82cfde8f7f17638b30863d27dadeeaa))
* improved JSON API text handling and minor translations ([c9a5603](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/c9a560373af51ac21a7095eb53833dc82e4c43e1))
* Move inline JS from members template to external file and add configurable items per page setting ([2ec03a3](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/2ec03a3c1ada496ea6fb04afbd25be282765da39))
* **template:** add inline JS paginator to members.php ([f0c81a4](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/f0c81a46e679c715333dd5f1aecf7c56230e7b65))
* **template:** create new detail template with separated and more structured data ([2a11a39](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/2a11a391380f27c5db3f6d34ed42713524735320))


### Miscellaneous Chores

* **docs:** update README.md and CHANGELOG.md for members.php pagination ([344c858](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/344c8582210712b35dd3a418bf0fa7a89fd94a80))
* **plugin:** update main plugin header and add license comments to classes and includes ([a567fb9](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/a567fb953b08a9a235dd7c4865deac20287814b4))

## [Unreleased]

## [1.2.1] - 2025-05-20
### Added
- Paginator to one of the sample templates (members.php).

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
