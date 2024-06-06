<template>
  <AuthenticatedLayout>
    <Head title="Item Management" />
    <div class="min-h-screen bg-white py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <div class="flex justify-between items-center mb-8">
              <h1 class="text-2xl font-bold">Item Management</h1>
              <div class="flex items-center">
                <span class="text-gray-600 mr-2">Pending Items:</span>
                <span class="bg-red-500 text-white font-bold py-1 px-2 rounded">{{ pendingItemsCount }}</span>
                <span class="text-gray-600 mr-2 ml-4">Active Items:</span>
                <span class="bg-green-500 text-white font-bold py-1 px-2 rounded">{{ activeItemsCount }}</span>
                <span class="text-gray-600 mr-2 ml-4">Sold Out Items:</span>
                <span class="bg-blue-500 text-white font-bold py-1 px-2 rounded">{{ soldOutItemsCount }}</span>
              </div>
            </div>
            <!-- Search and Filter Form -->
            <form @submit.prevent="handleSearch" class="mb-8">
              <div class="flex items-center mb-4">
                <input type="text" id="searchQuery" v-model="searchQuery" placeholder="Search items by SKU..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
                <button type="submit" class="ml-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                  Search
                </button>
              </div>
              <div class="flex items-center">
                <label class="mr-4">
                  <input type="checkbox" v-model="showPendingItems" class="mr-1" />
                  Show Pending Items
                </label>
                <label class="mr-4">
                  <input type="checkbox" v-model="showActiveItems" class="mr-1" />
                  Show Active Items
                </label>
                <label>
                  <input type="checkbox" v-model="showSoldOutItems" class="mr-1" />
                  Show Sold Out Items
                </label>
              </div>
            </form>
            <!-- Listings -->
            <div class="overflow-x-auto">
              <table class="w-full whitespace-no-wrap">
                <thead>
                  <tr class="text-left font-bold">
                    <th class="px-6 py-3 bg-gray-100">SKU</th>
                    <th class="px-6 py-3 bg-gray-100">Name</th>
                    <th class="px-6 py-3 bg-gray-100">Category</th>
                    <th class="px-6 py-3 bg-gray-100">Full Price</th>
                    <th class="px-6 py-3 bg-gray-100">Tickets</th>
                    <th class="px-6 py-3 bg-gray-100">Ticket Price</th>
                    <th class="px-6 py-3 bg-gray-100">Company</th>
                    <th class="px-6 py-3 bg-gray-100">Status</th>
                    <th class="px-6 py-3 bg-gray-100">Shipping Status</th>
                    <th class="px-6 py-3 bg-gray-100">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="listing in paginatedListings" :key="listing.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="px-6 py-4 border-t">{{ listing.SKU }}</td>
                    <td class="px-6 py-4 border-t">{{ listing.name }}</td>
                    <td class="px-6 py-4 border-t">{{ getCategoryName(listing.category_id) }}</td>
                    <td class="px-6 py-4 border-t">{{ listing.full_price }}</td>
                    <td class="px-6 py-4 border-t">{{ listing.amount_of_tickets }}</td>
                    <td class="px-6 py-4 border-t">{{ listing.ticket_price }}</td>
                    <td class="px-6 py-4 border-t">{{ listing.company }}</td>
                    <td class="px-6 py-4 border-t">
                      <span :class="['px-2 inline-flex text-xs leading-5 font-semibold rounded-full', listing.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800']">
                        {{ listing.is_active ? 'Active' : 'Inactive' }}
                      </span>
                    </td>
                    <td class="px-6 py-4 border-t">
                      <select v-model="listing.shipping_status" @change="updateShippingStatus(listing)">
                        <option value="pending">To be shipped</option>
                        <option value="shipped">Out for delivery</option>
                        <option value="delivered">Delivery completed</option>
                      </select>
                    </td>
                    <td class="px-6 py-4 border-t">
                      <Link :href="`/listings/${listing.id}/edit`" class="text-indigo-600 hover:text-indigo-900 mr-4">
                        Edit
                      </Link>
                      <button @click="toggleActivation(listing.id)" class="text-indigo-600 hover:text-indigo-900 mr-4">
                        {{ listing.is_active ? 'Deactivate' : 'Activate' }}
                      </button>
                      <button @click="deleteListing(listing.id)" class="text-red-600 hover:text-red-900">
                        Delete
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <!-- Pagination -->
            <div class="mt-8">
              <nav class="flex justify-between">
                <button @click="previousPage" :disabled="currentPage === 1" class="px-4 py-2 font-bold text-gray-500 bg-white rounded-md hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed">
                  Previous
                </button>
                <button @click="nextPage" :disabled="currentPage === totalPages" class="px-4 py-2 font-bold text-gray-500 bg-white rounded-md hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed">
                  Next
                </button>
              </nav>
            </div>
            <!-- Carousel Image Upload Modal -->
            <div class="mt-8">
              <button @click="openModal" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Upload Carousel Images
              </button>
            </div>
            <div v-if="showModal" class="fixed z-10 inset-0 overflow-y-auto">
              <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                  <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                  <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-headline">
                      Upload Carousel Images
                    </h3>
                    <div class="mt-4">
                      <input type="file" @change="handleFileUpload" multiple accept="image/*" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
                    </div>
                    <div class="mt-4">
                      <h4 class="text-md leading-6 font-medium text-gray-900">Current Images</h4>
                      <div class="grid grid-cols-3 gap-4">
                        <div v-for="image in carouselImages" :key="image.id" class="relative">
                          <img :src="getImageUrl(image.image_path)" alt="Carousel Image" class="w-full h-32 object-cover rounded-md">
                          <button @click="deleteImage(image.id)" class="absolute top-0 right-0 mt-2 mr-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center">
                            &times;
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button @click="uploadImages" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-500 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                      Upload
                    </button>
                    <button @click="closeModal" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                      Cancel
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import axios from 'axios';

const listings = ref([]);
const categories = ref([]);
const searchQuery = ref('');
const currentPage = ref(1);
const itemsPerPage = 10;
const showPendingItems = ref(true);
const showActiveItems = ref(true);
const showSoldOutItems = ref(true);
const showModal = ref(false);
const form = ref({
  images: [],
});
const carouselImages = ref([]);

const pendingItemsCount = computed(() => {
  return listings.value.filter(listing => !listing.is_active).length;
});

const activeItemsCount = computed(() => {
  return listings.value.filter(listing => listing.is_active).length;
});

const soldOutItemsCount = computed(() => {
  return listings.value.filter(listing => listing.tickets_sold === listing.amount_of_tickets).length;
});

const paginatedListings = computed(() => {
  let filteredListings = listings.value.filter(listing => {
    const matchesSearch = listing.SKU.toLowerCase().includes(searchQuery.value.toLowerCase());
    const matchesPendingFilter = showPendingItems.value ? listing.is_active === false : true;
    const matchesActiveFilter = showActiveItems.value ? listing.is_active === true : true;
    const matchesSoldOutFilter = showSoldOutItems.value ? listing.tickets_sold === listing.amount_of_tickets : true;
    return matchesSearch && matchesPendingFilter && matchesActiveFilter && matchesSoldOutFilter;
  });

  const startIndex = (currentPage.value - 1) * itemsPerPage;
  const endIndex = startIndex + itemsPerPage;
  return filteredListings.slice(startIndex, endIndex);
});

const totalPages = computed(() => {
  let filteredListings = listings.value.filter(listing => {
    const matchesSearch = listing.SKU.toLowerCase().includes(searchQuery.value.toLowerCase());
    const matchesPendingFilter = showPendingItems.value ? listing.is_active === false : true;
    const matchesActiveFilter = showActiveItems.value ? listing.is_active === true : true;
    const matchesSoldOutFilter = showSoldOutItems.value ? listing.tickets_sold === listing.amount_of_tickets : true;
    return matchesSearch && matchesPendingFilter && matchesActiveFilter && matchesSoldOutFilter;
  });

  return Math.ceil(filteredListings.length / itemsPerPage);
});

const getCategoryName = (categoryId) => {
  const category = categories.value.find(category => category.id === categoryId);
  return category ? category.name : '';
};

const fetchListings = async () => {
  try {
    const response = await axios.get(route('item-management.index'));
    listings.value = response.data.listings || [];
    categories.value = response.data.categories || [];
  } catch (error) {
    console.error('Error fetching listings:', error);
  }
};

const handleSearch = () => {
  currentPage.value = 1;
};

const toggleActivation = async (id) => {
  try {
    const listing = listings.value.find(listing => listing.id === id);
    if (listing) {
      listing.is_active = !listing.is_active;
      await axios.put(`/item-management/${id}/update-status`, { is_active: listing.is_active });
    }
  } catch (error) {
    console.error('Error updating listing status:', error);
  }
};

const deleteListing = async (id) => {
  if (confirm('Are you sure you want to delete this listing?')) {
    try {
      await axios.delete(`/listings/${id}`);
      listings.value = listings.value.filter(listing => listing.id !== id);
    } catch (error) {
      console.error('Error deleting listing:', error);
    }
  }
};

const updateShippingStatus = async (listing) => {
  try {
    await axios.put(`/item-management/${listing.id}/update-shipping-status`, { shipping_status: listing.shipping_status });
if (listing.shipping_status === 'delivered') {
listing.is_active = false;
}
} catch (error) {
console.error('Error updating shipping status:', error);
}
};
const previousPage = () => {
if (currentPage.value > 1) {
currentPage.value--;
}
};
const nextPage = () => {
if (currentPage.value < totalPages.value) {
currentPage.value++;
}
};
const openModal = () => {
showModal.value = true;
};
const closeModal = () => {
showModal.value = false;
};
const handleFileUpload = (event) => {
form.value.images = Array.from(event.target.files);
};
const uploadImages = async () => {
try {
const formData = new FormData();
form.value.images.forEach(image => {
formData.append('images[]', image);
});
await axios.post('/carousel-images', formData);
form.value.images = [];
closeModal();
fetchCarouselImages();
} catch (error) {
console.error('Error uploading carousel images:', error);
}
};
const fetchCarouselImages = async () => {
try {
const response = await axios.get('/carousel-images');
carouselImages.value = response.data;
} catch (error) {
console.error('Error fetching carousel images:', error);
}
};
const deleteImage = async (id) => {
  try {
    await axios.delete(`/carousel-images/${id}`);
    carouselImages.value = carouselImages.value.filter(image => image.id !== id);
  } catch (error) {
    console.error('Error deleting carousel image:', error);
  }
};
const getImageUrl = (path) => {
  return `/storage/${path}`;
};

onMounted(() => {
  fetchListings();
  fetchCarouselImages();
});
</script>
