# Manual

## Requirements

1. **Homestead** virtual machine:
  - installed and configured for cli: PHP v. 8.1, Composer, Git
2**Host** machine:
  - installed and configured for cli: Node(JavaScript runtime environment) v. 18

## Install

1. into **Homestead** virtual machine:
  - change directory to `DOCUMENT_ROOT `: `cd /home/vagrant/code/81/home/test/http/laravel/d`
  - copy **homestead.backend..sh** files to `DOCUMENT_ROOT`
  - update **+x** permission for ***.sh** files,
  - exec `homestead.backend..1.sh`
  - in `config/app.php` in `aliases`:
    ```php
    // remove:
    `App\Providers\RouteServiceProvider::class` => ..
    // add:
    App\Http\Controllers\Auth\LoginController::class => LA09R\StarterKit\UI\Vue\Inertia\Users\App\Http\Controllers\Auth\LoginController::class
    App\Providers\RouteServiceProvider::class        => LA09R\StarterKit\UI\Vue\Inertia\Users\App\Providers\RouteServiceProvider::class,
    ```
  - in `config/app.php` in `providers`:
    ```php
    // remove:
    LA09R\StarterKit\UI\Vue\Inertia\App\Providers\RouteServiceProvider::class
    // add:
    LA09R\StarterKit\UI\Vue\Inertia\Users\App\Providers\RouteServiceProvider::class,
    ```
  - in `app/Http/Kernel.php` in `protected $middleware array`:
    ```php
    // remove:
    \LA09R\StarterKit\UI\Vue\Inertia\App\Http\Middleware\HandleInertiaRequests::class,
    // add:
    \LA09R\StarterKit\UI\Vue\Inertia\Users\App\Http\Middleware\HandleInertiaRequests::class,
    ```
  - in `app/Http/Kernel.php` uncomment
    ```php
    \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
    ```
    
2. on **Host** machine:
  - copy **host.frontend..sh** files to `DOCUMENT_ROOT`
  - update **+x** permission for ***.sh** files,
  - replace `#!/bin/zsh` from **host.frontend..sh** files to you **bash** bin path
  - exec `host.frontend.install.sh` on **Host** machine
  - `npm run build` and chekout browser `APP_URL `

## Note

Frontend NPM packages with data tables for Vue, November 2023:
- Suite
  - https://primevue.org/datatable/#single_sort
  - https://vuetifyjs.com/en/components/data-tables/basics/
  - https://bootstrap-vue.org/docs/components/table#pagination
  - https://buefy.org/documentation/table
- Library
  - https://happy-coding-clans.github.io/vue-easytable/#/en/demo?ref=madewithvuejs.com
  - https://revolist.github.io/revogrid/demo/
  - https://handsontable.com/docs/javascript-data-grid/vue3-installation/?ref=madewithvuejs.com
  - https://xaksis.github.io/vue-good-table/guide/configuration/sort-options.html#enabled
  - https://njleonzhang.github.io/vue-data-tables/#/en-us/sort
  - https://github.com/DataTables/Vue
  - https://github.com/TheoXiong/vue-table-dynamic
  - https://niiknow.github.io/vue-datatables-net/
  - https://github.com/iliakut/food-table
  - https://github.com/aquilesb/v-datatable-light
  - https://github.com/HC200ok/vue3-easy-data-table