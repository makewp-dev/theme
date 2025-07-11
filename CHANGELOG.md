# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- `enqueue_style()`
- `build_theme()` and `BuildCommand` (untested)
  - Build command can `--watch` file changes (untested)
- Prove testing in local development
  - PSR-4 autoloading compliance with `MakeWP\Theme\` namespace
  - Functional programming support in composer configuration
- Cursor rules for development environment
- Documentation in `README.md` and `CONTRIBUTING.md`

### Changed
- Code splitting and modular architecture
- Improved code organization and structure

### Fixed
- Capitalization issues in composer configuration
- PSR-4 compliance requiring classes to be in separate files

## [0.1.2] - 2024-10-07

I think literally just to, again, prove the Packagist publish process.

### Added
- MIT license to the project

## [0.1.1] - 2024-10-07

Really, just for getting this started, on Packagist, and claim the makewp name.

### Added
- New composer package
- `require_functions_in_folder()`
- `register_blocks_in_folder()`
