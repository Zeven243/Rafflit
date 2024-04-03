<template>
  <AuthenticatedLayout>
    <div class="min-h-screen bg-gray-100 flex justify-center items-center">
      <form @submit.prevent="submit" class="w-full max-w-lg p-8 bg-white rounded-lg shadow-xl">
        <div class="flex justify-center mb-6">
          <img src="/storage/raffl-logo.png" alt="Raffl Logo" class="h-16">
        </div>
        <div class="flex flex-col gap-6">
          <div>
            <label for="amount" class="block text-sm font-medium text-gray-700">Amount to Upload</label>
            <input type="number" id="amount" name="amount" min="1" step="any"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" v-model.number="form.amount">
            <div v-if="form.errors.amount" class="text-red-500 text-xs italic">{{ form.errors.amount }}</div>
          </div>

          <div>
            <label for="bank" class="block text-sm font-medium text-gray-700">Bank Name</label>
            <input type="text" id="bank" name="bank"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" v-model="form.bank">
            <div v-if="form.errors.bank" class="text-red-500 text-xs italic">{{ form.errors.bank }}</div>
          </div>

          <div>
            <label for="account" class="block text-sm font-medium text-gray-700">Account Number</label>
            <input type="text" id="account" name="account"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" v-model="form.account">
            <div v-if="form.errors.account" class="text-red-500 text-xs italic">{{ form.errors.account }}</div>
          </div>

          <button type="submit"
            class="mt-6 w-full bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white font-bold py-2 px-4 rounded">
            Upload Money
          </button>
        </div>
      </form>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineProps({
  errors: Object,
});

const form = useForm({
  amount: '',
  bank: '',
  account: '',
});

const submit = () => {
  // Here you would integrate with your backend to process the bank upload.
  // This might involve calling an API that handles the transaction.
  // For now, this is a placeholder function.
  console.log('Submitting form', form);
  alert('This feature is under development.');
};
</script>

<style scoped>
/* Feel free to add any additional styles that fit your theme */
</style>