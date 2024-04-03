<template>
    <AuthenticatedLayout>
        <div class="bg-white min-h-screen">
            <div class="max-w-6xl mx-auto py-10">
                <h1 class="text-3xl font-bold mb-5">User Management</h1>
                <input type="text" v-model="search" placeholder="Search users..." class="mb-5 p-2 border rounded">
                <div class="overflow-x-auto rounded-lg shadow overflow-y-auto relative">
                    <table class="border-collapse table-auto w-full whitespace-no-wrap bg-white table-striped relative">
                        <thead>
                            <tr class="text-left">
                                <th class="bg-blue-500 text-white p-2">DB ID</th>
                                <th class="bg-blue-500 text-white p-2">first_name</th>
                                <th class="bg-blue-500 text-white p-2">last_name</th>
                                <th class="bg-blue-500 text-white p-2">Email</th>
                                <th class="bg-blue-500 text-white p-2">Roles</th>
                                <th class="bg-blue-500 text-white p-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="user in filteredUsers" :key="user.id" class="hover:bg-gray-100">
                                <td class="p-2">{{ user.id }}</td>
                                <td class="p-2">{{ user.first_name }}</td>
                                <td class="p-2">{{ user.last_name }}</td> <!-- Assuming you have a last_name field -->
                                <td class="p-2">{{ user.email }}</td>
                                <td class="p-2">
                                    <span v-for="role in user.roles" :key="role.id" class="mr-2">{{ role.first_name }}</span>
                                </td>
                                <td class="p-2">
                                    <a :href="route('user-management.edit', user.id)"
                                        class="text-blue-500 hover:text-blue-600">Edit</a>
                                    |
                                    <a href="#" @click="confirmDelete(user)"
                                        class="text-red-500 hover:text-red-600">Delete</a>
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
import { ref, computed } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

// Define the users prop
const props = defineProps({
  users: Array,
});

const search = ref('');

const filteredUsers = computed(() => {
  return props.users.filter(user =>
    (user.first_name && user.first_name.toLowerCase().includes(search.value.toLowerCase())) ||
    (user.last_name && user.last_name.toLowerCase().includes(search.value.toLowerCase())) ||
    (user.email && user.email.toLowerCase().includes(search.value.toLowerCase()))
  );
});


const confirmDelete = (user) => {
  if (confirm(`Are you sure you want to delete ${user.first_name}?`)) {
    Inertia.delete(route('user-management.destroy', user.id));
  }
};
</script>


<style scoped>
/* Add any additional styles here */
</style>