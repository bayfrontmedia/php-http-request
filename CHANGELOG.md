# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

- `Added` for new features.
- `Changed` for changes in existing functionality.
- `Deprecated` for soon-to-be removed features.
- `Removed` for now removed features.
- `Fixed` for any bug fixes.
- `Security` in case of vulnerabilities

## [3.0.0] - 2023.01.26

### Added

- Added support for PHP 8.

## [2.1.0] - 2021.09.14

### Added

- Added support for `$_FILES` global with the `getFile` and `hasFile` methods.

### Changed

- Updated vendor libraries.

## [2.0.1] - 2021.02.15

### Changed

- Updated vendor libraries.

### Fixed

- Fixed bug in `isHttps` method not detecting https behind a load balancer.

## [2.0.0] - 2020.11.06

### Changed

- Renamed methods `getContent` and `hasContent` to `getBody` and `hasBody` respectively, as this more accurately depicts the HTTP request element.

## [1.3.0] - 2020.09.14

### Added

- Added `isCli` method.

## [1.2.0] - 2020.09.04

### Added

- Added support for `CONNECT`, `OPTIONS` and `TRACE` request methods.

## [1.1.0] - 2020.09.02

### Added

- Added `isJson` and `wantsJson` methods.

## [1.0.1] - 2020.08.29

### Changed

- Replaced using `$_SERVER['SERVER_NAME']` with `$_SERVER['HTTP_HOST']` in `getRequest()`, as this is more accurately associated with the incoming HTTP request.

## [1.0.0] - 2020.07.27

### Added

- Initial release.