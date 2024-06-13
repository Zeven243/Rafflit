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
                <div class="relative inline-block text-left mr-4">
                  <div>
                    <button type="button"
                      class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                      id="options-menu" aria-haspopup="true" aria-expanded="true" @click="toggleFilterDropdown">
                      <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10 12a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                        <path fill-rule="evenodd" d="M2 10a8 8 0 1116 0 8 8 0 01-16 0zm8-6a6 6 0 100 12 6 6 0 000-12z"
                          clip-rule="evenodd" />
                      </svg>
                    </button>
                  </div>
                </div>
                <input type="text" id="searchQuery" v-model="searchQuery" placeholder="Search items..."
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
                <button type="submit" class="ml-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                  Search
                </button>
              </div>
              <div v-if="showFilterDropdown" class="mb-4">
                <div class="grid grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700">Category</label>
                    <select v-model="filterFields.category"
                      class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                      <option value="">All</option>
                      <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}
                      </option>
                    </select>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700">Company</label>
                    <input type="text" v-model="filterFields.company"
                      class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700">Price Range</label>
                    <div class="mt-1 flex">
                      <input type="number" v-model.number="filterFields.minPrice" placeholder="Min"
                        class="w-1/2 py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                      <span class="mx-2 text-gray-500">-</span>
                      <input type="number" v-model.number="filterFields.maxPrice" placeholder="Max"
                        class="w-1/2 py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                    </div>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <select v-model="filterFields.status"
                      class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                      <option value="">All</option>
                      <option value="active">Active Items</option>
                      <option value="pending">Pending Items</option>
                      <option value="sold_out">Sold Out Items</option>
                    </select>
                  </div>
                </div>
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
                  <tr v-for="listing in paginatedListings" :key="listing.id"
                    class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="px-6 py-4 border-t">{{ listing.SKU }}</td>
                    <td class="px-6 py-4 border-t">{{ listing.name }}</td>
                    <td class="px-6 py-4 border-t">{{ listing.category ? listing.category.name : '' }}</td>
                    <td class="px-6 py-4 border-t">{{ listing.full_price }}</td>
                    <td class="px-6 py-4 border-t">{{ listing.amount_of_tickets }}</td>
                    <td class="px-6 py-4 border-t">{{ listing.ticket_price }}</td>
                    <td class="px-6 py-4 border-t">{{ listing.company }}</td>
                    <td class="px-6 py-4 border-t">
                      <span :class="[
                        'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                        listing.amount_of_tickets === listing.tickets_sold
                          ? 'bg-green-100 text-green-800'
                          : listing.is_active
                            ? 'bg-green-100 text-green-800'
                            : 'bg-red-100 text-red-800'
                      ]">
                        {{ listing.amount_of_tickets === listing.tickets_sold ? 'Completed' : listing.is_active ?
                        'Active' : 'Inactive' }}
                      </span>
                    </td>
                    <td>{{ listing.shipping_status }}</td>
                    <td class="px-6 py-4 border-t">
                      <Link :href="`/listings/${listing.id}/edit`" class="text-indigo-600 hover:text-indigo-900 mr-4">
                      Edit
                      </Link>
                      <button @click="toggleActivation(listing)" class="text-indigo-600 hover:text-indigo-900 mr-4">
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
                <button @click="previousPage" :disabled="currentPage === 1"
                  class="px-4 py-2 font-bold text-gray-500 bg-white rounded-md hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed">
                  Previous
                </button>
                <button @click="nextPage" :disabled="currentPage === totalPages"
                  class="px-4 py-2 font-bold text-gray-500 bg-white rounded-md hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed">
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
                <div
                  class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                  role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                  <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-headline">
                      Upload Carousel Images
                    </h3>
                    <div class="mt-4">
                      <input type="file" @change="handleFileUpload" multiple accept="image/*"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
                    </div>
                    <div class="mt-4">
                      <h4 class="text-md leading-6 font-medium text-gray-900">Current Images</h4>
                      <div class="grid grid-cols-3 gap-4">
                        <div v-for="image in carouselImages" :key="image.id" class="relative">
                          <img :src="getImageUrl(image.image_path)" alt="Carousel Image"
                            class="w-full h-32 object-cover rounded-md">
                          <button @click="deleteImage(image.id)"
                            class="absolute top-0 right-0 mt-2 mr-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center">
                            &times;
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button @click="uploadImages" type="button"
                      class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-500 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                      Upload
                    </button>
                    <button @click="closeModal" type="button"
                      class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                      Cancel
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Advertisement Upload Modal -->
            <div class="mt-8">
              <button @click="openAdvertisementModal"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Upload Advertisement
              </button>
            </div>
            <div v-if="showAdvertisementModal" class="fixed z-10 inset-0 overflow-y-auto">
              <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Modal Background -->
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                  <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                <!-- Modal Content -->
                <div
                  class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                  <form @submit.prevent="uploadAdvertisement">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                      <div>
                        <label for="advertisement-image" class="block text-sm font-medium text-gray-700">Advertisement
                          Image</label>
                        <input type="file" id="advertisement-image" ref="advertisementImage" accept="image/*"
                          class="mt-1 block w-full" required>
                      </div>
                      <div class="mt-4">
                        <label for="advertisement-url" class="block text-sm font-medium text-gray-700">URL Link</label>
                        <input type="url" id="advertisement-url" v-model="advertisementUrl" class="mt-1 block w-full"
                          required>
                      </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                      <button type="submit"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Upload
                      </button>
                      <button type="button" @click="closeAdvertisementModal"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                      </button>
                    </div>
                  </form>

                  <!-- Display uploaded advertisements -->
                  <div class="mt-4">
                    <h4 class="text-lg font-bold mb-2">Uploaded Advertisements</h4>
                    <div v-for="advertisement in advertisements" :key="advertisement.id" class="flex items-center mb-4">
                      <img :src="getAdvertisementImageUrl(advertisement.image_path)" alt="Advertisement"
                        class="w-20 h-20 object-cover rounded mr-4">
                      <div>
                        <a :href="advertisement.url" target="_blank" class="text-blue-500 hover:underline">{{
                          advertisement.url }}</a>
                        <button @click="deleteAdvertisement(advertisement.id)"
                          class="text-red-500 hover:text-red-700 ml-4">Delete</button>
                        <button @click="openReplaceAdvertisementModal(advertisement)"
                          class="text-blue-500 hover:text-blue-700 ml-4">Replace</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Replace Advertisement Modal -->
            <div v-if="showReplaceAdvertisementModal" class="fixed z-10 inset-0 overflow-y-auto">
              <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Modal Background -->
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                  <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                <!-- Modal Content -->
                <div
                  class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                  <form @submit.prevent="replaceAdvertisement">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                      <div>
                        <label for="replace-advertisement-image" class="block text-sm font-medium text-gray-700">Replace
                          Advertisement Image</label>
                        <input type="file" id="replace-advertisement-image" ref="replaceAdvertisementImage"
                          accept="image/*" class="mt-1 block w-full" required>
                      </div>
                      <div class="mt-4">
                        <label for="replace-advertisement-url" class="block text-sm font-medium text-gray-700">Replace
                          URL Link</label>
                        <input type="url" id="replace-advertisement-url" v-model="replaceAdvertisementUrl"
                          class="mt-1 block w-full" required>
                      </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                      <button type="submit"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Replace
                      </button>
                      <button type="button" @click="closeReplaceAdvertisementModal"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                      </button>
                    </div>
                  </form>
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
import { Head, Link, router } from '@inertiajs/vue3';
import axios from 'axios';

const props = defineProps({
  listings: Array,
  categories: Array,
  advertisements: Array,
});

const listings = ref(props.listings);
const categories = ref(props.categories);
const advertisements = ref(props.advertisements);
const searchQuery = ref('');
const currentPage = ref(1);
const itemsPerPage = 10;
const showModal = ref(false);
const form = ref({
  images: [],
});
const carouselImages = ref([]);
const showFilterDropdown = ref(false);
const filterFields = ref({
  name: false,
  description: false,
  category: '',
  company: '',
  sku: false,
  minPrice: null,
  maxPrice: null,
  status: '',
});

const pendingItemsCount = computed(() => {
  return listings.value.filter(listing => 
    !listing.is_active && listing.amount_of_tickets !== listing.tickets_sold
  ).length;
});


const activeItemsCount = computed(() => {
  return listings.value.filter(listing => listing.is_active).length;
});

const soldOutItemsCount = computed(() => {
  return listings.value.filter(listing => listing.tickets_sold === listing.amount_of_tickets).length;
});

const paginatedListings = computed(() => {
  const startIndex = (currentPage.value - 1) * itemsPerPage;
  const endIndex = startIndex + itemsPerPage;

  let filteredListings = listings.value;

  if (searchQuery.value) {
    filteredListings = listings.value.filter(listing => {
      return listing.name.toLowerCase().includes(searchQuery.value.toLowerCase());
    });
  }

  if (filterFields.value.category) {
    filteredListings = filteredListings.filter(listing => listing.category && listing.category.id === filterFields.value.category);
  }

  if (filterFields.value.company) {
    filteredListings = filteredListings.filter(listing => listing.company.toLowerCase().includes(filterFields.value.company.toLowerCase()));
  }

  if (filterFields.value.minPrice !== null) {
    filteredListings = filteredListings.filter(listing => listing.full_price >= filterFields.value.minPrice);
  }

  if (filterFields.value.maxPrice !== null) {
    filteredListings = filteredListings.filter(listing => listing.full_price <= filterFields.value.maxPrice);
  }

  if (filterFields.value.status) {
    if (filterFields.value.status === 'active') {
      filteredListings = filteredListings.filter(listing => listing.is_active);
    } else if (filterFields.value.status === 'pending') {
      filteredListings = filteredListings.filter(listing => !listing.is_active);
    } else if (filterFields.value.status === 'sold_out') {
      filteredListings = filteredListings.filter(listing => listing.tickets_sold === listing.amount_of_tickets);
    }
  }

  return filteredListings.slice(startIndex, endIndex);
});

const totalPages = computed(() => {
  return Math.ceil(listings.value.length / itemsPerPage);
});

const handleSearch = () => {
  currentPage.value = 1;
};

const toggleActivation = async (listing) => {
  try {
    listing.is_active = !listing.is_active;
    await axios.put(route('item-management.updateStatus', listing.id), { is_active: listing.is_active });
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

const toggleFilterDropdown = () => {
  showFilterDropdown.value = !showFilterDropdown.value;
};

const showAdvertisementModal = ref(false);
const advertisementImage = ref(null);
const advertisementUrl = ref('');
const showReplaceAdvertisementModal = ref(false);
const selectedAdvertisement = ref(null);
const replaceAdvertisementUrl = ref('');

const openAdvertisementModal = () => {
  showAdvertisementModal.value = true;
};

const closeAdvertisementModal = () => {
  showAdvertisementModal.value = false;
  advertisementImage.value = null;
  advertisementUrl.value = '';
};

const uploadAdvertisement = async () => {
  const formData = new FormData();
  formData.append('image', advertisementImage.value.files[0]);
  formData.append('url', advertisementUrl.value);

  try {
    await axios.post('/item-management/upload-advertisement', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    });
    closeAdvertisementModal();
    fetchAdvertisements();
    alert('Advertisement uploaded successfully!');
  } catch (error) {
    console.error('Error uploading advertisement:', error);
    alert('Failed to upload advertisement. Please check the file format and try again.');
  }
};

const getAdvertisementImageUrl = (path) => {
  return `/storage/${path}`;
};

const deleteAdvertisement = async (id) => {
  try {
    await axios.delete(`/item-management/advertisements/${id}`);
    advertisements.value = advertisements.value.filter(advertisement => advertisement.id !== id);
  } catch (error) {
    console.error('Error deleting advertisement:', error);
  }
};

const openReplaceAdvertisementModal = (advertisement) => {
  selectedAdvertisement.value = advertisement;
  replaceAdvertisementUrl.value = advertisement.url;
  showReplaceAdvertisementModal.value = true;
};

const closeReplaceAdvertisementModal = () => {
  selectedAdvertisement.value = null;
  replaceAdvertisementUrl.value = '';
  showReplaceAdvertisementModal.value = false;
};

const replaceAdvertisement = async () => {
  const formData = new FormData();
  formData.append('image', replaceAdvertisementImage.value.files[0]);
  formData.append('url', replaceAdvertisementUrl.value);

  try {
    await axios.post(`/item-management/advertisements/${selectedAdvertisement.value.id}/replace`, formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    });
    closeReplaceAdvertisementModal();
    fetchAdvertisements();
  } catch (error) {
    console.error('Error replacing advertisement:', error);
  }
};

const fetchAdvertisements = async () => {
  try {
    const response = await axios.get('/api/advertisements');
    advertisements.value = response.data;
  } catch (error) {
    console.error('Error fetching advertisements:', error);
  }
};

onMounted(() => {
  fetchCarouselImages();
  fetchAdvertisements();
});
</script>
