<script setup>
import { FilterMatchMode } from 'primevue/api';
import { ref, onMounted, onBeforeMount } from 'vue';
import  UserResource  from '@/app/api/user';
import { useToast } from 'primevue/usetoast';

const toast = useToast();

const user = ref({});
const users = ref(null);
const userDialog = ref(null);
const deleteUserDialog = ref(null);
const deleteUsersDialog = ref(null);
const selectedUsers = ref(null);
const dt = ref(null);
const filters = ref({});
const submitted = ref(false);

const userResource = new UserResource();

onBeforeMount(() => {
    initFilters();
});

onMounted(() => {
    userResource.list().then((data) => {
        users.value = data.data;
    });
});

const openNew = () => {
    user.value = {};
    submitted.value = false;
    userDialog.value = true;
}
const hideDialog = () => {
    userDialog.value = false;
    submitted.value = false;
}

const saveUser = () => {
    submitted.value = true;
}

const editUser = (editUser) => {
    user.value = {...editUser}
    userDialog.value = true;
}

const confirmDeleteUser = (deleteUser) => {
    user.value = deleteUser;
    deleteUserDialog.value = true;
}

const deleteUser = () => {

}

const findIndexById = (id) => {
    let index = -1;
    for (let i = 0; i < users.value.length; i++) {
        if (users.value[i].id === id) {
            index = i;
            break;
        }
    }
    return index;
}

const confirmDeleteSelected = () => {
    deleteUsersDialog.value = true;
}

const deleteSelectedUsers = () => {

}

const initFilters = () => {
    filters.value = {
        global: { value: null, matchMode: FilterMatchMode.CONTAINS }
    };
};
</script>

<template>
    <div class="grid">
        <div class="col-12">
            <div class="card">
                <Toolbar class="mb-4">
                    <template v-slot:start>
                        <div class="my-2">
                            <Button label="New" icon="pi pi-plus" class="mr-2" severity="success" @click="openNew" />
                        </div>
                    </template>

                    <template v-slot:end>
                        <FileUpload mode="basic" accept="image/*" :maxFileSize="1000000" label="Import" chooseLabel="Import" class="mr-2 inline-block" disabled />
                        <Button label="Export" icon="pi pi-upload" severity="help" @click="exportCSV($event)" disabled />
                    </template>
                </Toolbar>

                <DataTable
                    ref="dt"
                    :value="users"
                    v-model:selection="selectedUsers"
                    dataKey="id"
                    :paginator="true"
                    :rows="10"
                    :filters="filters"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 25]"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} users"
                >
                    <template #header>
                        <div class="flex flex-column md:flex-row md:justify-content-between md:align-items-center">
                            <h5 class="m-0">Manage Users</h5>
                            <IconField iconPosition="left" class="block mt-2 md:mt-0">
                                <InputIcon class="pi pi-search" />
                                <InputText class="w-full sm:w-auto" v-model="filters['global'].value" placeholder="Search..." />
                            </IconField>
                        </div>
                    </template>
                    <Column field="document" header="Documento" :sortable="false" headerStyle="width:14%; min-width:10rem;">
                        <template #body="slotProps">
                            <span class="p-column-title">Documento</span>
                            {{ slotProps.data.document}}
                        </template>
                    </Column>
                    <Column field="name" header="Nombre" :sortable="false" headerStyle="width:31%; min-width:10rem;">
                        <template #body="slotProps">
                            <span class="p-column-title">Nombre</span>
                            {{ slotProps.data.name}}
                        </template>
                    </Column>
                    <Column field="email" header="Correo" :sortable="false" headerStyle="width:14%; min-width:10rem;">
                        <template #body="slotProps">
                            <span class="p-column-title">Correo Electronico</span>
                            {{ slotProps.data.email}}
                        </template>
                    </Column>
                    <Column field="ipress" header="Ipress" :sortable="false" headerStyle="width:31%; min-width:10rem;">
                        <template #body="slotProps">
                            <span class="p-column-title">IPRESS</span>
                            {{ slotProps.data.ipress ? slotProps.data.ipress.name : '' }}
                        </template>
                    </Column>
                    <Column field="role" header="Rol" :sortable="false" headerStyle="width:10%; min-width:10rem;">
                        <template #body="slotProps">
                            <span class="p-column-title">Rol</span>
                            {{ slotProps.data.roles.join(', ') }}
                        </template>
                    </Column>
                    <Column headerStyle="min-width:10rem;">
                        <template #body="slotProps">
                            <div v-if="!slotProps.data.roles.includes('superadmin')">
                                <Button icon="pi pi-pencil" class="mr-2" severity="success" rounded
                                    @click="editUser(slotProps.data)" />
                                <Button icon="pi pi-trash" class="mt-2" severity="warning" rounded
                                    @click="confirmDeleteUser(slotProps.data)" />
                            </div>
                        </template>
                    </Column>
                </DataTable>

                <Dialog v-model:visible="userDialog" :style="{ width: '720px' }" header="Detalles del usuario" :modal="true" class="p-fluid">
                    <div class="formgrid grid">
                        <div class="field col">
                            <label for="role">Rol</label>
                            <Dropdown id="role" v-model="user.role" :options="roles" optionLabel="name" placeholder="Select a Role" />
                        </div>
                        
                        <div class="field col">
                            <label for="document">Documento</label>
                            <InputText id="document" v-model.trim="user.document" :showIcon="true" required="true" autofocus :invalid="submitted && !user.document" />
                        </div>
                    </div>
                    <div class="field">
                        <label for="name">Nombres</label>
                        <InputText id="name" v-model.trim="user.name" required="true" autofocus :invalid="submitted && !user.name" />
                        <small class="p-invalid" v-if="submitted && !user.name">Name is required.</small>
                    </div>
                    <div class="field">
                        <label for="name">Apellidos</label>
                        <InputText id="name" v-model.trim="user.name" required="true" autofocus :invalid="submitted && !user.name" />
                        <small class="p-invalid" v-if="submitted && !user.name">Name is required.</small>
                    </div>
                    <div class="formgrid grid">
                        <div class="field col">
                            <label for="email">Email</label>
                            <InputText id="email" v-model.trim="user.email" required="true" autofocus :invalid="submitted && !user.email" />
                        </div>
                        <div class="field col">
                            <label for="phone">Telefono</label>
                            <InputText id="phone" v-model.trim="user.phone" required="true" autofocus :invalid="submitted && !user.phone" />
                        </div>
                    </div>
                     <div class="formgrid grid">
                        <div class="field col">
                            <label for="birthdate">Fecha de nacimiento</label>
                            <Calendar id="birthdate" v-model="user.birthdate" dateFormat="dd/mm/yy" required="true" autofocus :invalid="submitted && !user.birthdate" />
                        </div>
                        <div class="field col">
                            <label for="sex">Sexo</label>
                            <Dropdown id="sex" v-model="user.sex" :options="sexes" optionLabel="name" placeholder="Select a Sex" />
                        </div>
                    </div>
                     <div class="formgrid grid">
                        <div class="field col">
                            <label for="department">Departamento</label>
                            <Dropdown id="department" v-model="user.department" :options="departments" optionLabel="name" placeholder="Select a Department" />
                        </div>
                        <div class="field col">
                            <label for="province">Provincia</label>
                            <Dropdown id="province" v-model="user.province" :options="provinces" optionLabel="name" placeholder="Select a Province" />
                        </div>
                        <div class="field col">
                            <label for="district">Distrito</label>
                            <Dropdown id="district" v-model="user.district" :options="districts" optionLabel="name" placeholder="Select a District" />
                        </div>
                    </div>
                    <div class="field">
                        <label for="ipress">Ipress</label>
                        <Dropdown id="ipress" v-model="user.ipress" :options="ipresses" optionLabel="name" placeholder="Select an Ipress" />
                    </div>

                    <div class="formgrid grid">
                        <div class="field col">
                            <label for="password">Contraseña</label>
                            <InputText id="password" v-model.trim="user.password" required="true" autofocus :invalid="submitted && !user.password" />
                        </div>
                        <div class="field col">
                            <label for="confirm-password">Repita la contraseña</label>
                            <InputText id="confirm-password" v-model.trim="user.confirm_password" required="true" autofocus :invalid="submitted && !user.confirm_password" />
                        </div>
                    </div>
                    <template #footer>
                        <Button label="Cancel" icon="pi pi-times" text="" @click="hideDialog" />
                        <Button label="Save" icon="pi pi-check" text="" @click="saveuser" />
                    </template>
                </Dialog>

                <!-- <Dialog v-model:visible="deleteuserDialog" :style="{ width: '450px' }" header="Confirm" :modal="true">
                    <div class="flex align-items-center justify-content-center">
                        <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
                        <span v-if="user"
                            >Are you sure you want to delete <b>{{ user.name }}</b
                            >?</span
                        >
                    </div>
                    <template #footer>
                        <Button label="No" icon="pi pi-times" text @click="deleteuserDialog = false" />
                        <Button label="Yes" icon="pi pi-check" text @click="deleteuser" />
                    </template>
                </Dialog> -->

                <!-- <Dialog v-model:visible="deleteusersDialog" :style="{ width: '450px' }" header="Confirm" :modal="true">
                    <div class="flex align-items-center justify-content-center">
                        <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
                        <span v-if="user">Are you sure you want to delete the selected users?</span>
                    </div>
                    <template #footer>
                        <Button label="No" icon="pi pi-times" text @click="deleteusersDialog = false" />
                        <Button label="Yes" icon="pi pi-check" text @click="deleteSelectedusers" />
                    </template>
                </Dialog> -->
            </div>
        </div>
    </div>
</template>
