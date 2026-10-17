# 0002. A `users.role` enum instead of Spatie

- Status: Accepted
- Date: 2026-10-17

## Context

The legacy app installs `spatie/laravel-permission` with four roles (Admin, Moderator, Booster, Member) and 64 permissions. `RoleSeeder` assigns no permission to any role. The only permission ever granted is "Change visibility", which an Admin action gives to individual boosters. Authorisation actually happens in two other places:

- `->canSee()`/`->canRun()` closures and `indexQuery()` in the Nova resources, which call `hasRole()`;
- the policies, some of which are always true (`OrderPolicy::view` returns a truthy query builder).

The split is what leaked data. Registration assigned no role, and every closure was written as "not a Member" or "not a Booster", so a new user passed as staff and saw every order with its Riot credentials.

## Decision

- Each user has exactly one role, stored in `users.role` and cast to a PHP backed enum `App\Enums\Role` (`admin`, `moderator`, `booster`, `customer`). The column is not nullable and defaults to `customer`.
- Registration always creates a customer. Only an Admin can change a role.
- **Policies are the only authorisation layer.** Controllers, broadcast channels and the API resources' `allowed_actions` all ask the policies; nothing else checks a role.
- Spatie is not installed in v2.

## Consequences

- A user can't end up without a role, and there's one place to read the rules: `app/Policies`, tested role × ability × status.
- No per-user permission grants. The legacy "Change visibility" permission becomes a rule: boosters control their own `visible` flag, and Admins can change anyone's.
- Adding a role means adding an enum case and handling it in the policies. That's fine for four fixed roles; a permission system would be worth it only if admins needed to define their own roles.
- The legacy roles map one to one, with Member renamed to `customer`.
