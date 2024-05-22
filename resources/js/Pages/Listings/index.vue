<template>
  <AuthenticatedLayout>
    <div class="min-h-screen bg-gray-100 py-12">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
          <h1 class="text-3xl font-bold text-gray-800">My Listings</h1>
          <Link href="/listings/create"
            class="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700">
            New Listing
          </Link>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div class="bg-white rounded-lg shadow-lg overflow-hidden" v-for="listing in listings" :key="listing.id">
            <img :src="`/storage/${listing.image_path}`" alt="Listing Image" class="w-full h-48 object-cover">
            <div class="p-5">
              <h2 class="text-xl font-bold text-gray-800 mb-2">{{ listing.name }}</h2>
              <p class="text-gray-600 mb-4">{{ listing.description }}</p>
              <div class="flex justify-between items-center">
                <span class="text-gray-900 font-bold">Full Price: R{{ listing.full_price }}</span>
                <span class="text-gray-500">Per Ticket: R{{ listing.ticket_price }}</span>
              </div>
              <div class="mt-4 flex justify-between items-center">
                <span class="text-gray-500">Amount of Tickets: {{ listing.amount_of_tickets }}</span>
                <Link :href="`/listings/${listing.id}`"
                  class="inline-block bg-gradient-to-r from-orange-500 to-yellow-500 hover:from-orange-600 hover:to-yellow-600 text-white font-bold py-2 px-4 rounded">
                  View Listing
                </Link>
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

defineProps({
  listings: Array,
});
</script>

<style>
/* Add any additional styles here */
</style>
