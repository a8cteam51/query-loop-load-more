---
name: prepare-plugin-release
description: >
  Prepares a new release of the Query Loop Load More WordPress plugin. Use when the user
  asks to "bump version", "prepare release", "cut a release", "update changelog for release",
  or when releasing a new plugin version to WordPress.org or GitHub.
---

# Prepare Plugin Release

## Overview

This skill covers the checklist for preparing a new versioned release of the plugin. It touches multiple files and steps that are easy to miss.

## Release Checklist

### 1. Version number

Update the version in **both** places:

- **`query-loop-load-more.php`**: The `Version:` line in the plugin header.
- **`readme.txt`**: The `Stable tag:` line (must match exactly).

### 2. Changelog

Add an entry for the new version in **both** files:

- **`readme.txt`**: Under `== Changelog ==`, add a new section:
  ```text
  = X.Y.Z =
  * Type - Brief description (e.g. "Fix - Description" or "Update - Description" or "Add - Description")
  ```

- **`README.md`**: Add a matching entry under `## Changelog` using the same format.

### 3. Build and i18n

```bash
composer run internationalize
npm run build
```

The build step is required; the plugin will not work correctly without built assets.

### 4. Commit and tag

Commit the version and changelog changes, then create a git tag (e.g. `1.0.19`). Pushing the tag can trigger the release workflow.

## Boundaries

- Does not cover GitHub release creation (handled by workflow on `release` event).
- Does not cover WordPress.org SVN deployment (manual workflow `push-deploy`).
- Does not cover feature development or testing before release.
