<template>
    <section class="flex items-center justify-center min-h-screen bg-gray-100">
      <div class="w-full max-w-md p-8 space-y-8 bg-white rounded-lg shadow">
        <div class="flex justify-center">
          <img src="/storage/raffl-logo.png" alt="Rafflit Logo" class="w-16 h-16">
        </div>
        <h2 class="text-2xl font-bold text-center text-gray-900">Log in to Your Account</h2>
        <form @submit.prevent="submit" class="mt-8 space-y-6">
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" id="email" v-model="form.email" required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            <div v-if="form.errors.email" class="text-red-500 text-xs italic">{{ form.errors.email }}</div>
          </div>
          <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input type="password" id="password" v-model="form.password" required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            <div v-if="form.errors.password" class="text-red-500 text-xs italic">{{ form.errors.password }}</div>
          </div>
          <div class="block mt-4">
            <label class="flex items-center">
              <input type="checkbox" name="remember" v-model="form.remember" class="form-checkbox">
              <span class="ml-2 text-sm text-gray-600">Remember me</span>
            </label>
          </div>
          <div class="flex items-center justify-end mt-4">
            <Link
              v-if="canResetPassword"
              :href="route('password.request')"
              class="underline text-sm text-gray-600 hover:text-gray-900"
            >
              Forgot your password?
            </Link>
            <button type="submit"
              class="ml-4 bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white font-bold py-2 px-4 rounded"
              :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
              Log in
            </button>
            <Link :href="route('register')" class="ml-4 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
              Register
            </Link>
          </div>
        </form>
      </div>
    </section>
  </template>
  
  <script setup>
  import { useForm, Head, Link } from '@inertiajs/vue3';
  
  defineProps({
    canResetPassword: Boolean,
    status: String,
  });
  
  const form = useForm({
    email: '',
    password: '',
    remember: false,
  });
  
  const submit = () => {
    form.post(route('login'), {
      onFinish: () => form.reset('password'),
    });
  };
  </script>
  
  <style>
  /* Additional global styles can be placed in your main CSS file */
  </style>
  