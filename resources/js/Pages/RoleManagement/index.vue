<template>
  <AuthenticatedLayout>
    <div class="min-h-screen bg-white">
      <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
          <h1 class="text-2xl font-semibold text-gray-900">Role Management</h1>
          <Link href="/user-management" class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">Back to User Management</Link>
        </div>
        <div class="mb-4">
          <form @submit.prevent="createRole">
            <div class="flex items-center space-x-4">
              <div>
                <label for="roleName" class="block text-sm font-medium text-gray-700">Role Name</label>
                <input type="text" id="roleName" v-model="newRole.name" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Enter role name">
              </div>
              <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">Create Role</button>
            </div>
          </form>
        </div>
        <div class="mt-4">
          <table class="min-w-full divide-y divide-gray-200 shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
            <thead class="bg-gray-50">
              <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-widest">
                  Role Name
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-widest">
                  Actions
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="role in roles" :key="role.id">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">{{ role.name }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <!-- Add any action buttons here -->
                  <a href="#" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                  <a href="#" class="text-red-600 hover:text-red-900 ml-4">Delete</a>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import axios from 'axios';

const newRole = ref({ name: '' });
const { props } = usePage();
const roles = ref(props.roles || []);

const fetchRoles = async () => {
  try {
    const response = await axios.get('/api/roles');
    roles.value = response.data;
  } catch (error) {
    console.error('Failed to fetch roles:', error);
  }
};

const createRole = async () => {
  try {
    await axios.post('/api/roles', { name: newRole.value.name });
    newRole.value.name = ''; // Reset the form
    await fetchRoles(); // Refresh the list of roles
  } catch (error) {
    console.error('Error creating role:', error);
  }
};

onMounted(fetchRoles);
</script>

<style>
/* Tailwind CSS is utility-first, you might not need additional styles */
</style>
