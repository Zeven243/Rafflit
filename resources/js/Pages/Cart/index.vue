<template>
    <AuthenticatedLayout>
      <div class="min-h-screen bg-gray-100 py-5">
        <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="grid grid-cols-12 gap-6">
            <div class="col-span-12">
              <h1 class="text-2xl font-bold mb-4">Shopping Cart</h1>
  
              <div v-if="cart && cart.items.length" class="bg-white rounded-lg shadow-lg p-6">
                <ul>
                  <li v-for="item in cart.items" :key="item.id" class="flex justify-between items-center mb-4 last:mb-0">
                    <div class="flex items-center">
                      <img :src="`/storage/${item.listing.image_path}`" alt="Listing Image" class="w-16 h-16 object-cover rounded-md mr-4">
                      <div>
                        <div class="text-lg font-semibold text-gray-800">{{ item.listing.name }}</div>
                        <div class="text-gray-600">Quantity: {{ item.quantity }}</div>
                      </div>
                    </div>
                    <div>
                      <div class="text-gray-900 font-semibold">Price: ${{ item.listing.price }}</div>
                      <button @click="removeFromCart(item.id)" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Remove</button>
                    </div>
                  </li>
                </ul>
  
                <div class="mt-6 flex justify-end">
                  <div class="text-xl font-bold">Total: ${{ cartTotal }}</div>
                </div>
              </div>
              <div v-else class="bg-white rounded-lg shadow-lg p-6">
                <p>Your cart is empty.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </AuthenticatedLayout>
  </template>
  
  <script>
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  
  export default {
    components: {
      AuthenticatedLayout,
    },
    props: {
      cart: Object,
    },
    computed: {
      cartTotal() {
        return this.cart.items.reduce((total, item) => total + item.listing.price * item.quantity, 0);
      },
    },
    methods: {
      removeFromCart(itemId) {
        this.$inertia.delete(route('cart.destroy', itemId));
      },
    },
  };
  </script>
  