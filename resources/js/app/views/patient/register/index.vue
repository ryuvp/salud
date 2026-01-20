<script setup>
import { FilterMatchMode } from 'primevue/api';
import { ref, onMounted, onBeforeMount, nextTick, computed } from 'vue';
import UserResource from '@/app/api/user';
import ReniecResource from '@/app/api/reniec';
import UbigeoResource from '@/app/api/ubigeo';
import IpressResource from '@/app/api/ipress';
import { userStore } from "@/app/store/user";
import { useToast } from 'primevue/usetoast';
import AppLoadingScreen from '@/app/views/layout/AppLoadingScreen.vue';

const toast = useToast();

const user = ref({});
const users = ref(null);
const departments = ref(null);
const provinces = ref(null);
const districts = ref(null);
const provincesUbigeo = ref(null);
const districtsUbigeo = ref(null);
const ipresses = ref(null);
const ubigeo = ref({});
const ipress = ref({});
const userDialog = ref(null);
const deleteUserDialog = ref(null);
const dt = ref(null);
const filters = ref({});
const submitted = ref(false);
const isDocumentChecked = ref(false);
const isFieldsDisabled = ref(false);
const isEditing = ref(false);
const sexes = ref([
    { name: 'Masculino', id: 0 },
    { name: 'Femenino', id: 1 }
]);
const loading = ref(false);

const store = userStore();
const storedRoles = computed(() => {
    return store.roles;
})

const userResource = new UserResource();
const reniecResource = new ReniecResource();
const ubigeoResource = new UbigeoResource();
const ipressResource = new IpressResource();

onBeforeMount(() => {
    initFilters();
});

onMounted(() => {
    loadUsers();
});

const openNew = () => {
    user.value = {};
    submitted.value = false;
    userDialog.value = true;
    loadDepartments();
}

const loadUsers = async () => {
    try {
        const data = await userResource.patients();
        users.value = data.data;
    } catch (error) {
        toast.add({ severity: 'error', summary: 'Error', detail: 'Error al cargar los usuarios', life: 3000 });
    }
};

const loadDepartments = () => {
    ubigeoResource.departments().then((data) => {
        departments.value = data.filter(department => department.id == 10);
    });
}

const loadProvinces = () => {
    ubigeoResource.provinces(ipress.value.department.id).then((data) => {
        provinces.value = data;
    });
}

const loadProvincesUbigeo = () => {
    ubigeoResource.provinces(ubigeo.value.department.id).then((data) => {
        provincesUbigeo.value = data;
    });
}
const loadDistricts = () => {
    ubigeoResource.districts(ipress.value.province.id).then((data) => {
        districts.value = data;
    });
}
const loadDistrictsUbigeo = () => {
    ubigeoResource.districts(ubigeo.value.province.id).then((data) => {
        districtsUbigeo.value = data;
    });
}

const loadIpresses = () => {
    ipressResource.ipress(ipress.value.district.ubigeo_inei).then((data) => {
        ipresses.value = data;
    })
}
const hideDialog = () => {
    user.value = {};
    ubigeo.value = {};
    userDialog.value = false;
    submitted.value = false;
    isFieldsDisabled.value = false;
    isEditing.value = false;
    isDocumentChecked.value = false;
}

const saveUser = async () => {
    submitted.value = true;
    if (!isEditing) {
        if (!user.value.name || !user.value.lastname || !user.value.document ||
            !user.value.password || user.value.password !== user.value.confirm_password ||
            (user.value.sex === null || user.value.sex === undefined)) {
            return;
        }
    } else {
        if (!user.value.name || !user.value.lastname || !user.value.document ||
            (user.value.sex === null || user.value.sex === undefined)) {
            return;
        }
    }
    try {
        user.value.ubigeo = ubigeo.value.district.ubigeo_inei;
        let response;
        if (isEditing.value) {
            response = await userResource.update(user.value.id, user.value);
        } else {
            response = await userResource.storePatient(user.value);
        }

        if (response) {
            const { status, message } = response;
            if (status === "success") {
                user.value = {};
                ubigeo.value = {};
                userDialog.value = false;
                loadUsers();
                toast.add({ severity: status, summary: status, detail: message, life: 3000 });
                submitted.value = false;
                isEditing.value = false;
            } else {
                toast.add({ severity: status, summary: status, detail: message, life: 3000 });
            }
        }
    } catch (error) {
        toast.add({ severity: 'error', summary: 'Error', detail: error, life: 3000 });
    }
}

const parseBirthdate = (birthdate) => {
    if (!birthdate || birthdate instanceof Date) {
        return birthdate ?? null;
    }
    if (typeof birthdate !== 'string') {
        return null;
    }
    const isoMatch = birthdate.match(/^(\d{4})-(\d{2})-(\d{2})/);
    if (!isoMatch) {
        return null;
    }
    const year = Number(isoMatch[1]);
    const month = Number(isoMatch[2]);
    const day = Number(isoMatch[3]);
    return new Date(year, month - 1, day);
};

const editUser = (editUser) => {
    user.value = { ...editUser }
    user.value.birthdate = parseBirthdate(editUser.birthdate);
    ubigeo.value = {
        department: editUser.department ?? null,
        province: editUser.province ?? null,
        district: editUser.district ?? null,
    };
    loadDepartments();
    if (ubigeo.value.department?.id) {
        loadProvincesUbigeo();
    }
    if (ubigeo.value.province?.id) {
        loadDistrictsUbigeo();
    }
    userDialog.value = true;
    isEditing.value = true;
}

const confirmDeleteUser = (deleteUser) => {
    user.value = deleteUser;
    deleteUserDialog.value = true;
}

const deleteUser = () => {
    userResource.destroy(user.value.id).then((data) => {
        const { status, message } = data;
        if (status === "success") {
            deleteUserDialog.value = false;
            loadUsers();
            toast.add({ severity: status, summary: status, detail: message, life: 3000 });
        } else {
            toast.add({ severity: status, summary: status, detail: message, life: 3000 });
        }
    });
}

const initFilters = () => {
    filters.value = {
        global: { value: null, matchMode: FilterMatchMode.CONTAINS }
    };
};

const checkDocument = async () => {
    if (isDocumentChecked.value) return;

    isDocumentChecked.value = true;
    loading.value = true;

    try {
        const data = await reniecResource.get(user.value.document);

        if (data.success) {
            const { nombres, apellido_paterno, apellido_materno } = data.data;
            user.value.name = nombres;
            user.value.lastname = `${apellido_paterno} ${apellido_materno}`;
            isFieldsDisabled.value = true;
            focusField('email');
        } else {
            showWarning(data.message);
            isDocumentChecked.value = false;
            isFieldsDisabled.value = false;
            focusField('name');
        }
    } catch (error) {
        // Manejo del error si es necesario
    } finally {
        loading.value = false;
    }
};

const focusField = (fieldId) => {
    nextTick(() => {
        const field = document.getElementById(fieldId);
        if (field) {
            field.focus();
        }
    });
};

const showWarning = (message) => {
    toast.add({
        severity: 'warn',
        summary: 'Alerta',
        detail: `${message} Ingrese los datos manualmente`,
        life: 3000,
    });
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
                        <FileUpload mode="basic" accept="image/*" :maxFileSize="1000000" label="Import"
                            chooseLabel="Import" class="mr-2 inline-block" disabled />
                        <Button label="Export" icon="pi pi-upload" severity="help" @click="exportCSV($event)"
                            disabled />
                    </template>
                </Toolbar>

                <DataTable ref="dt" :value="users" dataKey="id" :paginator="true" :rows="10" :filters="filters"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 25]"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} users">
                    <template #header>
                        <div class="flex flex-column md:flex-row md:justify-content-between md:align-items-center">
                            <h5 class="m-0">Administrar Pacientes</h5>
                            <IconField iconPosition="left" class="block mt-2 md:mt-0">
                                <InputIcon class="pi pi-search" />
                                <InputText class="w-full sm:w-auto" v-model="filters['global'].value"
                                    placeholder="Search..." />
                            </IconField>
                        </div>
                    </template>
                    <Column field="document" header="Documento" :sortable="false"
                        headerStyle="width:14%; min-width:10rem;">
                        <template #body="slotProps">
                            <span class="p-column-title">Documento</span>
                            {{ slotProps.data.document }}
                        </template>
                    </Column>
                    <Column field="name" header="Nombre" :sortable="false" headerStyle="width:31%; min-width:10rem;">
                        <template #body="slotProps">
                            <span class="p-column-title">Apellidos y Nombres</span>
                            {{ slotProps.data.lastname }}, {{ slotProps.data.name }}
                        </template>
                    </Column>
                    <Column field="age" header="Edad" :sortable="false" headerStyle="width:14%; min-width:10rem;">
                        <template #body="slotProps">
                            <span class="p-column-title">Edad</span>
                            {{ slotProps.data.age }}
                        </template>
                    </Column>
                    <Column field="sex" header="Sexo" :sortable="false" headerStyle="width:10%; min-width:10rem;">
                        <template #body="slotProps">
                            <span class="p-column-title">Sexo</span>
                            {{ slotProps.data.sex_format }}
                        </template>
                    </Column>
                    <Column field="ipress" header="Ipress" :sortable="false" headerStyle="width:31%; min-width:10rem;">
                        <template #body="slotProps">
                            <span class="p-column-title">IPRESS</span>
                            {{ slotProps.data.ipress ? slotProps.data.ipress.name : '' }}
                        </template>
                    </Column>
                    <Column headerStyle="min-width:10rem;">
                        <template #body="slotProps">
                            <Button icon="pi pi-pencil" class="mr-2" severity="success" rounded
                                @click="editUser(slotProps.data)" />
                            <Button icon="pi pi-trash" class="mt-2" severity="warning" rounded
                                @click="confirmDeleteUser(slotProps.data)" />
                        </template>
                    </Column>
                </DataTable>

                <Dialog v-model:visible="userDialog" :style="{ width: '720px' }" header="Detalles del usuario"
                    :modal="true" class="p-fluid">
                    <div v-if="storedRoles.some(role => role.name === 'superadmin' || role.name === 'administrator')">
                        <div class="formgrid grid">
                            <div class="field col">
                                <label for="department">Departamento</label>
                                <Dropdown id="department" v-model="ipress.department" @change="loadProvinces"
                                    :options="departments" optionLabel="name" dataKey="id"
                                    placeholder="Select a Department" />
                            </div>
                            <div class="field col">
                                <label for="province">Provincia</label>
                                <Dropdown id="province" v-model="ipress.province" @change="loadDistricts"
                                    :options="provinces" optionLabel="name" dataKey="id"
                                    placeholder="Select a Province" />
                            </div>
                            <div class="field col">
                                <label for="district">Distrito</label>
                                <Dropdown id="district" v-model="ipress.district" @change="loadIpresses"
                                    :options="districts" optionLabel="name" dataKey="id"
                                    placeholder="Select a District" />
                            </div>
                        </div>
                        <div class="field">
                            <label for="ipress">Ipress</label>
                            <Dropdown id="ipress" v-model="user.ipress" :options="ipresses" required="true"
                                optionLabel="name" placeholder="Select an Ipress" />
                        </div>
                    </div>
                    <div class="formgrid grid">
                        <div class="field col">
                            <label for="document">Documento</label>
                            <InputText id="document" v-model.trim="user.document" required="true" autofocus
                                :disabled="isEditing" :invalid="submitted && !user.document"
                                @keyup.enter="checkDocument" />
                            <small class="p-invalid" v-if="submitted && !user.document">Documento es requerido.</small>
                        </div>
                        <div class="field col">
                            <label for="name">Nombres</label>
                            <InputText id="name" v-model.trim="user.name" :disabled="isFieldsDisabled || isEditing"
                                required="true" autofocus :invalid="submitted && !user.name" ref="nameField" />
                            <small class="p-invalid" v-if="submitted && !user.name">Nombre es requerido.</small>
                        </div>
                    </div>
                    <div class="field">
                        <label for="lastname">Apellidos</label>
                        <InputText id="lastname" v-model.trim="user.lastname" :disabled="isFieldsDisabled || isEditing"
                            required="true" :invalid="submitted && !user.lastname" />
                        <small class="p-invalid" v-if="submitted && !user.lastname">Apellidos es requerido.</small>
                    </div>
                    <div class="formgrid grid">
                        <div class="field col">
                            <label for="email">Email</label>
                            <InputText id="email" v-model.trim="user.email" required="false" ref="emailField" />
                        </div>
                        <div class="field col">
                            <label for="phone">Telefono</label>
                            <InputText id="phone" v-model.trim="user.phone" required="false" />
                        </div>
                        <div class="field col">
                            <label for="clinic_history">Historia Clinica</label>
                            <InputText id="clinic_history" v-model.trim="user.clinic_history" required="false" />
                        </div>
                    </div>
                    <div class="formgrid grid">
                        <div class="field col">
                            <label for="birthdate">Fecha de nacimiento</label>
                            <Calendar id="birthdate" v-model="user.birthdate" dateFormat="dd/mm/yy" required="true"
                                placeholder="Día/Mes/Año" autofocus :invalid="submitted && !user.birthdate" />
                            <small class="p-invalid" v-if="submitted && !user.birthdate">Fecha de nacimiento es
                                requerido.</small>
                        </div>
                        <div class="field col">
                            <label for="sex">Sexo</label>
                            <Dropdown id="sex" v-model="user.sex" :options="sexes" optionLabel="name" optionValue="id"
                                required="true" placeholder="Select a Sex" :invalid="submitted && !user.sex" />
                            <small class="p-invalid" v-if="submitted && !user.sex">Sexo es requerido.</small>
                        </div>
                    </div>
                    <div class="formgrid grid">
                        <div class="field col">
                            <label for="department">Departamento</label>
                            <Dropdown id="department" v-model="ubigeo.department" @change="loadProvincesUbigeo"
                                :options="departments" optionLabel="name" dataKey="id"
                                placeholder="Select a Department" />
                        </div>
                        <div class="field col">
                            <label for="province">Provincia</label>
                            <Dropdown id="province" v-model="ubigeo.province" @change="loadDistrictsUbigeo"
                                :options="provincesUbigeo" optionLabel="name" dataKey="id"
                                placeholder="Select a Province" />
                        </div>
                        <div class="field col">
                            <label for="district">Distrito</label>
                            <Dropdown id="district" v-model="ubigeo.district" :options="districtsUbigeo"
                                optionLabel="name" dataKey="id" placeholder="Select a District" />
                        </div>
                    </div>
                    <div class="field">
                        <label for="address">Dirección</label>
                        <InputText id="address" v-model.trim="user.address" required="true"
                            :invalid="submitted && !user.address" />
                        <small class="p-invalid" v-if="submitted && !user.address">Dirección es requerido.</small>
                    </div>
                    <template #footer>
                        <Button label="Cancel" icon="pi pi-times" text="" @click="hideDialog" />
                        <Button label="Save" icon="pi pi-check" text="" @click="saveUser" />
                    </template>
                </Dialog>

                <Dialog v-model:visible="deleteUserDialog" :style="{ width: '450px' }" header="Confirm" :modal="true">
                    <div class="flex align-items-center justify-content-center">
                        <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
                        <span v-if="user">Are you sure you want to delete <b>{{ user.name }}</b>?</span>
                    </div>
                    <template #footer>
                        <Button label="No" icon="pi pi-times" text @click="deleteUserDialog = false" />
                        <Button label="Yes" icon="pi pi-check" text @click="deleteUser" />
                    </template>
                </Dialog>
            </div>
        </div>
        <AppLoadingScreen v-if="loading"></AppLoadingScreen>
    </div>
</template>
