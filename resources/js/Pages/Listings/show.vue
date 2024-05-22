<template>
  <AuthenticatedLayout>
    <div class="min-h-screen bg-gray-100 py-12">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-center mb-6">
          <img src="/storage/raffl-logo.png" alt="Raffl Logo" class="h-16">
        </div>
        <div class="bg-white rounded-lg shadow-xl overflow-hidden">
          <div class="flex flex-col md:flex-row">
            <div class="md:w-1/2">
              <img :src="`/storage/${listing.image_path}`" alt="Listing Image" class="object-cover w-full h-full">
            </div>
            <div class="p-6 md:w-1/2">
              <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ listing.name }}</h1>
              <p class="text-gray-600 mb-4">{{ listing.description }}</p>
              <div class="mb-4">
                <span class="text-gray-900 font-bold">Category: {{ listing.category.name }}</span>
              </div>
              <div class="mb-4">
                <span class="text-gray-900 font-bold">Full Price: R {{ listing.full_price }}</span>
              </div>
              <div class="mb-4">
                <span class="text-gray-900 font-bold">Amount of Tickets: {{ listing.amount_of_tickets }}</span>
              </div>
              <div class="mb-4">
                <span class="text-gray-900 font-bold">Ticket Price: R {{ listing.ticket_price }}</span>
              </div>
              <!-- Progress Bar -->
              <div class="mb-4">
                <div class="relative pt-1">
                  <div class="flex mb-2 items-center justify-between">
                    <div>
                      <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-blue-600 bg-blue-200">
                        Progress
                      </span>
                    </div>
                    <div class="text-right">
                      <span class="text-xs font-semibold inline-block text-blue-600">
                        {{ progressPercentage }}%
                      </span>
                    </div>
                  </div>
                  <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-blue-200">
                    <div :style="{ width: progressPercentage + '%' }" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-blue-500"></div>
                  </div>
                </div>
              </div>
              <!-- Buttons -->
              <div class="flex justify-between items-center mb-4">
                <button @click="enterRaffle" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                  Enter Raffle
                </button>
                <button v-if="!hasRaffleEntries" @click="buyOut" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                  Buy Out
                </button>
              </div>
              <div class="flex justify-between items-center">
                <Link href="/listings" class="inline-block bg-gradient-to-r from-orange-500 to-yellow-500 hover:from-orange-600 hover:to-yellow-600 text-white font-bold py-2 px-4 rounded">
                  Back to Listings
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
import { ref, computed } from 'vue'; // Import computed from Vue
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';

const props = defineProps({
  listing: Object,
});

const progressPercentage = computed(() => {
  const soldTickets = props.listing.sold_tickets || 0;
  const totalTickets = props.listing.amount_of_tickets || 1;
  return ((soldTickets / totalTickets) * 100).toFixed(2);
});

const hasRaffleEntries = computed(() => {
  return props.listing.sold_tickets > 0;
});

const enterRaffle = () => {
  Inertia.post(route('listings.raffle-entry.store', props.listing.id), {
    onSuccess: () => {
      // Handle success, e.g., show a notification
    },
    onError: (errors) => {
      // Handle errors, e.g., show a notification
    }
  });
};

const buyOut = () => {
  Inertia.post(route('listings.buy-out', props.listing.id), {
    onSuccess: () => {
      // Handle success, e.g., show a notification
    },
    onError: (errors) => {
      // Handle errors, e.g., show a notification
    }
  });
};
</script>

<style scoped>
/* Add any additional styles here */
</style>
