#!/usr/bin/bash
# exec into Homestead

git add . && git commit -m 'add last changes'

composer require la09r/web-fullstack-starter-kit-ui-vue-inertia-users:10.1.1

cp -R ./vendor/la09r/web-fullstack-starter-kit-ui-vue-inertia-users/src/app/Console/Commands ./app/Console

php artisan app:publish-skluviu

rm -rf ./app/Console/Commands/PublishSkluviu.php

git add . && git commit -m 'publish package'

