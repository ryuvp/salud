<template>
    <div class="grid">
        <!-- Profile View -->
        <div class="col-12 md:col-6">
            <div class="card">
                <div class="flex flex-column align-items-center mb-4">
                    <Avatar :image="user.avatarUrl" size="xlarge" shape="circle" class="mb-2" />
                    <h2 class="text-2xl font-bold text-900 mb-1">{{ user.name }}</h2>
                    <p class="text-600">{{ user.jobTitle }}</p>
                </div>

                <div class="mb-4">
                    <p class="text-center text-sm text-600">{{ user.bio }}</p>
                </div>

                <div class="flex flex-wrap justify-content-center gap-2 mb-4">
                    <Chip v-for="skill in user.skills" :key="skill" :label="skill" />
                </div>

                <div class="flex flex-column gap-2">
                    <div class="flex align-items-center gap-2">
                        <i class="pi pi-envelope text-600"></i>
                        <span class="text-600">{{ user.email }}</span>
                    </div>
                    <div class="flex align-items-center gap-2">
                        <i class="pi pi-map-marker text-600"></i>
                        <span class="text-600">{{ user.location }}</span>
                    </div>
                    <div class="flex align-items-center gap-2">
                        <i class="pi pi-link text-600"></i>
                        <a :href="user.website" class="text-primary hover:underline">{{ user.website }}</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Panel -->
        <div class="col-12 md:col-6">
            <div class="card">
                <h3 class="text-xl font-semibold mb-4">Edit Profile</h3>
                <form @submit.prevent="updateProfile">
                    <div class="flex flex-column gap-3">
                        <div>
                            <label for="name" class="block text-sm font-medium mb-1">Name</label>
                            <InputText id="name" v-model="editForm.name" class="w-full" />
                        </div>
                        <div>
                            <label for="jobTitle" class="block text-sm font-medium mb-1">Job Title</label>
                            <InputText id="jobTitle" v-model="editForm.jobTitle" class="w-full" />
                        </div>
                        <div>
                            <label for="bio" class="block text-sm font-medium mb-1">Bio</label>
                            <Textarea id="bio" v-model="editForm.bio" rows="3" class="w-full" />
                        </div>
                        <div>
                            <label for="skills" class="block text-sm font-medium mb-1">Skills (comma-separated)</label>
                            <Chips v-model="editForm.skills" separator="," class="w-full" />
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium mb-1">Email</label>
                            <InputText id="email" v-model="editForm.email" type="email" class="w-full" />
                        </div>
                        <div>
                            <label for="location" class="block text-sm font-medium mb-1">Location</label>
                            <InputText id="location" v-model="editForm.location" class="w-full" />
                        </div>
                        <div>
                            <label for="website" class="block text-sm font-medium mb-1">Website</label>
                            <InputText id="website" v-model="editForm.website" type="url" class="w-full" />
                        </div>
                        <Button type="submit" label="Update Profile" class="w-full" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { reactive } from 'vue'
/*import Card from 'primevue/card'
import Avatar from 'primevue/avatar'
import Chip from 'primevue/chip'
import InputText from 'primevue/inputtext'
import Textarea from 'primevue/textarea'
import Chips from 'primevue/chips'
import Button from 'primevue/button'*/

const user = reactive({
    name: 'Jane Doe',
    jobTitle: 'Senior Developer',
    bio: 'Passionate about creating intuitive and efficient web applications. Always learning and exploring new technologies.',
    skills: ['Vue.js', 'Node.js', 'TypeScript'],
    email: 'jane.doe@example.com',
    location: 'San Francisco, CA',
    website: 'https://janedoe.dev',
    avatarUrl: '/placeholder.svg?height=96&width=96'
})

const editForm = reactive({ ...user })

const updateProfile = () => {
    Object.assign(user, editForm)

    console.log('Profile updated:', user)
    // Here you would typically send an API request to update the user profile

    // Optionally, show a success message or notification
    // You can use PrimeVue's Toast component for this
}
</script>

<style scoped>
/* Add any additional custom styles here */
</style>
