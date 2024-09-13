<template>
    <div class="grid">
        <!-- Profile View -->
        <div class="col-12 md:col-6">
            <div class="card">
                <div class="flex flex-column align-items-center mb-4">
                    <Avatar v-if="user.sex === 0" :image="'/layout/images/avatar-hombre.png'" size="xlarge"
                        shape="circle" class="mb-2" />
                    <Avatar v-else :image="'/layout/images/avatar-mujer.png'" size="xlarge" shape="circle"
                        class="mb-2" />
                    <h2 class="text-2xl font-bold text-900 mb-1">{{ user.name }}, {{ user.lastname }}</h2>
                </div>

                <div class="flex flex-wrap justify-content-center gap-2 mb-4">
                    <Chip v-for="role in user.roles" :key="role.id" :label="role.name" />
                </div>

                <div class="mb-4" v-if="user.ipress">
                    <p class="text-center text-sm text-600"><b>IPRESS</b>: {{ user.ipress.code }} - {{ user.ipress.name }}</p>
                    <p class="text-center text-sm text-600"><b>Dirección</b>: {{ user.ipress.address }}</p>
                </div>

                <div class="flex flex-column gap-2">
                    <div class="flex align-items-center gap-2 mb-2">
                        <v-icon name="hi-identification" class="text-600" scale="1"></v-icon>
                        <span class="text-600">{{ user.document }}</span>
                    </div>
                    <div class="flex align-items-center gap-2 mb-2">
                        <v-icon name="md-email-outlined" scale="1"></v-icon>
                        <span class="text-600">{{ user.email }}</span>
                    </div>
                    <div class="flex align-items-center gap-2 mb-2">
                        <v-icon name="pr-phone" class="text-600" scale="1"></v-icon>
                        <span class="text-600">{{ user.phone }}</span>
                    </div>
                    <div class="flex align-items-center gap-2 mb-2">
                        <v-icon v-if="user.sex === 0" name="bi-gender-male" scale="1"></v-icon>
                        <v-icon v-else name="bi-gender-female" scale="1"></v-icon>
                        <span class="text-600">{{ user.sex_format }}</span>
                    </div>
                    <div class="flex align-items-center gap-2 mb-2">
                        <i class="pi pi-calendar text-600"></i>
                        <span class="text-600">{{ user.birthdate }}</span>
                    </div>
                    <div class="flex align-items-center gap-2 mb-2">
                        <v-icon name="md-update" class="text-600" scale="1"></v-icon>
                        <span class="text-600">{{ user.age }} años</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Panel -->
        <div class="col-12 md:col-6">
            <div class="card">
                <h3 class="text-xl font-semibold mb-4">Editar mis datos</h3>
                <form @submit.prevent="updateProfile">
                    <div class="flex flex-column gap-3">
                        <div>
                            <label for="name" class="block text-sm font-medium mb-1">Nombres</label>
                            <InputText id="name" v-model="editForm.name" class="w-full" disabled />
                        </div>
                        <div>
                            <label for="lastname" class="block text-sm font-medium mb-1">Apellidos</label>
                            <InputText id="lastname" v-model="editForm.lastname" class="w-full" disabled />
                        </div>
                        <div class="formgrid grid">
                            <div class="field col">
                                <label for="email" class="block text-sm font-medium mb-1">Email</label>
                                <InputText id="email" v-model="editForm.email" type="email" class="w-full" />
                            </div>
                            <div class="field col">
                                <label for="phone" class="block text-sm font-medium mb-1">Telefono</label>
                                <InputText id="phone" v-model="editForm.phone" class="w-full" />
                            </div>
                        </div>
                        <div class="formgrid grid">
                            <div class="field col">
                                <label for="birthdate" class="block text-sm font-medium mb-1">Fecha de nacimiento</label>
                                <Calendar id="birthdate" v-model="editForm.birthdate" dateFormat="dd/mm/yy"
                                    class="w-full" />
                            </div>
                            <div class="field col">
                                <label for="sex" class="block text-sm font-medium mb-1">Sexo</label>
                                <Dropdown id="sex" v-model="editForm.sex" :options="sexes" optionLabel="name"
                                    optionValue="id" placeholder="Select a Sex" class="w-full" />
                            </div>
                        </div>
                        <div class="formgrid grid">
                            <div class="field col">
                                <label for="password" class="block text-sm font-medium mb-1">Contraseña</label>
                                <InputText id="password" v-model.trim="editForm.password" placeholder="*********"
                                    class="w-full" />
                            </div>
                            <div class="field col">
                                <label for="confirm-password" class="block text-sm font-medium mb-1">Repita la contraseña</label>
                                <InputText id="confirm-password" v-model.trim="editForm.confirm_password"
                                    placeholder="*********" class="w-full" />
                                <small class="p-invalid" v-if="editForm.password !== editForm.confirm_password">Repita la contraseña.</small>
                            </div>
                        </div>
                        <Button type="submit" label="Actualizar datos" class="w-full" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { reactive, onMounted } from 'vue'
import { userStore } from '@/app/store/user';
import UserResource from '@/app/api/user';
import { useToast } from 'primevue/usetoast';

const toast = useToast();

const user = userStore();
const userResource = new UserResource();
const editForm = reactive({});

const sexes = [
    { id: 0, name: 'Masculino' },
    { id: 1, name: 'Femenino' }
]

onMounted(() => {
    user.getInfo().then(() => {
        Object.assign(editForm, {
            id: user.id,
            name: user.name,
            lastname: user.lastname,
            document: user.document,
            email: user.email,
            phone: user.phone,
            sex: user.sex,
            birthdate: user.birthdate,
            password: '',
            confirm_password: ''
        });
    });
})

const updateProfile = () => {
    userResource.update(editForm.id, editForm).then((response) => {
        toast.add({ severity: response.status, summary: 'Información', detail: response.message, life: 5000 });
        user.getInfo();
        Object.assign(editForm, {
            password: '',
            confirm_password: ''
        });
    });
}
</script>
