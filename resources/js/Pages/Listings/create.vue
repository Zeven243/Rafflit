<template>
  <AuthenticatedLayout>
    <div class="min-h-screen bg-gray-100 flex justify-center items-center">
      <form @submit.prevent="submit" class="w-full max-w-lg p-8 bg-white rounded-lg shadow-xl" enctype="multipart/form-data">
        <div class="flex justify-center mb-6">
          <img src="/storage/raffl-logo.png" alt="Raffl Logo" class="h-16">
        </div>
        <div class="flex flex-col gap-6">
          <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" id="name" name="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" v-model="form.name">
            <div v-if="form.errors.name" class="text-red-500 text-xs italic">{{ form.errors.name }}</div>
          </div>

          <div>
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea id="description" name="description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" v-model="form.description" rows="3"></textarea>
            <div v-if="form.errors.description" class="text-red-500 text-xs italic">{{ form.errors.description }}</div>
          </div>

          <div>
            <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
            <div class="flex items-center mt-1">
              <select id="category" name="category" class="w-full rounded-md border-gray-300 shadow-sm" v-model="form.category_id">
                <option value="" disabled>Select a category</option>
                <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
              </select>
              <button type="button" @click="showCreateCategoryModal = true" class="ml-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                + Create Category
              </button>
            </div>
          </div>

          <div>
            <label for="company" class="block text-sm font-medium text-gray-700">Company</label>
            <select id="company" name="company" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" v-model="form.company">
              <option value="" disabled>Select a company</option>
              <option v-for="company in uniqueCompanies" :key="company" :value="company">{{ company }}</option>
            </select>
          </div>

          <div>
            <label for="full_price" class="block text-sm font-medium text-gray-700">Full Price</label>
            <input type="number" id="full_price" name="full_price" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" v-model.number="form.full_price">
            <div v-if="form.errors.full_price" class="text-red-500 text-xs italic">{{ form.errors.full_price }}</div>
          </div>

          <div>
            <label for="amount_of_tickets" class="block text-sm font-medium text-gray-700">Amount of Tickets</label>
            <input type="number" id="amount_of_tickets" name="amount_of_tickets" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" v-model.number="form.amount_of_tickets">
            <div v-if="form.errors.amount_of_tickets" class="text-red-500 text-xs italic">{{ form.errors.amount_of_tickets }}</div>
          </div>

          <div>
            <label for="ticket_price" class="block text-sm font-medium text-gray-700">Ticket Price</label>
            <input type="number" id="ticket_price" name="ticket_price" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" :value="ticketPrice" readonly>
          </div>

          <div>
            <label for="cover_image" class="block text-sm font-medium text-gray-700">Cover Image</label>
            <input type="file" id="cover_image" name="cover_image" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" @change="onCoverImageChange">
          </div>

          <div>
            <label for="images" class="block text-sm font-medium text-gray-700">Images</label>
            <input type="file" id="images" name="images" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" multiple @change="onImagesChange">
          </div>

          <button type="submit" class="mt-6 w-full bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white font-bold py-2 px-4 rounded">
            Create Listing
          </button>
        </div>
      </form>
    </div>

    <!-- Create Category Modal -->
    <div v-if="showCreateCategoryModal" class="fixed inset-0 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-lg p-6">
        <h3 class="text-lg font-medium mb-4">Create New Category</h3>
        <div>
          <label for="new-category-name" class="block text-sm font-medium text-gray-700">Category Name</label>
          <input type="text" id="new-category-name" v-model="newCategoryName" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
          <div v-if="categoryError" class="text-red-500 text-xs italic">{{ categoryError }}</div>
        </div>
        <div class="mt-4 flex justify-end">
          <button type="button" @click="showCreateCategoryModal = false" class="mr-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
            Cancel
          </button>
          <button type="button" @click="createCategory" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Create
          </button>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { computed, ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import axios from 'axios';

const props = defineProps({
  errors: Object,
  categories: {
    type: Array,
    required: true,
  },
  users: {
    type: Array,
    required: true,
  },
});

const form = useForm({
  name: '',
  description: '',
  category_id: null,
  company: '',
  full_price: 0,
  amount_of_tickets: 0,
  cover_image: null,
  images: [],
});

const ticketPrice = computed(() => {
  return form.full_price && form.amount_of_tickets ? (form.full_price / form.amount_of_tickets).toFixed(2) : 0;
});

const uniqueCompanies = computed(() => {
  const companies = props.users.map(user => user.company);
  return [...new Set(companies)];
});

const resetForm = () => {
  form.reset();
};

const submit = () => {
  console.log('Form data before submission:', form); // Add this line to check the form data
  form.post(route('listings.store'), {
    forceFormData: true,
    onSuccess: () => {
      Inertia.visit(route('listings.index'));
    },
    onError: (errors) => {
      console.error('Form submission errors:', errors);
    }
  });
};

const showCreateCategoryModal = ref(false);
const newCategoryName = ref('');
const categoryError = ref('');

const createCategory = async () => {
  try {
    const response = await axios.post(route('categories.store'), { name: newCategoryName.value });
    const newCategory = response.data;
    props.categories.push(newCategory);
    newCategoryName.value = '';
    showCreateCategoryModal.value = false;
    categoryError.value = '';
  } catch (error) {
    console.error('Error creating category:', error);
    if (error.response && error.response.data && error.response.data.errors) {
      categoryError.value = Object.values(error.response.data.errors).join(', ');
    } else {
      categoryError.value = 'An unexpected error occurred while creating the category.';
    }
  }
};

const onCoverImageChange = (event) => {
  form.cover_image = event.target.files[0];
};

const onImagesChange = (event) => {
  form.images = Array.from(event.target.files);
};
</script>

<style scoped>
/* Add any additional styles here */
</style>