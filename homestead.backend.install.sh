#!/usr/bin/bash
# exec into Homestead

git add . && git commit -m 'add last changes'

composer require la09r/web-fullstack-starter-kit-ui-vue-inertia-users:10.0.1

php artisan vendor:publish --provider="LA09R\StarterKit\UI\Vue\Inertia\Users\App\Providers\AppServiceProvider" --force

git add . && git commit -m 'publish package'

