<script setup>
import { Link } from "@inertiajs/inertia-vue3";

const menu = [
    { name: "Mpesa", link: "/mpesa/stkpush" },
    { name: "Paypal", link: "/paypal" },
    // { name: "Braintree", link: "/braintree" },
];
</script>

<template>
    <aside class="flex w-64 flex-col px-4 pt-10 pb-6">
        <Link
            href="/"
            class="flex gap-x-4 px-8 focus:outline-none items-center text-2xl font-medium text-white"
        >
            <span class="text-lg font-bold">Payments</span>
        </Link>
        <ul class="flex flex-1 flex-col gap-y-10 px-8 pt-14">
            <li v-for="item in menu" :key="item.name">
                <Link
                    :href="item.link"
                    class="flex items-center gap-x-4 text-gray-400 hover:font-medium hover:text-white focus:font-medium focus:text-white focus:outline-none"
                >
                    <span>{{ item.name }}</span>
                </Link>
            </li>
        </ul>
    </aside>
</template>
