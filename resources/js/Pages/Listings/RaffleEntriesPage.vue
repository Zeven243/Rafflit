<template>
  <AuthenticatedLayout>
    <div class="min-h-screen bg-gray-100 py-12">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-xl overflow-hidden">
          <div class="p-6">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Raffle Entries</h2>
            <RaffleEntries :raffleEntries="raffleEntries" :wonListings="wonListings" />
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { usePage } from '@inertiajs/vue3';
import RaffleEntries from '@/Components/RaffleEntries.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const { raffleEntries, wonListings } = usePage().props;
</script>