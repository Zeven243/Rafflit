<template>
    <AuthenticatedLayout>
      <Head title="Item Management" />
      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
              <h1 class="text-2xl font-bold mb-4">Item Management</h1>
              <p>Manage your items here.</p>
              <!-- Search Form -->
              <form @submit.prevent="handleSearch" class="mb-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-4">
                  <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" id="name" v-model="searchQuery" class="mt-1 block w-full">
                  </div>
                  <div>
                    <label for="full_price" class="block text-sm font-medium text-gray-700">Full Price</label>
                    <input type="number" id="full_price" v-model="search.full_price" class="mt-1 block w-full">
                  </div>
                  <div>
                    <label for="amount_of_tickets" class="block text-sm font-medium text-gray-700">Amount of Tickets</label>
                    <input type="number" id="amount_of_tickets" v-model="search.amount_of_tickets" class="mt-1 block w-full">
                  </div>
                  <div>
                    <label for="ticket_price" class="block text-sm font-medium text-gray-700">Ticket Price</label>
                    <input type="number" id="ticket_price" v-model="search.ticket_price" class="mt-1 block w-full">
                  </div>
                </div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                  Search
                </button>
              </form>
              <!-- Listings -->
              <div v-if="searchPerformed" class="mt-8">
                <h2 class="text-xl font-bold mb-4">Listings</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                  <div v-for="listing in filteredListings" :key="listing.id" class="relative group">
                    <img :src="getImageUrl(listing.image_path)" alt="Listing Image" class="w-full h-48 object-cover rounded-lg shadow-md">
                    <div class="p-4">
                      <h3 class="text-lg font-bold">{{ listing.name }}</h3>
                      <p class="text-sm text-gray-600">{{ listing.description }}</p>
                      <div class="flex justify-between items-center mt-4">
                        <Link :href="`/listings/${listing.id}/edit`" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded">
                          Edit
                        </Link>
                        <button @click="deleteListing(listing.id)" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded">
                          Delete
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div v-else class="mt-8">
                <p class="text-gray-600">No listings found. Please adjust your search criteria.</p>
              </div>
              <!-- Image Upload Form -->
              <form @submit.prevent="uploadImages" enctype="multipart/form-data" class="mt-8">
                <div class="mb-4">
                  <label for="images" class="block text-sm font-medium text-gray-700">Upload Images</label>
                  <input type="file" id="images" name="images" multiple @change="handleFileUpload" class="mt-1 block w-full">
                </div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                  Upload
                </button>
              </form>
              <!-- Existing Images -->
              <div class="mt-8">
                <h2 class="text-xl font-bold mb-4">Dashboard Carousel Images</h2>
                <p class="text-sm text-gray-600 mb-4">Recommended image size: 1200 x 675 pixels</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                  <div v-for="image in images" :key="image.id" class="relative group">
                    <img :src="getImageUrl(image.image_path)" alt="Carousel Image" class="w-full h-48 object-cover rounded-lg shadow-md">
                    <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                      <label class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded cursor-pointer">
                        Replace
                        <input type="file" @change="replaceImage($event, image.id)" class="hidden">
                      </label>
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
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import axios from 'axios';

const { listings, categories } = usePage().props;

const searchQuery = ref('');
const search = ref({
  full_price: '',
  amount_of_tickets: '',
  ticket_price: '',
});
const selectedCategories = ref([]);
const isSearchFocused = ref(false);
const showDropdown = ref(false);
const recentSearches = ref([]);
const trendingSearches = ref([]);
const searchPerformed = ref(false);
const carouselImages = ref([]);

const getImageUrl = (path) => {
  return `/storage/${path}`;
};

onMounted(async () => {
  try {
    const response = await axios.get('/api/carousel-images', { withCredentials: true });
    console.log('Fetched carousel images:', response.data);
    carouselImages.value = response.data;
  } catch (error) {
    console.error('Error fetching carousel images:', error);
  }
});

const filteredListings = computed(() => {
  return listings.filter(listing => {
    const matchesSearch = listing.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      listing.description.toLowerCase().includes(searchQuery.value.toLowerCase());
    const matchesCategory = selectedCategories.value.length === 0 || selectedCategories.value.includes(listing.category_id);
    return matchesSearch && matchesCategory;
  });
});

const toggleCategory = (categoryId) => {
  if (selectedCategories.value.includes(categoryId)) {
    selectedCategories.value = selectedCategories.value.filter(id => id !== categoryId);
  } else {
    selectedCategories.value.push(categoryId);
  }
};

const fetchRecentAndTrendingSearches = async () => {
  try {
    const recentResponse = await fetch('/api/recent-searches');
    const trendingResponse = await fetch('/api/trending-searches');
    recentSearches.value = await recentResponse.json();
    trendingSearches.value = await trendingResponse.json();
  } catch (error) {
    console.error('Error fetching recent and trending searches:', error);
  }
};

const handleSearch = () => {
  if (searchQuery.value) {
    // Save the search query to the database or perform other actions
    saveSearchQuery(searchQuery.value);

    // Perform search logic here
    searchPerformed.value = true;
  }
};

const saveSearchQuery = async (query) => {
  try {
    const response = await fetch('/api/save-search', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ query }),
    });

    if (response.ok) {
      if (!recentSearches.value.includes(query)) {
        recentSearches.value.unshift(query);
        recentSearches.value = recentSearches.value.slice(0, 5);
      }
    } else {
      console.error('Error saving search query:', response.status);
    }
  } catch (error) {
    console.error('Error saving search query:', error);
  }
};

const hideDropdown = () => {
  setTimeout(() => {
    showDropdown.value = false;
  }, 200); // Delay to allow click event to register
};

const form = useForm({
  images: null,
});

const images = ref([]);

const handleFileUpload = (event) => {
  form.images = event.target.files;
};

const uploadImages = () => {
  form.post(route('carousel-images.store'), {
    onSuccess: () => {
      form.reset();
      fetchImages();
    },
  });
};

const fetchImages = async () => {
  try {
    const response = await axios.get('/api/carousel-images', { withCredentials: true });
    images.value = response.data;
  } catch (error) {
    console.error('Error fetching images:', error);
  }
};

const replaceImage = async (event, id) => {
  const file = event.target.files[0];
  if (file) {
    const formData = new FormData();
    formData.append('image', file);
    try {
      await axios.post(`/carousel-images/${id}/replace`, formData, { withCredentials: true });
      fetchImages();
    } catch (error) {
      console.error('Error replacing image:', error);
    }
  }
};

const deleteListing = async (id) => {
  if (confirm('Are you sure you want to delete this listing?')) {
    try {
      await axios.delete(`/listings/${id}`);
      fetchListings();
    } catch (error) {
      console.error('Error deleting listing:', error);
    }
  }
};

const fetchListings = async () => {
  try {
    const response = await axios.get(route('item-management.index'), { params: { searchQuery: searchQuery.value } });
    listings.value = response.data.listings || [];
  } catch (error) {
    console.error('Error fetching listings:', error);
  }
};

onMounted(() => {
  fetchImages();
  fetchListings();
});
</script>
