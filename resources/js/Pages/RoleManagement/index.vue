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
              <div>
                <label class="block text-sm font-medium text-gray-700">Permissions</label>
                <div class="mt-2">
                  <div v-for="permission in permissions" :key="permission.id" class="flex items-center">
                    <input type="checkbox" :id="'permission-' + permission.id" :value="permission.id" v-model="newRole.permissions" class="form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out">
                    <label :for="'permission-' + permission.id" class="ml-2 block text-sm leading-5 text-gray-700">
                      {{ permission.name }}
                    </label>
                  </div>
                </div>
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
                  Permissions
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
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex flex-wrap">
                    <div v-for="permission in role.permissions" :key="permission.id" class="px-2 py-1 bg-gray-200 text-gray-700 rounded-full text-xs font-medium mr-2 mb-2">
                      {{ permission.name }}
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <a href="#" class="text-indigo-600 hover:text-indigo-900" @click="editRole(role)">Edit</a>
                  <a href="#" class="text-red-600 hover:text-red-900 ml-4" @click="deleteRole(role)">Delete</a>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Edit Role Modal -->
    <div v-if="showEditModal" class="fixed z-10 inset-0 overflow-y-auto">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
          <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-headline">
                  Edit Role
                </h3>
                <div class="mt-2">
                  <form @submit.prevent="updateRole">
                    <div class="mb-4">
                      <label for="editRoleName" class="block text-sm font-medium text-gray-700">Role Name</label>
                      <input type="text" id="editRoleName" v-model="editedRole.name" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                    <div class="mb-4">
                      <label class="block text-sm font-medium text-gray-700">Permissions</label>
                      <div class="mt-2">
                        <div v-for="permission in permissions" :key="permission.id" class="flex items-center">
                          <input type="checkbox" :id="'permission-' + permission.id" :value="permission.id" v-model="editedRole.permissions" class="form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out">
                          <label :for="'permission-' + permission.id" class="ml-2 block text-sm leading-5 text-gray-700">
                            {{ permission.name }}
                          </label>
                        </div>
                      </div>
                    </div>
                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                      <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Update
                      </button>
                      <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:w-auto sm:text-sm" @click="closeEditModal">
                        Cancel
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
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

const newRole = ref({ name: '', permissions: [] });
const { props } = usePage();
const roles = ref(props.roles || []);
const permissions = ref(props.permissions || []);
const showEditModal = ref(false);
const editedRole = ref({ id: null, name: '', permissions: [] });

const fetchRoles = async () => {
  try {
    const response = await axios.get('/api/roles');
    roles.value = response.data;
  } catch (error) {
    console.error('Failed to fetch roles:', error);
  }
};

const fetchPermissions = async () => {
  try {
    const response = await axios.get('/api/permissions');
    permissions.value = response.data;
  } catch (error) {
    console.error('Failed to fetch permissions:', error);
  }
};

const createRole = async () => {
  try {
    await axios.post('/api/roles', {
      name: newRole.value.name,
      permissions: newRole.value.permissions
    });
    newRole.value = { name: '', permissions: [] }; // Reset the form
    await fetchRoles(); // Refresh the list of roles
  } catch (error) {
    console.error('Error creating role:', error);
  }
};

const editRole = (role) => {
  editedRole.value = {
    id: role.id,
    name: role.name,
    permissions: role.permissions.map(permission => permission.id)
  };
  showEditModal.value = true;
};

const updateRole = async () => {
  try {
    await axios.put(`/api/roles/${editedRole.value.id}`, {
      name: editedRole.value.name,
      permissions: editedRole.value.permissions
    });
    await fetchRoles(); // Refresh the list of roles
    closeEditModal();
  } catch (error) {
    console.error('Error updating role:', error);
  }
};

const deleteRole = async (role) => {
  if (confirm('Are you sure you want to delete this role?')) {
    try {
      await axios.delete(`/api/roles/${role.id}`);
      await fetchRoles(); // Refresh the list of roles
    } catch (error) {
      console.error('Error deleting role:', error);
    }
  }
};

const closeEditModal = () => {
  showEditModal.value = false;
  editedRole.value = { id: null, name: '', permissions: [] };
};

onMounted(() => {
  fetchRoles();
  fetchPermissions();
});
</script>

<style>
/* Tailwind CSS is utility-first, you might not need additional styles */
</style>
