<template>
    <Welcome>
        <div class="border-b border-gray-200 dark:border-gray-700">
            <div class="flex gap-x-4">
                <div class="basis-1/2 m-4">
                    <h4 class="text-white text-2xl mb-4">Braintree</h4>
                    <form @submit.prevent="submit" id="braintreeForm">
                        <div class="relative z-0 w-full mb-6 group">
                            <input
                                type="tel"
                                name="floating_cardno"
                                id="floating_cardno"
                                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                placeholder=" "
                                required
                            />
                            <label
                                for="floating_cardno"
                                class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                                >Card Number</label
                            >
                        </div>
                        <div class="relative z-0 w-full mb-6 group">
                            <input
                                type="text"
                                name="floating_cvv"
                                id="floating_cvv"
                                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                placeholder=" "
                                required
                            />
                            <label
                                for="floating_cvv"
                                class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                                >CVV</label
                            >
                        </div>
                        <div class="relative z-0 w-full mb-6 group">
                            <input
                                type="text"
                                name="floating_expiry"
                                id="floating_expiry"
                                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                placeholder=" "
                                required
                            />
                            <label
                                for="floating_expiry"
                                class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                                >Expiry Date</label
                            >
                        </div>
                        <div class="relative z-0 w-full mb-6 group">
                            <input
                                type="text"
                                name="floating_postal"
                                id="floating_postal"
                                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                placeholder=" "
                                required
                            />
                            <label
                                for="floating_postal"
                                class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                                >Postal Code</label
                            >
                        </div>

                        <button
                            type="submit"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                        >
                            Purchase
                        </button>
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
import { onMounted, ref } from "vue";
import Welcome from "../../Pages/Welcome.vue";
import Response from "./Partials/Response.vue";
import Shimmer from "@/Components/Shimmer.vue";
import { client, hostedFields } from "braintree-web";

const shimmer = ref(false);

// onMounted(() => {
//     const braintreeScript = document.createElement("script");
//     braintreeScript.setAttribute(
//         "src",
//         "https://js.braintreegateway.com/web/dropin/1.33.7/js/dropin.js"
//     );
//     document.head.appendChild(braintreeScript);
// });

const props = defineProps({
    token: String,
    response: {
        Type: String,
        default: null,
    },
});

var form = document.getElementById("braintreeForm");
// var submit = document.querySelector('input[type="submit"]');

// console.log(form);

// client.create(
//     {
//         authorization: props.token,
//     },
//     function (clientErr, clientInstance) {
//         if (clientErr) {
//             // Handle error in client creation
//             return;
//         }
//         var options = {
//             client: clientInstance,
//             styles: {
//                 /* ... */
//             },
//             fields: {
//                 number: {
//                     container: "#floating_cardno",
//                     placeholder: "4111 1111 1111 1111",
//                 },
//                 cvv: {
//                     container: "#floating_cvv",
//                     placeholder: "123",
//                 },
//                 expirationDate: {
//                     container: "#floating_expiry",
//                     placeholder: "10/2022",
//                 },
//             },
//         };

//         console.log(options);
//         hostedFields.create(
//             options,
//             function (hostedFieldsErr, hostedFieldsInstance) {
//                 if (hostedFieldsErr) {
//                     // Handle error in Hosted Fields creation
//                     return;
//                 }
//                 // submit.removeAttribute('disabled');
//                 if (form) {
//                     form.addEventListener("submit", function (event) {
//                         event.preventDefault();
//                         hostedFieldsInstance.tokenize(function (
//                             tokenizeErr,
//                             payload
//                         ) {
//                             if (tokenizeErr) {
//                                 console.error(tokenizeErr);
//                                 return;
//                             }
//                             console.log("Got a nonce: " + payload.nonce);
//                         });

//                         // Use the Hosted Fields instance here to tokenize a card
//                     });
//                 }
//             },
//             false
//         );
//     }
// );
client
    .create({
        authorization: props.token,
    })
    .then(function (clientInstance) {
        var options = {
            client: clientInstance,
            styles: {
                /* ... */
            },
            fields: {
                number: {
                    container: "#floating_cardno",
                    placeholder: "4111 1111 1111 1111",
                },
                cvv: {
                    container: "#floating_cvv",
                    placeholder: "123",
                },
                expirationDate: {
                    container: "#floating_expiry",
                    placeholder: "10/2022",
                },
            },
        };

        return hostedFields.create(options);
    })
    .then(function (hostedFieldsInstance) {
        form.addEventListener(
            "submit",
            function (event) {
                event.preventDefault();

                hostedFieldsInstance.tokenize(function (tokenizeErr, payload) {
                    if (tokenizeErr) {
                        console.error(tokenizeErr);
                        return;
                    }

                    // If this was a real integration, this is where you would
                    // send the nonce to your server.
                    console.log("Got a nonce: " + payload.nonce);
                });
            },
            false
        );
    })
    .catch(function (err) {
        // Handle error in component creation
    });
</script>
