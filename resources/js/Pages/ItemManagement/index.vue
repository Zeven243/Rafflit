<template>
    <AuthenticatedLayout>
      <Head title="Item Management" />
      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
              <h1 class="text-2xl font-bold mb-4">Item Management</h1>
              <p>Manage your items here.</p>
              <!-- Category Filter Menu -->
              <div class="col-span-12 lg:col-span-2 bg-white rounded-lg shadow-lg p-4 mb-4 lg:mb-0">
                <h3 class="text-lg font-bold mb-4">Filter by Category</h3>
                <div v-for="category in categories" :key="category.id" class="mb-2">
                  <label class="inline-flex items-center">
                    <input type="checkbox" :value="category.id" v-model="selectedCategories" class="form-checkbox">
                    <span class="ml-2">{{ category.name }}</span>
                  </label>
                </div>
              </div>
  
              <!-- Main Content Area -->
              <div :class="{'col-span-12 lg:col-span-8': !searchPerformed, 'col-span-12': searchPerformed}">
                <!-- Listings and Search Bar -->
                <div class="mb-4">
                  <h2 class="text-2xl font-bold mb-4">Search Listings</h2>
                  <div class="search-container relative z-10">
                    <div class="flex justify-between items-center mb-4">
                      <input
                        type="text"
                        v-model="searchQuery"
                        placeholder="Search listings..."
                        class="form-input w-full rounded-lg border-gray-300 shadow-sm p-4"
                        @focus="isSearchFocused = true; showDropdown = true; fetchRecentAndTrendingSearches()"
                        @blur="isSearchFocused = false; hideDropdown()"
                        @keyup.enter="handleSearch"
                      />
                      <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-4" @click="handleSearch">
                        Search
                      </button>
                    </div>
                    <div v-if="showDropdown" class="absolute z-10 w-full bg-white rounded-lg shadow-lg mt-2">
                      <div v-if="recentSearches.length > 0" class="py-2 px-4">
                        <h3 class="text-sm font-semibold mb-2">Recent Searches</h3>
                        <ul>
                          <li v-for="(search, index) in recentSearches.slice(0, 3)" :key="index" class="cursor-pointer hover:bg-gray-100 py-1 px-2 rounded" @click="searchQuery = search; handleSearch()">
                            {{ search }}
                          </li>
                        </ul>
                      </div>
                      <div v-if="trendingSearches.length > 0" class="py-2 px-4 border-t border-gray-200">
                        <h3 class="text-sm font-semibold mb-2">Trending Searches</h3>
                        <ul>
                          <li v-for="(search, index) in trendingSearches" :key="index" class="cursor-pointer hover:bg-gray-100 py-1 px-2 rounded" @click="searchQuery = search; handleSearch()">
                            {{ search }}
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div>
                  <div v-if="searchPerformed" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6 py-3">
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
  
  const goToListing = (id) => {
    router.visit(route('listings.show', id));
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
  </script>
  
  <style scoped>
  .group:hover .group-hover\:scale-105 {
    transform: scale(1.05);
  }
  </style>
  