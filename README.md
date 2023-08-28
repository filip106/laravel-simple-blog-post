# Blog post

Simple blog post application

## Local Environment

In order to start application

```shell
cp .env.docker.example .env
composer install
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan passport:install
./vendor/bin/sail artisan db:seed
./vendor/bin/sail artisan npm install
./vendor/bin/sail artisan npm run build
./vendor/bin/sail artisan test
```

Useful links:
[http://localhost](Application)
[http://localhost:8080](Swagger)
