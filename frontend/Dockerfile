# syntax=docker/dockerfile:1

# King Boosting web app (Nuxt 4): the storefront and every role's dashboard.
#
# Targets:
#   runtime (default)  production Nitro server on port 3000
#   dev                `nuxt dev` with hot reload, used by compose.yaml (the source is bind-mounted)
#
# Nothing is baked in at build time: NUXT_PUBLIC_STRIPE_KEY, NUXT_PUBLIC_REVERB_* and NUXT_API_BASE are
# read when the server starts, so one image runs in every environment.
#
#   docker build -t kingboosting-frontend frontend

ARG NODE_VERSION=22

FROM node:${NODE_VERSION}-bookworm-slim AS base

# The node user is remapped to your host UID/GID so dev files on the bind mount belong to you.
ARG UID=1000
ARG GID=1000

# npm 11: npm 10's installer crashes on this dependency tree, and npm 11 honours package.json's allowScripts.
RUN set -eux; \
	apt-get update; \
	apt-get upgrade -y; \
	apt-get install -y --no-install-recommends tini; \
	rm -rf /var/lib/apt/lists/*; \
	npm install -g npm@11 --no-audit --no-fund; \
	groupmod --non-unique --gid "$GID" node; \
	usermod --non-unique --uid "$UID" --gid "$GID" node; \
	mkdir /app; \
	chown node:node /app

WORKDIR /app

ENV NUXT_TELEMETRY_DISABLED=1 \
	HOST=0.0.0.0 \
	PORT=3000

ENTRYPOINT ["tini", "--"]

# -----------------------------------------------------------------------------
# Development: node_modules lives in a volume and follows package-lock.json.
# -----------------------------------------------------------------------------
FROM base AS dev

COPY --chmod=0755 docker/dev-entrypoint.sh /usr/local/bin/dev-entrypoint

# A named volume copies the mount point's owner from the image, so node_modules must already exist as node's
RUN install -d -o node -g node /app/node_modules /app/.nuxt

USER node
EXPOSE 3000

ENTRYPOINT ["tini", "--", "dev-entrypoint"]
CMD ["npm", "run", "dev"]

# -----------------------------------------------------------------------------
# Production build.
# -----------------------------------------------------------------------------
FROM base AS build

COPY package.json package-lock.json ./
RUN --mount=type=cache,target=/root/.npm npm ci --no-audit --no-fund

COPY . .
RUN npm run build

# -----------------------------------------------------------------------------
# Production runtime (default target): just the Nitro output.
# -----------------------------------------------------------------------------
FROM node:${NODE_VERSION}-bookworm-slim AS runtime

RUN set -eux; \
	apt-get update; \
	apt-get upgrade -y; \
	apt-get install -y --no-install-recommends tini; \
	rm -rf /var/lib/apt/lists/*

WORKDIR /app

ENV NODE_ENV=production \
	NUXT_TELEMETRY_DISABLED=1 \
	HOST=0.0.0.0 \
	PORT=3000

COPY --from=build --chown=node:node /app/.output ./.output

USER node
EXPOSE 3000

HEALTHCHECK --interval=30s --timeout=5s --start-period=20s --retries=3 \
	CMD node -e "fetch('http://127.0.0.1:3000/').then((r) => process.exit(r.ok ? 0 : 1)).catch(() => process.exit(1))"

ENTRYPOINT ["tini", "--"]
CMD ["node", ".output/server/index.mjs"]
