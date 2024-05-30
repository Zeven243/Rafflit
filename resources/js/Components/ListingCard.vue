<template>
  <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 ease-in-out">
    <img :src="`/storage/${listing.image_path}`" alt="Listing Image" class="w-full h-48 object-cover">
    <div class="p-4">
      <h5 class="text-lg font-semibold text-gray-800">{{ listing.name }}</h5>
      <p class="text-gray-600 mt-2">{{ listing.description }}</p>
      <p class="text-gray-600 mt-2">Sold by: {{ listing.company }}</p>
      <div class="mt-4 flex justify-between items-center">
        <span class="text-gray-900 font-semibold">Price: R{{ pricePerTicket(listing.full_price, listing.amount_of_tickets) }}</span>
      </div>
      <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700 mt-4">
        <div class="bg-blue-600 h-2.5 rounded-full" :style="{ width: progressPercentage(listing.tickets_sold, listing.amount_of_tickets) + '%' }"></div>
      </div>
      <div class="mt-4 flex justify-between items-center">
        <template v-if="$page.props.auth.user">
          <button
            v-if="listing.tickets_sold < listing.amount_of_tickets"
            @click="enterRaffle(listing.id)"
            class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-300 ease-in-out"
          >
            Enter Raffle
          </button>
          <button
            v-else
            disabled
            class="bg-gray-400 text-white font-bold py-2 px-4 rounded-lg"
          >
            Winner Selection in progress
          </button>
          <button
            v-if="listing.tickets_sold === 0"
            @click="buyOut(listing.id)"
            class="bg-gradient-to-r from-green-500 to-teal-600 hover:from-green-600 hover:to-teal-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-300 ease-in-out"
          >
            Buy Out
          </button>
        </template>
        <template v-else>
          <p class="text-gray-600">Please register to purchase item raffle tickets</p>
        </template>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Inertia } from '@inertiajs/inertia';

const props = defineProps({
  listing: Object,
});

const pricePerTicket = (price, amount_of_tickets) => {
  return amount_of_tickets > 0 ? ` ${(price / amount_of_tickets).toFixed(2)}` : 'R 0.00';
};

const enterRaffle = (listingId) => {
  Inertia.post(`/listings/${listingId}/raffle-entry`, {}, {
    onSuccess: () => {
      // Optionally, refresh the page or update the state to show the new raffle entry
    }
  });
};

const buyOut = () => {
  Inertia.post(route('listings.buy-out', props.listing.id), {
    onSuccess: (response) => {
      // Update the listing data with the response
      props.listing = response.props.listing;
      // Handle success, e.g., show a notification
    },
    onError: (errors) => {
      // Handle errors, e.g., show a notification
    }
  });
};

const progressPercentage = (ticketsSold, amount_of_tickets) => {
  return (ticketsSold / amount_of_tickets) * 100;
};
</script>

<style scoped>
/* Additional styles can be added here if needed */
</style>
