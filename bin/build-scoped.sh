#!/usr/bin/env bash
#
# Builds a production copy of the plugin with every bundled namespace prefixed.
#
#   bin/build-scoped.sh
#
# Output: build/advanced-control-manager/, ready to zip or deploy.

set -euo pipefail

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
BUILD="$ROOT/build"
SOURCE="$BUILD/source"
OUTPUT="$BUILD/advanced-control-manager"

rm -rf "$BUILD"
mkdir -p "$SOURCE"

# 1. Copy the distributable files, without dev tooling or installed dependencies.
rsync -a "$ROOT/" "$SOURCE/" \
    --exclude-from="$ROOT/.distignore" \
    --exclude=/build \
    --exclude=/tools \
    --exclude=/vendor \
    --exclude=/node_modules \
    --exclude=/bin \
    --exclude=/scoper.inc.php \
    --exclude=/c3.php \
    --exclude='/codeception*' \
    --exclude=/rector.php \
    --exclude=/studio.json \
    --exclude=/bower.json \
    --exclude=/docs \
    --exclude=/README.md
cp "$ROOT/composer.json" "$ROOT/composer.lock" "$SOURCE/"

# 2. Install production dependencies. --no-plugins keeps franzl/studio path packages out.
composer install --working-dir="$SOURCE" --no-dev --no-plugins --no-scripts --prefer-dist --no-progress --no-interaction
find "$SOURCE/vendor" -mindepth 3 -maxdepth 3 -type d \( -name tests -o -name test -o -name docs \) -prune -exec rm -rf {} +

# 3. Install the scoper tool if needed.
if [ ! -x "$ROOT/tools/php-scoper/vendor/bin/php-scoper" ]; then
    composer install --working-dir="$ROOT/tools/php-scoper" --no-progress --no-interaction
fi

# 4. Prefix everything.
(cd "$SOURCE" && "$ROOT/tools/php-scoper/vendor/bin/php-scoper" add-prefix \
    --config="$ROOT/scoper.inc.php" \
    --output-dir="$OUTPUT" \
    --force \
    --no-interaction)

# 5. Rebuild the autoloader for the prefixed classes.
composer dump-autoload --working-dir="$OUTPUT" --classmap-authoritative --no-dev --no-plugins --no-scripts
rm -f "$OUTPUT/composer.json" "$OUTPUT/composer.lock"

echo "Scoped build ready in $OUTPUT"
