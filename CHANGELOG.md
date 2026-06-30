# Changelog

All notable changes to this project will be documented in this file.

## [1.4.0](https://github.com/oskratch/wordpress-plugin-jsonifywp/compare/jsonifywp-v1.3.0...jsonifywp-v1.4.0) (2026-06-30)


### Features

* add api_domain field to database for full endpoint URLs ([10ac6bc](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/10ac6bc78cfa150dfb98573decdb3a6b492b7cd1))
* add members list template and update translations ([2d86751](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/2d86751a9d4d324c798f40ecfff399a7dc5f7f66))
* add support for separate list/detail templates and detail page navigation ([db9a8b3](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/db9a8b3c147028b821aac668f06d2635490c8c92))
* **admin:** add duplicate record option to endpoints list ([6c32cfb](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/6c32cfbd7b5044db04f784a9e92ef4890e8eb4ae))
* Configure release-please and move manifest ([6c854c9](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/6c854c9a55dfac9025fedb86fada5091a3436f26))
* **i18n:** add multi-language support with Catalan and Spanish translations ([1a3733a](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/1a3733a8d82cfde8f7f17638b30863d27dadeeaa))
* improve code quality, reliability and admin UX ([0920270](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/0920270996b3ee7235f9c19f9650a6c59c891b4b))
* improved JSON API text handling and minor translations ([c9a5603](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/c9a560373af51ac21a7095eb53833dc82e4c43e1))
* **members.js:** scroll to top with offset on paginator click ([8919db7](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/8919db746e911aff8261f26cecbce7eb226cb01b))
* Move inline JS from members template to external file and add configurable items per page setting ([2ec03a3](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/2ec03a3c1ada496ea6fb04afbd25be282765da39))
* **template:** add inline JS paginator to members.php ([f0c81a4](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/f0c81a46e679c715333dd5f1aecf7c56230e7b65))
* **template:** create new detail template with separated and more structured data ([2a11a39](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/2a11a391380f27c5db3f6d34ed42713524735320))


### Bug Fixes

* **ci:** use default GITHUB_TOKEN in release-please workflow ([70e94cb](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/70e94cb47c13228d1075c7a95362b21cf2060c91))
* correct limit parameter and paginator behavior in publications list ([2132b0f](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/2132b0f8b7d16343787586485aef9473a5569d79))
* resolve persistent first item visibility, add top paginator, and sync active page highlight ([ba0c9de](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/ba0c9de56ce79daa66de91e9c853ea64577ff467))
* various adjustments to publications demo template ([b3fe89a](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/b3fe89aeffdd3adc263a892cb20f8a47a0053fae))


### Miscellaneous Chores

* **docs:** update README.md and CHANGELOG.md for members.php pagination ([344c858](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/344c8582210712b35dd3a418bf0fa7a89fd94a80))
* **main:** release 1.0.0 ([927c84c](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/927c84c46868602216e40fa650e1dea090e804d6))
* **main:** release 1.1.0 ([4800365](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/4800365381422ed85baf282e16cfebf6932df6fe))
* **main:** release 1.1.1 ([bef6b0d](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/bef6b0ddf9e0cda963f887d1f4eb1b0c6d3ab824))
* **main:** release 1.1.2 ([dec819c](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/dec819cc3834d8157bbdd367037df42a5145ac55))
* **main:** release 1.1.3 ([849089a](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/849089ac314e5781c7c61180c5375fb0ebf862c1))
* **main:** release 1.1.4 ([bc28fdd](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/bc28fddcf11c012e96eac63f50503ead414066d0))
* **main:** release 1.2.0 ([#8](https://github.com/oskratch/wordpress-plugin-jsonifywp/issues/8)) ([95b579c](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/95b579cac341b487be2a15ed4f057289505e34e0))
* **main:** release 1.2.0 ([#9](https://github.com/oskratch/wordpress-plugin-jsonifywp/issues/9)) ([995e226](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/995e22609f63cbd8e6a6192a58b3a287fffa7995))
* **main:** release 1.3.0 ([#10](https://github.com/oskratch/wordpress-plugin-jsonifywp/issues/10)) ([9fe5aa4](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/9fe5aa45211b20647182e72779d37baea43dd8d2))
* **plugin:** update main plugin header and add license comments to classes and includes ([a567fb9](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/a567fb953b08a9a235dd7c4865deac20287814b4))

## [1.3.0](https://github.com/oskratch/wordpress-plugin-jsonifywp/compare/v1.2.0...v1.3.0) (2025-10-08)


### Features

* add api_domain field to database for full endpoint URLs ([10ac6bc](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/10ac6bc78cfa150dfb98573decdb3a6b492b7cd1))
* add members list template and update translations ([2d86751](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/2d86751a9d4d324c798f40ecfff399a7dc5f7f66))
* add support for separate list/detail templates and detail page navigation ([db9a8b3](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/db9a8b3c147028b821aac668f06d2635490c8c92))
* **admin:** add duplicate record option to endpoints list ([6c32cfb](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/6c32cfbd7b5044db04f784a9e92ef4890e8eb4ae))
* Configure release-please and move manifest ([6c854c9](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/6c854c9a55dfac9025fedb86fada5091a3436f26))
* **i18n:** add multi-language support with Catalan and Spanish translations ([1a3733a](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/1a3733a8d82cfde8f7f17638b30863d27dadeeaa))
* improved JSON API text handling and minor translations ([c9a5603](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/c9a560373af51ac21a7095eb53833dc82e4c43e1))
* **members.js:** scroll to top with offset on paginator click ([8919db7](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/8919db746e911aff8261f26cecbce7eb226cb01b))
* Move inline JS from members template to external file and add configurable items per page setting ([2ec03a3](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/2ec03a3c1ada496ea6fb04afbd25be282765da39))
* **template:** add inline JS paginator to members.php ([f0c81a4](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/f0c81a46e679c715333dd5f1aecf7c56230e7b65))
* **template:** create new detail template with separated and more structured data ([2a11a39](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/2a11a391380f27c5db3f6d34ed42713524735320))


### Bug Fixes

* **ci:** use default GITHUB_TOKEN in release-please workflow ([70e94cb](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/70e94cb47c13228d1075c7a95362b21cf2060c91))
* correct limit parameter and paginator behavior in publications list ([2132b0f](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/2132b0f8b7d16343787586485aef9473a5569d79))
* resolve persistent first item visibility, add top paginator, and sync active page highlight ([ba0c9de](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/ba0c9de56ce79daa66de91e9c853ea64577ff467))
* various adjustments to publications demo template ([b3fe89a](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/b3fe89aeffdd3adc263a892cb20f8a47a0053fae))


### Miscellaneous Chores

* **docs:** update README.md and CHANGELOG.md for members.php pagination ([344c858](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/344c8582210712b35dd3a418bf0fa7a89fd94a80))
* **main:** release 1.0.0 ([927c84c](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/927c84c46868602216e40fa650e1dea090e804d6))
* **main:** release 1.1.0 ([4800365](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/4800365381422ed85baf282e16cfebf6932df6fe))
* **main:** release 1.1.1 ([bef6b0d](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/bef6b0ddf9e0cda963f887d1f4eb1b0c6d3ab824))
* **main:** release 1.1.2 ([dec819c](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/dec819cc3834d8157bbdd367037df42a5145ac55))
* **main:** release 1.1.3 ([849089a](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/849089ac314e5781c7c61180c5375fb0ebf862c1))
* **main:** release 1.1.4 ([bc28fdd](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/bc28fddcf11c012e96eac63f50503ead414066d0))
* **main:** release 1.2.0 ([#8](https://github.com/oskratch/wordpress-plugin-jsonifywp/issues/8)) ([95b579c](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/95b579cac341b487be2a15ed4f057289505e34e0))
* **main:** release 1.2.0 ([#9](https://github.com/oskratch/wordpress-plugin-jsonifywp/issues/9)) ([995e226](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/995e22609f63cbd8e6a6192a58b3a287fffa7995))
* **plugin:** update main plugin header and add license comments to classes and includes ([a567fb9](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/a567fb953b08a9a235dd7c4865deac20287814b4))

## [1.2.0](https://github.com/oskratch/wordpress-plugin-jsonifywp/compare/v1.1.4...v1.2.0) (2025-07-29)


### Features

* **admin:** add duplicate record option to endpoints list ([6c32cfb](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/6c32cfbd7b5044db04f784a9e92ef4890e8eb4ae))


### Miscellaneous Chores

* **main:** release 1.2.0 ([#8](https://github.com/oskratch/wordpress-plugin-jsonifywp/issues/8)) ([95b579c](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/95b579cac341b487be2a15ed4f057289505e34e0))

## [1.2.0](https://github.com/oskratch/wordpress-plugin-jsonifywp/compare/v1.1.4...v1.2.0) (2025-07-28)


### Features

* **admin:** add duplicate record option to endpoints list ([6c32cfb](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/6c32cfbd7b5044db04f784a9e92ef4890e8eb4ae))

## [1.1.4](https://github.com/oskratch/wordpress-plugin-jsonifywp/compare/v1.1.3...v1.1.4) (2025-06-25)


### Bug Fixes

* various adjustments to publications demo template ([b3fe89a](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/b3fe89aeffdd3adc263a892cb20f8a47a0053fae))

## [1.1.3](https://github.com/oskratch/wordpress-plugin-jsonifywp/compare/v1.1.2...v1.1.3) (2025-06-20)


### Bug Fixes

* correct limit parameter and paginator behavior in publications list ([2132b0f](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/2132b0f8b7d16343787586485aef9473a5569d79))

## [1.1.2](https://github.com/oskratch/wordpress-plugin-jsonifywp/compare/v1.1.1...v1.1.2) (2025-05-28)


### Bug Fixes

* **ci:** use default GITHUB_TOKEN in release-please workflow ([70e94cb](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/70e94cb47c13228d1075c7a95362b21cf2060c91))

## [1.1.1](https://github.com/oskratch/wordpress-plugin-jsonifywp/compare/v1.1.0...v1.1.1) (2025-05-27)


### Bug Fixes

* resolve persistent first item visibility, add top paginator, and sync active page highlight ([ba0c9de](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/ba0c9de56ce79daa66de91e9c853ea64577ff467))

## [1.1.0](https://github.com/oskratch/wordpress-plugin-jsonifywp/compare/v1.0.0...v1.1.0) (2025-05-24)


### Features

* add api_domain field to database for full endpoint URLs ([10ac6bc](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/10ac6bc78cfa150dfb98573decdb3a6b492b7cd1))
* add members list template and update translations ([2d86751](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/2d86751a9d4d324c798f40ecfff399a7dc5f7f66))
* add support for separate list/detail templates and detail page navigation ([db9a8b3](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/db9a8b3c147028b821aac668f06d2635490c8c92))
* Configure release-please and move manifest ([6c854c9](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/6c854c9a55dfac9025fedb86fada5091a3436f26))
* **i18n:** add multi-language support with Catalan and Spanish translations ([1a3733a](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/1a3733a8d82cfde8f7f17638b30863d27dadeeaa))
* improved JSON API text handling and minor translations ([c9a5603](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/c9a560373af51ac21a7095eb53833dc82e4c43e1))
* **members.js:** scroll to top with offset on paginator click ([8919db7](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/8919db746e911aff8261f26cecbce7eb226cb01b))
* Move inline JS from members template to external file and add configurable items per page setting ([2ec03a3](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/2ec03a3c1ada496ea6fb04afbd25be282765da39))
* **template:** add inline JS paginator to members.php ([f0c81a4](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/f0c81a46e679c715333dd5f1aecf7c56230e7b65))
* **template:** create new detail template with separated and more structured data ([2a11a39](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/2a11a391380f27c5db3f6d34ed42713524735320))


### Miscellaneous Chores

* **docs:** update README.md and CHANGELOG.md for members.php pagination ([344c858](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/344c8582210712b35dd3a418bf0fa7a89fd94a80))
* **main:** release 1.0.0 ([927c84c](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/927c84c46868602216e40fa650e1dea090e804d6))
* **plugin:** update main plugin header and add license comments to classes and includes ([a567fb9](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/a567fb953b08a9a235dd7c4865deac20287814b4))

## 1.0.0 (2025-05-22)


### Features

* add api_domain field to database for full endpoint URLs ([10ac6bc](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/10ac6bc78cfa150dfb98573decdb3a6b492b7cd1))
* add members list template and update translations ([2d86751](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/2d86751a9d4d324c798f40ecfff399a7dc5f7f66))
* add support for separate list/detail templates and detail page navigation ([db9a8b3](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/db9a8b3c147028b821aac668f06d2635490c8c92))
* Configure release-please and move manifest ([6c854c9](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/6c854c9a55dfac9025fedb86fada5091a3436f26))
* **i18n:** add multi-language support with Catalan and Spanish translations ([1a3733a](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/1a3733a8d82cfde8f7f17638b30863d27dadeeaa))
* improved JSON API text handling and minor translations ([c9a5603](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/c9a560373af51ac21a7095eb53833dc82e4c43e1))
* Move inline JS from members template to external file and add configurable items per page setting ([2ec03a3](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/2ec03a3c1ada496ea6fb04afbd25be282765da39))
* **template:** add inline JS paginator to members.php ([f0c81a4](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/f0c81a46e679c715333dd5f1aecf7c56230e7b65))
* **template:** create new detail template with separated and more structured data ([2a11a39](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/2a11a391380f27c5db3f6d34ed42713524735320))


### Miscellaneous Chores

* **docs:** update README.md and CHANGELOG.md for members.php pagination ([344c858](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/344c8582210712b35dd3a418bf0fa7a89fd94a80))
* **plugin:** update main plugin header and add license comments to classes and includes ([a567fb9](https://github.com/oskratch/wordpress-plugin-jsonifywp/commit/a567fb953b08a9a235dd7c4865deac20287814b4))

## [Unreleased]

---

*This changelog follows [Keep a Changelog](https://keepachangelog.com/en/1.0.0/) and [Semantic Versioning](https://semver.org/spec/v2.0.0.html).*
