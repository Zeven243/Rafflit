<template>
  <AuthenticatedLayout>
    <div class="bg-white min-h-screen">
      <div class="max-w-6xl mx-auto py-10">
        <h1 class="text-3xl font-bold mb-5">User Management</h1>
        <div class="flex justify-between mb-5">
          <input type="text" v-model="search" placeholder="Search users..." class="p-2 border rounded">
          <!-- Sub-navbar for administrative links -->
          <div class="flex gap-4">
            <Link :href="route('audit-systems.index')" class="p-2 bg-blue-500 text-white rounded hover:bg-blue-700">Audit Systems</Link>
            <Link :href="route('role-management.index')" class="p-2 bg-blue-500 text-white rounded hover:bg-blue-700">Role Management</Link>
          </div>
        </div>
        <div class="overflow-x-auto rounded-lg shadow overflow-y-auto relative">
          <table class="border-collapse table-auto w-full whitespace-no-wrap bg-white table-striped relative">
            <thead>
              <tr class="text-left">
                <th class="bg-blue-500 text-white p-2">DB ID</th>
                <th class="bg-blue-500 text-white p-2">First Name</th>
                <th class="bg-blue-500 text-white p-2">Last Name</th>
                <th class="bg-blue-500 text-white p-2">Email</th>
                <th class="bg-blue-500 text-white p-2">Roles</th>
                <th class="bg-blue-500 text-white p-2">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="user in filteredUsers" :key="user.id" class="hover:bg-gray-100">
                <td class="p-2">{{ user.id }}</td>
                <td class="p-2">{{ user.first_name }}</td>
                <td class="p-2">{{ user.last_name }}</td>
                <td class="p-2">{{ user.email }}</td>
                <td class="p-2">
                  <div v-if="!user.editing">
                    <span v-for="role in user.roles" :key="role.id" class="badge">{{ role.name }}</span>
                  </div>
                  <div v-else>
                    <select v-model="user.selected_role" class="p-1 rounded border">
                      <option value="">Select Role</option>
                      <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.name }}</option>
                    </select>
                  </div>
                </td>
                <td class="p-2 flex gap-2">
                  <button v-if="!user.editing" @click="startEditing(user)" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Edit
                  </button>
                  <template v-if="user.editing">
                    <button @click="updateRole(user)" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                      Confirm
                    </button>
                    <button @click="cancelEditing(user)" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                      Cancel
                    </button>
                  </template>
                  <button @click="confirmDelete(user)" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    Delete
                  </button>
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
import { ref, computed, defineEmits } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
  users: Array,
  roles: Array,
});

const emit = defineEmits(['user-deleted']);

const search = ref('');

const filteredUsers = computed(() => {
  return props.users.filter(user =>
    user.first_name.toLowerCase().includes(search.value.toLowerCase()) ||
    user.last_name.toLowerCase().includes(search.value.toLowerCase()) ||
    user.email.toLowerCase().includes(search.value.toLowerCase())
  );
});

const startEditing = (user) => {
  user.editing = true;
};

const cancelEditing = (user) => {
  user.editing = false;
  user.selected_role = user.roles.map(role => role.id);
};

const updateRole = (user) => {
  Inertia.put(route('user-management.updateRole', user.id), {
    role_id: user.selected_role,
  });
};

const confirmDelete = (user) => {
  if (confirm(`Are you sure you want to delete ${user.first_name}?`)) {
    Inertia.delete(route('user-management.destroy', user.id), {
      onSuccess: () => {
        emit('user-deleted', user.id);
      },
      onError: (errors) => {
        console.error(errors);
      }
    });
  }
};
</script>

<style scoped>
.badge {
  display: inline-block;
  padding: 0.25em 0.4em;
  font-size: 75%;
  font-weight: 700;
  line-height: 1;
  text-align: center;
  white-space: nowrap;
  vertical-align: baseline;
  border-radius: 0.25rem;
  background-color: #f0f0f0;
  color: #333;
  margin-right: 0.5em;
}
</style>
