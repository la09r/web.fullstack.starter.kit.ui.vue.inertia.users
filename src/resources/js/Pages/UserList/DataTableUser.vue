<template>
    <DataTable :value="users" paginator :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]" tableStyle="min-width: 50rem">
        <Column sortable field="id" header="ID"></Column>
        <Column sortable field="name" header="Name">
            <template #body="slotProps">
                <div class="d-flex align-items-center">
                    <Button class="me-2" @click="$emit('showModalEditUser', {id: slotProps.data.id})" icon="pi pi-user-edit" label="Edit" severity="primary"></Button>
                    <span>{{ slotProps.data.name }}</span>
                </div>
            </template>
        </Column>
        <Column sortable field="email" header="Email"></Column>
        <Column field="id" header="Action">
            <template #body="slotProps">
                <Button @click="confirmDelete({id: slotProps.data.id})" icon="pi pi-times" label="Delete" severity="danger"></Button>
            </template>
        </Column>
    </DataTable>
</template>

<script setup>
import Toast from 'primevue/toast';
import ConfirmDialog from 'primevue/confirmdialog';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from "primevue/button"

const emit = defineEmits(['created']);

const confirmDelete = (data) => {
    window.PRIMEVUE_SERVICE_CONFIRM.require({
        message: 'Do you want to delete this record?',
        header: 'Delete Confirmation',
        icon: 'pi pi-info-circle',
        rejectClass: 'p-button-text p-button-text',
        acceptClass: 'p-button-danger p-button-text',
        accept: () => {
            emit('callbackButtonDelete', data);
        },
        reject: () => {

        }
    });
};
</script>
<script>
export default {
    name: "DataTableUser",
    data() {
        return {
            users: [],
        }
    },
    mounted() {
        const requestOptions = {
            method: "GET",
            headers: {
                "Accept": "application/json",
                // "Authorization": "Bearer " + USER_DATA.token.bearer ?? 0, // worl fine without token
            },
        };
        fetch(window.location.origin + "/api/backend/user/list", requestOptions)
            .then(response => response.json())
            .then(data => {
                this.users = data
            });
    },
}
</script>

<style scoped>

</style>