<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';

const { props } = usePage();

const form = useForm({
    name: '',
    email: '',
    message: '',
});

const submit = () => {
    form.post(route('contact.submit'), {
        onFinish: () => form.reset(),
    });
};
</script>

<template>

    <Head>
        <title>Contact Us - Rafflit</title>
        <meta name="description"
            content="Get in touch with Rafflit, the online raffle platform. Contact us for any inquiries, support, or feedback.">
    </Head>

    <component :is="props.auth.user ? AuthenticatedLayout : GuestLayout">
        <div class="min-h-screen bg-gray-100">
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <h1 class="text-3xl font-bold mb-6">Contact Us</h1>
                            <p class="mb-8">If you have any questions, inquiries, or feedback, please don't hesitate to
                                reach out to us. We're here to assist you!</p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div>
                                    <h2 class="text-2xl font-bold mb-4">Contact Information</h2>
                                    <p class="mb-4">
                                        Rafflit<br>
                                        51 Killarney Street, Oakdale, Bellville<br>
                                        Cape Town, South Africa<br>
                                        Phone: +27 069 720 8308<br>
                                        Email: <a href="mailto:info@rafflit.co.za"
                                            class="text-blue-500 hover:underline">info@rafflit.co.za</a>
                                    </p>
                                    <p>Our customer support team is available Monday to Friday, from 9:00 AM to 5:00 PM
                                        (SAST).</p>
                                </div>

                                <div>
                                    <h2 class="text-2xl font-bold mb-4">Send Us a Message</h2>
                                    <form @submit.prevent="submit">
                                        <div class="mb-4">
                                            <label for="name"
                                                class="block text-gray-700 text-sm font-bold mb-2">Name</label>
                                            <input type="text" id="name" v-model="form.name"
                                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                                required>
                                        </div>
                                        <div class="mb-4">
                                            <label for="email"
                                                class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                                            <input type="email" id="email" v-model="form.email"
                                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                                required>
                                        </div>
                                        <div class="mb-4">
                                            <label for="message"
                                                class="block text-gray-700 text-sm font-bold mb-2">Message</label>
                                            <textarea id="message" v-model="form.message"
                                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                                rows="4" required></textarea>
                                        </div>
                                        <div>
                                            <button type="submit"
                                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                                Send Message
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </component>
</template>
