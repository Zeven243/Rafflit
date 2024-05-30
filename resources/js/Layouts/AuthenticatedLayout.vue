<script setup>
import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);

const { props } = usePage();

const hasRole = (roles) => {
  const userRoles = props.auth.user.roles || [];
  return userRoles.some(role => roles.includes(role.name));
};
</script>

<template>
  <div>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
      <nav class="bg-gradient-to-r from-blue-500 to-purple-600 border-b border-gray-100 dark:border-gray-700">
        <!-- Primary Navigation Menu -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between h-16">
            <div class="flex">
              <!-- Logo -->
              <div class="shrink-0 flex items-center">
                <Link :href="route('dashboard')">
                  <img src="/storage/raffl-logo.png" class="block h-9 w-auto" />
                </Link>
              </div>
              <!-- Navigation Links -->
              <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                  My Dashboard
                </NavLink>
                <NavLink :href="route('listings.index')" :active="route().current('listings.index')">
                  My Listings
                </NavLink>
                <NavLink v-if="hasRole(['Administrator', 'Developer-Master'])" :href="route('listings.create')" :active="route().current('listings.create')">
                  Create a Listing
                </NavLink>
                <NavLink :href="route('raffle-entries.index')" :active="route().current('raffle-entries.index')">
                  My Raffle Entries
                </NavLink>
              </div>
            </div>
            <div class="hidden sm:flex sm:items-center sm:ms-6">
              <!-- Cart Link -->
              <NavLink :href="route('cart.index')" :active="route().current('cart.index')" class="mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                My Cart
              </NavLink>
              <!-- Settings Dropdown -->
              <div class="ms-3 relative">
                <Dropdown align="right" width="48">
                  <template #trigger>
                    <span class="inline-flex rounded-md">
                      <button type="button"
                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-white bg-opacity-20 hover:bg-opacity-30 focus:outline-none transition ease-in-out duration-150">
                        <div class="relative mr-2">
                          <img
                            :src="props.auth.user.profile_picture ? `/storage/${props.auth.user.profile_picture.replace('public/', '')}` : '/storage/default-profile-picture.png'"
                            alt="Profile Picture" class="w-10 h-10 rounded-full" />
                        </div>
                        {{ props.auth.user.name }}
                        <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                          fill="currentColor">
                          <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                        </svg>
                      </button>
                    </span>
                  </template>
                  <template #content>
                    <DropdownLink :href="route('profile.edit')">
                      Profile
                    </DropdownLink>
                    <DropdownLink v-if="hasRole(['Administrator', 'Developer-Master'])" :href="route('user-management.index')">
                      User Management
                    </DropdownLink>
                    <DropdownLink v-if="hasRole(['Administrator', 'Developer-Master'])" :href="route('item-management.index')">
                      Item Management
                    </DropdownLink>
                    <DropdownLink :href="route('logout')" method="post" as="button">
                      Log Out
                    </DropdownLink>
                  </template>
                </Dropdown>
              </div>
            </div>
            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
              <button @click="showingNavigationDropdown = !showingNavigationDropdown"
                class="inline-flex items-center justify-center p-2 rounded-md text-white hover:bg-white hover:bg-opacity-20 focus:outline-none focus:bg-opacity-30 transition duration-150 ease-in-out">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                  <path :class="{ hidden: showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }"
                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                  <path :class="{ hidden: !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }"
                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
          </div>
        </div>
        <!-- Responsive Navigation Menu -->
        <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }" class="sm:hidden">
          <div class="pt-2 pb-3 space-y-1">
            <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
              Dashboard
            </ResponsiveNavLink>
            <ResponsiveNavLink :href="route('listings.index')" :active="route().current('listings.index')">
              My Listings
            </ResponsiveNavLink>
            <ResponsiveNavLink v-if="hasRole(['Administrator', 'Developer-Master'])" :href="route('listings.create')" :active="route().current('listings.create')">
              Create a Listing
            </ResponsiveNavLink>
            <ResponsiveNavLink :href="route('raffle-entries.index')" :active="route().current('raffle-entries.index')">
              My Raffle Entries
            </ResponsiveNavLink>
            <ResponsiveNavLink :href="route('cart.index')" :active="route().current('cart.index')">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
              My Cart
            </ResponsiveNavLink>
          </div>
          <!-- Responsive Settings Options -->
          <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
              <div class="font-medium text-base text-white">{{ props.auth.user.name }}</div>
              <div class="font-medium text-sm text-gray-200">{{ props.auth.user.email }}</div>
            </div>
            <div class="mt-3 space-y-1">
              <ResponsiveNavLink :href="route('profile.edit')">
                Profile
              </ResponsiveNavLink>
              <ResponsiveNavLink v-if="hasRole(['Administrator', 'Developer-Master'])" :href="route('user-management.index')">
                User Management
              </ResponsiveNavLink>
              <ResponsiveNavLink v-if="hasRole(['Administrator', 'Developer-Master'])" :href="route('item-management.index')">
                Item Management
              </ResponsiveNavLink>
              <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                Log Out
              </ResponsiveNavLink>
            </div>
          </div>
        </div>
      </nav>
      <!-- Page Heading -->
      <header class="bg-white dark:bg-gray-800 shadow" v-if="$slots.header">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
          <slot name="header" />
        </div>
      </header>
      <!-- Page Content -->
      <main>
        <slot />
      </main>
    </div>
  </div>
</template>
