#!/usr/bin/env bash

npm run lint:fix
cd ./frontend/assets/images/ && find . -name "*" -size -8k -delete