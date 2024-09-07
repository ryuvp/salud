<script setup>
import { FilterMatchMode } from 'primevue/api';
import { ref, onMounted, onBeforeMount } from 'vue';
import UserResource from '@/app/api/user';
import IpressResource from '@/app/api/ipress';
import { useToast } from 'primevue/usetoast';

const toast = useToast();

const users = ref(null);
const search = ref({});
const ipresses = ref(null);
const dt = ref(null);
const filters = ref({});
const filteredIpress = ref([]);

const userResource = new UserResource();
const ipressResource = new IpressResource();

onBeforeMount(() => {
    initFilters();
});

onMounted(() => {
    loadUsers();
    loadIpresses();
});

const loadUsers = async () => {
    try {
        const data = await userResource.patients();
        users.value = data.data;
    } catch (error) {
        toast.add({ severity: 'error', summary: 'Error', detail: 'Error al cargar los usuarios', life: 3000 });
    }
};

const loadIpresses = async () => {
    try{
        const data = await ipressResource.list();
        ipresses.value = data;
        console.log(ipresses.value);

    } catch (error){
        toast.add({ severity: 'error', summary: 'Error', detail: 'Error al cargar los centro de salud', life: 3000 });
    }
}
const initFilters = () => {
    filters.value = {
        global: { value: null, matchMode: FilterMatchMode.CONTAINS }
    };
};

const searchIpress = (event) => {
    const queryWords = event.query.toLowerCase().split(' ');
    filteredIpress.value = ipresses.value.filter(ipress =>
        queryWords.every(word => ipress.name.toLowerCase().includes(word))
    );
}
</script>

<template>
    <div class="grid">
        <div class="col-12">
            <div class="card">
                <Toolbar class="mb-4">
                    <template v-slot:start>
                        <div class="my-2">
                            <label for="ipress"><b>IPRESS:</b></label>
                            <AutoComplete id="ipress" :dropdown="false"
                            v-model="search.ipress" optionValue="id" :suggestions="filteredIpress" @complete="searchIpress"
                            field="name" placeholder="Seleccione un medicamento" />
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
                </DataTable>
            </div>
        </div>
    </div>
</template>
