# 0006. Pint, configured to the house style

- Status: Accepted
- Date: 2026-10-17

## Context

The legacy repo's PHP style is set in `.php-cs-fixer.php`:
- tab indentation;
- `declare(strict_types=1);` in every file;
- grouped imports (`use App\Models\{Order, User};`) sorted by length;
- arrow functions where possible.

php-cs-fixer isn't a dev dependency. CI runs it in a third-party image and then **pushes a "Fix styling" commit** to the PR branch, so contributors have to pull before pushing again.

Laravel 13 ships Pint, and Laravel Boost tells coding agents to run `vendor/bin/pint --dirty`. Pint has **no indentation setting**: as of Pint 1.30 it always configures php-cs-fixer for four spaces.

## Decision

- Use Pint as a dev dependency of `backend/`, with this `backend/pint.json`:

  ```json
  {
  	"preset": "laravel",
  	"rules": {
  		"declare_strict_types": true,
  		"group_import": true,
  		"single_import_per_statement": false,
  		"ordered_imports": { "sort_algorithm": "length", "imports_order": ["class", "function", "const"] },
  		"use_arrow_functions": true,
  		"ordered_traits": true,
  		"cast_spaces": { "space": "single" },
  		"indentation_type": false,
  		"statement_indentation": false,
  		"array_indentation": false,
  		"method_chaining_indentation": false
  	}
  }
  ```

  The last four rules are off so that Pint leaves tabs alone instead of converting them to spaces.
- `.editorconfig` keeps tabs for PHP. CI also fails if a PHP file under `backend/app`, `backend/config`, `backend/database`, `backend/routes` or `backend/tests` has a line indented with spaces.
- CI runs `pint --test`, **check-only**. Nothing commits to a contributor's branch.

## Consequences

- The configuration was tested on Pint 1.30.4: it leaves tab-indented code as is, adds `declare(strict_types=1)`, groups and length-sorts imports, and turns single-statement closures into arrow functions.
- Pint doesn't enforce tabs itself; the CI check does.
- Laravel's generated files (`laravel new`, `make:*` stubs) use spaces. Converting them is a one-off `unexpand` when they're added; publishing the stubs with tabs avoids it for new files.
- The style job in CI no longer needs write access to the repository.
