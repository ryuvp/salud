<script setup>
import { ref, onBeforeMount, computed } from 'vue';
import { FilterMatchMode, FilterOperator } from 'primevue/api';
import UserResource from '@/app/api/user';
import Cie10Resource from '@/app/api/cie10';
import DiagnosticResource from '@/app/api/diagnostic';
import PrescriptionResource from '@/app/api/prescription';
import MedicamentResource from '@/app/api/medicament';
import { userStore } from "@/app/store/user";

import { useToast } from 'primevue/usetoast';


const toast = useToast();

const patients = ref(null);
const patient = ref({});
const diagnostic = ref({});
const prescription = ref({});
const filters = ref({});
const diagnosticDialog = ref(null);
const changeIpressDialog = ref(null);
const medicamentDialog = ref(null);
const deleteMedicamentDialog = ref(null);
const medicaments = ref(null);
const filteredCie10s = ref([]);
const filteredMedicament = ref([]);

const expandedRows = ref([]);
const submitted = ref(false);

const patientResource = new UserResource();
const cie10Resource = new Cie10Resource();
const diagnosticResource = new DiagnosticResource();
const medicamentResource = new MedicamentResource();
const prescriptionResource = new PrescriptionResource();

const store = userStore();
const storedIpress = computed(() => {
    return store.ipress.id;
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

const loadMedicaments = async () => {
    try {
        const data = await medicamentResource.list();
        medicaments.value = data;
    }catch (error){
        toast.add({ severity: 'error', summary: 'Error', detail: 'Error al cargar los medicaments', life: 3000 });
    }
}

const searchCie10 = async (event) => {
    const query = event.query ? event.query.trim() : '';
    if (!query) {
        filteredCie10s.value = [];
        return;
    }

    try {
        const firstBatch = await cie10Resource.list({ q: query, limit: 10, page: 1 });
        let results = Array.isArray(firstBatch) ? firstBatch : (firstBatch.data || []);

        if (results.length === 10) {
            const secondBatch = await cie10Resource.list({ q: query, limit: 10, page: 2 });
            const moreResults = Array.isArray(secondBatch) ? secondBatch : (secondBatch.data || []);
            results = results.concat(moreResults).slice(0, 20);
        }

        filteredCie10s.value = results;
    } catch (error) {
        filteredCie10s.value = [];
        toast.add({ severity: 'error', summary: 'Error', detail: 'Error al buscar diagnosticos', life: 3000 });
    }
};
const searchMedicament = (event) =>{
    const queryWords = event.query.toLowerCase().split(' ');
    filteredMedicament.value = medicaments.value.filter(medicament =>
        queryWords.every(word => medicament.name.toLowerCase().includes(word))
    );
};
const initFilters = () => {
    filters.value = {
        global: { value: null, matchMode: FilterMatchMode.CONTAINS }
    };
};

const openNew = (user) => {
    diagnosticDialog.value = true;

    diagnostic.value.patient = user;
    patient.value = user;
    patient.value.fullname = `${user.name} ${user.lastname}`;
};

const hideDialog = () => {
    diagnosticDialog.value = false;
    filteredCie10s.value = [];
}

const hideChangeIpressDialog = () => {
    changeIpressDialog.value = false;
    hideDialog();
}

const saveDiagnostic = async () => {
    //console.log(diagnostic.value);
    const newDiagnostic = {
        user_id: diagnostic.value.patient.id,
        ipress_id: storedIpress.value,
        cie10_id: diagnostic.value.cie10.id,
    }
    console.log(newDiagnostic);

    try {
        let response;
        response = await diagnosticResource.store(newDiagnostic);
        if (response) {
            toast.add({ severity: 'success', summary: 'Guardado', detail: 'Diagnostico guardado', life: 3000 });
            hideDialog();
            loadUsers();
        }
    }catch(error){
        toast.add({ severity: 'error', summary: 'Error', detail: 'Error al guardar el diagnostico', life: 3000 });
    }
}

const confirmSaveDiagnostic = () => {

    if(storedIpress.value != diagnostic.value.patient.ipress.id){
        changeIpressDialog.value = true;
        return;
    }
    else{
        saveDiagnostic();
    }
};

const changeIpress = async () => {
    const patientUpdate = {
        ipress: {
            id: storedIpress.value
        }
    };
    try{
        let response;
        response = await patientResource.update(diagnostic.value.patient.id, patientUpdate);

        if (response) {
            changeIpressDialog.value = false;
            saveDiagnostic();
        }
    }catch(error){
        hideChangeIpressDialog();
        toast.add({ severity: 'error', summary: 'Error', detail: 'Error al cambiar la IPRESS', life: 3000 });
    }
}

const openNewMedicament = (diagnostic) => {
    medicamentDialog.value = true;
    submitted.value = false;
    prescription.value.diagnostic = diagnostic;

    loadMedicaments();
}

const confirmSavePrescription = async () => {
    submitted.value = true;
    if(!prescription.value.medicament){
        return;
    }
    try{
        let response;
        response = await prescriptionResource.store(prescription.value);
        if (response) {
            toast.add({ severity: 'success', summary: 'Guardado', detail: 'Prescripción guardada', life: 3000 });
            hideMedicamentDialog();
            loadUsers();
        }
    } catch(error){
        toast.add({ severity: 'error', summary: 'Error', detail: 'Error al guardar la prescripción', life: 3000 });
    }
}

const hideMedicamentDialog = () => {
    medicamentDialog.value = false;
    prescription.value = {};
    submitted.value = false;
    filteredMedicament.value = [];
}

const confirmDeleteMedicament = (deleteMedicament) => {
    console.log(deleteMedicament);

    prescription.value = deleteMedicament;
    deleteMedicamentDialog.value = true;    
}

const deleteMedicament = () => {
    prescriptionResource.destroy(prescription.value.id).then((data) => {
        const { status, message } = data;
        if (status === "success") {
            deleteMedicamentDialog.value = false;
            loadUsers();
            toast.add({ severity: status, summary: status, detail: message, life: 3000 });
        } else {
            toast.add({ severity: status, summary: status, detail: message, life: 3000 });
        }
    });
}
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
                                            @click="openNewMedicament(diagnostic)" class="mr-2 inline-block" />
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
                                                @click="confirmDeleteMedicament(slotProps.data)" />
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
                        <Button label="Save" icon="pi pi-check" text="" @click="confirmSaveDiagnostic" />
                    </template>
                </Dialog>
                <Dialog v-model:visible="changeIpressDialog" :style="{ width: '450px' }" header="Confirm" :modal="true">
                    <div class="flex align-items-center justify-content-center">
                        <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
                        <span>El paciente no se encuentra registrado en tu centro de salud, <br> <b>¿Deseas hacer el cambio?</b>?</span>
                    </div>
                    <template #footer>
                        <Button label="No" icon="pi pi-times" text @click="hideChangeIpressDialog" />
                        <Button label="Yes" icon="pi pi-check" text @click="changeIpress" />
                    </template>
                </Dialog>

                <Dialog v-model:visible="medicamentDialog" :style="{ width: '850px' }" header="Nuevo Medicamento"
                    :modal="true" class="p-fluid">
                    <div class="field">
                        <label><b>Medicamento:</b></label>
                        <AutoComplete id="cie10" :dropdown="false" v-model="prescription.medicament" optionValue="id" :suggestions="filteredMedicament" @complete="searchMedicament" 
                        field="name" required="true" :invalid="submitted && !prescription.medicament" placeholder="Seleccione un medicamento" />
                        <small class="p-invalid" v-if="submitted && !prescription.medicament">Medicamento es requerido.</small>
                    </div>
                    <div class="formgrid grid">
                    <div class="field col">
                        <label><b>Cantidad:</b></label>
                        <InputNumber v-model="prescription.quantity" mode="decimal" :minFractionDigits="2" />
                    </div>
                    <div class="field col">
                        <label><b>Frecuencia:</b></label>
                        <InputNumber v-model="prescription.frequency" mode="decimal" :minFractionDigits="2" />
                    </div>
                    </div>
                    <div class="field">
                        <label><b>Fecha de inicio:</b></label>
                        <Calendar v-model="prescription.start_date" dateFormat="dd/mm/yy" />
                    </div>
                    <template #footer>
                        <Button label="Cancel" icon="pi pi-times" text="" @click="hideDialog" />
                        <Button label="Save" icon="pi pi-check" text="" @click="confirmSavePrescription" />
                    </template>
                </Dialog>
                <Dialog v-model:visible="deleteMedicamentDialog" :style="{ width: '450px' }" header="Confirm" :modal="true">
                    <div class="flex align-items-center justify-content-center">
                        <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
                        <span>Are you sure you want to delete this medicament?</span>
                    </div>
                    <template #footer>
                        <Button label="No" icon="pi pi-times" text @click="deleteMedicamentDialog = false" />
                        <Button label="Yes" icon="pi pi-check" text @click="deleteMedicament" />
                    </template>
                </Dialog>
            </div>
        </div>
    </div>
</template>
