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
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
          <div class="px-4 py-5 sm:p-6">
            <div class="grid grid-cols-6 gap-6">
              <div class="col-span-6 sm:col-span-3">
                <label for="startDate" class="block text-sm font-medium text-gray-700">Start Date</label>
                <input type="date" id="startDate" v-model="startDate" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
              </div>
              <div class="col-span-6 sm:col-span-3">
                <label for="endDate" class="block text-sm font-medium text-gray-700">End Date</label>
                <input type="date" id="endDate" v-model="endDate" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
              </div>
            </div>
          </div>
        </div>
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
          <div class="px-4 py-5 sm:p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Select Columns to Display</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
              <div v-for="column in availableColumns" :key="column.value">
                <label class="inline-flex items-center">
                  <input type="checkbox" :value="column.value" v-model="selectedColumns" class="form-checkbox h-5 w-5 text-blue-600">
                  <span class="ml-2 text-gray-700">{{ column.label }}</span>
                </label>
              </div>
            </div>
          </div>
        </div>
        <div class="overflow-x-auto mt-6">
          <table class="min-w-full divide-y divide-gray-200 shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
            <thead class="bg-gray-50">
              <tr>
                <th v-for="column in selectedColumns" :key="column" scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  {{ getColumnLabel(column) }}
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="audit in paginatedAudits" :key="audit.id">
                <td v-for="column in selectedColumns" :key="column" class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">
                    <template v-if="column === 'subject_details'">
                      {{ audit.subject_type ? `${audit.subject_type.split('\\').pop()} (ID: ${audit.subject_id})` : 'N/A' }}
                    </template>
                    <template v-else-if="column === 'causer_details'">
                      {{ audit.causer ? `${audit.causer.first_name} ${audit.causer.last_name} (ID: ${audit.causer_id})` : 'System' }}
                    </template>
                    <template v-else-if="column === 'properties'">
                      {{ audit.properties ? JSON.stringify(audit.properties, null, 2) : 'None' }}
                    </template>
                    <template v-else-if="column === 'created_at'">
                      {{ new Date(audit.created_at).toLocaleString() }}
                    </template>
                    <template v-else>
                      {{ audit[column] || 'N/A' }}
                    </template>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <Pagination :links="audits.links" @paginate="fetchAudits" />
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { defineProps, ref, computed, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { Link } from '@inertiajs/vue3';
import * as XLSX from 'xlsx';

const props = defineProps({
  audits: Object,
});

const availableColumns = [
  { label: 'Log Name', value: 'log_name' },
  { label: 'Description', value: 'description' },
  { label: 'Event', value: 'event' },
  { label: 'Subject Details', value: 'subject_details' },
  { label: 'Causer Details', value: 'causer_details' },
  { label: 'Properties', value: 'properties' },
  { label: 'Batch UUID', value: 'batch_uuid' },
  { label: 'Date & Time', value: 'created_at' },
];

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

const startDate = ref('');
const endDate = ref('');
const currentPage = ref(1);

const filteredAudits = computed(() => {
  return props.audits.data.filter((audit) => {
    const auditDate = new Date(audit.created_at);
    const start = startDate.value ? new Date(startDate.value) : null;
    const end = endDate.value ? new Date(endDate.value) : null;

    return (!start || auditDate >= start) && (!end || auditDate <= end);
  });
});

const paginatedAudits = computed(() => {
  const startIndex = (currentPage.value - 1) * props.audits.per_page;
  const endIndex = startIndex + props.audits.per_page;
  return filteredAudits.value.slice(startIndex, endIndex);
});

const fetchAudits = async (page = 1) => {
  currentPage.value = page;
  await axios.get(route('audits.index', { page }));
};

const exportToExcel = () => {
  const worksheet = XLSX.utils.json_to_sheet(filteredAudits.value);
  const workbook = XLSX.utils.book_new();
  XLSX.utils.book_append_sheet(workbook, worksheet, 'Audits');
  XLSX.writeFile(workbook, 'audits.xlsx');
};

const getColumnLabel = (column) => {
  const columnObj = availableColumns.find((col) => col.value === column);
  return columnObj ? columnObj.label : '';
};

onMounted(() => {
  fetchAudits();
});
</script>

<style>
/* Tailwind CSS is utility-first, you might not need additional styles */
</style>
