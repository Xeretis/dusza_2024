mkdir dist
docker buildx build --cache-to type=gha --cache-from type=gha,mode=max -t static-laravel-app -f static-build.Dockerfile .
docker cp $(docker create --name static-laravel-app-tmp static-laravel-app):/go/src/app/dist/frankenphp-linux-x86_64 ./dist/larawhale_linux-x86_64
docker rm static-laravel-app-tmp
