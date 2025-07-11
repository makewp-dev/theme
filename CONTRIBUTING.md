# Contributing

## Local Development

1. Set up WordPress locally to your liking
2. Clone this repository
3. In your WordPress theme, require this package with a local path

## Release Process

1. Update documentation to match code changes
3. Update `CHANGELOG.md` with changes
2. Bump version in `composer.json`
  - Patch (bug fixes): 0.1.2 → 0.1.3
  - Minor (new features): 0.1.2 → 0.2.0
  - Major (breaking changes): 0.1.2 → 1.0.0
4. Tag this commit with message "v[NEW VERSION NUMBER]" (we'll automate this later)
4. Push to `main` branch (we'll make this pull-request-only later)
5. Updates to `main` will publish to Packagist
