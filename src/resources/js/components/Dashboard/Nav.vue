<template>
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" :href="data.app.url">{{ data.app.name }}</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ data.app.support }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent" style="min-height: 50px;">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav me-auto">

                </ul>
                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ms-auto">
                    <!-- Authentication Links -->
                    <template v-if="!data.app.auth">
                        <li class="nav-item" v-if="data.menu.guest.login.condition">
                            <a class="nav-link" :href="data.menu.guest.login.link">{{ data.menu.guest.login.title }}</a>
                        </li>
                        <li class="nav-item" v-if="data.menu.guest.public.condition">
                            <a class="nav-link" :href="data.menu.guest.public.link">{{ data.menu.guest.public.title }}</a>
                        </li>
                    </template>
                    <template v-if="data.app.auth">
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ data.menu.user.dashboard.info.name }}
                            </a>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <template v-if="data.menu.user.dashboard.menu.condition">
                                    <Link class="dropdown-item" :href="item.link" v-for="item in data.menu.user.dashboard.menu.items">
                                        {{ item.title }}
                                    </Link>
                                    <div style="height: 48px;"></div>
                                    <div><hr class="dropdown-divider"></div>
                                </template>
                                <template v-if="data.menu.user.dashboard.index.condition">
                                    <Link class="dropdown-item" :href="data.menu.user.dashboard.index.link" >
                                        {{ data.menu.user.dashboard.index.title }}
                                    </Link>
                                    <div><hr class="dropdown-divider"></div>
                                </template>
                                <template v-if="data.menu.user.public.condition">
                                    <a class="dropdown-item" :href="data.menu.user.public.link" >
                                        {{ data.menu.user.public.title }}
                                    </a>
                                    <div><hr class="dropdown-divider"></div>
                                </template>
                                <button class="dropdown-item" :href="data.menu.user.dashboard.logout.link" @click="logout">
                                    {{ data.menu.user.dashboard.logout.title }}
                                </button>
                            </div>
                        </li>
                    </template>
                </ul>
            </div>
        </div>
    </nav>
    <Toast />
    <ConfirmDialog />
</template>

<script setup>
    import {getUserData} from "@/app.js";
    import { Link } from '@inertiajs/vue3'
    import ConfirmDialog from 'primevue/confirmdialog';
    import Toast from 'primevue/toast';

    import { useConfirm } from "primevue/useconfirm";
    window.PRIMEVUE_SERVICE_CONFIRM = useConfirm();
</script>
<script>
    export default {
        data() {
            return {
                data: {
                    app: {
                        token: {

                        }
                    },
                    menu: {
                        guest: {
                            login: {

                            },
                            public: {

                            },
                        },
                        user: {
                            public: {

                            },
                            dashboard: {
                                index: {

                                },
                                logout: {

                                },
                                info: {

                                },
                                menu: {
                                    items: {

                                    }
                                },
                            }
                        }
                    }
                },
            }
        },
        methods: {
            logout() {
                window.PRIMEVUE_SERVICE_CONFIRM.require({
                    message: 'Do you want to logout?',
                    header: 'Logout',
                    icon: 'pi pi-info-circle',
                    rejectClass: 'p-button-text p-button-text',
                    acceptClass: 'p-button-danger p-button-text',
                    accept: () => {
                        const requestOptions = {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "Accept": "application/json",
                            },
                            body: JSON.stringify({_token: this.data.app.token.csrf ?? '',}),
                        };
                        fetch(window.location.origin + "/api/backend/nav/logout", requestOptions)
                            .then(data => {
                                window.location.href = window.location.origin;
                            });
                    },
                    reject: () => {

                    }
                });
            }
        },
        mounted() {
            const USER_DATA = getUserData();
            const requestOptions = {
                method: "GET",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                },
            };
            fetch(window.location.origin + "/api/backend/nav/select?pathname=" + window.location.pathname, requestOptions)
                .then(response => response.json())
                .then(data => {
                    this.data = data
                });
        }
    }
</script>
