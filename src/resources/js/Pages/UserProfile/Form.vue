<template>
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
                    <Button type="submit" :label="$store.state.users.Component.UserProfileForm.text.buttonSubmitText"/>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import Button from "primevue/button"
import InputGroup from 'primevue/inputgroup';
import InputGroupAddon from 'primevue/inputgroupaddon'
import InputText from 'primevue/inputtext';

import * as yup from "yup";
import {reactive} from "vue";
import {useField, useForm} from 'vee-validate';

import {getUserData, createPassword} from "@/app.js";

import {useStore} from 'vuex';
const store = useStore();

import {useToast} from 'primevue/usetoast';
const toast = useToast();

const form1Object = yup.object({
    name: yup.string().nullable().required().min(2),
    email: yup.string().nullable().required().min(2),
    password: yup.string().nullable().required().min(8),
    password_confirmation: yup.string().nullable().required().min(8),
});
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
    values['_token'] = USER_DATA.token.csrf;
    values['id']     = store.state.users.Component.UserProfileForm.parameters.id ?? 0;
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

window.resetFormPassword    = PASSWORD;
window.resetForm            = resetForm;
window.onEditUser           = onEdit;
window.userData             = USER_DATA;

</script>
<script>
export default {
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
            window.resetForm({
                values: {
                    name:  '',
                    email: '',
                    password:              window.resetFormPassword,
                    password_confirmation: window.resetFormPassword,
                },
            });
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