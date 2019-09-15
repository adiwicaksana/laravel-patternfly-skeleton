#!/usr/bin/env bash

LAST_TAG=$(git describe --tags)

STRING_REPLACE="${LAST_TAG//[vV\-release]/}"

LOCAL=./.env
SEARCH='\(APP_VERSION=\).*'
REPLACE="\1${STRING_REPLACE}"

echo "Last TAG: $LAST_TAG"
echo "String Replace Last TAG: $STRING_REPLACE"

#ubuntu
sed -i "s/${SEARCH}/${REPLACE}/g" "$LOCAL"

#mac
#sed -i '' "s/${SEARCH}/${REPLACE}/g" "$LOCAL"