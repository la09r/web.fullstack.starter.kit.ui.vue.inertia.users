import './bootstrap';

import { createApp, h } from 'vue';
import { createStore } from 'vuex'

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';

import PrimeVue from 'primevue/config';
import ToastService from 'primevue/toastservice';
import ConfirmationService from 'primevue/confirmationservice';

import 'primevue/resources/themes/bootstrap4-light-blue/theme.css'
import 'primeicons/primeicons.css'

import Nav from './components/Dashboard/Nav.vue';

if(document.querySelector('#app-nav'))
{
    const navApp = createApp(Nav)
        .use(PrimeVue)
        .use(ToastService)
        .use(ConfirmationService)
        .mount('#app-nav');
}
if(document.querySelector('#app'))
{
    // Create a new store instance.
    const store = createStore({
        state () {
            return {
                count: 0,
                page: {
                    visibleModalAdd: false,
                },
                component: {
                    'User_List_Index_DataTable': {
                        key: 0
                    }
                }
            }
        },
        mutations: {
            increment (state) {
                state.count++
            },
            toggleVisibleModalAdd (state) {
                state.page.visibleModalAdd = !state.page.visibleModalAdd;
            },
            forceRerender (name) {
                state.component[name].key += 1;
            }
        }
    });

    createInertiaApp({
        resolve(name) {
            let path  = '';
            let pages = '';

            switch (name)
            {
                case 'Permission/List':
                case 'Permission/Edit':
                case 'Role/List':
                case 'Role/Edit':
                case 'Status/List':
                case 'Status/Edit':
                case 'UserList/Index':
                case 'UserEdit/Index':
                    path  = `../../vendor/la09r/web-fullstack-starter-kit-ui-vue-inertia-users/src/resources/js/Pages/%PAGE_NAME%.vue`;
                    pages = import.meta.glob(`../../vendor/la09r/web-fullstack-starter-kit-ui-vue-inertia-users/src/resources/js/Pages/**/*.vue`)
                    break;
                case 'Welcome/Index':
                    path  = `../../vendor/la09r/web-fullstack-starter-kit-ui-vue-inertia/src/resources/js/Pages/%PAGE_NAME%.vue`;
                    pages = import.meta.glob(`../../vendor/la09r/web-fullstack-starter-kit-ui-vue-inertia/src/resources/js/Pages/**/*.vue`)
                    break;
                default:
                    path  = `./Pages/%PAGE_NAME%.vue`;
                    pages = import.meta.glob(`./Pages/**/*.vue`);
                    break;
            }

            return resolvePageComponent(path.replace('%PAGE_NAME%', name), pages);
        },
        setup({ el, App, props, plugin }) {
            return createApp({ render: () => h(App, props) })
                .mixin({ methods: { route }})
                .use(plugin)
                .use(PrimeVue)
                .use(ToastService)
                .use(ConfirmationService)
                .use(store)
                .mount(el)
        },
    });
}

import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

export function getUserData()
{
    const page = usePage();
    const data = computed(() => page.props);

    let user = {};
    try
    {
        user = data.value.user;
    }
    catch (e)
    {

    }

    return user;
}

export function createPassword() {

    let l = 'qwertyuiopasdfghjklzxcvbnm';

    let result = '';

    for(let i = 1; i <= 12; i++) {
        let o  = Math.floor(Math.random() * (l.length - 1));
        let lr = l.substr(o, 1);

        let n = Math.floor(Math.random() * 4);

        if(n == 1 || n == 4) { lr = lr.toUpperCase() }
        if(n == 3) { lr = Math.floor(Math.random() * 9) }

        result += lr;
    }

    return result;
}