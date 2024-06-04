<template>
  <AuthenticatedLayout>
    <div class="min-h-screen bg-gray-100 py-12">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-xl overflow-hidden">
          <div class="p-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">Edit Listing</h1>
            <form @submit.prevent="submitForm" enctype="multipart/form-data">
              <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name:</label>
                <input type="text" id="name" v-model="form.name" class="block w-full rounded-md border-gray-300 shadow-sm" required>
              </div>
              <div class="mb-4">
                <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description:</label>
                <textarea id="description" v-model="form.description" class="block w-full rounded-md border-gray-300 shadow-sm" required></textarea>
              </div>
              <div class="mb-4">
                <label for="category" class="block text-gray-700 text-sm font-bold mb-2">Category:</label>
                <select id="category" v-model="form.category_id" class="block w-full rounded-md border-gray-300 shadow-sm" required>
                  <option value="" disabled>Select a category</option>
                  <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
                </select>
              </div>
              <div class="mb-4">
                <label for="full_price" class="block text-gray-700 text-sm font-bold mb-2">Full Price:</label>
                <input type="number" id="full_price" v-model="form.full_price" class="block w-full rounded-md border-gray-300 shadow-sm" required>
              </div>
              <div class="mb-4">
                <label for="amount_of_tickets" class="block text-gray-700 text-sm font-bold mb-2">Amount of Tickets:</label>
                <input type="number" id="amount_of_tickets" v-model="form.amount_of_tickets" class="block w-full rounded-md border-gray-300 shadow-sm" required>
              </div>
              <div class="mb-4">
                <label for="ticket_price" class="block text-gray-700 text-sm font-bold mb-2">Ticket Price:</label>
                <input type="number" id="ticket_price" v-model="form.ticket_price" class="block w-full rounded-md border-gray-300 shadow-sm" readonly>
              </div>
              <div class="mb-4">
                <label for="cover_image" class="block text-gray-700 text-sm font-bold mb-2">Cover Image:</label>
                <input type="file" id="cover_image" @change="handleCoverImageUpload" class="block w-full rounded-md border-gray-300 shadow-sm">
                <div v-if="form.cover_image" class="mt-2">
                  <img :src="URL.createObjectURL(form.cover_image)" class="max-w-xs max-h-60 rounded-md">
                </div>
              </div>
              <div class="mb-4">
                <label for="images" class="block text-gray-700 text-sm font-bold mb-2">Images:</label>
                <input type="file" id="images" @change="handleImagesUpload" class="block w-full rounded-md border-gray-300 shadow-sm" multiple>
                <div v-if="form.images.length" class="mt-2 flex flex-wrap gap-2">
                  <img v-for="(image, index) in form.images" :key="index" :src="URL.createObjectURL(image)" class="max-w-xs max-h-60 rounded-md">
                </div>
              </div>
              <div class="flex items-center justify-between">
                <Link href="/listings" class="inline-block bg-gradient-to-r from-orange-500 to-yellow-500 hover:from-orange-600 hover:to-yellow-600 text-white font-bold py-2 px-4 rounded">
                  Back to Listings
                </Link>
                <button type="submit" class="inline-block bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white font-bold py-2 px-4 rounded">
                  Save Changes
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { Inertia } from '@inertiajs/inertia';

const props = defineProps({
  listing: Object,
  categories: Array,
});

const form = ref({
  name: props.listing.name,
  description: props.listing.description,
  category_id: props.listing.category_id,
  full_price: props.listing.full_price,
  amount_of_tickets: props.listing.amount_of_tickets,
  ticket_price: props.listing.ticket_price,
  cover_image: null,
  images: [],
});

const ticketPrice = computed(() => {
  return form.value.full_price && form.value.amount_of_tickets ? (form.value.full_price / form.value.amount_of_tickets).toFixed(2) : 0;
});

const handleCoverImageUpload = event => {
  form.value.cover_image = event.target.files[0];
};

const handleImagesUpload = event => {
  form.value.images = Array.from(event.target.files);
};

const submitForm = () => {
  let formData = new FormData();
  formData.append('name', form.value.name);
  formData.append('description', form.value.description);
  formData.append('category_id', form.value.category_id);
  formData.append('full_price', form.value.full_price);
  formData.append('amount_of_tickets', form.value.amount_of_tickets);
  formData.append('ticket_price', ticketPrice.value);
  if (form.value.cover_image) {
    formData.append('cover_image', form.value.cover_image);
  }
  form.value.images.forEach((image, index) => {
    formData.append(`images[${index}]`, image);
  });

  Inertia.post(`/listings/${props.listing.id}`, formData, {
    onSuccess: () => {
      form.value.cover_image = null; // Clear the image input after successful upload
      form.value.images = []; // Clear the images input after successful upload
    },
    preserveState: true,
  });
};
</script>

<style>
/* Add any additional styles here */
</style>