<script setup>
import { ref, onBeforeMount, computed } from 'vue';
import { FilterMatchMode, FilterOperator } from 'primevue/api';
import UserResource from '@/app/api/user';
import Cie10Resource from '@/app/api/cie10';
import { userStore } from "@/app/store/user";

import { useToast } from 'primevue/usetoast';

const toast = useToast();

const patients = ref(null);
const patient = ref({});
const diagnostic = ref({});
const filters = ref({});
const loading = ref(null);
const diagnosticDialog = ref(null);
const cie10s = ref(null);
const filteredCie10s = ref([]);

const expandedRows = ref([]);

const patientResource = new UserResource();
const cie10Resource = new Cie10Resource();

const store = userStore();
const storedIpress = computed(() => {
    return store;
})

onBeforeMount(() => {
    loadUsers();
    initFilters();
});

const loadUsers = async () => {
    try {
        const data = await patientResource.follow();
        patients.value = data.data;
    } catch (error) {
        toast.add({ severity: 'error', summary: 'Error', detail: 'Error al cargar los usuarios', life: 3000 });
    }
};

const loadCie10s = async () => {
    try {
        const data = await cie10Resource.list();
        cie10s.value = data;
    } catch (error) {
        toast.add({ severity: 'error', summary: 'Error', detail: 'Error al cargar los diagnosticos', life: 3000 });
    }
}

const searchCie10 = (event) => {
    const queryWords = event.query.toLowerCase().split(' ');

    filteredCie10s.value = cie10s.value.filter(cie10 =>
        queryWords.every(word => cie10.name.toLowerCase().includes(word))
    );
};
const initFilters = () => {
    filters.value = {
        global: { value: null, matchMode: FilterMatchMode.CONTAINS }
    };
};

const openNew = (user) => {
    diagnosticDialog.value = true;

    loadCie10s();

    diagnostic.value.patient = user;
    patient.value = user;
    patient.value.fullname = `${user.name} ${user.lastname}`;
};

const hideDialog = () => {
    diagnosticDialog.value = false;
}

const saveDiagnostic = async () => {

    console.log(storedIpress.value);
    //console.log(diagnostic.value);    
};
</script>

<template>
    <div class="grid">
        <div class="col-12">
            <div class="card">
                <DataTable :value="patients" v-model:expandedRows="expandedRows" dataKey="id" :paginator="true"
                    :rows="50" :filters="filters"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 50, 100]"
                    currentPageReportTemplate="Mostrando {first} a {last} de {totalRecords} pacientes">
                    <template #header>
                        <div class="flex flex-column md:flex-row md:justify-content-between md:align-items-center">
                            <h5 class="m-0">Seguimineto de Pacientes</h5>
                            <IconField iconPosition="left" class="block mt-2 md:mt-0">
                                <InputIcon class="pi pi-search" />
                                <InputText class="w-full sm:w-auto" v-model="filters['global'].value"
                                    placeholder="Buscar..." />
                            </IconField>
                        </div>
                    </template>
                    <Column :expander="true" headerStyle="width: 3rem" />
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
                            <Button label="Nuevo" severity="success" @click="openNew(slotProps.data)"
                                class="mr-2 inline-block" />
                        </template>
                    </Column>
                    <template #expansion="slotProps">
                        <div class="p-3">
                            <div v-for="diagnostic in slotProps.data.diagnostics" :key="diagnostic.id">
                                <Toolbar class="mb-0 mt-1">
                                    <template v-slot:start>
                                        <div class="my-2">
                                            <p class="m-0"> <b>Diagnostico:</b> {{ diagnostic.cie10 }}</p>
                                            <p class="m-0"> <b>IPRESS:</b> {{ diagnostic.ipress.name }}</p>
                                        </div>
                                    </template>
                                    <template v-slot:end>
                                        <Button label="Agregar Medicamento" icon="pi pi-plus" severity="success"
                                            @click="exportCSV($event)" class="mr-2 inline-block" />
                                        <Button label="Guardar" icon="pi pi-upload" severity="warning"
                                            @click="exportCSV($event)" />
                                    </template>
                                </Toolbar>
                                <DataTable :value="diagnostic.prescriptions">
                                    <Column field="medicament" header="Medicamento" :sortable="false"
                                        headerStyle="width:25%; min-width:10rem;">
                                        <template #body="slotProps">
                                            {{ slotProps.data.medicament }}
                                        </template>
                                    </Column>
                                    <Column field="date" header="Fecha" :sortable="false"
                                        headerStyle="width:25%; min-width:10rem;">
                                        <template #body="slotProps">
                                            {{ (slotProps.data.created_at) }}
                                        </template>
                                    </Column>
                                    <Column field="quantity" header="Cantidad" :sortable="false"
                                        headerStyle="width:25%; min-width:10rem;">
                                        <template #body="slotProps">
                                            {{ slotProps.data.quantity }}
                                        </template>
                                    </Column>
                                    <Column field="frequency" header="Frecuencia" :sortable="false"
                                        headerStyle="width:25%; min-width:10rem;">
                                        <template #body="slotProps">
                                            Cada {{ slotProps.data.frequency }} horas
                                        </template>
                                    </Column>
                                    <Column headerStyle="min-width:10rem;">
                                        <template #body="slotProps">
                                            <Button icon="pi pi-trash" class="mt-2" severity="warning" rounded
                                                @click="confirmDeleteUser(slotProps.data)" />
                                        </template>
                                    </Column>
                                </DataTable>
                            </div>
                        </div>
                    </template>
                </DataTable>

                <Dialog v-model:visible="diagnosticDialog" :style="{ width: '850px' }" header="Nuevo Diagnostico"
                    :modal="true" class="p-fluid">
                    <div class="field">
                        <label><b>Paciente:</b> {{ patient.fullname }}</label>                        
                    </div>
                    <div class="formgrid grid">
                    <div class="field col">
                        <label><b>Edad:</b> {{ patient.age }} años</label>
                    </div>
                    <div class="field col">
                        <label><b>Sexo:</b> {{ patient.sex_format }}</label>
                    </div>
                    </div>
                    <div class="field">
                        <label><b>Dirección:</b> {{ patient.address }}, {{ patient.district.name }}</label>
                    </div>
                    <div class="field">
                        <label><b>IPRESS:</b> {{ patient.ipress ? patient.ipress.name : '' }}</label>
                    </div>
                    <div class="field">
                        <label><b>Diagnostico:</b></label>
                        <AutoComplete id="cie10" :dropdown="false" v-model="diagnostic.cie10" optionValue="id" :suggestions="filteredCie10s" @complete="searchCie10" field="name" placeholder="Seleccione un diagnóstico" />
                    </div>
                    <template #footer>
                        <Button label="Cancel" icon="pi pi-times" text="" @click="hideDialog" />
                        <Button label="Save" icon="pi pi-check" text="" @click="saveDiagnostic" />
                    </template>
                </Dialog>
            </div>
        </div>
    </div>
</template>
