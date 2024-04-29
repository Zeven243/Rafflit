<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const form = useForm({
    profile_picture: null,
});

const profilePictureInput = ref(null);

const updateProfilePicture = () => {
    form.post(route('profile.update-picture'), {
        preserveScroll: true,
        onSuccess: () => form.reset('profile_picture'),
    });
};

const selectNewProfilePicture = () => {
    profilePictureInput.value.click();
};

const updateProfilePicturePreview = () => {
    const file = profilePictureInput.value.files[0];
    form.profile_picture = file;
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
                <input
                    type="file"
                    class="hidden"
                    ref="profilePictureInput"
                    @change="updateProfilePicturePreview"
                >
                <div class="mt-2" v-if="form.profile_picture">
                    <img :src="URL.createObjectURL(form.profile_picture)" class="w-20 h-20 rounded-full object-cover">
                </div>
                <PrimaryButton @click="selectNewProfilePicture" class="mt-4">Select A New Photo</PrimaryButton>
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
