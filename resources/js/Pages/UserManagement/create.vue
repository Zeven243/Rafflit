<template>
    <AuthenticatedLayout>
      <div class="min-h-screen bg-gray-100">
        <div class="py-12">
          <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
              <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-bold mb-10">Create New User</h2>
                <form @submit.prevent="submit">
                  <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                      Name
                    </label>
                    <input v-model="form.name" type="text" placeholder="Name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                  </div>
                  <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                      Email
                    </label>
                    <input v-model="form.email" type="email" placeholder="Email" name="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                  </div>
                  <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="role_id">
                      Role
                    </label>
                    <select v-model="form.role_id" name="role_id" class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
                      <option value="">Select a role</option>
                      <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.name }}</option>
                    </select>
                  </div>
                  <div class="flex items-center justify-between">
                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                      Create User
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </AuthenticatedLayout>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import { Inertia } from '@inertiajs/inertia';
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  
  const props = defineProps({
    roles: Array,
  });
  
  const form = ref({
    name: '',
    email: '',
    role_id: '',
  });
  
  const submit = () => {
    Inertia.post(route('user-management.store'), form.value, {
      onSuccess: () => {
        form.value.name = '';
        form.value.email = '';
        form.value.role_id = '';
      },
    });
  };
  </script>