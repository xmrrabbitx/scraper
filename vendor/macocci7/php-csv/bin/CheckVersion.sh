#!/usr/bin/bash

# Script to check
# if the version in composer.json
# is not in git tags

if [ ! -r composer.json ]; then
    echo "composer.json not found."
    echo "operation aborted."
    exit 1
fi

VERSION=$(cat composer.json | grep version | head -1 | grep -Po '\d+\.\d+\.\d+')
printf 'version in composer.json: \033[1;33m%s\033[m\n' $VERSION


show_latest_tags() {
    echo "The latest $1 tags are:"
    for tag in `git tag | sort | tail -$1`
    do
        printf '\033[93m%s\033[m\n' $tag
    done
}

for tag in `git tag`
do
    if [ $tag = $VERSION ]; then
        printf '\033[41m Error! version %s already exists in git tags. \033[m\n' $VERSION
        show_latest_tags 3
        exit 1
    fi
done
printf '\033[1;102m%s\033[m\n' " OK! versoin $VERSION is not in git tags. "
show_latest_tags 3
printf '\033[93m%s\033[m\n' "Don't forget to run \`composer update\` before commit."
