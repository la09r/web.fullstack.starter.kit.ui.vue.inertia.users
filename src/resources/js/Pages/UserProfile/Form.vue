<template>
    <form @submit="onSubmit" class="">
    <div class="row">
        <div class="col-4">
<!--            <form @submit="onSubmit" class="">-->
                <div class="h6">Info</div>
                <div class="mb-3">
                    <div class="mb-3">
                        <InputGroup>
                            <InputGroupAddon>Name</InputGroupAddon>
                            <InputText type="text" inputId="form_name_id"
                                       name="name"
                                       @input="nameField.handleChange"
                                       @blur="nameField.handleBlur"
                                       v-model="nameField.value"/>
                        </InputGroup>
                        <small class="mt-1 p-error d-block"
                               id="form_name_id-error">{{ nameField.errorMessage }}</small>
                    </div>
                    <div class="mb-3">
                        <InputGroup>
                            <InputGroupAddon>Email</InputGroupAddon>
                            <InputText type="text" inputId="form_email_id"
                                       name="email"
                                       @input="emailField.handleChange"
                                       @blur="emailField.handleBlur"
                                       v-model="emailField.value"/>
                        </InputGroup>
                        <small class="mt-1 p-error d-block"
                               id="form_email_id-error">{{ emailField.errorMessage }}</small>
                    </div>
                    <div class="mb-3">
                        <InputGroup>
                            <InputGroupAddon>Password</InputGroupAddon>
                            <InputText type="text" inputId="form_passwordField_id"
                                       name="password"
                                       @input="passwordField.handleChange"
                                       @blur="passwordField.handleBlur"
                                       v-model="passwordField.value"/>
                        </InputGroup>
                        <small class="mt-1 p-error d-block" id="form_password_id-error">{{
                                passwordField.errorMessage
                            }}</small>
                    </div>
                    <div class="mb-3">
                        <InputGroup>
                            <InputGroupAddon>Password Confirmation</InputGroupAddon>
                            <InputText type="text" inputId="form_password_confirmation_id"
                                       name="password_confirmation"
                                       @input="passwordConfirmationField.handleChange"
                                       @blur="passwordConfirmationField.handleBlur"
                                       v-model="passwordConfirmationField.value"/>
                        </InputGroup>
                        <small class="mt-1 p-error d-block"
                               id="form_password_confirmation_id-error">{{
                                passwordConfirmationField.errorMessage
                            }}</small>
                    </div>
                    <div class="mb-3">
                        <Dropdown v-model="userRoleSelected" :options="userRoleList" optionLabel="name" placeholder="Select a role" class="w-full md:w-14rem" />
                    </div>
                </div>

<!--            </form>-->
        </div>
        <div class="col-6">
            <div class="d-flex align-items-center">
                <div class="h6 mb-0 me-2">Auth services</div>
                <div>
                    <span class="p-float-label">
                        <Dropdown id="dd" v-model="authServicesDataSelected" :options="authServicesDataTemplate.filter((item) => !item.selected)" optionLabel="name" aria-describedby="dd-error" style="width: 150px;" class="me-2" />
                        <label for="dd">select</label>
                    </span>
                </div>
                <Button @click="authServicesSubmitAdd" icon="pi pi-plus" rounded raised class="small"></Button>
            </div>
            <div class="mb-2"></div>
            <div class="mb-3">
                <div v-for="(authService, index) in authServicesData" class="mb-3">
                    <h6>{{authService.name}}</h6>
                    <div class="d-flex">
                        <Textarea v-model="authServicesSubmit[index]" autoResize rows="5" style="width: 100%;" />
                        <div class="ps-2">
                            <Button @click="authServicesSubmitDelete(index)" icon="pi pi-times" label="" severity="danger" class="small"></Button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <div>
            <Button type="submit" :label="$store.state.users.Component.UserProfileForm.text.buttonSubmitText"/>
        </div>
    </form>
</template>

<script setup>
import Button from "primevue/button"
import InputGroup from 'primevue/inputgroup';
import InputGroupAddon from 'primevue/inputgroupaddon'
import InputText from 'primevue/inputtext';
import Dropdown from 'primevue/dropdown';
import Textarea from 'primevue/textarea';


import * as yup from "yup";
import {reactive} from "vue";
import {useField, useForm} from 'vee-validate';
import { ref, watchEffect } from "vue";


import {getUserData, createPassword} from "@/app.js";

import {useStore} from 'vuex';
const store = useStore();

import {useToast} from 'primevue/usetoast';
const toast = useToast();

const ROUTE = store.state.users.Component.UserProfileForm.route;

let form1Object = null;
if(ROUTE == 'insert')
{
    form1Object = yup.object({
        name: yup.string().nullable().required().min(2),
        email: yup.string().nullable().required().min(2),
        password: yup.string().nullable().required().min(8),
        password_confirmation: yup.string().nullable().required().min(8),
    });
}
else
{
    form1Object = yup.object({
        name: yup.string().nullable().required().min(2),
        email: yup.string().nullable().required().min(2),
        password: yup.string().nullable(),
        password_confirmation: yup.string().nullable(),
    });
}

const {handleSubmit, resetForm} = useForm({
    validationSchema: form1Object,
});

// form validation intialize

const USER_DATA = getUserData();
const PASSWORD  = store.state.users.Component.UserProfileForm.route === 'insert' ? createPassword() : null;

const nameField = reactive(
    useField("name", undefined, {initialValue: null})
);
const emailField = reactive(
    useField("email", undefined, {initialValue: null})
);
const passwordField = reactive(
    useField("password", undefined, {initialValue: PASSWORD})
);
const passwordConfirmationField = reactive(
    useField("password_confirmation", undefined, {initialValue: PASSWORD})
);

const authServicesDataSelected  = ref({});
const authServicesData          = ref([]);
let authServicesDataTemplate    = [];
let authServicesSubmit          = [];

function authServicesSubmitDelete(index)
{
    for(const authServicesTemplate of authServicesDataTemplate)
    {
        if(authServicesTemplate.service_id == authServicesData.value[index].service_id)
        {
            authServicesTemplate.selected = false;
            break;
        }
    }

    authServicesSubmit.splice(index, 1);
    authServicesData.value.splice(index, 1);
}
function authServicesSubmitAdd()
{
    if(!authServicesDataSelected.value.service_id) { return; }

    authServicesData.value.push(authServicesDataSelected.value);
    authServicesSubmit.push(authServicesDataSelected.value.json);

    for(const authServicesTemplate of authServicesDataTemplate)
    {
        if(authServicesTemplate.service_id == authServicesDataSelected.value.service_id)
        {
            authServicesTemplate.selected = true;
            break;
        }
    }
    authServicesDataSelected.value = {};
}

let userRoleSelected = ref({});
let userRoleList     = ref([]);

const onAdd = () => {
    const requestOptions = {
        method: "GET",
        headers: {
            "Accept": "application/json",
        },
    };
    fetch(window.location.origin + "/api/backend/user/new", requestOptions)
        .then(response => response.json())
        .then(data => {
            userRoleSelected = ref({});
            userRoleList     = ref(data.role_list);

            resetForm({
                values: {
                    name:  '',
                    email: '',
                    password:              PASSWORD,
                    password_confirmation: PASSWORD,
                },
            });
        });
};

const onEdit = async (values) => {
    // console.log(values);
    const requestOptions = {
        method: "GET",
        headers: {
            "Accept": "application/json",
            // "Authorization": "Bearer " + USER_DATA.token.bearer ?? 0, // worl fine without token
        },
    };
    fetch(window.location.origin + "/api/backend/user/select/" + values.id, requestOptions)
        .then(response => response.json())
        .then(data => {
            userRoleSelected = ref(data.role_selected);
            userRoleList     = ref(data.role_list);

                authServicesDataTemplate = data.auth_services_template;
            for (const authService of data.auth_services)
            {
                authServicesData.value.push(authService);
                authServicesSubmit.push(authService.json);
            }

            resetForm({
                values: {
                    name:  data.name,
                    email: data.email,
                    password: '',
                    password_confirmation: '',
                },
            });
        });
};

const onSubmit = handleSubmit(async (values) => {
    values['auth_services'] = [];
    for(let i = 0; i < authServicesData.value.length; i++)
    {
        values['auth_services'].push({
            name: authServicesData.value[i].name,
            service_id: authServicesData.value[i].service_id,
            value: authServicesSubmit[i],
        })
    }

    values['role_id'] = userRoleSelected.value.id;
    values['_token']  = USER_DATA.token.csrf;
    values['id']      = store.state.users.Component.UserProfileForm.parameters.id ?? 0;

    const requestOptions = {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json",
            // "Authorization": "Bearer " + USER_DATA.token.bearer ?? 0, // worl fine without token
        },
        body: JSON.stringify(values)
    };
    fetch(window.location.origin + `/api/backend/user/${store.state.users.Component.UserProfileForm.route}`, requestOptions)
        .then(response => response.json())
        .then(data => {
            if(0) //(data.id == USER_DATA.user_id)
            {
                const requestOptions = {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                    },
                    body: JSON.stringify({_token: USER_DATA.token.csrf ?? '',}),
                };
                fetch(window.location.origin + "/api/backend/nav/logout", requestOptions)
                    .then(data => {
                        window.location.href = window.location.origin;
                    });
            }


            if (data.errors) {
                for (const field in data.errors) {
                    toast.add({severity: 'error', summary: data.errors[field][0], life: 3000});
                }
            } else {
                toast.add({severity: 'info', summary: 'Form Submitted', detail: values.value, life: 3000});
                const PASSWORD = createPassword();

                store.commit('usersUserListIndexUpdateCommit');

                if(store.state.users.Component.UserProfileForm.route === 'insert')
                {
                    resetForm({
                        values: {
                            name:  '',
                            email: '',
                            password: PASSWORD,
                            password_confirmation: PASSWORD,
                        },
                    });
                }
            }
        });
});

window.onAddUser            = onAdd;
window.onEditUser           = onEdit;
window.userData             = USER_DATA;



</script>
<script>
export default {
    components: {

    },
    data() {
        return {

        }
    },
    name: "Form",
    props: {
        route: String
    },
    mounted() {

        if(this.route === 'profile')
        {
            this.$store.commit('usersUserProfileFormCommit', {
                route: 'update',
                text: {
                    buttonSubmitText: 'Save',
                    headerText: 'Save',
                },
                parameters: {id: window.userData.user_id}
            });
        }

        if(this.$store.state.users.Component.UserProfileForm.route === 'insert')
        {
            window.onAddUser();
        }
        if(this.$store.state.users.Component.UserProfileForm.route === 'update')
        {
            window.onEditUser(this.$store.state.users.Component.UserProfileForm.parameters);
        }
    },
    methods: {

    }
}
</script>

<style scoped>

</style>