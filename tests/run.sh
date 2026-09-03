#!/usr/bin/env bash
# Runs all local checks. Meant to be run before every FTP upload or
# GitHub release, from the project root:
#
#   bash tests/run.sh
#
set -uo pipefail
cd "$(dirname "$0")/.."

status=0

echo "=== Syntax check ==="
php tests/check-syntax.php
if [ $? -ne 0 ]; then status=1; fi

echo ""
echo "=== Language key check ==="
php tests/check-lang-keys.php
if [ $? -ne 0 ]; then status=1; fi

echo ""
echo "=== Embedded-tag check ==="
php tests/check-embedded-tags.php
if [ $? -ne 0 ]; then status=1; fi

echo ""
echo "=== Stray backslash check ==="
php tests/check-stray-backslashes.php
if [ $? -ne 0 ]; then status=1; fi

echo ""
echo "=== Identical-value check ==="
php tests/check-identical-values.php
if [ $? -ne 0 ]; then status=1; fi

echo ""
if [ $status -eq 0 ]; then
    echo "All checks passed."
else
    echo "One or more checks FAILED. See output above."
fi

exit $status
