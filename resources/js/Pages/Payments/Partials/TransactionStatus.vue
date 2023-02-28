<template>
    <Mpesa>
        <div class="flex gap-x-4 flex-row">
            <div class="basis-1/2 m-4">
                <h4 class="text-white text-2xl mb-4">Transaction Status</h4>
                <form @submit.prevent="submit">
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
                            type="text"
                            name="floating_transactionid"
                            id="floating_transactionid"
                            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                            placeholder=" "
                            v-model="form.transactionid"
                            required
                        />
                        <label
                            for="floating_transactionid"
                            class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                            >Transaction ID</label
                        >
                    </div>
                    <div class="relative z-0 w-full mb-6 group flex flex-col">
                        <label for="command" class="text-white text-sm my-2"
                            >Identifier Type</label
                        >
                        <Select @change="onSelectChange($event)">
                            <option :value="null" selected>
                                Select A Type of Organization
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
                            name="floating_remarks"
                            id="floating_remarks"
                            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                            placeholder=" "
                            v-model="form.remarks"
                            required
                        />
                        <label
                            for="floating_remarks"
                            class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                            >Remarks</label
                        >
                    </div>

                    <button
                        type="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                    >
                        Send Request
                    </button>
                </form>
            </div>
            <div class="basis-1/2 m-4">
                <Shimmer v-if="shimmer" />
                <Response v-else :text="response" />
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

const form = reactive({
    shortcode: "",
    identiertype: "",
    transactionid: "",
    remarks: "",
});

const options = [
    { value: "1", id: 1, name: "Phone Number" },
    { value: "2", id: 2, name: "Till Number" },
    { value: "4", id: 3, name: "Organization short code(Paybill)" },
];
defineProps({
    response: {
        Type: String,
        default: null,
    },
});

function submit() {
    shimmer.value = true;
    router.post("/transaction-status", form);
    router.on("success", () => {
        shimmer.value = false;
    });
}
function onSelectChange(e) {
    var identiertype = e.target.value;

    form.identiertype = identiertype;
}
</script>
