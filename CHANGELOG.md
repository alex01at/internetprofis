# Changelog

All notable changes to this project are documented here.

## 2026-09-03

### Added

- Added database migration support to the application updater. SQL files placed in `migrations/` are shown for review and applied only on explicit confirmation, tracked in a `schema_migrations` table so each one runs once. Same statement blocklist as the plugin installer.
- `dashboard.php` now shows the actual deployed release instead of a manually maintained value, with an update indicator when a newer release is available.

### Changed

- Standardized remaining admin pages from the old breadcrumb-bar header onto the layout used by the rest of the admin panel.
- Verified PHP 8.5 compatibility; fixed a deprecated PDO constant usage and removed redundant resource-cleanup calls.
- Self-hosted Google Fonts, the Summernote editor assets, and Chosen.js instead of loading them from third-party CDNs. Stripe.js, the PayPal SDK, reCAPTCHA, and Google Tag Manager remain external, as required by those services.

### Fixed

- Fixed a double-HTML-escaping bug in the homepage's trust-badges section.
- Fixed the update-available indicator never triggering due to inverted logic when reading the GitHub compare API status.
- Fixed a navigation link that had been added to a file not actually included by the page; removed the dead file.
- Consolidated two duplicate legacy implementations of the proposal filter feature that produced invalid nested page markup.
- Removed a handful of confirmed-unused PHP files.
- Fixed upload directories that would not have existed after a fresh checkout, since empty directories aren't tracked by git.
- Fixed the dashboard showing a raw commit hash instead of a release name for deployments tracked before release-name storage existed; the name is now resolved automatically.

## 2026-08-27

### Added

- Added a safe, GitHub-based update mechanism to the admin panel, replacing a previous version that accepted arbitrary file uploads. Pulls only from this project's own repository over HTTPS.
- Hardened and restored the plugin update/installer flow with strict path and content validation.

### Changed

- Updated Composer dependencies; no known vulnerabilities remain.
- Removed an abandoned PayPal SDK; the payment flow it powered had already been discontinued upstream and now routes through the standard manual payout process.
- Updated the embedded CodeIgniter framework to its latest 3.x release.
- Restored several admin pages (plugin management, order handling, referrals, reports, payouts) that existed before an earlier admin redesign but were never carried over.
- Restored the admin password-reset page, without which the "forgot password" email had no working destination.
- Removed the legacy admin panel after confirming its remaining pages were either superseded, intentionally dropped, or never finished.

### Fixed

- Fixed two PHP fatal errors that were present on live pages.
- Fixed several broken admin navigation links using outdated parameters.

### Security

- Fixed a PHP object injection vulnerability in the checkout flow.
- Removed an unused internal API that had an authentication bypass exposing full database read access.
- Removed an exposed debug script and a duplicate dead file.
- Disabled public error output; errors are now logged instead.
- Hardened session cookies and fixed session fixation across all login flows.
- Blocked direct web access to version-control and backup files.

### Removed

- Removed leftover installer scripts and a backup file from the web root.

### Other

- Set up version control for the project.
