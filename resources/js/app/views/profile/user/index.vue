<template>
    <div class="grid">
        <!-- Profile View -->
        <div class="col-12 md:col-6">
            <div class="card profile-card">
                <div class="profile-hero">
                    <div class="profile-identity">
                        <Avatar v-if="user.sex === 0" :image="'/layout/images/avatar-hombre.png'" size="xlarge"
                            shape="circle" class="profile-avatar" />
                        <Avatar v-else :image="'/layout/images/avatar-mujer.png'" size="xlarge" shape="circle"
                            class="profile-avatar" />
                        <div class="profile-name">{{ user.name }} {{ user.lastname }}</div>
                        <div class="profile-meta">
                            <span class="meta-pill">
                                <v-icon name="hi-identification" scale="1"></v-icon>
                                {{ user.document }}
                            </span>
                            <span class="meta-pill">
                                <v-icon v-if="user.sex === 0" name="bi-gender-male" scale="1"></v-icon>
                                <v-icon v-else name="bi-gender-female" scale="1"></v-icon>
                                {{ user.sex_format }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="profile-body">
                    <div class="section-row">
                        <span class="section-title">Roles</span>
                        <div class="flex flex-wrap justify-content-start gap-2">
                            <Chip v-for="role in user.roles" :key="role.id" :label="role.name" />
                        </div>
                    </div>

                    <div class="info-grid">
                        <div class="info-tile">
                            <div class="info-icon">
                                <v-icon name="md-email-outlined" scale="1"></v-icon>
                            </div>
                            <div>
                                <div class="info-label">Email</div>
                                <div class="info-value">{{ user.email }}</div>
                            </div>
                        </div>
                        <div class="info-tile">
                            <div class="info-icon">
                                <v-icon name="pr-phone" scale="1"></v-icon>
                            </div>
                            <div>
                                <div class="info-label">Telefono</div>
                                <div class="info-value">{{ user.phone }}</div>
                            </div>
                        </div>
                        <div class="info-tile">
                            <div class="info-icon">
                                <i class="pi pi-calendar"></i>
                            </div>
                            <div>
                                <div class="info-label">Nacimiento</div>
                                <div class="info-value">{{ user.birthdate }}</div>
                            </div>
                        </div>
                        <div class="info-tile">
                            <div class="info-icon">
                                <v-icon name="md-update" scale="1"></v-icon>
                            </div>
                            <div>
                                <div class="info-label">Edad</div>
                                <div class="info-value">{{ user.age }} años</div>
                            </div>
                        </div>
                    </div>

                    <div class="ipress-card" v-if="user.ipress">
                        <div class="ipress-title">
                            <i class="pi pi-building"></i>
                            IPRESS
                        </div>
                        <div class="ipress-name">{{ user.ipress.code }} - {{ user.ipress.name }}</div>
                        <div class="ipress-address">{{ user.ipress.address }}</div>
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
                                <InputGroup>
                                    <InputText
                                        id="password"
                                        v-model.trim="editForm.password"
                                        :type="showPassword ? 'text' : 'password'"
                                        placeholder="*********"
                                        :class="{ 'p-invalid': passwordMismatch }"
                                        class="w-full"
                                    />
                                    <Button
                                        type="button"
                                        text
                                        :icon="showPassword ? 'pi pi-eye-slash' : 'pi pi-eye'"
                                        @click="showPassword = !showPassword"
                                        :aria-label="showPassword ? 'Ocultar contraseña' : 'Mostrar contraseña'"
                                    />
                                </InputGroup>
                            </div>
                            <div class="field col">
                                <label for="confirm-password" class="block text-sm font-medium mb-1">Repita la contraseña</label>
                                <InputGroup>
                                    <InputText
                                        id="confirm-password"
                                        v-model.trim="editForm.confirm_password"
                                        :type="showConfirmPassword ? 'text' : 'password'"
                                        placeholder="*********"
                                        :class="{ 'p-invalid': passwordMismatch }"
                                        class="w-full"
                                    />
                                    <Button
                                        type="button"
                                        text
                                        :icon="showConfirmPassword ? 'pi pi-eye-slash' : 'pi pi-eye'"
                                        @click="showConfirmPassword = !showConfirmPassword"
                                        :aria-label="showConfirmPassword ? 'Ocultar contraseña' : 'Mostrar contraseña'"
                                    />
                                </InputGroup>
                                <small class="p-invalid text-red-500" v-if="passwordMismatch">Las contraseñas no coinciden.</small>
                            </div>
                        </div>
                        <Button type="submit" label="Actualizar datos" class="w-full" :disabled="passwordMismatch" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { reactive, onMounted, computed, ref } from 'vue'
import { userStore } from '@/app/store/user';
import UserResource from '@/app/api/user';
import { useToast } from 'primevue/usetoast';

const toast = useToast();

const user = userStore();
const userResource = new UserResource();
const editForm = reactive({});
const showPassword = ref(false);
const showConfirmPassword = ref(false);

const sexes = [
    { id: 0, name: 'Masculino' },
    { id: 1, name: 'Femenino' }
]
const passwordMismatch = computed(() => {
    const password = (editForm.password || '').trim();
    const confirmPassword = (editForm.confirm_password || '').trim();
    if (!password && !confirmPassword) {
        return false;
    }
    return password !== confirmPassword;
});

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
    const payload = { ...editForm };
    payload.birthdate = normalizeBirthdate(payload.birthdate);
    userResource.update(payload.id, payload).then((response) => {
        toast.add({ severity: response.status, summary: 'Información', detail: response.message, life: 5000 });
        user.getInfo();
        Object.assign(editForm, {
            password: '',
            confirm_password: ''
        });
    });
}

const normalizeBirthdate = (value) => {
    if (!value) {
        return value;
    }
    if (value instanceof Date && !Number.isNaN(value.valueOf())) {
        const year = value.getFullYear();
        const month = String(value.getMonth() + 1).padStart(2, '0');
        const day = String(value.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }
    if (typeof value === 'string' && value.includes('/')) {
        const [day, month, year] = value.split('/');
        if (day && month && year) {
            return `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`;
        }
    }
    return value;
}
</script>

<style scoped>
.profile-card {
    padding: 0;
    overflow: hidden;
}

.profile-hero {
    position: relative;
    padding: 2.5rem 2rem 1.5rem;
    background: linear-gradient(135deg, var(--primary-100) 0%, var(--primary-200) 55%, var(--primary-50) 100%);
}

.profile-identity {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    gap: 0.75rem;
}

.profile-avatar {
    border: 4px solid #ffffff;
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
}

.profile-name {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-color);
    letter-spacing: 0.3px;
}

.profile-meta {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 0.5rem;
}

.meta-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.35rem 0.75rem;
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.7);
    color: var(--text-color);
    font-size: 0.85rem;
    font-weight: 600;
}

.profile-body {
    padding: 1.75rem 2rem 2rem;
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    animation: profileFade 0.6s ease-out;
}

.section-row {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.section-title {
    font-size: 0.95rem;
    text-transform: uppercase;
    letter-spacing: 0.12rem;
    color: var(--text-color-secondary);
    font-weight: 700;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 1rem;
}

.info-tile {
    display: flex;
    gap: 0.75rem;
    padding: 0.9rem 1rem;
    border-radius: 14px;
    background: var(--surface-50);
    border: 1px solid var(--surface-border);
    align-items: center;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.info-tile:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(15, 23, 42, 0.08);
}

.info-icon {
    width: 36px;
    height: 36px;
    border-radius: 12px;
    background: var(--surface-0);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary-color);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.08);
}

.info-label {
    font-size: 0.78rem;
    text-transform: uppercase;
    letter-spacing: 0.1rem;
    color: var(--text-color-secondary);
    font-weight: 700;
}

.info-value {
    font-size: 0.95rem;
    color: var(--text-color);
    font-weight: 600;
}

.ipress-card {
    padding: 1.25rem 1.5rem;
    border-radius: 16px;
    background: linear-gradient(120deg, var(--primary-100) 0%, var(--primary-300) 100%);
    color: var(--text-color);
    box-shadow: 0 14px 28px rgba(0, 0, 0, 0.12);
}

.ipress-title {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.1rem;
    font-size: 0.8rem;
}

.ipress-name {
    margin-top: 0.5rem;
    font-size: 1.05rem;
    font-weight: 700;
}

.ipress-address {
    margin-top: 0.25rem;
    font-size: 0.9rem;
    opacity: 0.85;
}

@keyframes profileFade {
    from {
        opacity: 0;
        transform: translateY(8px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@media (max-width: 768px) {
    .profile-hero {
        padding: 2rem 1.5rem 1.25rem;
    }

    .profile-body {
        padding: 1.5rem;
    }
}
</style>
