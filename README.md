**What is Gigtodo?**

Gigtodo is a free open-source platform script for publishing or buying proposals. Similar to major platforms like Fiverr, Upwork, or Freelancer.

**Basics:**

-Fully translatable frontend.

-Full control of all kinds of options on the platform.

-Admin interface and frontend fully responsive (Bootstrap).

-Fully PHP 8.2 compatible.

-Many payment gateways.

-Extremely fast (no need for high-end hardware).

-Different look possible for any language.

-Vacation mode, knowledge bank, referral system, coupon code system, favorite system, ratings, user levels, and much more.


**History:**
From 2018 to 2021, the script was available for purchase on CodeCanyon and Codester. The company Pixinal Studio acquired it from ***Perola Hammar.***

Since he didn't want to develop the original codebase further, I took full ownership of all rights to make it a free open-source script.

**What's new?**

*Admin interface completely redesigned.

*Over 100 new translations implemented.

*PHP 8.2 compatibility.

*New color schemes.

*Removal of unnecessary code.

*Fixing over 200 known bugs.

**Testing:**

There's no SSH access to the live host, so changes are also verified locally before every FTP upload or GitHub release. Run the local test suite from the project root:

```
bash tests/run.sh
```

It runs six checks, none of which need a database connection:

-`tests/check-syntax.php` - runs `php -l` across the whole codebase (excluding vendored third-party libraries) to catch fatal syntax errors before upload.

-`tests/check-lang-keys.php` - loads both `languages/english.php` and `languages/deutsch.php` as real PHP and checks that every `$lang[]` key referenced anywhere in the code actually exists in both files, and flags duplicate or unused key definitions.

-`tests/check-embedded-tags.php` - uses PHP's tokenizer to find a `<?=` or `<?php` tag accidentally typed inside an already-open string literal, where it silently never evaluates instead of throwing an error.

-`tests/check-stray-backslashes.php` - catches a `\$lang` artifact left behind by a scripted find-and-replace.

-`tests/check-identical-values.php` - flags `$lang[]` keys whose English and German text is byte-identical, usually meaning the German version was never actually translated. A small allowlist inside the script covers legitimately-identical values (brand names, country names, common loanwords).

-`tests/check-mailer-require.php` - `send_mail()` is defined in `functions/mailer.php`, which `includes/db.php` does NOT load automatically; every page that calls it has to require it itself, directly or through something like `functions/email.php`. This walks each caller's actual require/include chain (resolving the codebase's common `$dir/...` pattern) and flags any file where that chain never reaches `functions/mailer.php` - the exact bug that caused a fatal error on every widerruf.php submission. A small allowlist covers fragments that are only ever `include()`'d into a page that already loaded the mailer itself.

These same checks also run automatically on every push and pull request via GitHub Actions (`.github/workflows/tests.yml`).
