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
                            <InputGroupAddon>Code</InputGroupAddon>
                            <InputText type="text" inputId="form_code_id"
                                       name="code"
                                       @input="codeField.handleChange"
                                       @blur="codeField.handleBlur"
                                       v-model="codeField.value"/>
                        </InputGroup>
                        <small class="mt-1 p-error d-block"
                               id="form_code_id-error">{{ codeField.errorMessage }}</small>
                    </div>
                </div>
                <div>
                    <Button type="submit" :label="$store.state.users.Component.RoleForm.text.buttonSubmitText"/>
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

import {getUserData} from "@/app.js";

import {useStore} from 'vuex';
const store = useStore();

import {useToast} from 'primevue/usetoast';
const toast = useToast();

const form1Object = yup.object({
    name: yup.string().nullable().required().min(2),
    code: yup.string().nullable().required().min(2),
});
const {handleSubmit, resetForm} = useForm({
    validationSchema: form1Object,
});

// form validation intialize

const USER_DATA = getUserData();

const nameField = reactive(
    useField("name", undefined, {initialValue: null})
);
const codeField = reactive(
    useField("code", undefined, {initialValue: null})
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
    fetch(window.location.origin + "/api/backend/role/select/" + values.id, requestOptions)
        .then(response => response.json())
        .then(data => {
            resetForm({
                values: {
                    name:  data.name,
                    code: data.code,
                },
            });
        });
};

const onSubmit = handleSubmit(async (values) => {
    values['_token'] = USER_DATA.token.csrf;
    values['id']     = store.state.users.Component.RoleForm.parameters.id ?? 0;
    const requestOptions = {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json",
            // "Authorization": "Bearer " + USER_DATA.token.bearer ?? 0, // worl fine without token
        },
        body: JSON.stringify(values)
    };
    fetch(window.location.origin + `/api/backend/role/${store.state.users.Component.RoleForm.route}`, requestOptions)
        .then(response => response.json())
        .then(data => {
            if (data.errors) {
                for (const field in data.errors) {
                    toast.add({severity: 'error', summary: data.errors[field][0], life: 3000});
                }
            } else {
                toast.add({severity: 'info', summary: 'Form Submitted', detail: values.value, life: 3000});

                store.commit('usersRoleListIndexUpdateCommit');

                if(store.state.users.Component.RoleForm.route === 'insert')
                {
                    resetForm({
                        values: {
                            name:  '',
                            code: '',
                        },
                    });
                }
            }
        });
});

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

        if(this.$store.state.users.Component.RoleForm.route === 'insert')
        {
            window.resetForm({
                values: {
                    name:  '',
                    code: '',
                },
            });
        }
        if(this.$store.state.users.Component.RoleForm.route === 'update')
        {
            window.onEditUser(this.$store.state.users.Component.RoleForm.parameters);
        }
    },
    methods: {

    }
}
</script>

<style scoped>

</style>