# Changelog

All notable changes to this project are documented here.

## 2026-09-04

### Added

- Added the withdrawal_notices table to the base install schema (gigtodo.sql), so a fresh install has it without needing to separately run the migration.
- Added tests/check-mailer-require.php: statically walks each send_mail() caller's require/include chain and flags any that never reach functions/mailer.php, where the function is actually defined.
- Added a boxed/full-width layout choice for the frontend, in Admin -> Settings -> Theme Settings -> Basic Settings.

### Fixed

- Fixed a fatal error on every widerruf.php submission: it called send_mail() without requiring functions/mailer.php first (includes/db.php doesn't pull that in automatically). Also fixed the same bug, found independently by the new check, in proposals/sections/edit/publish.php - its require path was missing two levels of `../` and pointed at a directory that doesn't exist, which would have fataled the first time a draft proposal's publish tab was opened.

## 2026-09-03

### Added

- Added database migration support to the application updater. SQL files placed in `migrations/` are shown for review and applied only on explicit confirmation, tracked in a `schema_migrations` table so each one runs once. Same statement blocklist as the plugin installer.
- `dashboard.php` now shows the actual deployed release instead of a manually maintained value, with an update indicator when a newer release is available.
- Added German as a selectable site language, with translated categories, subcategories, terms, support text, seller level badges, footer links, and homepage sections. Included a migration for sites that already have data.
- Added a local test suite (`tests/`) that runs without a database: PHP syntax check, `$lang[]` key consistency check, a check for `$lang[]` keys whose English and German values are identical (usually meaning the German text was never actually translated), and two checks for recurring bug patterns (PHP tags embedded inside string literals, stray escaped `$lang` references). Wired into GitHub Actions to run on every push and pull request.
- Added a right-of-withdrawal (Widerruf) page with the legally required model withdrawal form, linked from the footer under the language selector. Submissions notify admins through the existing alerts panel and are listed on a new Refunds page under Money in the admin sidebar; the submitting customer also receives an email confirming receipt.

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
- Fixed missing icons across 25 pages (checkout, cart, login, dashboard, knowledge bank, and others) caused by a missing Font Awesome stylesheet link.
- Translated several hardcoded English strings that ignored the site language (knowledge bank empty state, dashboard greeting, mobile purchase form, feedback empty state), and translated the referral/affiliate text, which had never been localized. Also fixed the "Knowledge Bank" page title showing the old script's brand name, and a broken link in the referral text caused by invalid syntax inside the translation string.
- Completed a site-wide sweep of hardcoded English strings across the frontend (checkout, orders, proposals, conversations, requests, payments, tickets, feedback, blog): several hundred strings across roughly 90 files now go through the site's language system instead of always showing English. Also translated the German transactional email templates, which had been an unmodified copy of the English ones.
- Extended the translation sweep to strings the initial pass missed: text containing a colon, and strings inside `alert()`/`confirm()`/`swal()`/`.text()` calls and PHP validator message arrays. Uncovered and fixed two long-standing bugs along the way: the mobile navigation menu referenced undefined `$lang[]` keys and showed blank labels sitewide, and two strings in the language files themselves had an unevaluated `<?= ?>` baked in, displaying literally instead of the site name/URL.
- Fixed the order resolution center's "order does not meet requirements" option, which was missing the "not" in both languages and said the opposite of what it meant.
- Removed 20 duplicate and 13 unused key definitions from the language files.
- Translated 28 German strings that had been left as an untranslated copy of the English text (payment method buttons/settings, referral pages, Bitcoin wallet warning, and others), found with the new identical-value check.

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
