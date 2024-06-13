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
              <!-- Image Gallery -->
              <div class="flex flex-col items-center">
                <img :src="mainImage" alt="Main Image" class="object-cover w-full h-full mb-4">
                <div class="grid grid-cols-3 gap-2">
                  <img v-for="(image, index) in allImages" :key="index" :src="`/storage/${image}`" alt="Additional Image" class="object-cover w-full h-24 cursor-pointer" @click="setMainImage(image)">
                </div>
              </div>
            </div>
            <div class="p-6 md:w-1/2">
              <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ listing.name }}</h1>
              <p class="text-gray-600 mb-4">{{ listing.description }}</p>
              <p class="text-gray-600 mb-4">Sold by: {{ listing.company }}</p>
              <p class="text-gray-600 mt-2 mb-4"> <b>Item Condition :</b> {{ listing.item_condition }}</p>
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
              <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700 mt-4 relative">
                <div class="bg-blue-500 h-2.5 rounded-l-full absolute" :style="{ width: `${soldTickets * 100 / listing.amount_of_tickets}%` }"></div>
                <div class="bg-yellow-500 h-2.5 rounded-r-full absolute" :style="{ width: `${potentialTickets * 100 / listing.amount_of_tickets}%`, left: `${soldTickets * 100 / listing.amount_of_tickets}%` }"></div>
              </div>
              <div v-if="!listing.is_active" class="mt-4">
                <span class="bg-yellow-100 text-yellow-800 text-sm font-medium px-2.5 py-0.5 rounded">
                  Pending Approval
                </span>
              </div>
              <div class="mt-4 flex justify-between items-center">
                <template v-if="$page.props.auth.user && listing.is_active">
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
              <!-- Buttons -->
              <div class="flex justify-between items-center">
                <Link href="/dashboard" class="inline-block bg-gradient-to-r from-orange-500 to-yellow-500 hover:from-orange-600 hover:to-yellow-600 text-white font-bold py-2 px-4 rounded">
                  Back to Dashboard
                </Link>
              </div>
            </div>
          </div>
        </div>
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
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';

const props = defineProps({
  listing: Object,
});

const showRaffleModal = ref(false);
const ticketQuantity = ref(1);
const mainImage = ref(`/storage/${props.listing.cover_image_path}`);
const additionalImages = ref(JSON.parse(props.listing.image_paths || '[]'));

const allImages = computed(() => {
  return [props.listing.cover_image_path, ...additionalImages.value];
});

const progressPercentage = computed(() => {
  const soldTickets = props.listing.tickets_sold || 0;
  const totalTickets = props.listing.amount_of_tickets || 1;
  return ((soldTickets / totalTickets) * 100).toFixed(2);
});

const allTicketsSold = computed(() => {
  return props.listing.tickets_sold >= props.listing.amount_of_tickets;
});

const hasRaffleEntries = computed(() => {
  return props.listing.tickets_sold > 0;
});

const maxTickets = computed(() => {
  return props.listing.amount_of_tickets - props.listing.tickets_sold;
});

const enterRaffle = () => {
  Inertia.post(route('listings.raffle-entry.store', props.listing.id), {
    onSuccess: () => {
      // Reload the page to fetch the updated listing data
      Inertia.reload();
    },
    onError: (errors) => {
      // Handle errors, e.g., show a notification
    }
  });
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
    onSuccess: () => {
      // Reload the page to fetch the updated listing data
      Inertia.reload();
    },
    onError: (errors) => {
      // Handle errors, e.g., show a notification
    }
  });
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

const setMainImage = (image) => {
  mainImage.value = `/storage/${image}`;
};

const soldTickets = computed(() => {
  return props.listing.tickets_sold;
});

const potentialTickets = computed(() => {
  return props.listing.potential_tickets;
});
</script>

<style scoped>
/* Add any additional styles here */
</style>