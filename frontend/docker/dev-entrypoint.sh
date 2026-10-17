#!/bin/sh
# Development entrypoint: install node_modules on a fresh clone, and again whenever package-lock.json
# changes, then run the container command (`npm run dev` by default).
set -e

lock_hash=$(sha1sum package-lock.json | cut -d' ' -f1)
stamp=node_modules/.package-lock.sha1

if [ "$(cat "$stamp" 2>/dev/null)" != "$lock_hash" ]; then
	echo "dev-entrypoint: installing node_modules"
	npm ci --no-audit --no-fund
	echo "$lock_hash" > "$stamp"
fi

exec "$@"
