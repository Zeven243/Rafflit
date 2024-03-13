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
                <label for="price" class="block text-gray-700 text-sm font-bold mb-2">Price:</label>
                <input type="number" id="price" v-model="form.price" class="block w-full rounded-md border-gray-300 shadow-sm" required>
              </div>
              <div class="mb-4">
                <label for="image" class="block text-gray-700 text-sm font-bold mb-2">Image:</label>
                <input type="file" id="image" @change="handleFileUpload" class="block w-full rounded-md border-gray-300 shadow-sm">
                <div v-if="form.image" class="mt-2">
                  <img :src="URL.createObjectURL(form.image)" class="max-w-xs max-h-60 rounded-md">
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
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';

const props = defineProps({
  listing: Object,
});

const form = ref({
  name: props.listing.name,
  description: props.listing.description,
  price: props.listing.price,
  image: null,
});

const handleFileUpload = event => {
  form.value.image = event.target.files[0];
};

const submitForm = () => {
  let formData = new FormData();
  formData.append('name', form.value.name);
  formData.append('description', form.value.description);
  formData.append('price', form.value.price);
  if (form.value.image) {
    formData.append('image', form.value.image);
  }

  Inertia.post(`/listings/${props.listing.id}`, formData, {
    onSuccess: () => {
      form.value.image = null; // Clear the image input after successful upload
    },
    preserveState: true,
  });
};
</script>

<style>
/* Add any additional styles here */
</style>
