# Manual

## Requirements

1. **Homestead** virtual machine:
  - installed and configured for cli: PHP v. 8.1, Composer, Git
2. **Host** machine:
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
    LA09R\StarterKit\UI\Vue\Inertia\Users\App\Providers\AuthServiceProvider::class,
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
                    RoleListIndex: {
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
                    },
                    RoleForm: {
                        route: '',
                        text: {
                            buttonSubmitText: '',
                            headerText: '',
                        },
                        parameters: {},
                    },
                }
            },
            mutations: {
                usersRoleListIndexModalUpdateCommit: function(state) {
                    state.users.Page.RoleListIndex.visibleModalAdd = !state.users.Page.RoleListIndex.visibleModalAdd;
                },
                usersUserListIndexModalUpdateCommit: function(state) {
                    state.users.Page.UserListIndex.visibleModalAdd = !state.users.Page.UserListIndex.visibleModalAdd;
                },
                usersRoleFormCommit: function(state, arg) {
                    state.users.Component.RoleForm = {
                        route:  arg.route,
                        text:   arg.text,
                        parameters: arg.parameters,
                    };
                },
                usersUserProfileFormCommit: function(state, arg) {
                    state.users.Component.UserProfileForm = {
                        route:  arg.route,
                        text:   arg.text,
                        parameters: arg.parameters,
                    };
                },
                usersRoleListIndexUpdateCommit: function(state) {
                    state.users.Page.RoleListIndex.forceUsersListCounter += 1;
                },
                usersUserListIndexUpdateCommit: function(state) {
                    state.users.Page.UserListIndex.forceUsersListCounter += 1;
                },
            }
        },
        name: "Users",
        pages: {
            match: ['UserList/Index', 'UserProfile/Index', 'RoleList/Index', 'Welcome/Index'],
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
    },
    ```