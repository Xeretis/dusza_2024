mkdir dist
docker buildx --cache-from type=gha --cache-to type=gha,mode=max bake --load static-builder
docker cp $(docker create --name static-builder dunglas/frankenphp:static-builder):/go/src/app/dist/frankenphp-linux-$(uname -m) ./dist/larawhale_linux-x86_64
docker rm static-builder
