##!/usr/bin/env bash
#

# shellcheck disable=SC2002
set -a # automatically export all variables
source ".env"
set +a

docker compose up -d --build