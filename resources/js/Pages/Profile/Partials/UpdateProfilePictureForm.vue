<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';

const form = useForm({
    profile_picture: null,
});

// Function to handle the form submission
const updateProfilePicture = () => {
    const formData = new FormData();
    formData.append('profile_picture', form.profile_picture);

    form.post(route('profile.update-picture'), formData, {
        preserveScroll: true,
        onSuccess: () => {
            form.reset('profile_picture'); // Reset the form field after successful upload
        },
        onError: error => console.error('Error updating profile picture:', error),
    });
};
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Update Profile Picture</h2>
            <p class="mt-1 text-sm text-gray-600">Update your account's profile picture.</p>
        </header>
        <form @submit.prevent="updateProfilePicture" class="mt-6 space-y-6">
            <div>
                <InputLabel for="profile_picture" value="Profile Picture" />
                <input
                    id="profile_picture"
                    type="file"
                    class="mt-1 block w-full"
                    @change="form.profile_picture = $event.target.files[0]"
                />
                <InputError class="mt-2" :message="form.errors.profile_picture" />
            </div>
            <div class="mt-4" v-if="form.profile_picture">
                <img :src="URL.createObjectURL(form.profile_picture)" class="w-20 h-20 rounded-full object-cover">
            </div>
            <div class="flex items-center gap-4">
                <PrimaryButton
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Save
                </PrimaryButton>
            </div>
        </form>
    </section>
</template>
