<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';

const { props } = usePage();
</script>

<template>

    <Head>
        <title>About Us - Rafflit</title>
        <meta name="description"
            content="Learn about Rafflit, an online raffle platform that connects sellers and buyers. Discover our mission, values, and commitment to providing a secure and enjoyable raffle experience.">
    </Head>

    <component :is="props.auth.user ? AuthenticatedLayout : GuestLayout">
        <div class="min-h-screen bg-gray-100">
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <h1 class="text-3xl font-bold mb-6">About Rafflit</h1>
                            <p class="text-lg mb-8">Rafflit is a leading online raffle platform that connects sellers
                                and buyers, providing a secure and exciting way to participate in raffles for various
                                items. Our mission is to create a trusted and transparent marketplace where individuals
                                and businesses can showcase their products and engage with a wide audience through the
                                thrill of raffles.</p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div>
                                    <h2 class="text-2xl font-bold mb-4">Our Story</h2>
                                    <p class="mb-4">Founded in 2024, Rafflit was born out of a passion for bringing
                                        people together and creating memorable experiences. The idea had been in
                                        development for many years before its official launch. We recognized the
                                        potential of online raffles to not only provide entertainment but also support
                                        businesses and individuals in reaching their goals. With a dedicated team of
                                        professionals, we set out to build a platform that prioritizes user
                                        satisfaction, security, and innovation.</p>
                                    <p>Over the years, Rafflit has grown to become a trusted name in the online raffle
                                        industry. We have successfully facilitated numerous raffles, connecting sellers
                                        with enthusiastic buyers and enabling dreams to come true. Our commitment to
                                        excellence and continuous improvement drives us to deliver the best possible
                                        experience for our users.</p>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold mb-4">Our Values</h2>
                                    <ul class="list-disc list-inside mb-4">
                                        <li>Transparency: We believe in maintaining an open and honest platform,
                                            ensuring that all transactions are fair and above board.</li>
                                        <li>Security: Protecting the privacy and security of our users is of utmost
                                            importance to us. We employ robust measures to safeguard personal
                                            information and transactions.</li>
                                        <li>Innovation: We constantly seek new ways to enhance the raffle experience,
                                            leveraging technology and user feedback to improve our platform.</li>
                                        <li>Community: Rafflit is more than just a platform; it's a community of
                                            passionate individuals who share a love for raffles and the excitement they
                                            bring.</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="mt-12">
                                <h2 class="text-2xl font-bold mb-4">Join the Rafflit Community</h2>
                                <p class="mb-4">Whether you're a seller looking to showcase your products or a buyer
                                    eager to participate in thrilling raffles, Rafflit welcomes you. Join our vibrant
                                    community today and experience the excitement of online raffles like never before.
                                </p>
                                <p>If you have any questions or would like to learn more about Rafflit, please don't
                                    hesitate to <a href="/contact" class="text-blue-500 hover:underline">contact us</a>.
                                    We're here to assist you every step of the way.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </component>
</template>
