<template>
    <Welcome>
        <div class="border-b border-gray-200 dark:border-gray-700">
            <ul
                class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500 dark:text-gray-400"
            >
                <li class="mr-2" v-for="api in mpesa" :key="api.name">
                    <Link
                        :href="api.link"
                        :class="
                            $page.url === api.link
                                ? 'inline-flex p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg dark:text-blue-500 dark:border-blue-500 group'
                                : 'inline-flex p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group'
                        "
                    >
                        <svg
                            aria-hidden="true"
                            :class="
                                $page.url === api.link
                                    ? 'w-5 h-5 mr-2 text-blue-600 dark:text-blue-500'
                                    : 'w-5 h-5 mr-2 text-gray-400 group-hover:text-gray-500 dark:text-gray-500 dark:group-hover:text-gray-300'
                            "
                            fill="currentColor"
                            viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                d="M5 4a1 1 0 00-2 0v7.268a2 2 0 000 3.464V16a1 1 0 102 0v-1.268a2 2 0 000-3.464V4zM11 4a1 1 0 10-2 0v1.268a2 2 0 000 3.464V16a1 1 0 102 0V8.732a2 2 0 000-3.464V4zM16 3a1 1 0 011 1v7.268a2 2 0 010 3.464V16a1 1 0 11-2 0v-1.268a2 2 0 010-3.464V4a1 1 0 011-1z"
                            ></path>
                        </svg>
                        {{ api.name }}
                    </Link>
                </li>
            </ul>
        </div>

        <slot></slot>
    </Welcome>
</template>

<script setup>
import Welcome from "../../Pages/Welcome.vue";
import { Link } from "@inertiajs/vue3";

import { ref } from "vue";

const mpesa = [
    { name: "STKPUSH", link: "/mpesa/stkpush" },
    { name: "C2B", link: "/mpesa/c2b" },
    { name: "B2C", link: "/mpesa/b2c" },
    { name: "Account Balance", link: "/mpesa/balance" },
    { name: "Transaction Status", link: "/mpesa/status" },
    { name: "Reversals", link: "/mpesa/reversals" },
];
</script>
