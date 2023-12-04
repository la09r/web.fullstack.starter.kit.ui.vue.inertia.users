<template>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Login via Services</div>
                    <div class="card-body">
                        <div class="row mb-0 d-flex align-items-center">
                            <label class="col-md-4 col-form-label text-md-end">Services</label>
                            <div class="col-md-6">
                                <span class="cursor-pointer" @click="showModal(data.app.auth, service)" v-for="(service) in data.service">
                                    <span :class="'pi pi-' + service.service_id" style="color: black; font-size: 1.2rem;"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <Dialog v-model:visible="visible" :header="headerText" :style="{ border: '1px solid rgba(0, 0, 0, .175)', width: '25rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
        <div class="d-flex justify-content-between">
            <InputText type="text" v-model="username" placeholder="username" />
            <Button label="Login" icon="pi pi-user" @click="openAuthRoute" />
        </div>
    </Dialog>
</template>

<script setup>
import Button from "primevue/button"
import Dialog from 'primevue/dialog';
import InputText from 'primevue/inputtext';

import {useToast} from 'primevue/usetoast';
const toast = useToast();

import { ref } from "vue";

const visible = ref(false);
let headerText    = '';
let username      = '';
let urlAuthRoute  = '';
let serviceData   = {};

function getUrl(path, service_id, service_user_id) {
    return path.replace('{service_id}', service_id).replace('{service_user_id}', service_user_id);
}
function openAuthRoute()
{
    urlAuthRoute = getUrl(serviceData.url, serviceData.service_id, username)

    if(username.trim().length === 0)
    {
        toast.add({severity: 'error', summary: 'Empty username', life: 3000});
        return;
    }
    // console.log(window.location.origin + urlAuthRoute);
    window.location.href = window.location.origin + urlAuthRoute;
}
function showModal(url, service)
{
    serviceData = service;
    serviceData.url = url;

    username      = '';
    headerText    = service.name;
    visible.value = !visible.value;
}

</script>
<script>

export default {
    name: "AuthServices",
    methods: {

    },
    data() {
        return {
            template: {
                github: {
                    icon: {
                        svg: {
                            path: 'node_modules/primeicons/raw-svg/github.svg',
                            css:  'icon-svg-size-32',
                        }
                    },
                },
                google: {
                    icon: {
                        svg: {
                            path: 'node_modules/primeicons/raw-svg/google.svg',
                            css:  'icon-svg-size-32',
                        }
                    },
                }
            },
            data: []
        }
    },
    mounted() {
        const requestOptions = {
            method: "GET",
            headers: {
                "Accept": "application/json",
            },
        };
        fetch(window.location.origin + "/api/backend/nav/auth?pathname=" + window.location.pathname, requestOptions)
            .then(response => response.json())
            .then(data => {
                this.data = data ?? []
            });
    }
}
</script>

<style scoped>

</style>