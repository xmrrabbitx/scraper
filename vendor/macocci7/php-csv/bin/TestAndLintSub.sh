#!/usr/bin/bash

# Script to Test and Lint
# requirement:
# - PHP versions defined in ../PHP_VERSIONS installed

echo "-----------------------------------------------------------"
echo "[PHP $1][php -v]"
php -v
echo "-----------------------------------------------------------"
echo "[PHP $1][parallel-lint]"
./vendor/bin/parallel-lint src tests examples
#echo "-----------------------------------------------------------"
#echo "[PHP $1][neon-lint]"
#./vendor/nette/neon/bin/neon-lint conf
echo "-----------------------------------------------------------"
echo "[PHP $1][phpcs]"
./vendor/bin/phpcs --ignore=vendor \
                    --standard=phpcs.xml \
                    -p \
                    -s \
                    .
#echo "-----------------------------------------------------------"
#echo "[PHP $1][phpmd]"
#./vendor/bin/phpmd \
#                   ./src/ ./examples/ ./tests/ text \
#                   phpmd.xml
echo "-----------------------------------------------------------"
echo "[PHP $1][phpstan]"
./vendor/bin/phpstan analyze -c phpstan.neon
echo "-----------------------------------------------------------"
echo "[PHP $1][phpunit]"
./vendor/bin/phpunit ./tests/
echo "-----------------------------------------------------------"
