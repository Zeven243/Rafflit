<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';

const { props } = usePage();
</script>

<template>

    <Head>
        <title>Terms and Conditions - Rafflit</title>
        <meta name="description"
            content="Read the terms and conditions of Rafflit, the online raffle platform. Understand the rules, fees, and responsibilities for using our platform.">
    </Head>

    <component :is="props.auth.user ? AuthenticatedLayout : GuestLayout">
        <div class="min-h-screen bg-gray-100">
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <h1 class="text-3xl font-bold mb-6">Terms and Conditions</h1>

                            <div class="mb-8">
                                <h2 class="text-2xl font-bold mb-4">1. Introduction</h2>
                                <p class="mb-4">Welcome to Rafflit.co.za ("Rafflit", "we", "us", "our"). These terms and
                                    conditions ("Terms") govern your use of our website and services. By using our
                                    platform, you agree to comply with these Terms.</p>
                            </div>

                            <div class="mb-8">
                                <h2 class="text-2xl font-bold mb-4">2. Definitions</h2>
                                <ul class="list-disc list-inside mb-4">
                                    <li>"User" refers to any individual or business that registers on Rafflit.</li>
                                    <li>"Seller" refers to a user who lists items for raffle on Rafflit.</li>
                                    <li>"Buyer" refers to a user who purchases raffle tickets or buys out an item.</li>
                                    <li>"Raffle" refers to the process of selling tickets for a chance to win an item.
                                    </li>
                                    <li>"Item" refers to any product listed for raffle on Rafflit.</li>
                                </ul>
                            </div>

                            <div class="mb-8">
                                <h2 class="text-2xl font-bold mb-4">3. User Registration</h2>
                                <p class="mb-4">To use our platform, you must register and provide accurate information.
                                    You must be at least 18 years old to register.</p>
                            </div>

                            <div class="mb-8">
                                <h2 class="text-2xl font-bold mb-4">4. Raffle Rules</h2>
                                <ul class="list-disc list-inside mb-4">
                                    <li>Each raffle will have a specified number of tickets available for purchase.</li>
                                    <li>Users can purchase one or more tickets for a chance to win the item.</li>
                                    <li>Once all tickets are sold, a winner will be randomly selected by our system.
                                    </li>
                                    <li>Users can also buy out an item by purchasing all available tickets.</li>
                                </ul>
                            </div>

                            <div class="mb-8">
                                <h2 class="text-2xl font-bold mb-4">5. Fees and Fundraising</h2>
                                <ul class="list-disc list-inside mb-4">
                                    <li>Rafflit takes a 10% cut from the total cost of each item sold through our
                                        platform as a fundraising activity to support our mission and operations.</li>
                                    <li>This fundraising cut is deducted from the total amount collected from ticket
                                        sales or buyout.</li>
                                </ul>
                            </div>

                            <div class="mb-8">
                                <h2 class="text-2xl font-bold mb-4">6. Item Delivery and Shipping</h2>
                                <ul class="list-disc list-inside mb-4">
                                    <li>Sellers must deliver or ship their items to our office before the raffle is made
                                        available on the platform. Sellers are responsible for all shipping/courier fees
                                        to get the items to our office.</li>
                                    <li>Once we receive the item, it will be listed for raffle.</li>
                                    <li>After a winner is declared, we will ship the item to the winner's specified
                                        shipping address. Buyers/winners are responsible for the shipping fees from our
                                        office to their address.</li>
                                </ul>
                            </div>

                            <div class="mb-8">
                                <h2 class="text-2xl font-bold mb-4">7. Winner Notification and Item Delivery</h2>
                                <ul class="list-disc list-inside mb-4">
                                    <li>Winners will be notified via email.</li>
                                    <li>Items will be shipped from our office to the winner's shipping address.</li>
                                </ul>
                            </div>

                            <div class="mb-8">
                                <h2 class="text-2xl font-bold mb-4">8. User Responsibilities</h2>
                                <ul class="list-disc list-inside mb-4">
                                    <li>Users must provide accurate and up-to-date information.</li>
                                    <li>Users must comply with all applicable laws and regulations.</li>
                                    <li>Sellers must ensure that their items are in good condition and meet the
                                        description provided.</li>
                                </ul>
                            </div>

                            <div class="mb-8">
                                <h2 class="text-2xl font-bold mb-4">9. Prohibited Activities</h2>
                                <ul class="list-disc list-inside mb-4">
                                    <li>Users may not engage in fraudulent activities or misrepresentations.</li>
                                    <li>Users may not use our platform for illegal activities.</li>
                                </ul>
                            </div>

                            <div class="mb-8">
                                <h2 class="text-2xl font-bold mb-4">10. Privacy Policy</h2>
                                <p class="mb-4">We collect and process personal information in accordance with our
                                    Privacy Policy. By using our platform, you consent to the collection and use of your
                                    information as described in our Privacy Policy.</p>
                            </div>

                            <div class="mb-8">
                                <h2 class="text-2xl font-bold mb-4">11. Limitation of Liability</h2>
                                <ul class="list-disc list-inside mb-4">
                                    <li>Rafflit is not liable for any indirect, incidental, or consequential damages
                                        arising from the use of our platform.</li>
                                    <li>Our liability is limited to the amount of fees paid by the user.</li>
                                </ul>
                            </div>

                            <div class="mb-8">
                                <h2 class="text-2xl font-bold mb-4">12. Governing Law</h2>
                                <p class="mb-4">These Terms are governed by the laws of South Africa. Any disputes
                                    arising from these Terms will be subject to the exclusive jurisdiction of the South
                                    African courts.</p>
                            </div>

                            <div class="mb-8">
                                <h2 class="text-2xl font-bold mb-4">13. Changes to Terms and Conditions</h2>
                                <p class="mb-4">We may update these Terms from time to time. Users will be notified of
                                    any changes, and continued use of our platform constitutes acceptance of the updated
                                    Terms.</p>
                            </div>

                            <div>
                                <h2 class="text-2xl font-bold mb-4">14. Contact Information</h2>
                                <p>If you have any questions or concerns about these Terms, please contact us at <a
                                        href="mailto:support@rafflit.co.za"
                                        class="text-blue-500 hover:underline">support@rafflit.co.za</a>.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </component>
</template>
