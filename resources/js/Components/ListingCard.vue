<template>
  <div v-if="listing" class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 ease-in-out">
    <img v-if="listing.cover_image_path" :src="`/storage/${listing.cover_image_path}`" alt="Listing Cover Image" class="w-full h-48 object-cover" @click="goToShowPage">
    <div class="p-4">
      <h5 class="text-lg font-semibold text-gray-800">{{ listing.name }}</h5>
      <p class="text-gray-600 mt-2">{{ listing.description }}</p>
      <p class="text-gray-600 mt-2">Sold by: {{ listing.company }}</p>
      <div class="mt-4 flex justify-between items-center">
        <span class="text-gray-900 font-semibold">Price: R{{ pricePerTicket(listing.full_price, listing.amount_of_tickets) }}</span>
      </div>
      <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700 mt-4 relative">
        <div class="bg-blue-500 h-2.5 rounded-l-full absolute" :style="{ width: `${soldTickets * 100 / listing.amount_of_tickets}%` }"></div>
        <div class="bg-yellow-500 h-2.5 rounded-r-full absolute" :style="{ width: `${potentialTickets * 100 / listing.amount_of_tickets}%`, left: `${soldTickets * 100 / listing.amount_of_tickets}%` }"></div>
      </div>
      <div class="mt-4 flex justify-between items-center">
        <template v-if="$page.props.auth.user">
          <button
            v-if="listing.tickets_sold < listing.amount_of_tickets"
            @click.stop="showRaffleModal = true"
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
            v-if="listing.tickets_sold === 0 && !listing.exists_in_cart"
            @click.stop="buyOut(listing.id)"
            class="bg-gradient-to-r from-green-500 to-teal-600 hover:from-green-600 hover:to-teal-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-300 ease-in-out"
          >
            Buy Out
          </button>
        </template>
        <template v-else>
          <p class="text-gray-600">Please register to purchase item raffle tickets</p>
        </template>
      </div>
      <div class="mt-4">
        <p class="text-gray-600">Potential Tickets: {{ listing.potential_tickets }}</p>
      </div>
    </div>

    <!-- Raffle Modal -->
    <div v-if="showRaffleModal" class="fixed inset-0 flex items-center justify-center z-50" @click.stop>
      <div class="bg-white rounded-lg shadow-lg p-6" @click.stop>
        <h3 class="text-lg font-medium mb-4">Enter Raffle</h3>
        <div>
          <label for="ticket-quantity" class="block text-sm font-medium text-gray-700">Number of Tickets</label>
          <div class="flex items-center mt-1">
            <button @click="decrementQuantity" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-l">-</button>
            <input type="number" id="ticket-quantity" v-model="ticketQuantity" :max="maxTickets - listing.potential_tickets" min="1" class="w-16 text-center border-t border-b border-gray-300 shadow-sm" @click.stop>
            <button @click="incrementQuantity" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-r">+</button>
          </div>
          <p class="text-gray-600 mt-2">Available Tickets: {{ maxTickets - listing.potential_tickets }}</p>
          <p class="text-gray-600 mt-2">Potential Tickets: {{ listing.potential_tickets }}</p>
        </div>
        <div class="mt-4 flex justify-end">
          <button type="button" @click="showRaffleModal = false" class="mr-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
            Cancel
          </button>
          <button type="button" @click="addToCart" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Add to Cart
          </button>
        </div>
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

const showRaffleModal = ref(false);
const ticketQuantity = ref(1);

const maxTickets = computed(() => {
  return props.listing.amount_of_tickets - props.listing.tickets_sold;
});

const pricePerTicket = (price, amount_of_tickets) => {
  return amount_of_tickets > 0 ? ` ${(price / amount_of_tickets).toFixed(2)}` : 'R 0.00';
};

const addToCart = () => {
  const totalPotentialTickets = props.listing.potential_tickets + ticketQuantity.value;
  if (totalPotentialTickets > maxTickets.value) {
    alert(`You can only buy up to ${maxTickets.value - props.listing.potential_tickets} tickets.`);
    return;
  }

  Inertia.post(route('cart.store'), {
    listing_id: props.listing.id,
    quantity: ticketQuantity.value,
  }, {
    onSuccess: () => {
      showRaffleModal.value = false;
    },
    onError: (errors) => {
      console.error('Error adding to cart:', errors);
    }
  });
};

const buyOut = () => {
  Inertia.post(route('listings.buy-out', props.listing.id), {
    onSuccess: (response) => {
      props.listing = response.props.listing;
    },
    onError: (errors) => {
      console.error('Error buying out:', errors);
    }
  });
};

const soldTickets = computed(() => {
  return props.listing.tickets_sold;
});

const potentialTickets = computed(() => {
  return props.listing.potential_tickets;
});

const goToShowPage = () => {
  if (!showRaffleModal.value) {
    Inertia.visit(route('listings.show', props.listing.id));
  }
};

const incrementQuantity = () => {
  if (ticketQuantity.value < maxTickets.value - props.listing.potential_tickets) {
    ticketQuantity.value++;
  }
};

const decrementQuantity = () => {
  if (ticketQuantity.value > 1) {
    ticketQuantity.value--;
  }
};
</script>

<style scoped>
/* Additional styles can be added here if needed */
</style>