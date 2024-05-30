<template>
  <section class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md p-8 space-y-8 bg-white rounded-lg shadow">
      <div class="flex justify-center">
        <img src="/storage/raffl-logo.png" alt="Raffl Logo" class="w-16 h-16">
      </div>
      <h2 class="text-2xl font-bold text-center text-gray-900">Create Your Account</h2>
      <form @submit.prevent="submit" class="mt-8 space-y-6">
        <div>
          <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
          <input type="text" id="first_name" v-model="form.first_name" required
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
          <div v-if="form.errors.first_name" class="text-red-500 text-xs italic">{{ form.errors.first_name }}</div>
        </div>
        <div>
          <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
          <input type="text" id="last_name" v-model="form.last_name" required
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
          <div v-if="form.errors.last_name" class="text-red-500 text-xs italic">{{ form.errors.last_name }}</div>
        </div>
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
          <input type="email" id="email" v-model="form.email" required
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
          <div v-if="form.errors.email" class="text-red-500 text-xs italic">{{ form.errors.email }}</div>
        </div>
        <div>
          <label for="company" class="block text-sm font-medium text-gray-700">Company</label>
          <input type="text" id="company" v-model="form.company" required
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
          <div v-if="form.errors.company" class="text-red-500 text-xs italic">{{ form.errors.company }}</div>
        </div>
        <div>
          <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
          <input type="password" id="password" v-model="form.password" required
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
          <div v-if="form.errors.password" class="text-red-500 text-xs italic">{{ form.errors.password }}</div>
        </div>
        <div>
          <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
          <input type="password" id="password_confirmation" v-model="form.password_confirmation" required
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
          <div v-if="form.errors.password_confirmation" class="text-red-500 text-xs italic">{{ form.errors.password_confirmation }}</div>
        </div>
        <div>
          <label for="profile_picture" class="block text-sm font-medium text-gray-700">Profile Picture</label>
          <input type="file" id="profile_picture" @change="onFileChange" accept="image/*"
            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
          <div v-if="form.errors.profile_picture" class="text-red-500 text-xs italic">{{ form.errors.profile_picture }}</div>
        </div>
        <button type="submit"
          class="mt-6 w-full bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white font-bold py-2 px-4 rounded">
          Register
        </button>
      </form>
    </div>
  </section>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';

const form = useForm({
  first_name: '',
  last_name: '',
  email: '',
  company: '',
  password: '',
  password_confirmation: '',
  profile_picture: null,
});

const onFileChange = (event) => {
  form.profile_picture = event.target.files[0];
};

const submit = () => {
  const formData = new FormData();
  formData.append('first_name', form.first_name);
  formData.append('last_name', form.last_name);
  formData.append('email', form.email);
  formData.append('company', form.company);
  formData.append('password', form.password);
  formData.append('password_confirmation', form.password_confirmation);
  if (form.profile_picture) {
    formData.append('profile_picture', form.profile_picture);
  }

  form.post(route('register'), formData, {
    forceFormData: true,
    onSuccess: () => {
      // Redirect or perform additional actions on successful registration
    },
    onError: (errors) => {
      // Handle errors
      form.errors = errors;
    }
  });
};
</script>

<style>
/* Additional global styles can be placed in your main CSS file */
</style>
