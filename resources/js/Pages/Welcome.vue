<template>
  <Head title="Welcome" />

  <div class="min-h-screen bg-gray-900 text-white">
    <nav class="border-b border-gray-700">
      <div class="container mx-auto flex justify-between items-center py-4">
        <div class="text-blue-500 text-3xl font-bold">
          <img src="/storage/raffl-logo.png" alt="Rafflit Logo" class="h-12">
        </div>
        <div>
          <Link v-if="canLogin && !$page.props.auth.user" :href="route('login')" class="hover:text-blue-500 px-4 py-2">
            Log in
          </Link>
          <Link v-if="canRegister" :href="route('register')" class="bg-orange-500 hover:bg-orange-600 px-4 py-2 rounded">
            Register
          </Link>
        </div>
      </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
      <h1 class="text-4xl font-bold text-center mb-4">Welcome to Rafflit</h1>
      <!-- Swiper implementation -->
      <swiper class="gap-4" :slides-per-view="4" :space-between="30" :loop="true"
        :autoplay="{ delay: 1500, disableOnInteraction: false }" @swiper="onSwiper" @slideChange="onSlideChange">
        <swiper-slide v-for="listing in listings" :key="listing.id">
          <div class="bg-white rounded-lg overflow-hidden shadow-lg">
            <img class="w-full object-cover" :src="listing.image" alt="Listing Image">
            <div class="py-4">
              <h2 class="text-2xl font-bold">{{ listing.name }}</h2>
              <p class="text-gray-600">{{ listing.description }}</p>
              <div class="flex justify-between mt-3">
                <span class="text-lg font-bold">{{ listing.price }}</span>
                <button class="px-3 py-2 bg-blue-500 hover:bg-blue-600 text-white text-xs font-bold uppercase rounded">
                  Enter Raffle
                </button>
              </div>
            </div>
          </div>
        </swiper-slide>
      </swiper>
    </div>
  </div>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { Swiper, SwiperSlide } from 'swiper/vue';
import 'swiper/css'; // Import Swiper styles

const { canLogin, canRegister, laravelVersion, phpVersion, listings } = defineProps({
  canLogin: Boolean,
  canRegister: Boolean,
  laravelVersion: String,
  phpVersion: String,
  listings: Array,
});

const onSwiper = (swiper) => {
  console.log(swiper);
};

const onSlideChange = () => {
  console.log('slide changed');
};
</script>

<style>
/* Tailwind CSS is used for styling */
</style>
