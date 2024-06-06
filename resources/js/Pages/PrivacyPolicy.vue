<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';

const { props } = usePage();
</script>

<template>

    <Head>
        <title>Privacy Policy - Rafflit</title>
        <meta name="description"
            content="Read the privacy policy of Rafflit, the online raffle platform. Learn how we collect, use, and protect your personal information.">
    </Head>

    <component :is="props.auth.user ? AuthenticatedLayout : GuestLayout">
        <div class="min-h-screen bg-gray-100">
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <h1 class="text-3xl font-bold mb-6">Privacy Policy</h1>

                            <div class="mb-8">
                                <h2 class="text-2xl font-bold mb-4">1. Introduction</h2>
                                <p class="mb-4">This Privacy Policy explains how we collect, use, and protect your
                                    personal information.</p>
                            </div>

                            <div class="mb-8">
                                <h2 class="text-2xl font-bold mb-4">2. Information We Collect</h2>
                                <ul class="list-disc list-inside mb-4">
                                    <li>Personal information provided during registration (e.g., name, email, address).
                                    </li>
                                    <li>Payment information for transactions.</li>
                                    <li>Usage data and cookies.</li>
                                </ul>
                            </div>

                            <div class="mb-8">
                                <h2 class="text-2xl font-bold mb-4">3. How We Use Your Information</h2>
                                <ul class="list-disc list-inside mb-4">
                                    <li>To provide and improve our services.</li>
                                    <li>To process transactions and send notifications.</li>
                                    <li>To comply with legal obligations.</li>
                                </ul>
                            </div>

                            <div class="mb-8">
                                <h2 class="text-2xl font-bold mb-4">4. Data Security</h2>
                                <p class="mb-4">We implement security measures to protect your information. However, no
                                    method of transmission over the internet is completely secure.</p>
                            </div>

                            <div class="mb-8">
                                <h2 class="text-2xl font-bold mb-4">5. Your Rights</h2>
                                <ul class="list-disc list-inside mb-4">
                                    <li>You have the right to access, update, and delete your personal information.</li>
                                    <li>You can opt-out of marketing communications.</li>
                                </ul>
                            </div>

                            <div class="mb-8">
                                <h2 class="text-2xl font-bold mb-4">6. Changes to Privacy Policy</h2>
                                <p class="mb-4">We may update this Privacy Policy from time to time. Users will be
                                    notified of any changes.</p>
                            </div>

                            <div>
                                <h2 class="text-2xl font-bold mb-4">7. Contact Information</h2>
                                <p>If you have any questions about this Privacy Policy, please contact us at <a
                                        href="mailto:privacy@rafflit.co.za"
                                        class="text-blue-500 hover:underline">privacy@rafflit.co.za</a>.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </component>
</template>
