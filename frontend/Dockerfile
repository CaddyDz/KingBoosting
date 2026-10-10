# syntax=docker/dockerfile:1
# check=skip=SecretsUsedInArgOrEnv

# King Boosting storefront (Nuxt 2, server-side rendered).
#
# Targets:
#   runtime (default)  production `nuxt start` server on port 3301
#   dev                `nuxt` dev server with hot reload, used by compose.yaml (source is bind-mounted)
#
# HOST_URL (the API base URL), STRIPE_KEY (publishable key) and SENTRY_DSN are compiled
# into the bundles, so production builds take them as build args and changing them needs
# a rebuild. The build values also become the runtime defaults that `nuxt start` needs.
#
#   docker build --build-arg HOST_URL=https://api.example.com --build-arg STRIPE_KEY=pk_live_... -t kingboosting-frontend .

# Node 16 rather than the 14.x in package.json: Node 14 ships npm 6, which can't read the
# v3 package-lock.json, and webpack 4 (Nuxt 2) breaks on Node 17+ (OpenSSL 3). Node 16
# itself is end-of-life.
ARG NODE_VERSION=16

FROM node:${NODE_VERSION}-bookworm-slim AS base

# The node user is remapped to your host UID/GID so dev files on the bind mount belong to you.
ARG UID=1000
ARG GID=1000

RUN set -eux; \
	apt-get update; \
	apt-get upgrade -y; \
	apt-get install -y --no-install-recommends tini; \
	rm -rf /var/lib/apt/lists/*; \
	groupmod --non-unique --gid "$GID" node; \
	usermod --non-unique --uid "$UID" --gid "$GID" node; \
	mkdir /app; \
	chown node:node /app

WORKDIR /app

# The lockfile only installs with legacy peer-dependency handling (crc's optional peer
# buffer@>=6 conflicts with the buffer@4 that webpack 4 needs). As an ENV it also covers
# npm commands run inside the dev container.
ENV NUXT_HOST=0.0.0.0 \
	NUXT_TELEMETRY_DISABLED=1 \
	NPM_CONFIG_LEGACY_PEER_DEPS=true

USER node
EXPOSE 3301
ENTRYPOINT ["tini", "--"]

FROM base AS deps

ARG UID=1000
ARG GID=1000

COPY --chown=node:node package.json package-lock.json ./

# The stamp lets the dev entrypoint notice when package-lock.json changes later.
RUN --mount=type=cache,target=/home/node/.npm,uid=${UID},gid=${GID} \
	npm ci --no-audit --no-fund \
	&& sha1sum package-lock.json | cut -d' ' -f1 > node_modules/.package-lock.sha1

FROM deps AS dev

COPY --chmod=0755 docker/dev-entrypoint.sh /usr/local/bin/dev-entrypoint

ENTRYPOINT ["tini", "--", "dev-entrypoint"]
CMD ["npm", "run", "dev"]

FROM deps AS build

ARG HOST_URL
ARG STRIPE_KEY
ARG SENTRY_DSN

ENV HOST_URL=${HOST_URL} \
	STRIPE_KEY=${STRIPE_KEY} \
	SENTRY_DSN=${SENTRY_DSN}

COPY --chown=node:node . .

RUN test -n "$HOST_URL" || { echo "Pass the API base URL: --build-arg HOST_URL=https://..." >&2; exit 1; }; \
	npm run build

FROM base AS runtime

ARG HOST_URL
ARG SENTRY_DSN

ENV NODE_ENV=production \
	HOST_URL=${HOST_URL} \
	SENTRY_DSN=${SENTRY_DSN}

# Root-owned, so the server can read but not modify its code and assets.
USER root
RUN chown root:root /app && chmod 755 /app
USER node

COPY --from=build --chown=root:root /app/package.json /app/nuxt.config.js ./
COPY --from=build --chown=root:root /app/config ./config
COPY --from=build --chown=root:root /app/static ./static
COPY --from=build --chown=root:root /app/node_modules ./node_modules
COPY --from=build --chown=root:root /app/.nuxt ./.nuxt

HEALTHCHECK --interval=30s --timeout=5s --start-period=30s --retries=3 \
	CMD node -e "require('http').get('http://127.0.0.1:3301/favicon.ico', r => process.exit(r.statusCode === 200 ? 0 : 1)).on('error', () => process.exit(1))"

CMD ["node_modules/.bin/nuxt", "start"]
