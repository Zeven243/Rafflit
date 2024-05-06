<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import AllListings from '@/Components/AllListings.vue';

const { listings } = usePage().props;
</script>

<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <div class="min-h-screen bg-gray-100 py-12">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-xl overflow-hidden">
          <div class="p-6">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Dashboard</h2>
            <div class="mt-6 text-gray-900">You're logged in!</div>
            <AllListings :listings="listings" />
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style>
/* Additional styles can be added here if needed */
</style>
