<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <div class="min-h-screen bg-gray-100 py-5" :class="{ 'overlay-active': isSearchFocused }">
      <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8">
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
              <h2 v-if="!searchPerformed" class="text-2xl font-bold mb-4">New to Rafflit</h2>
              <div v-if="!searchPerformed" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-2 xl:grid-cols-3 gap-6 py-3">
                <div v-for="listing in latestListings" :key="listing.id" class="relative group" @click="goToListing(listing.id)">
                  <ListingCard
                    :listing="listing"
                    class="transform transition-transform duration-300 ease-in-out group-hover:scale-105 cursor-pointer"
                  />
                </div>
              </div>
              <div v-if="searchPerformed" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6 py-3">
                <div v-for="listing in filteredListings" :key="listing.id" class="relative group" @click="goToListing(listing.id)">
                  <ListingCard
                    :listing="listing"
                    class="transform transition-transform duration-300 ease-in-out group-hover:scale-105 cursor-pointer"
                  />
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
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage, router } from '@inertiajs/vue3';
import ListingCard from '@/Components/ListingCard.vue';
import { ref, computed, onMounted } from 'vue';
import { Swiper, SwiperSlide } from 'swiper/vue';
import 'swiper/swiper-bundle.css';
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

const latestListings = computed(() => {
  return listings.slice().sort((a, b) => new Date(b.created_at) - new Date(a.created_at)).slice(0, 6);
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
</script>
