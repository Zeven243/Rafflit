<template>
    <GuestLayout>
      <Head title="Reset Password" />
  
      <section class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="w-full max-w-md p-8 space-y-8 bg-white rounded-lg shadow">
          <div class="flex justify-center">
            <img src="/storage/raffl-logo.png" alt="Rafflit Logo" class="w-16 h-16">
          </div>
          <h2 class="text-2xl font-bold text-center text-gray-900">Reset Password</h2>
  
          <form @submit.prevent="submit" class="mt-8 space-y-6">
            <div>
              <InputLabel for="email" value="Email" class="block text-sm font-medium text-gray-700" />
              <TextInput
                id="email"
                type="email"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                v-model="form.email"
                required
                autofocus
                autocomplete="username"
              />
              <InputError class="mt-2 text-red-500 text-xs italic" :message="form.errors.email" />
            </div>
  
            <div>
              <InputLabel for="password" value="Password" class="block text-sm font-medium text-gray-700" />
              <TextInput
                id="password"
                type="password"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                v-model="form.password"
                required
                autocomplete="new-password"
              />
              <InputError class="mt-2 text-red-500 text-xs italic" :message="form.errors.password" />
            </div>
  
            <div>
              <InputLabel for="password_confirmation" value="Confirm Password" class="block text-sm font-medium text-gray-700" />
              <TextInput
                id="password_confirmation"
                type="password"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                v-model="form.password_confirmation"
                required
                autocomplete="new-password"
              />
              <InputError class="mt-2 text-red-500 text-xs italic" :message="form.errors.password_confirmation" />
            </div>
  
            <div class="flex items-center justify-end mt-4">
              <PrimaryButton
                class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white font-bold py-2 px-4 rounded"
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
              >
                Reset Password
              </PrimaryButton>
            </div>
          </form>
        </div>
      </section>
    </GuestLayout>
  </template>
  
  <script setup>
  import GuestLayout from '@/Layouts/GuestLayout.vue';
  import InputError from '@/Components/InputError.vue';
  import InputLabel from '@/Components/InputLabel.vue';
  import PrimaryButton from '@/Components/PrimaryButton.vue';
  import TextInput from '@/Components/TextInput.vue';
  import { Head, useForm } from '@inertiajs/vue3';
  
  const props = defineProps({
    email: {
      type: String,
      required: true,
    },
    token: {
      type: String,
      required: true,
    },
  });
  
  const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
  });
  
  const submit = () => {
    form.post(route('password.store'), {
      onFinish: () => form.reset('password', 'password_confirmation'),
    });
  };
  </script>
  