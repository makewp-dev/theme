# Contributing

## Local Development

1. Set up WordPress locally to your liking
2. Clone this repository
3. In your WordPress theme, require this package with a local path

## Release Process

1. Update documentation to match code changes
2. Bump version in `composer.json`
  - Patch (bug fixes): 0.1.2 → 0.1.3
  - Minor (new features): 0.1.2 → 0.2.0
  - Major (breaking changes): 0.1.2 → 1.0.0
3. Update `CHANGELOG.md` with changes
4. Push to repository and pull request into `main` branch
5. Updates to `main` will publish to Packagist
