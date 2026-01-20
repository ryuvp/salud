<script setup>
import { onMounted, reactive, ref, watch } from 'vue';
import { useLayout } from '@/sakai-vue/layout/composables/layout';
import DashboardResource from '@/app/api/dashboard';
import { useToast } from 'primevue/usetoast';

const { isDarkTheme } = useLayout();

const dashboardResource = new DashboardResource();
const toast = useToast();
const summary = ref(null);
const recentFollowups = ref([]);
const topCie10 = ref([]);

const lineData = reactive({
    labels: [],
    datasets: [
        {
            label: 'Seguimientos',
            data: [],
            fill: false,
            backgroundColor: 'var(--primary-500)',
            borderColor: 'var(--primary-500)',
            tension: 0.4
        }
    ]
});
const barData = reactive({
    labels: [],
    datasets: [
        {
            label: 'Total',
            data: [],
            backgroundColor: 'var(--primary-300)',
            borderColor: 'var(--primary-500)',
            borderWidth: 1
        }
    ]
});
const lineOptions = ref(null);
const barOptions = ref(null);

const applyDatasetColors = () => {
    const styles = getComputedStyle(document.documentElement);
    const primary500 = styles.getPropertyValue('--primary-500')?.trim() || '#2f4860';
    const primary300 = styles.getPropertyValue('--primary-300')?.trim() || '#7aa3c9';

    lineData.datasets[0].backgroundColor = primary500;
    lineData.datasets[0].borderColor = primary500;
    barData.datasets[0].backgroundColor = primary300;
    barData.datasets[0].borderColor = primary500;
};

onMounted(async () => {
    try {
        const data = await dashboardResource.summary();
        summary.value = data;
        recentFollowups.value = data.recent_followups || [];
        topCie10.value = data.top_cie10 || [];

        lineData.labels = data.followups_trend?.labels || [];
        lineData.datasets[0].data = data.followups_trend?.data || [];

        barData.labels = topCie10.value.map((item) => item.name);
        barData.datasets[0].data = topCie10.value.map((item) => item.total);

        applyDatasetColors();
    } catch (error) {
        toast.add({ severity: 'error', summary: 'Error', detail: 'No se pudo cargar el dashboard', life: 3000 });
    }
});
const applyLightTheme = () => {
    lineOptions.value = {
        plugins: {
            legend: {
                labels: {
                    color: '#495057'
                }
            }
        },
        scales: {
            x: {
                ticks: {
                    color: '#495057'
                },
                grid: {
                    color: '#ebedef'
                }
            },
            y: {
                ticks: {
                    color: '#495057'
                },
                grid: {
                    color: '#ebedef'
                }
            }
        }
    };
    barOptions.value = {
        plugins: {
            legend: {
                labels: {
                    color: '#495057'
                }
            }
        },
        scales: {
            x: {
                ticks: {
                    color: '#495057'
                },
                grid: {
                    color: '#ebedef'
                }
            },
            y: {
                ticks: {
                    color: '#495057'
                },
                grid: {
                    color: '#ebedef'
                }
            }
        }
    };
};

const applyDarkTheme = () => {
    lineOptions.value = {
        plugins: {
            legend: {
                labels: {
                    color: '#ebedef'
                }
            }
        },
        scales: {
            x: {
                ticks: {
                    color: '#ebedef'
                },
                grid: {
                    color: 'rgba(160, 167, 181, .3)'
                }
            },
            y: {
                ticks: {
                    color: '#ebedef'
                },
                grid: {
                    color: 'rgba(160, 167, 181, .3)'
                }
            }
        }
    };
    barOptions.value = {
        plugins: {
            legend: {
                labels: {
                    color: '#ebedef'
                }
            }
        },
        scales: {
            x: {
                ticks: {
                    color: '#ebedef'
                },
                grid: {
                    color: 'rgba(160, 167, 181, .3)'
                }
            },
            y: {
                ticks: {
                    color: '#ebedef'
                },
                grid: {
                    color: 'rgba(160, 167, 181, .3)'
                }
            }
        }
    };
};

watch(
    isDarkTheme,
    (val) => {
        if (val) {
            applyDarkTheme();
        } else {
            applyLightTheme();
        }
        applyDatasetColors();
    },
    { immediate: true }
);

const formatDate = (value) => {
    if (!value) {
        return '';
    }
    const date = new Date(value);
    if (Number.isNaN(date.getTime())) {
        return value;
    }
    return date.toLocaleDateString('es-PE');
};
</script>

<template>
    <div class="grid">
        <div class="col-12 lg:col-6 xl:col-3">
            <div class="card mb-0">
                <div class="flex justify-content-between mb-3">
                    <div>
                        <span class="block text-500 font-medium mb-3">IPRESS con usuarios</span>
                        <div class="text-900 font-medium text-xl">{{ summary?.ipress_with_users ?? 0 }}</div>
                    </div>
                    <div class="flex align-items-center justify-content-center bg-green-100 border-round" style="width: 2.5rem; height: 2.5rem">
                        <i class="pi pi-building text-green-600 text-xl"></i>
                    </div>
                </div>
                <span class="text-500">Centros con personal asignado</span>
            </div>
        </div>
        <div class="col-12 lg:col-6 xl:col-3">
            <div class="card mb-0">
                <div class="flex justify-content-between mb-3">
                    <div>
                        <span class="block text-500 font-medium mb-3">Usuarios</span>
                        <div class="text-900 font-medium text-xl">{{ summary?.users_total ?? 0 }}</div>
                    </div>
                    <div class="flex align-items-center justify-content-center bg-blue-100 border-round" style="width: 2.5rem; height: 2.5rem">
                        <i class="pi pi-users text-blue-500 text-xl"></i>
                    </div>
                </div>
                <span class="text-500">Personal activo en el sistema</span>
            </div>
        </div>
        <div class="col-12 lg:col-6 xl:col-3">
            <div class="card mb-0">
                <div class="flex justify-content-between mb-3">
                    <div>
                        <span class="block text-500 font-medium mb-3">Pacientes</span>
                        <div class="text-900 font-medium text-xl">{{ summary?.patients_total ?? 0 }}</div>
                    </div>
                    <div class="flex align-items-center justify-content-center bg-teal-100 border-round" style="width: 2.5rem; height: 2.5rem">
                        <i class="pi pi-user text-teal-600 text-xl"></i>
                    </div>
                </div>
                <span class="text-500">Registrados en seguimiento</span>
            </div>
        </div>
        <div class="col-12 lg:col-6 xl:col-3">
            <div class="card mb-0">
                <div class="flex justify-content-between mb-3">
                    <div>
                        <span class="block text-500 font-medium mb-3">Seguimientos (mes)</span>
                        <div class="text-900 font-medium text-xl">{{ summary?.followups_month ?? 0 }}</div>
                    </div>
                    <div class="flex align-items-center justify-content-center bg-orange-100 border-round" style="width: 2.5rem; height: 2.5rem">
                        <i class="pi pi-clipboard text-orange-500 text-xl"></i>
                    </div>
                </div>
                <span class="text-500">Atenciones registradas este mes</span>
            </div>
        </div>

        <div class="col-12 xl:col-6">
            <div class="card">
                <div class="flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Seguimientos recientes</h5>
                    <span class="text-500 text-sm">Total: {{ summary?.followups_total ?? 0 }}</span>
                </div>
                <DataTable :value="recentFollowups" :rows="6" responsiveLayout="scroll">
                    <Column field="patient" header="Paciente" style="width: 35%"></Column>
                    <Column field="cie10" header="Diagnostico" style="width: 35%"></Column>
                    <Column field="ipress" header="IPRESS" style="width: 20%"></Column>
                    <Column field="created_at" header="Fecha" style="width: 10%">
                        <template #body="slotProps">
                            {{ formatDate(slotProps.data.created_at) }}
                        </template>
                    </Column>
                </DataTable>
            </div>
        </div>
        <div class="col-12 xl:col-6">
            <div class="card">
                <h5>Seguimientos por mes</h5>
                <Chart type="line" :data="lineData" :options="lineOptions" />
            </div>
        </div>

        <div class="col-12 xl:col-7">
            <div class="card">
                <h5>CIE10 mas frecuentes</h5>
                <Chart type="bar" :data="barData" :options="barOptions" />
            </div>
        </div>
        <div class="col-12 xl:col-5">
            <div class="card">
                <h5>Alertas operativas</h5>
                <div class="flex flex-column gap-3">
                    <div class="flex align-items-center justify-content-between p-3 border-round surface-50">
                        <div class="flex align-items-center gap-2">
                            <i class="pi pi-exclamation-triangle text-orange-500"></i>
                            <span class="text-700">Pacientes sin seguimiento</span>
                        </div>
                        <span class="text-900 font-semibold">{{ summary?.patients_without_followup ?? 0 }}</span>
                    </div>
                    <div class="flex align-items-center justify-content-between p-3 border-round surface-50">
                        <div class="flex align-items-center gap-2">
                            <i class="pi pi-chart-line text-primary"></i>
                            <span class="text-700">Seguimientos acumulados</span>
                        </div>
                        <span class="text-900 font-semibold">{{ summary?.followups_total ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
