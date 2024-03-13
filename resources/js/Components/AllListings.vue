<script setup>
import { ref, computed } from 'vue';
import { Inertia } from '@inertiajs/inertia';

const props = defineProps({
  allListings: {
    type: Array,
    default: () => [],
  },
});

const searchQuery = ref('');

const filteredListings = computed(() => {
  return props.allListings.filter((listing) => {
    const matchesSearch = listing.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      listing.description.toLowerCase().includes(searchQuery.value.toLowerCase());
    return listing.is_active && matchesSearch;
  });
});

const pricePerTicket = (price, amount_of_tickets) => {
  return amount_of_tickets > 0 ? `R ${(price / amount_of_tickets).toFixed(2)}` : 'R 0.00';
};

const enterRaffle = (listingId) => {
  Inertia.post(`/listings/${listingId}/raffle-entry`, {}, {
    onSuccess: () => {
      // Optionally, refresh the page or update the state to show the new raffle entry
    }
  });
};

const progressPercentage = (ticketsSold, amount_of_tickets) => {
  return (ticketsSold / amount_of_tickets) * 100;
};
</script>

<template>
  <div class="container mx-auto mt-8 px-4">
    <h3 class="text-3xl font-bold text-gray-900 mb-6">Available Raffles</h3>
    <div class="mb-6">
      <input
        type="text"
        v-model="searchQuery"
        placeholder="Search listings..."
        class="form-input w-full rounded-lg border-gray-300 shadow-sm p-4"
      />
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
      <div v-for="listing in filteredListings" :key="listing.id" class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 ease-in-out">
        <img :src="listing.image" alt="Listing Image" class="w-full h-48 object-cover">
        <div class="p-6">
          <h5 class="text-2xl font-semibold text-gray-800">{{ listing.name }}</h5>
          <p class="text-gray-600 mt-2">{{ listing.description }}</p>
          <div class="mt-4">
            <span class="text-gray-900 font-semibold">Price: {{ pricePerTicket(listing.price, listing.amount_of_tickets) }}</span>
          </div>
          <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700 mt-4">
            <div class="bg-blue-600 h-2.5 rounded-full" :style="{ width: progressPercentage(listing.tickets_sold, listing.amount_of_tickets) + '%' }"></div>
          </div>
          <button
            v-if="listing.tickets_sold < listing.amount_of_tickets"
            @click="enterRaffle(listing.id)"
            class="mt-4 w-full bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-300 ease-in-out"
          >
            Enter Raffle
          </button>
          <button
            v-else
            disabled
            class="mt-4 w-full bg-gray-400 text-white font-bold py-2 px-4 rounded-lg"
          >
            Winner Selection in progress
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Tailwind CSS provides utility classes, so additional styles might not be necessary. */
</style>