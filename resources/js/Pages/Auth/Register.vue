<template>
  <section class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md p-8 space-y-8 bg-white rounded-lg shadow">
      <div class="flex justify-center">
        <img src="/storage/raffl-logo.png" alt="Raffl Logo" class="w-16 h-16">
      </div>
      <h2 class="text-2xl font-bold text-center text-gray-900">Create Your Account</h2>
      <form @submit.prevent="submit">
        <div>
          <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
          <input type="text" id="first_name" v-model="form.first_name" required
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
          <div v-if="form.errors.first_name" class="text-red-500 text-xs italic">{{ form.errors.first_name }}</div>
        </div>
        <div>
          <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
          <input type="text" id="last_name" v-model="form.last_name" required
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
          <div v-if="form.errors.last_name" class="text-red-500 text-xs italic">{{ form.errors.last_name }}</div>
        </div>
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
          <input type="email" id="email" v-model="form.email" required
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
          <div v-if="form.errors.email" class="text-red-500 text-xs italic">{{ form.errors.email }}</div>
        </div>
        <div>
          <label for="phone" class="block text-sm font-medium text-gray-700" >Contact Number</label>
          <input type="text" id="phone" v-model="form.phone" required 
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        </div>
        <div>
          <label for="shipping_address" class="block text-sm font-medium text-gray-700">Shipping Address</label>
          <input type="text" id="shipping_address" v-model="form.shipping_address" required
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
          <div v-if="form.errors.shipping_address" class="text-red-500 text-xs italic">{{ form.errors.shipping_address
            }}</div>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">User Type</label>
          <div class="mt-1">
            <label class="inline-flex items-center">
              <input type="radio" v-model="form.user_type" value="individual" class="form-radio">
              <span class="ml-2">Individual</span>
            </label>
            <label class="inline-flex items-center ml-6">
              <input type="radio" v-model="form.user_type" value="business" class="form-radio">
              <span class="ml-2">Business</span>
            </label>
          </div>
          <div v-if="form.errors.user_type" class="text-red-500 text-xs italic">{{ form.errors.user_type }}</div>
        </div>
        <div v-if="form.user_type === 'business'">
          <label for="company" class="block text-sm font-medium text-gray-700">Company</label>
          <input type="text" id="company" v-model="form.company" required
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
          <div v-if="form.errors.company" class="text-red-500 text-xs italic">{{ form.errors.company }}</div>
        </div>
        <div v-if="form.user_type === 'business'">
          <label for="vat_number" class="block text-sm font-medium text-gray-700">VAT Number</label>
          <input type="text" id="vat_number" v-model="form.vat_number" required
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
          <div v-if="form.errors.vat_number" class="text-red-500 text-xs italic">{{ form.errors.vat_number }}</div>
        </div>
        <div>
          <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
          <input type="password" id="password" v-model="form.password" required
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
          <div v-if="form.errors.password" class="text-red-500 text-xs italic">{{ form.errors.password }}</div>
        </div>
        <div>
          <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
          <input type="password" id="password_confirmation" v-model="form.password_confirmation" required
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
          <div v-if="form.errors.password_confirmation" class="text-red-500 text-xs italic">{{
            form.errors.password_confirmation }}</div>
        </div>
        <div>
          <label for="profile_picture" class="block text-sm font-medium text-gray-700">Profile Picture</label>
          <input type="file" id="profile_picture" @change="onFileChange" accept="image/*"
            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
          <div v-if="form.errors.profile_picture" class="text-red-500 text-xs italic">{{ form.errors.profile_picture }}
          </div>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Selling Preference</label>
          <div class="mt-1">
            <label class="inline-flex items-center">
              <input type="radio" v-model="form.selling_preference" value="sell" class="form-radio">
              <span class="ml-2">Sell items using the platform</span>
            </label>
            <label class="inline-flex items-center ml-6">
              <input type="radio" v-model="form.selling_preference" value="buy" class="form-radio">
              <span class="ml-2">Only buy items</span>
            </label>
          </div>
          <div v-if="form.errors.selling_preference" class="text-red-500 text-xs italic">{{
            form.errors.selling_preference }}</div>
        </div>
        <div>
          <label class="inline-flex items-center">
            <input type="checkbox" v-model="form.terms_accepted" class="form-checkbox" true-value="1" false-value="0">
            <span class="ml-2">I accept the <a href="#" @click.prevent="openTermsModal" class="text-blue-500 hover:underline">terms and conditions</a></span>
          </label>
          <div v-if="form.errors.terms_accepted" class="text-red-500 text-xs italic">{{ form.errors.terms_accepted }}</div>
        </div>
        <button type="submit" class="mt-6 w-full bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white font-bold py-2 px-4 rounded">
          Register
        </button>
      </form>
    </div>

    <!-- Terms and Conditions Modal -->
    <TermsAndConditions :showModal="showTermsModal" @close-modal="closeTermsModal" />
  </section>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import TermsAndConditions from '@/Components/TermsAndConditions.vue';

const form = useForm({
  first_name: '',
  last_name: '',
  email: '',
  user_type: 'individual',
  company: '',
  vat_number: '',
  password: '',
  password_confirmation: '',
  profile_picture: '',
  selling_preference: 'sell',
  terms_accepted: '0',
  shipping_address: '',
  phone: '',
});

const showTermsModal = ref(false);

const openTermsModal = () => {
  showTermsModal.value = true;
};

const closeTermsModal = () => {
  showTermsModal.value = false;
};

const onFileChange = (event) => {
  form.profile_picture = event.target.files[0];
};

const submit = () => {
  if (form.user_type === 'individual') {
    form.company = form.first_name + ' ' + form.last_name;
    form.vat_number = 'individual';
  }

  form.transform((data) => {
    const formData = new FormData();
    for (const key in data) {
      formData.append(key, data[key]);
    }
    return formData;
  }).post(route('register'), {
    onSuccess: () => {
      // Redirect or perform additional actions on successful registration
    },
    onError: (errors) => {
      // Handle errors
      form.errors = errors;
    }
  });
};
</script>