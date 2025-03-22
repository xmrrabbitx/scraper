#!/usr/bin/bash

# Script to Test and Lint
# requirement:
# - https://github.com/jdx/mise installed
# - PHP versions defined in ../PHP_VERSIONS installed

CMD=mise
$CMD -v &> /dev/null
if [ $? -ne 0 ]; then
    echo "command [${CMD}] not found!"
    exit 1
fi
echo "-----------------------------------------------------------"
echo "[composer validate]"
composer validate
if [ $? -ne 0 ]; then
    echo "Operation aborted."
    exit 1
fi

switch_version() {
    echo "==========================================================="
    echo "[PHP $1][Switching PHP version to $1]"
    mise x php@$1 -- bash bin/TestAndLintSub.sh $1;
}

echo "[[TestAndLint.sh]]"

SUPPORTED_PHP_VERSIONS=PHP_VERSIONS
if [ ! -f $SUPPORTED_PHP_VERSIONS ]; then
    echo "file [$SUPPORTED_PHP_VERSIONS] not found."
    echo "operation aborted."
    exit 1
fi
if [ ! -r $SUPPORTED_PHP_VERSIONS ]; then
    echo "cannot read file[$SUPPORTED_PHP_VERSIONS]."
    echo "operation aborted."
    exit 1
fi
STR_CMD=''
while read version ; do
    STR_CMD="$STR_CMD switch_version $version;"
done < $SUPPORTED_PHP_VERSIONS
eval $STR_CMD
