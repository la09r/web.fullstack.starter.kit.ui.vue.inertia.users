<template>
    <NavLogin />
    <CardLayoutFluid title="User List" showButtonAdd="false" @callbackButtonAdd="showModalAddUser">

        <DataTableUser :key="componentKeyDataTable" @callbackButtonDelete="onDelete" @showModalEditUser="showModalEditUser"/>

        <Dialog v-model:visible="stateVisibleModalAdd" :modal="false" :closeOnEscape="false"
                :style="{ width: '80rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
            <template #container="{ stateVisibleModalAdd }">
                <div class="card">
                    <div class="card-header p-dialog-header">
                        <div class="p-dialog-title">{{ modalHeaderText }}</div>

                        <Button aria-label="Close" @click="showModalAddUser" text
                                class="p-1 w-full text-primary-50 border-1 border-white-alpha-30 hover:bg-white-alpha-10">
                            <svg width="12" height="12" viewBox="0 0 14 14" fill="none"
                                 xmlns="http://www.w3.org/2000/svg"
                                 class="p-icon p-dialog-header-close-icon">
                                <path
                                    d="M8.01186 7.00933L12.27 2.75116C12.341 2.68501 12.398 2.60524 12.4375 2.51661C12.4769 2.42798 12.4982 2.3323 12.4999 2.23529C12.5016 2.13827 12.4838 2.0419 12.4474 1.95194C12.4111 1.86197 12.357 1.78024 12.2884 1.71163C12.2198 1.64302 12.138 1.58893 12.0481 1.55259C11.9581 1.51625 11.8617 1.4984 11.7647 1.50011C11.6677 1.50182 11.572 1.52306 11.4834 1.56255C11.3948 1.60204 11.315 1.65898 11.2488 1.72997L6.99067 5.98814L2.7325 1.72997C2.59553 1.60234 2.41437 1.53286 2.22718 1.53616C2.03999 1.53946 1.8614 1.61529 1.72901 1.74767C1.59663 1.88006 1.5208 2.05865 1.5175 2.24584C1.5142 2.43303 1.58368 2.61419 1.71131 2.75116L5.96948 7.00933L1.71131 11.2675C1.576 11.403 1.5 11.5866 1.5 11.7781C1.5 11.9696 1.576 12.1532 1.71131 12.2887C1.84679 12.424 2.03043 12.5 2.2219 12.5C2.41338 12.5 2.59702 12.424 2.7325 12.2887L6.99067 8.03052L11.2488 12.2887C11.3843 12.424 11.568 12.5 11.7594 12.5C11.9509 12.5 12.1346 12.424 12.27 12.2887C12.4053 12.1532 12.4813 11.9696 12.4813 11.7781C12.4813 11.5866 12.4053 11.403 12.27 11.2675L8.01186 7.00933Z"
                                    fill="currentColor"></path>
                            </svg>
                        </Button>

                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4">
                                <form @submit="onSubmit" class="">
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
                                    </div>
                                    <div>
                                        <Button type="submit" :label="modalSubmitButtonText"/>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </template>
        </Dialog>

    </CardLayoutFluid>
</template>

<script setup>

import DataTableUser from "./DataTableUser.vue";

import CardLayoutFluid from "@/Layouts/CardLayoutFluid.vue";
import NavLogin from "@/components/Dashboard/Nav.vue";
import {getUserData, createPassword} from "@/app.js";

import Button from "primevue/button"
import Dialog from 'primevue/dialog';

// form
import * as yup from "yup";
import {reactive} from "vue";
import {useField, useForm} from 'vee-validate';
import {useToast} from 'primevue/usetoast';
import InputGroup from 'primevue/inputgroup';
import InputGroupAddon from 'primevue/inputgroupaddon'
import InputText from 'primevue/inputtext';
import Toast from 'primevue/toast';

const form1Object = yup.object({
    name: yup.string().nullable().required().min(2),
    email: yup.string().nullable().required().min(2),
    password: yup.string().nullable().required().min(8),
    password_confirmation: yup.string().nullable().required().min(8),
});

// form validation intialize
const {handleSubmit, resetForm} = useForm({
    validationSchema: form1Object,
});
const USER_DATA = getUserData();
const PASSWORD  = createPassword();

const nameField = reactive(
    useField("name", undefined, {initialValue: null})
);
const emailField = reactive(
    useField("email", undefined, {initialValue: null})
);
const passwordField = reactive(
    useField("password", undefined, {initialValue: null})
);
const passwordConfirmationField = reactive(
    useField("password_confirmation", undefined, {initialValue: null})
);

window.dataSubmit = { method: 'insert',  id: null, };
window.resetForm = resetForm;

const toast = useToast();
const onEdit = async (values) => {
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
            window.dataSubmit = { method: 'update',  id: values.id };
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
window.onEditUser  = onEdit;

const onDelete = async (values) => {
    values['_token'] = USER_DATA.token.csrf;
    const requestOptions = {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json",
            // "Authorization": "Bearer " + USER_DATA.token.bearer ?? 0, // worl fine without token
        },
        body: JSON.stringify(values)
    };
    fetch(window.location.origin + "/api/backend/user/delete", requestOptions)
        .then(response => response.json())
        .then(data => {
            if (data.errors) {
                for (const field in data.errors) {
                    toast.add({severity: 'error', summary: data.errors[field][0], life: 3000});
                }
            } else {
                toast.add({severity: 'info', summary: 'User deleted', detail: values.value, life: 3000});
                forceRerender();
            }
        });
};
const onSubmit = handleSubmit(async (values) => {
    values['_token'] = USER_DATA.token.csrf;
    values['id']     = window.dataSubmit.id;
    const requestOptions = {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json",
            // "Authorization": "Bearer " + USER_DATA.token.bearer ?? 0, // worl fine without token
        },
        body: JSON.stringify(values)
    };
    fetch(window.location.origin + `/api/backend/user/${window.dataSubmit.method}`, requestOptions)
        .then(response => response.json())
        .then(data => {
            if (data.errors) {
                for (const field in data.errors) {
                    toast.add({severity: 'error', summary: data.errors[field][0], life: 3000});
                }
            } else {
                toast.add({severity: 'info', summary: 'Form Submitted', detail: values.value, life: 3000});
                const PASSWORD = createPassword();

                forceRerender();

                if(!window.dataSubmit.id)
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
                    window.dataSubmit = { method: 'insert',  id: null };
            }
        });
});
// form

import {ref} from 'vue';
import {useStore} from 'vuex'

const componentKeyDataTable = ref(0);

const forceRerender = () => {
    componentKeyDataTable.value += 1;
};

</script>
<script>

export default {
    props: {
        text: String,
    },
    data() {
        return {
            stateVisibleModalAdd: false,
            modalHeaderText: '',
            modalSubmitButtonText: '',
        }
    },
    mounted() {

    },
    methods: {
        showModalAddUser(arg) {
            window.dataSubmit = { method: 'insert',  id: null };

            this.modalHeaderText = 'Add';
            this.modalSubmitButtonText = 'Submit';

            this.$store.commit('toggleVisibleModalAdd');
            this.stateVisibleModalAdd = this.$store.state.page.visibleModalAdd;

            window.resetForm({
                values: {
                    name:  '',
                    email: '',
                    password: '',
                    password_confirmation: '',
                },
            });
        },
        showModalEditUser(arg){
            this.modalHeaderText = 'Edit';
            this.modalSubmitButtonText = 'Save';

            this.$store.commit('toggleVisibleModalAdd');
            this.stateVisibleModalAdd = this.$store.state.page.visibleModalAdd;

            window.onEditUser(arg);
        }
    }
};
</script>