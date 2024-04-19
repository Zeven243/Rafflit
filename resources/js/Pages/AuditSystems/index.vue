<template>
  <AuthenticatedLayout>
    <div class="min-h-screen bg-gray-100">
      <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
          <h1 class="text-2xl font-semibold text-gray-900">Audit Systems</h1>
          <div>
            <button @click="exportToExcel" class="inline-flex items-center px-4 py-2 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-600 active:bg-green-700 focus:outline-none focus:border-green-700 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150 mr-4">Export to Excel</button>
            <Link href="/user-management" class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">Back to User Management</Link>
          </div>
        </div>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200 shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
            <thead class="bg-gray-50">
              <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  <input type="checkbox" v-model="selectedColumns" value="log_name" class="mr-2">
                  Log Name
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  <input type="checkbox" v-model="selectedColumns" value="description" class="mr-2">
                  Description
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  <input type="checkbox" v-model="selectedColumns" value="event" class="mr-2">
                  Event
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  <input type="checkbox" v-model="selectedColumns" value="subject_details" class="mr-2">
                  Subject Details
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  <input type="checkbox" v-model="selectedColumns" value="causer_details" class="mr-2">
                  Causer Details
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  <input type="checkbox" v-model="selectedColumns" value="properties" class="mr-2">
                  Properties
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  <input type="checkbox" v-model="selectedColumns" value="batch_uuid" class="mr-2">
                  Batch UUID
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  <input type="checkbox" v-model="selectedColumns" value="created_at" class="mr-2">
                  Date & Time
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="audit in filteredAudits" :key="audit.id">
                <td v-if="selectedColumns.includes('log_name')" class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">{{ audit.log_name }}</div>
                </td>
                <td v-if="selectedColumns.includes('description')" class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">{{ audit.description }}</div>
                </td>
                <td v-if="selectedColumns.includes('event')" class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">{{ audit.event }}</div>
                </td>
                <td v-if="selectedColumns.includes('subject_details')" class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">{{ audit.subject_type ? `${audit.subject_type.split('\\').pop()} (ID: ${audit.subject_id})` : 'N/A' }}</div>
                </td>
                <td v-if="selectedColumns.includes('causer_details')" class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">
                    {{ audit.causer ? `${audit.causer.first_name} ${audit.causer.last_name} (ID: ${audit.causer_id})` : 'System' }}
                  </div>
                </td>
                <td v-if="selectedColumns.includes('properties')" class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">{{ audit.properties ? JSON.stringify(audit.properties, null, 2) : 'None' }}</div>
                </td>
                <td v-if="selectedColumns.includes('batch_uuid')" class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">{{ audit.batch_uuid || 'N/A' }}</div>
                </td>
                <td v-if="selectedColumns.includes('created_at')" class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">{{ new Date(audit.created_at).toLocaleString() }}</div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <Pagination :links="audits.links" />
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { defineProps, ref, computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { Link } from '@inertiajs/vue3';
import * as XLSX from 'xlsx';

const props = defineProps({
  audits: Object,
});

const selectedColumns = ref([
  'log_name',
  'description',
  'event',
  'subject_details',
  'causer_details',
  'properties',
  'batch_uuid',
  'created_at',
]);

const filteredAudits = computed(() => {
  return props.audits.data.filter((audit) => {
    return selectedColumns.value.some((column) => {
      return audit.hasOwnProperty(column);
    });
  });
});

const exportToExcel = () => {
  const worksheet = XLSX.utils.json_to_sheet(filteredAudits.value);
  const workbook = XLSX.utils.book_new();
  XLSX.utils.book_append_sheet(workbook, worksheet, 'Audits');
  XLSX.writeFile(workbook, 'audits.xlsx');
};
</script>

<style>
/* Tailwind CSS is utility-first, you might not need additional styles */
</style>