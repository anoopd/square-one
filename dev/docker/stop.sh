#!/usr/bin/env bash

SCRIPTDIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )

cd "$SCRIPTDIR"

PROJECT_ID=$(cat ./.projectID)

echo "Stopping docker-compose project: ${PROJECT_ID}"
docker-compose --project-name=${PROJECT_ID} down