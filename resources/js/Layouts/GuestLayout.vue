<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
  <div class="min-h-screen flex flex-col justify-between bg-gray-100 dark:bg-gray-900">
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

    <div class="flex-grow">
      <slot />
    </div>

    <!-- Footer -->
    <footer class="bg-gradient-to-r from-blue-500 to-purple-600 text-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
              <h3 class="text-xl font-semibold mb-4">About Rafflit</h3>
              <p class="text-sm">Rafflit is a raffle web application that allows users to create and participate in
                raffles
                for various items. We provide a platform for sellers to list their items and for buyers to purchase
                tickets
                for a chance to win those items.</p>
            </div>
            <div>
              <h3 class="text-xl font-semibold mb-4">Quick Links</h3>
              <ul class="space-y-2">
                <li>
                  <Link :href="route('about')" class="text-sm hover:underline">About Us</Link>
                </li>
                <li>
                  <Link :href="route('contact')" class="text-sm hover:underline">Contact Us</Link>
                </li>
                <li>
                  <Link :href="route('terms')" class="text-sm hover:underline">Terms and Conditions</Link>
                </li>
                <li>
                  <Link :href="route('privacy')" class="text-sm hover:underline">Privacy Policy</Link>
                </li>
              </ul>
            </div>
            <div>
              <h3 class="text-xl font-semibold mb-4">Contact Us</h3>
              <p class="text-sm">51 Killarney Street, Oakdale, Bellville<br>Cape Town, South Africa<br>Phone: +27 069 720 8308 <br>Email:
                info@rafflit.co.za</p>
            </div>
          </div>
          <div class="mt-8 border-t border-white pt-8">
            <p class="text-sm text-center">&copy; {{ new Date().getFullYear() }} Rafflit. All rights reserved.</p>
          </div>
        </div>
      </footer>
  </div>
</template>
