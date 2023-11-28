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
  - in `config/app.php` in `providers`:
    ```php
    // add:
    LA09R\StarterKit\UI\Vue\Inertia\Users\App\Providers\RouteServiceProvider::class,
    ```
  - in `resources/js/packages.js` add:
    ```js
    users: {
        store: {
            state: {
                Page: {
                    UserListIndex: {
                        visibleModalAdd: false,
                        forceUsersListCounter: 0
                    },
                },
                Component: {
                    UserProfileForm: {
                        route: '',
                        text: {
                            buttonSubmitText: '',
                            headerText: '',
                        },
                        parameters: {},
                    }
                }
            },
            mutations: {
                usersUserListIndexModalUpdateCommit: function(state) {
                    state.users.Page.UserListIndex.visibleModalAdd = !state.users.Page.UserListIndex.visibleModalAdd;
                },
                usersUserProfileFormCommit: function(state, arg) {
                    state.users.Component.UserProfileForm = {
                        route:  arg.route,
                        text:   arg.text,
                        parameters: arg.parameters,
                    };
                },
                usersUserListIndexUpdateCommit: function(state) {
                    state.users.Page.UserListIndex.forceUsersListCounter += 1;
                },
            }
        },
        name: "Users",
        pages: {
            match: ['UserList/Index', 'UserProfile/Index', ],
            resolve: function () {
                return {
                    path: `../../vendor/la09r/web-fullstack-starter-kit-ui-vue-inertia-users/src/resources/js/Pages/%PAGE_NAME%.vue`,
                    pathImport: import.meta.glob(`../../vendor/la09r/web-fullstack-starter-kit-ui-vue-inertia-users/src/resources/js/Pages/**/*.vue`),
                }
            }
        },
        Components: {

        },
        ComponentsAsync: {
            'Dashboard/Widget': {
                basePath: 'resources/js/ComponentsAsync/Dashboard/Widget',
                data: [
                    { path: 'UsersStat' },
                ]
            }
        }
    }
    ```

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