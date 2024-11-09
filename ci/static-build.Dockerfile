FROM --platform=linux/amd64 dunglas/frankenphp:static-builder

# Copy your app
WORKDIR /go/src/app/dist/app
COPY . .

# Remove the tests and other unneeded files to save space
# Alternatively, add these files to a .dockerignore file
RUN rm -Rf tests/

RUN cp .env .env.prod && cp .env.example .env

# Install the dependencies
RUN composer install --ignore-platform-reqs --no-dev -a

RUN cp .env.prod .env

RUN apk add --no-cache nodejs npm
RUN rm -rf node_modules/ package-lock.json
RUN npm install -g pnpm
RUN pnpm install && \
    pnpm run build
RUN rm -rf node_modules/ .pnpm-store/


# Build the static binary
WORKDIR /go/src/app/
RUN EMBED=dist/app/ NO_COMPRESS=1 ./build-static.sh
