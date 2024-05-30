<template>
  <Head title="Welcome" />

  <div class="min-h-screen bg-gray-100">
    <nav class="bg-gradient-to-r from-blue-500 to-purple-600 border-b border-gray-100 dark:border-gray-700">
      <div class="container mx-auto flex justify-between items-center py-4">
        <div class="flex items-center">
          <img src="/storage/raffl-logo.png" alt="Rafflit Logo" class="h-12 mr-4">
          <span class="ml-4 text-white text-xl font-semibold">Elevate Your Luck, One Ticket at a Time</span>
        </div>
        <div>
          <Link v-if="!$page.props.auth.user" :href="route('login')" class="text-white hover:bg-white hover:bg-opacity-20 px-4 py-2 rounded">
            Log in
          </Link>
          <Link v-if="!$page.props.auth.user" :href="route('register')" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded ml-4">
            Register
          </Link>
        </div>
      </div>
    </nav>

    <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
      <div class="grid grid-cols-12 gap-6">
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
          <!-- Feature Items Carousel -->
          <div v-if="!searchPerformed" class="mb-8">
            <h2 class="text-2xl font-bold mb-4">Featured Items</h2>
            <swiper :slides-per-view="1" :loop="true" :autoplay="{ delay: 2000 }" :speed="500">
              <swiper-slide v-for="image in carouselImages" :key="image.id">
                <div class="relative flex justify-center">
                  <img :src="getImageUrl(image.image_path)" alt="Carousel Image" class="w-full h-96 object-cover rounded-lg shadow-md">
                  <div class="absolute bottom-0 left-0 bg-black bg-opacity-50 text-white p-4 rounded-b-lg">
                    <h3 class="text-2xl font-bold">{{ image.title }}</h3>
                    <p class="text-base">{{ image.description }}</p>
                  </div>
                </div>
              </swiper-slide>
              <div class="swiper-pagination" slot="pagination"></div>
              <div class="swiper-button-next" slot="button-next"></div>
              <div class="swiper-button-prev" slot="button-prev"></div>
            </swiper>
          </div>

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
                <!-- Recent and Trending Searches -->
              </div>
            </div>
            <h2 v-if="!searchPerformed" class="text-2xl font-bold mb-4">New to Rafflit</h2>
            <div v-if="!searchPerformed" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-2 xl:grid-cols-3 gap-6 py-3">
              <div v-for="listing in listings" :key="listing.id" class="relative group">
                <ListingCard :listing="listing" class="transform transition-transform duration-300 ease-in-out group-hover:scale-105 cursor-pointer" />
              </div>
            </div>
            <div v-if="searchPerformed" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6 py-3">
              <div v-for="listing in filteredListings" :key="listing.id" class="relative group">
                <ListingCard :listing="listing" class="transform transition-transform duration-300 ease-in-out group-hover:scale-105 cursor-pointer" />
              </div>
            </div>
          </div>
        </div>

        <!-- Advertisement or Future Feature Space -->
        <div v-if="!searchPerformed" class="col-span-12 lg:col-span-2">
          <div class="bg-white rounded-lg shadow-lg p-4 mb-4">
            <h3 class="text-lg font-bold mb-4">Advertisements</h3>
            <p class="text-base">This space is reserved for advertisements or future features.</p>
          </div>
          <div class="bg-white rounded-lg shadow-lg p-4">
            <h3 class="text-lg font-bold mb-4">Featured Products</h3>
            <p class="text-base">Showcase some featured products here.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { Swiper, SwiperSlide } from 'swiper/vue';
import 'swiper/css';
import ListingCard from '@/Components/ListingCard.vue';
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';

const props = defineProps({
  canLogin: Boolean,
  canRegister: Boolean,
  listings: Array,
  categories: Array,
  carouselImages: Array,
});

const searchQuery = ref('');
const selectedCategories = ref([]);
const isSearchFocused = ref(false);
const showDropdown = ref(false);
const recentSearches = ref([]);
const trendingSearches = ref([]);
const searchPerformed = ref(false);

const getImageUrl = (path) => {
  return `/storage/${path}`;
};

const filteredListings = computed(() => {
  return props.listings.filter(listing => {
    const matchesSearch = listing.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      listing.description.toLowerCase().includes(searchQuery.value.toLowerCase());
    const matchesCategory = selectedCategories.value.length === 0 || selectedCategories.value.includes(listing.category_id);
    return matchesSearch && matchesCategory;
  });
});

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
</script>

<style>
/* Tailwind CSS is used for styling */
</style>
