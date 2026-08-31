#!/usr/bin/env bash
#
# Verifies the plugin's version is declared consistently across the three files
# that must always agree, and (optionally) that it matches a release tag.
#
# Usage:
#   check-versions.sh            # only checks the three files agree with each other
#   check-versions.sh <tag>      # also checks they match the given release tag
#
set -euo pipefail

header_version=$(grep -iE '^[[:space:]]*\*[[:space:]]*Version:' beautiful-taxonomy-filters.php \
  | head -1 | sed -E 's/.*Version:[[:space:]]*//' | tr -d '[:space:]')
readme_version=$(grep -iE '^Stable tag:' README.txt \
  | head -1 | sed -E 's/.*Stable tag:[[:space:]]*//' | tr -d '[:space:]')
class_version=$(grep -E '\$this->version[[:space:]]*=' includes/class-beautiful-taxonomy-filters.php \
  | head -1 | sed -E "s/.*=[[:space:]]*['\"]([^'\"]+)['\"].*/\1/")

echo "Plugin header (beautiful-taxonomy-filters.php) : ${header_version:-<empty>}"
echo "README.txt (Stable tag)                        : ${readme_version:-<empty>}"
echo "Class (\$this->version)                          : ${class_version:-<empty>}"

fail=0

for v in "$header_version" "$readme_version" "$class_version"; do
  if [ -z "$v" ]; then
    echo "::error::Could not extract one of the version strings."
    fail=1
  fi
done

if [ "$header_version" != "$readme_version" ] || [ "$header_version" != "$class_version" ]; then
  echo "::error::Version strings are out of sync across the three files."
  fail=1
fi

if [ "${1:-}" != "" ]; then
  tag="$1"
  echo "Release tag                                    : $tag"
  if [ "$header_version" != "$tag" ]; then
    echo "::error::Release tag ($tag) does not match the plugin version ($header_version)."
    fail=1
  fi
fi

if [ "$fail" -ne 0 ]; then
  echo "Version check FAILED."
  exit 1
fi

echo "Version check passed."
