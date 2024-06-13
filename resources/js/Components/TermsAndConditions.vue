<template>
    <div v-if="showModal" class="fixed z-10 inset-0 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-headline">
                                Terms and Conditions
                            </h3>
                            <div class="mt-2 text-sm text-gray-500" style="max-height: 400px; overflow-y: auto;">
                                <h4 class="text-lg font-semibold">Terms and Conditions</h4>
                                <p><strong>1. Introduction</strong><br>Welcome to Rafflit.co.za ("Rafflit", "we", "us",
                                    "our"). These
                                    terms and conditions ("Terms") govern your use of our website and services. By using
                                    our platform,
                                    you agree to comply with these Terms.</p>
                                <p><strong>2. Definitions</strong><br>"User" refers to any individual or business that
                                    registers on
                                    Rafflit.<br>"Seller" refers to a user who lists items for raffle on
                                    Rafflit.<br>"Buyer" refers to a
                                    user who purchases raffle tickets or buys out an item.<br>"Raffle" refers to the
                                    process of selling
                                    tickets for a chance to win an item.<br>"Item" refers to any product listed for
                                    raffle on Rafflit.
                                </p>
                                <p><strong>3. User Registration</strong><br>To use our platform, you must register and
                                    provide
                                    accurate information. You must be at least 18 years old to register.</p>
                                <p><strong>4. Raffle Rules</strong><br>Each raffle will have a specified number of
                                    tickets available
                                    for purchase.<br>Users can purchase one or more tickets for a chance to win the
                                    item.<br>Once all
                                    tickets are sold, a winner will be randomly selected by our system.<br>Users can
                                    also buy out an
                                    item by purchasing all available tickets.</p>
                                <p><strong>5. Fees and Fundraising</strong><br>Rafflit takes a 10% cut from the total
                                    cost of each
                                    item sold through our platform as a fundraising activity to support our mission and
                                    operations.<br>This fundraising cut is deducted from the total amount collected from
                                    ticket sales or
                                    buyout.</p>
                                <p><strong>6. Item Delivery and Shipping</strong><br>
                                    Rafflit is not responsible for the collection or delivering of any items being sold on the site.<br>
                                    The delivery and/or pickup of items after a winner has been declared is solely between the winner and the seller.<br>
                                    The winner should be responsible for arranging delivery with the seller and or pickup.<br>
                                    Buyers and Sellers will be notified via email if a winner is selected by our system.<br>
                                    As a security measure towards both parties, we hold the sellers money until the winner/buyer has declared that they have received the item by clicking the link in the email sent to them when they win.<br>
                                    If the winner/buyer fails to update us by clicking on the link after 7 days the sellers money will be paid out to them which after Rafflit holds no responsibility of the item etc.

                                </p>
                                <p><strong>7. Winner Notification and Item Delivery</strong><br>
                                    Winners will be notified via email. Sellers are responsible for shipping the item
                                    directly to the winner's shipping address. Buyers are responsible for providing a
                                    valid shipping address and paying the shipping cost. Sellers will receive their
                                    payment once the item has been successfully delivered to the buyer and delivery
                                    confirmation has been obtained.</p>
                                <p><strong>8. User Responsibilities</strong><br>Users must provide accurate and
                                    up-to-date
                                    information.<br>Users must comply with all applicable laws and
                                    regulations.<br>Sellers must ensure
                                    that their items are in good condition and meet the description provided.</p>
                                <p><strong>9. Prohibited Activities</strong><br>Users may not engage in fraudulent
                                    activities or
                                    misrepresentations.<br>Users may not use our platform for illegal activities.</p>
                                <p><strong>10. Privacy Policy</strong><br>We collect and process personal information in
                                    accordance
                                    with our Privacy Policy.<br>By using our platform, you consent to the collection and
                                    use of your
                                    information as described in our Privacy Policy.</p>
                                <p><strong>11. Limitation of Liability</strong><br>Rafflit is not liable for any
                                    indirect, incidental,
                                    or consequential damages arising from the use of our platform.<br>Our liability is
                                    limited to the
                                    amount of fees paid by the user.</p>
                                <p><strong>12. Governing Law</strong><br>These Terms are governed by the laws of South
                                    Africa. Any
                                    disputes arising from these Terms will be subject to the exclusive jurisdiction of
                                    the South African
                                    courts.</p>
                                <p><strong>13. Changes to Terms and Conditions</strong><br>We may update these Terms
                                    from time to
                                    time. Users will be notified of any changes, and continued use of our platform
                                    constitutes
                                    acceptance of the updated Terms.</p>
                                <p><strong>14. Contact Information</strong><br>If you have any questions or concerns
                                    about these
                                    Terms, please contact us at support@rafflit.co.za.</p>
                                <h4 class="text-lg font-semibold">Privacy Policy</h4>
                                <p><strong>1. Introduction</strong><br>This Privacy Policy explains how we collect, use,
                                    and protect
                                    your personal information.</p>
                                <p><strong>2. Information We Collect</strong><br>Personal information provided during
                                    registration
                                    (e.g., name, email, address).<br>Payment information for transactions.<br>Usage data
                                    and cookies.
                                </p>
                                <p><strong>3. How We Use Your Information</strong><br>To provide and improve our
                                    services.<br>To
                                    process transactions and send notifications.<br>To comply with legal obligations.
                                </p>
                                <p><strong>4. Data Security</strong><br>We implement security measures to protect your
                                    information.
                                    However, no method of transmission over the internet is completely secure.</p>
                                <p><strong>5. Your Rights</strong><br>You have the right to access, update, and delete
                                    your personal
                                    information.<br>You can opt-out of marketing communications.</p>
                                <p><strong>6. Changes to Privacy Policy</strong><br>We may update this Privacy Policy
                                    from time to
                                    time. Users will be notified of any changes.</p>
                                <p><strong>7. Contact Information</strong><br>If you have any questions about this
                                    Privacy Policy,
                                    please contact us at Support@rafflit.co.za.</p>
                                <h4 class="text-lg font-semibold">Rules</h4>
                                <p><strong>1. Eligibility</strong><br>Users must be at least 18 years old to participate
                                    in
                                    raffles.<br>Users must comply with all applicable laws and regulations.</p>
                                <p><strong>2. Raffle Participation</strong><br>Users can purchase tickets for a chance
                                    to win an
                                    item.<br>Users can buy out an item by purchasing all available tickets.</p>
                                <p><strong>3. Winner Selection</strong><br>Winners are randomly selected by our
                                    system.<br>Winners
                                    will be notified via email.</p>
                                <p><strong>4. Item Delivery</strong><br>Items will be shipped to the winner's specified
                                    shipping
                                    address. Buyers/winners are responsible for the shipping fees from our office to
                                    their address.</p>
                                <p><strong>5. Prohibited Activities</strong><br>Users may not engage in fraudulent
                                    activities or
                                    misrepresentations.<br>Users may not use our platform for illegal activities.</p>
                                <p>By using Rafflit, you agree to comply with these rules.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
                        @click="$emit('close-modal')">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        showModal: {
            type: Boolean,
            required: true,
        },
    },
    emits: ['close-modal'],
}
</script>