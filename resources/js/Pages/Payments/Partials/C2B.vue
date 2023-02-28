<template>
    <Mpesa>
        <div class="flex flex-col">
            <div class="flex gap-x-4 flex-row">
                <div class="basis-1/2 m-4">
                    <div class="">
                        <h4 class="text-white text-2xl mb-4">
                            Register C2B URLS
                        </h4>
                        <form @submit.prevent="submiturls">
                            <div class="relative z-0 w-full mb-6 group">
                                <input
                                    type="text"
                                    name="floating_shortcode"
                                    id="floating_shortcode"
                                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder=" "
                                    v-model="urlform.shortcode"
                                    required
                                />
                                <label
                                    for="floating_shortcode"
                                    class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                                    >Shortcode (Paybill or Till Number)</label
                                >
                            </div>

                            <button
                                type="submit"
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                            >
                                Register URLS
                            </button>
                        </form>
                    </div>

                    <hr class="m-8 border-spacing-2" />

                    <div class="mt-4">
                        <h4 class="text-white text-2xl mb-4">
                            Simulate C2B API
                        </h4>
                        <form @submit.prevent="submit" class="mt-4">
                            <div class="relative z-0 w-full mb-6 group">
                                <input
                                    type="text"
                                    name="floating_shortcode"
                                    id="floating_shortcode"
                                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder=" "
                                    v-model="form.shortcode"
                                    required
                                />
                                <label
                                    for="floating_shortcode"
                                    class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                                    >Shortcode (Paybill or Till Number)</label
                                >
                            </div>
                            <div class="relative z-0 w-full mb-6 group">
                                <input
                                    type="tel"
                                    name="floating_phone"
                                    id="floating_phone"
                                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder=" "
                                    v-model="form.phonenumber"
                                    required
                                />
                                <label
                                    for="floating_phone"
                                    class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                                    >Phone number (07-456-7890)</label
                                >
                            </div>
                            <div class="relative z-0 w-full mb-6 group">
                                <input
                                    type="text"
                                    name="floating_amount"
                                    id="floating_amount"
                                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder=" "
                                    v-model="form.amount"
                                    required
                                />
                                <label
                                    for="floating_amount"
                                    class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                                    >Amount (KES 100)</label
                                >
                            </div>
                            <div
                                class="relative z-0 w-full mb-6 group flex flex-col"
                            >
                                <label
                                    for="command"
                                    class="text-white text-sm my-2"
                                    >Transaction Type</label
                                >
                                <Select @change="onSelectChange($event)">
                                    <option :value="null" selected>
                                        Select A Transaction Type
                                    </option>
                                    <option
                                        v-for="option in options"
                                        :key="option.id"
                                        :value="option.value"
                                    >
                                        {{ option.name }}
                                    </option>
                                </Select>
                            </div>
                            <div class="relative z-0 w-full mb-6 group">
                                <input
                                    type="text"
                                    name="floating_accountno"
                                    id="floating_accountno"
                                    class="py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    :class="type ? 'block' : 'hidden'"
                                    placeholder=" "
                                    v-model="form.account"
                                />
                                <label
                                    for="floating_accountno"
                                    class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                                    :class="type ? 'block' : 'hidden'"
                                    >Account Number(For Paybill)</label
                                >
                            </div>

                            <button
                                type="submit"
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                            >
                                Simulate C2B
                            </button>
                        </form>
                    </div>
                </div>

                <div class="basis-1/2 m-4">
                    <Shimmer v-if="shimmer" />
                    <Response v-else :text="response" />
                </div>
            </div>
        </div>
    </Mpesa>
</template>

<script setup>
import Mpesa from "../Mpesa.vue";
import Response from "./Response.vue";
import Shimmer from "../../../Components/Shimmer.vue";
import { ref, reactive } from "vue";
import { router } from "@inertiajs/vue3";

const shimmer = ref(false);

const type = ref(false);

const form = reactive({
    shortcode: "",
    amount: "",
    account: null,
    command: "",
    phonenumber: "",
});
const urlform = reactive({
    shortcode: "",
});

defineProps({
    response: {
        Type: String,
        default: null,
    },
});

const options = [
    { value: "CustomerBuyGoodsOnline", id: 1, name: "Buy Goods" },
    { value: "CustomerPayBillOnline", id: 2, name: "Pay Bill" },
];

function submiturls() {
    shimmer.value = true;
    router.post("/register-urls", urlform);
    router.on("success", () => {
        shimmer.value = false;
    });
}

function submit() {
    shimmer.value = true;
    router.post("/c2b/simulate", form);
    router.on("success", () => {
        shimmer.value = false;
    });
}

function onSelectChange(e) {
    var command = e.target.value;

    form.command = command;
    if (command === "CustomerPayBillOnline") {
        type.value = true;
    } else {
        type.value = false;
    }

    console.log(command);
}
</script>
