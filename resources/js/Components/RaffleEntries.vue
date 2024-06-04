<template>
  <div class="mt-6 px-4 sm:px-6 lg:px-8">
    <h3 class="text-2xl font-bold text-gray-800 mb-6">Your Raffle Entries</h3>
    <div v-if="showNoEntriesMessage" class="bg-yellow-200 text-yellow-800 p-4 rounded-md shadow">
      Get started by entering a raffle and increase your chances to win!
    </div>
    <div v-for="(group, groupId) in groupedEntries" :key="groupId" class="bg-white rounded-lg p-4 shadow-md hover:shadow-lg transition-shadow duration-300 mb-4">
      <div class="flex flex-col sm:flex-row sm:items-center">
        <!-- Product image -->
        <img :src="`/storage/${group.listing.cover_image_path}`" alt="Product Image" class="w-16 h-16 object-cover rounded mr-4 mb-4 sm:mb-0">
        <div class="flex-1">
          <div class="font-medium text-gray-800">Listing: {{ group.listing.name }}</div>
          <div class="text-sm text-gray-500">Description: {{ group.listing.description }}</div>
          <!-- Progress bar -->
          <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700 mt-2">
            <div class="bg-blue-600 h-2.5 rounded-full" :style="{ width: progressPercentage(group.listing.tickets_sold, group.listing.amount_of_tickets) + '%' }"></div>
          </div>
          <div class="mt-2 text-sm">
            <span>{{ group.listing.tickets_sold }}/{{ group.listing.amount_of_tickets }} tickets sold</span>
          </div>
        </div>
      </div>
      <!-- Winner Selection in progress or Winner notification -->
      <div class="mt-4">
        <transition name="winner-animation" mode="out-in">
          <div v-if="group.isFull && group.isWinner" key="winner" class="bg-green-500 to-green-700 text-white font-bold py-2 px-4 rounded text-center">
            Congratulations, You won!
          </div>
          <div v-else-if="group.isFull && !group.isWinner" key="loser" class="bg-orange-500 to-orange-700 text-white font-bold py-2 px-4 rounded text-center">
            Better luck next time!
          </div>
          <div v-else-if="group.isFull" key="winner-name" class="bg-green-500 to-green-700 text-white font-bold py-2 px-4 rounded text-center">
            Winner: {{ getWinnerName(group.listing.id) }}
          </div>
        </transition>
        <button v-if="!group.isFull" @click="toggleGroup(groupId)" class="w-full bg-blue-500 text-white font-bold py-2 px-4 rounded mt-2">
          View Tickets ({{ group.tickets.length }})
        </button>
      </div>
      <!-- Dropdown for ticket numbers -->
      <div v-if="openGroup === groupId" class="mt-4">
        <div v-for="ticketId in group.tickets" :key="ticketId" class="text-gray-800">
          Ticket #: {{ ticketId }}
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const props = defineProps({
  raffleEntries: {
    type: Array,
    default: () => [],
  },
  wonListings: {
    type: Array,
    default: () => [],
  },
});

// Get the authenticated user's ID from the page props
const { auth } = usePage().props;

// Group raffle entries by listing
const groupedEntries = computed(() => {
  const groups = {};
  props.raffleEntries.forEach((entry) => {
    if (entry.listing) {
      if (!groups[entry.listing.id]) {
        groups[entry.listing.id] = {
          listing: entry.listing,
          tickets: [],
          isFull: isRaffleFull(entry.listing.tickets_sold, entry.listing.amount_of_tickets),
          isWinner: entry.listing.winner_user_id === auth.user.id,
        };
      }
      groups[entry.listing.id].tickets.push(entry.id);
    }
  });
  return groups;
});

const showNoEntriesMessage = computed(() => props.raffleEntries.length === 0);

// Function to calculate the progress percentage
const progressPercentage = (ticketsSold, amount_of_tickets) => {
  return (ticketsSold / amount_of_tickets) * 100;
};

// Function to determine if the raffle is full
const isRaffleFull = (ticketsSold, amount_of_tickets) => {
  return ticketsSold >= amount_of_tickets;
};

// Ref to track which group is open
const openGroup = ref(null);

// Function to toggle the open group
const toggleGroup = (groupId) => {
  openGroup.value = openGroup.value === groupId ? null : groupId;
};

// Function to get the winner's name from the activity log
const getWinnerName = (listingId) => {
  const activity = props.raffleEntries
    .find((entry) => entry.listing.id === listingId)
    .listing.activity.find((log) => log.description === 'selected winner');

  return activity ? activity.properties.winner_name : '';
};
</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.5s;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
.bg-green-500 {
  background-image: linear-gradient(to right, #22c55e, #16a34a);
}
.bg-orange-500 {
  background-image: linear-gradient(to right, #f97316, #ea580c);
}
.winner-animation-enter-active, .winner-animation-leave-active {
  transition: opacity 0.5s, transform 0.5s;
}
.winner-animation-enter-from, .winner-animation-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}
.winner-animation-enter-to, .winner-animation-leave-from {
  opacity: 1;
  transform: translateY(0);
}
</style>