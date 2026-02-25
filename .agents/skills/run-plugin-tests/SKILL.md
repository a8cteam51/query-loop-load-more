---
name: run-plugin-tests
description: >
  Runs the PHPUnit test suite for the Query Loop Load More plugin. Use when the user
  asks to "run tests", "execute tests", "run the test suite", "verify tests pass",
  "run phpunit", or when validating changes before commit/PR.
---

# Run Plugin Tests

## Overview

The project uses PHPUnit for PHP tests. Tests live in `tests/` and use a minimal bootstrap—no WordPress installation is required. The bootstrap mocks required WordPress classes.

## How to Run

### Full suite

```bash
composer install    # Ensures dev deps (phpunit) are installed
composer test      # Runs phpunit
```

### Single test file

```bash
./vendor/bin/phpunit tests/MultipleQueryRegionTest.php
```

### Single test method

```bash
./vendor/bin/phpunit tests/MultipleQueryRegionTest.php --filter testDifferentQueryIdsGetDifferentRegions
```

## Test Structure

| Path | Purpose |
|------|---------|
| `phpunit.xml` | PHPUnit config; points to `tests/bootstrap.php` |
| `tests/bootstrap.php` | Defines constants, mocks `WP_HTML_Tag_Processor` |
| `tests/MultipleQueryRegionTest.php` | Tests for `render_query_block` region assignment |

## When to Run

- After modifying PHP in `src/` or `includes/`
- Before opening a PR or committing
- When debugging region-related load-more behavior (multiple Query Loops on a page)

## Boundaries

- No JavaScript tests. The frontend (`assets/js/src/`) is not covered by this suite.
- No integration tests. Tests use mocks, not a real WordPress instance.
