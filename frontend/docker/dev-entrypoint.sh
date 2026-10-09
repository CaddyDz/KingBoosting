#!/bin/sh
# Keeps the node_modules volume in sync with package-lock.json, then runs the
# container command (`npm run dev` by default).
set -e

lock_hash=$(sha1sum package-lock.json | cut -d' ' -f1)
stamp=node_modules/.package-lock.sha1

if [ "$(cat "$stamp" 2>/dev/null)" != "$lock_hash" ]; then
	echo "dev-entrypoint: package-lock.json changed, running npm ci"
	npm ci --no-audit --no-fund
	echo "$lock_hash" > "$stamp"
fi

exec "$@"
