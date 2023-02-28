<template>
    <Welcome>
        <div
            class="border-b border-gray-200 dark:border-gray-700 flex flex-col"
        >
            <h3 class="text-white text-2xl">Paypal</h3>
            <div class="">
                <p class="text-white p-4">
                    This Demo utilizes Paypal's
                    <a
                        class="text-blue-500"
                        href="https://developer.paypal.com/docs/api/orders/v2/"
                        >Orders API</a
                    >
                </p>
            </div>
            <div class="flex gap-x-4 flex-row">
                <div class="basis-1/2 m-4">
                    <form @submit.prevent="submit">
                        <div class="relative z-0 w-full mb-6 group">
                            <input
                                type="number"
                                name="floating_amount"
                                id="floating_amount"
                                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                placeholder=" "
                                v-model="amount"
                                required
                            />
                            <label
                                for="floating_amount"
                                class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                                >Amount ($100)</label
                            >
                        </div>
                        <div id="paypal-button-container"></div>
                    </form>
                </div>
                <div class="basis-1/2 m-4">
                    <Shimmer v-if="shimmer" />
                    <Response v-else :text="response" />
                </div>
            </div>
        </div>
    </Welcome>
</template>

<script setup>
import Welcome from "../Welcome.vue";
import { loadScript } from "@paypal/paypal-js";

import Shimmer from "../../Components/Shimmer.vue";
import Response from "./Partials/Response.vue";
import { ref } from "vue";
import { router } from "@inertiajs/vue3";


const props = defineProps({
    response: {
        Type: String,
        default: null,
    },
});

const amount = ref("");
const shimmer = ref(false);

loadScript({
    "client-id": import.meta.env.VITE_PAYPAL_CLIENT_ID,
}).then((paypal) => {
    paypal
        .Buttons({
            env: import.meta.env
                .VITE_PAYPAL_ENVIRONMENT /* sandbox | production */,
            style: {
                layout: "horizontal", // horizontal | vertical
                size: "responsive" /* medium | large | responsive*/,
                shape: "pill" /* pill | rect*/,
                color: "gold" /* gold | blue | silver | black*/,
                fundingicons: false /* true | false */,
                tagline: false /* true | false */,
                label: "pay",
            },

            /* createOrder() is called when the button is clicked */

            createOrder: function (data, actions) {
                /* Create the order and set up the payment */
                shimmer.value = true;
                return actions.order.create({
                    purchase_units: [
                        {
                            amount: {
                                value: amount.value,
                            },
                        },
                    ],
                });
            },

            onApprove: function (data, actions) {
                /* Set up a url on your server to execute the payment */

                var EXECUTE_URL =
                    import.meta.env.VITE_APP_URL +
                    "api/paypal/transaction";

                router.post(EXECUTE_URL,{token : data.orderID});
                router.on("success", () => {
                    shimmer.value = false;
                });
            },
        })
        .render("#paypal-button-container");
});
</script>
