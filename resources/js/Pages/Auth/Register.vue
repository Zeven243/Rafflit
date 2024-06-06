<template>
  <section class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md p-8 space-y-8 bg-white rounded-lg shadow">
      <div class="flex justify-center">
        <img src="/storage/raffl-logo.png" alt="Raffl Logo" class="w-16 h-16">
      </div>
      <h2 class="text-2xl font-bold text-center text-gray-900">Create Your Account</h2>
      <form @submit.prevent="submit" class="mt-8 space-y-6">
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
            <input type="checkbox" v-model="form.terms_accepted" class="form-checkbox" :disabled="!termsRead">
            <span class="ml-2">I accept the <a href="#" @click.prevent="openTermsModal"
                class="text-blue-500 hover:underline">terms and conditions</a></span>
          </label>
          <div v-if="form.errors.terms_accepted" class="text-red-500 text-xs italic">{{ form.errors.terms_accepted }}
          </div>
        </div>
        <button type="submit"
          class="mt-6 w-full bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white font-bold py-2 px-4 rounded">
          Register
        </button>
      </form>
    </div>

    <!-- Terms and Conditions Modal -->
    <div v-if="showTermsModal" class="fixed z-10 inset-0 overflow-y-auto">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
          <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div
          class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
          role="dialog" aria-modal="true" aria-labelledby="modal-headline">
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-headline">
                  Terms and Conditions
                </h3>
                <div class="mt-2 text-sm text-gray-500" style="max-height: 400px; overflow-y: auto;">
                  <h4 class="text-lg font-semibold">Terms and Conditions</h4>
                  <p><strong>1. Introduction</strong><br>Welcome to Rafflit.co.za ("Rafflit", "we", "us", "our"). These
                    terms and conditions ("Terms") govern your use of our website and services. By using our platform,
                    you agree to comply with these Terms.</p>
                  <p><strong>2. Definitions</strong><br>"User" refers to any individual or business that registers on
                    Rafflit.<br>"Seller" refers to a user who lists items for raffle on Rafflit.<br>"Buyer" refers to a
                    user who purchases raffle tickets or buys out an item.<br>"Raffle" refers to the process of selling
                    tickets for a chance to win an item.<br>"Item" refers to any product listed for raffle on Rafflit.
                  </p>
                  <p><strong>3. User Registration</strong><br>To use our platform, you must register and provide
                    accurate information. You must be at least 18 years old to register.</p>
                  <p><strong>4. Raffle Rules</strong><br>Each raffle will have a specified number of tickets available
                    for purchase.<br>Users can purchase one or more tickets for a chance to win the item.<br>Once all
                    tickets are sold, a winner will be randomly selected by our system.<br>Users can also buy out an
                    item by purchasing all available tickets.</p>
                  <p><strong>5. Fees and Fundraising</strong><br>Rafflit takes a 10% cut from the total cost of each
                    item sold through our platform as a fundraising activity to support our mission and
                    operations.<br>This fundraising cut is deducted from the total amount collected from ticket sales or
                    buyout.</p>
                  <p><strong>6. Item Delivery and Shipping</strong><br>Sellers must deliver or ship their items to our
                    office before the raffle is made available on the platform. Sellers are responsible for all
                    shipping/courier fees to get the items to our office.<br>Once we receive the item, it will be listed
                    for raffle.<br>After a winner is declared, we will ship the item to the winner's specified shipping
                    address. Buyers/winners are responsible for the shipping fees from our office to their address.</p>
                  <p><strong>7. Winner Notification and Item Delivery</strong><br>Winners will be notified via
                    email.<br>Items will be shipped from our office to the winner's shipping address.</p>
                  <p><strong>8. User Responsibilities</strong><br>Users must provide accurate and up-to-date
                    information.<br>Users must comply with all applicable laws and regulations.<br>Sellers must ensure
                    that their items are in good condition and meet the description provided.</p>
                  <p><strong>9. Prohibited Activities</strong><br>Users may not engage in fraudulent activities or
                    misrepresentations.<br>Users may not use our platform for illegal activities.</p>
                  <p><strong>10. Privacy Policy</strong><br>We collect and process personal information in accordance
                    with our Privacy Policy.<br>By using our platform, you consent to the collection and use of your
                    information as described in our Privacy Policy.</p>
                  <p><strong>11. Limitation of Liability</strong><br>Rafflit is not liable for any indirect, incidental,
                    or consequential damages arising from the use of our platform.<br>Our liability is limited to the
                    amount of fees paid by the user.</p>
                  <p><strong>12. Governing Law</strong><br>These Terms are governed by the laws of South Africa. Any
                    disputes arising from these Terms will be subject to the exclusive jurisdiction of the South African
                    courts.</p>
                  <p><strong>13. Changes to Terms and Conditions</strong><br>We may update these Terms from time to
                    time. Users will be notified of any changes, and continued use of our platform constitutes
                    acceptance of the updated Terms.</p>
                  <p><strong>14. Contact Information</strong><br>If you have any questions or concerns about these
                    Terms, please contact us at support@rafflit.co.za.</p>
                  <h4 class="text-lg font-semibold">Privacy Policy</h4>
                  <p><strong>1. Introduction</strong><br>This Privacy Policy explains how we collect, use, and protect
                    your personal information.</p>
                  <p><strong>2. Information We Collect</strong><br>Personal information provided during registration
                    (e.g., name, email, address).<br>Payment information for transactions.<br>Usage data and cookies.
                  </p>
                  <p><strong>3. How We Use Your Information</strong><br>To provide and improve our services.<br>To
                    process transactions and send notifications.<br>To comply with legal obligations.</p>
                  <p><strong>4. Data Security</strong><br>We implement security measures to protect your information.
                    However, no method of transmission over the internet is completely secure.</p>
                  <p><strong>5. Your Rights</strong><br>You have the right to access, update, and delete your personal
                    information.<br>You can opt-out of marketing communications.</p>
                  <p><strong>6. Changes to Privacy Policy</strong><br>We may update this Privacy Policy from time to
                    time. Users will be notified of any changes.</p>
                  <p><strong>7. Contact Information</strong><br>If you have any questions about this Privacy Policy,
                    please contact us at privacy@rafflit.co.za.</p>
                  <h4 class="text-lg font-semibold">Rules</h4>
                  <p><strong>1. Eligibility</strong><br>Users must be at least 18 years old to participate in
                    raffles.<br>Users must comply with all applicable laws and regulations.</p>
                  <p><strong>2. Raffle Participation</strong><br>Users can purchase tickets for a chance to win an
                    item.<br>Users can buy out an item by purchasing all available tickets.</p>
                  <p><strong>3. Winner Selection</strong><br>Winners are randomly selected by our system.<br>Winners
                    will be notified via email.</p>
                  <p><strong>4. Item Delivery</strong><br>Items will be shipped to the winner's specified shipping
                    address. Buyers/winners are responsible for the shipping fees from our office to their address.</p>
                  <p><strong>5. Prohibited Activities</strong><br>Users may not engage in fraudulent activities or
                    misrepresentations.<br>Users may not use our platform for illegal activities.</p>
                  <p>By using Rafflit, you agree to comply with these rules.</p>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button type="button"
              class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
              @click="closeTermsModal">
              Close
            </button>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = useForm({
  first_name: '',
  last_name: '',
  email: '',
  user_type: 'individual',
  company: '',
  vat_number: '',
  password: '',
  password_confirmation: '',
  profile_picture: null,
  selling_preference: 'sell',
  terms_accepted: false,
  shipping_address: '',
});

const showTermsModal = ref(false);
const termsRead = ref(false);

const openTermsModal = () => {
  showTermsModal.value = true;
};

const closeTermsModal = () => {
  showTermsModal.value = false;
  termsRead.value = true;
};

const onFileChange = (event) => {
  form.profile_picture = event.target.files[0];
};

const submit = () => {
  const formData = new FormData();
  formData.append('first_name', form.first_name);
  formData.append('last_name', form.last_name);
  formData.append('email', form.email);
  formData.append('user_type', form.user_type);
  formData.append('company', form.company);
  formData.append('vat_number', form.vat_number);
  formData.append('password', form.password);
  formData.append('password_confirmation', form.password_confirmation);
  formData.append('selling_preference', form.selling_preference);
  formData.append('terms_accepted', form.terms_accepted);
  formData.append('shipping_address', form.shipping_address);
  if (form.profile_picture) {
    formData.append('profile_picture', form.profile_picture);
  }

  form.post(route('register'), formData, {
    forceFormData: true,
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

<style>
/* Additional global styles can be placed in your main CSS file */
</style>
