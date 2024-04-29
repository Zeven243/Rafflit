<template>
    <AuthenticatedLayout>
      <Head title="Edit Profile" />
  
      <div class="min-h-screen bg-gray-100 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="bg-white rounded-lg shadow-xl overflow-hidden">
            <div class="p-6">
              <h2 class="font-semibold text-2xl text-gray-900 leading-tight">Edit Profile</h2>
              <div class="mt-6 space-y-6">
                <!-- Profile Information Form -->
                <UpdateProfileInformationForm
                  :user="user"
                  class="max-w-xl"
                />
  
                <!-- Profile Picture Form -->
                <UpdateProfilePictureForm
                  :user="user"
                  class="max-w-xl"
                />
  
                <!-- Password Update Form -->
                <UpdatePasswordForm class="max-w-xl" />
  
                <!-- Delete User Form -->
                <DeleteUserForm class="max-w-xl" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </AuthenticatedLayout>
  </template>
  
  <script setup>
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
  import UpdateProfilePictureForm from './Partials/UpdateProfilePictureForm.vue';
  import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
  import DeleteUserForm from './Partials/DeleteUserForm.vue';
  import { Head, usePage } from '@inertiajs/vue3';
  
  const { user } = usePage().props;
  </script>
  
  <style>
  /* Enhanced styles for better readability */
  h2, p {
    color: #1F2937; /* Darker text color for better readability */
  }
  
  /* Additional styles for form elements */
  input, textarea, select, button {
    color: #1F2937; /* Ensuring text in inputs and buttons is also readable */
    background-color: #ffffff; /* White background for input fields */
    border: 1px solid #D1D5DB; /* Slightly darker border for inputs */
    border-radius: 0.375rem; /* Rounded corners for inputs */
  }
  
  input:focus, textarea:focus, select:focus {
    border-color: #6B7280; /* Darker border color on focus */
    outline: none; /* Removing default outline */
    box-shadow: 0 0 0 2px rgba(107, 114, 128, 0.5); /* Subtle shadow on focus */
  }
  
  button {
    background-color: #4F46E5; /* Primary button color */
    border: none; /* No border for buttons */
    color: #ffffff; /* White text color for buttons */
    padding: 8px 16px; /* Padding for buttons */
    border-radius: 0.375rem; /* Rounded corners for buttons */
    transition: background-color 0.3s; /* Smooth transition for hover effect */
  }
  
  button:hover {
    background-color: #3730A3; /* Darker shade on hover for buttons */
  }
  </style>
  