<template>
    <AuthenticatedLayout>
      <div class="min-h-screen bg-gray-100 py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex justify-center mb-6">
            <img src="path-to-your-logo" alt="Raffl Logo" class="h-16">
          </div>
          <div class="bg-white rounded-lg shadow-xl overflow-hidden">
            <!-- Increase the height of the image for better visibility -->
            <img :src="listing.image" alt="Listing Image" class="w-full h-96 object-cover">
            <div class="p-6">
              <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ listing.name }}</h1>
              <p class="text-gray-600 mb-4">{{ listing.description }}</p>
              <div class="flex justify-between items-center mb-4">
                <span class="text-gray-900 font-bold">Price: ${{ listing.price }}</span>
                <span class="text-gray-500">Per Ticket: ${{ (listing.price / listing.amount_of_tickets).toFixed(2) }}</span>
              </div>
              <div class="flex justify-between items-center">
                <Link href="/listings" class="inline-block bg-gradient-to-r from-orange-500 to-yellow-500 hover:from-orange-600 hover:to-yellow-600 text-white font-bold py-2 px-4 rounded">
                  Back to Listings
                </Link>
                <div>
                  <Link :href="`/listings/${listing.id}/edit`" class="inline-block bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white font-bold py-2 px-4 rounded mr-2">
                    Edit
                  </Link>
                  <button @click="deleteListing" class="inline-block bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 text-white font-bold py-2 px-4 rounded">
                    Delete
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </AuthenticatedLayout>
  </template>
  
  <script setup>
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  import { Link } from '@inertiajs/vue3';
  import { Inertia } from '@inertiajs/inertia';
  
  const props = defineProps({
    listing: Object,
  });
  
  const deleteListing = () => {
    if (confirm('Are you sure you want to delete this listing?')) {
      Inertia.delete(`/listings/${props.listing.id}`, {
        onSuccess: () => {
          // Redirect or perform additional actions after successful deletion
        }
      });
    }
  };
  </script>
  
  <style>
  /* Add any additional styles here */
  </style>
  