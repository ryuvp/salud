<script setup>
import * as XLSX from 'xlsx';
import { FilterMatchMode } from 'primevue/api';
import { ref, onMounted, onBeforeMount } from 'vue';
import UserResource from '@/app/api/user';
import DiagnosticResource from '@/app/api/diagnostic';
import { useToast } from 'primevue/usetoast';
import AppLoadingScreen from '@/app/views/layout/AppLoadingScreen.vue';


const toast = useToast();

const users = ref(null);
const search = ref({
    ipress: { id: 0, name: 'Todos' },
    cie10: { id: 0, name: 'Todos' }
});
const ipresses = ref(null);
const cie10s = ref(null);
const dt = ref(null);
const filters = ref({});
const filteredIpress = ref([]);
const filteredCie10s = ref([]);
const loading = ref(false);

const userResource = new UserResource();
const diagnosticResource = new DiagnosticResource();

onBeforeMount(() => {
    initFilters();
});

onMounted(() => {
    filterPatients();
    loadIpresses();
    loadCie10s();
});

const filterPatients = async () => {
    try {
        const data = await userResource.report(search.value);
        users.value = data.data;
    } catch (error) {
        toast.add({ severity: 'error', summary: 'Error', detail: 'Error al cargar los pacientes', life: 3000 });
    } finally {
        loading.value = false;
    }
}

const reload = () => {
    loading.value = true;
    filterPatients();
}

const loadIpresses = async () => {
    try {
        const data = await diagnosticResource.ipress();
        ipresses.value = [{ id: 0, name: 'Todos' }, ...data];
    } catch (error) {
        toast.add({ severity: 'error', summary: 'Error', detail: 'Error al cargar los centro de salud', life: 3000 });
    }
}

const loadCie10s = async () => {
    try {
        const data = await diagnosticResource.cie10();
        cie10s.value = [{ id: 0, name: 'Todos' }, ...data];
    } catch (error) {
        toast.add({ severity: 'error', summary: 'Error', detail: 'Error al cargar los diagnosticos', life: 3000 });
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

const searchCie10 = (event) => {
    const queryWords = event.query.toLowerCase().split(' ');
    filteredCie10s.value = cie10s.value.filter(cie10 =>
        queryWords.every(word => cie10.name.toLowerCase().includes(word))
    );
}

const formatDataForExcel = (data) => {
    return data.map((item, index) => {
        // Extraer y concatenar los datos de los diagnósticos y medicamentos
        const diagnosticsColumns = item.diagnostics.reduce((acc, diagnostic, index) => {
            // Agregar columnas para el diagnóstico y medicamentos
            acc[`DIAGNOSTICO_CIE10_${index + 1}`] = diagnostic.cie10;
            acc[`MEDICAMENTO_${index + 1}`] = diagnostic.prescriptions
                .map((prescription) => prescription.medicament)
                .join(', ');

            return acc;
        }, {});

        // Combinar los datos del paciente con las columnas de diagnósticos
        return {
            NRO: index + 1,
            NOMBRE: item.name,
            APELLIDO: item.lastname,
            DOCUMENTO: item.document,
            CORREO: item.email,
            TELEFONO: item.phone,
            SEXO: item.sex_format,
            EDAD: item.age,
            NACIMIENTO: item.birthdate,
            HC: item.clinic_history,
            UBIGEO: item.ubigeo,
            DIRECCION: item.address,
            MICRORED: item.ipress.microred_name?item.ipress.microred_name:'-',
            IPRESS: item.ipress.name,
            ...diagnosticsColumns, // Agregar columnas de diagnósticos dinámicamente
        };
    });
};

const exportExcel = () => {
    const formattedData = formatDataForExcel(users.value);

    // Convertir los datos formateados a un formato de hoja de cálculo
    const worksheet = XLSX.utils.json_to_sheet(formattedData);
    const workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, 'Datos');

    // Exportar el archivo Excel
    XLSX.writeFile(workbook, 'reporte.xlsx');
}
</script>

<template>
    <div class="grid">
        <div class="col-12">
            <div class="card">
                <Toolbar class="mb-4">
                    <template v-slot:start>
                        <div class="formgrid grid">
                            <div class="field col">
                                <label for="ipress">IPRESS:</label>
                                <AutoComplete id="ipress" :dropdown="true" v-model="search.ipress" optionValue="id"
                                    :suggestions="filteredIpress" @complete="searchIpress" field="name"
                                    placeholder="Seleccione un medicamento" />
                            </div>
                            <div class="field col">
                                <label for="cie10">CIE10:</label>
                                <AutoComplete id="cie10" :dropdown="true" v-model="search.cie10" optionValue="id"
                                    :suggestions="filteredCie10s" @complete="searchCie10" field="name"
                                    placeholder="Seleccione un CIE10" />
                            </div>
                            <div class="field col"></div>
                        </div>
                    </template>

                    <template v-slot:end>
                        <Button label="Filtar" icon="pi pi-search" severity="success" @click="reload" />
                        <Button label="Export" icon="pi pi-upload" severity="help" @click="exportExcel" />
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
                <AppLoadingScreen v-if="loading"></AppLoadingScreen>
            </div>
        </div>
    </div>
</template>
