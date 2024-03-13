<template>
    <AuthenticatedLayout>
      <div class="min-h-screen bg-gray-100 flex justify-center items-center">
        <form @submit.prevent="submit" class="w-full max-w-lg p-8 bg-white rounded-lg shadow-xl">
          <div class="flex justify-center mb-6">
            <img src="/storage/raffl-logo.png" alt="Raffl Logo" class="h-16">
          </div>
          <div class="flex flex-col gap-6">
            <div>
              <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
              <input type="text" id="name" name="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                v-model="form.name">
              <div v-if="form.errors.name" class="text-red-500 text-xs italic">{{ form.errors.name }}</div>
            </div>
  
            <div>
              <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
              <input type="text" id="description" name="description"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" v-model="form.description">
              <div v-if="form.errors.description" class="text-red-500 text-xs italic">{{ form.errors.description }}</div>
            </div>
  
            <div>
              <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
              <input type="number" id="price" name="price" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                v-model.number="form.price">
              <div v-if="form.errors.price" class="text-red-500 text-xs italic">{{ form.errors.price }}</div>
            </div>
  
            <div>
              <label for="amount_of_tickets" class="block text-sm font-medium text-gray-700">Amount of Tickets</label>
              <input type="number" id="amount_of_tickets" name="amount_of_tickets"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" v-model.number="form.amount_of_tickets">
            </div>
  
            <div class="mt-4">
              <label class="block text-sm font-medium text-gray-700">Price per Ticket</label>
              <div class="mt-1 block w-full rounded-md bg-gray-200 p-2">
                {{ pricePerTicket }}
              </div>
            </div>
  
            <div>
              <label for="image" class="block text-sm font-medium text-gray-700">Image</label>
              <input type="file" id="image" name="image" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                @change="onFileChange" />
            </div>
  
            <button type="submit"
              class="mt-6 w-full bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white font-bold py-2 px-4 rounded">
              Create Listing
            </button>
          </div>
        </form>
      </div>
    </AuthenticatedLayout>
  </template>
  
  <script setup>
  import { useForm } from '@inertiajs/vue3';
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  import { computed } from 'vue';
  
  defineProps({
    errors: Object,
  });
  
  const form = useForm({
    name: '',
    description: '',
    price: '',
    amount_of_tickets: '',
    image: '',
  });
  
  const onFileChange = (e) => {
    form.image = e.target.files[0];
  };
  
  const resetForm = () => {
    form.reset();
  };
  
  const submit = () => {
    form.post(route('listings.store'), {
      onSuccess: () => resetForm(),
    });
  };
  
  const pricePerTicket = computed(() => {
    return form.amount_of_tickets > 0 ? (form.price / form.amount_of_tickets).toFixed(2) : '0.00';
  });
  </script>
  
  <style scoped>
  /* Add any additional styles here */
  </style>
  