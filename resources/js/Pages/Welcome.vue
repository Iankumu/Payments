<script setup>
import SideBar from "../Components/SideBar.vue";
import { Head } from "@inertiajs/vue3";

</script>

<template>
    <Head title="Payments" />

    <div class="flex min-h-screen w-full dark:bg-gray-900 font-sans">
        <SideBar />
        <main class="flex min-h-screen flex-1 flex-col rounded-l-[48px] p-8">
            <div v-if="$page.url === '/'">
                <h2 class="text-white text-2xl">Payment Gateways</h2>
                <div>
                    <p
                        class="text-gray-400 items-center justify-center p-8 text-lg"
                    >
                        A simple Project Containing Implementation of Various
                        Payment Gateways in Laravel
                    </p>
                    <div class="flex gap-x-4">
                        <div
                            class="basis-1/2 shadow-lg bg-gray-700 w-auto place-items-center grid h-[300px]"
                        >
                            <span class="text-white text-lg">Mpesa</span>
                            <p class="text-white">
                                A popular payment Gateway in Kenya
                            </p>
                            <img
                                src="/img/mpesa.png"
                                alt="Mpesa"
                                width="200px"
                                height="200px"
                            />
                        </div>
                        <div
                            class="basis-1/2 shadow-lg bg-gray-700 w-auto place-items-center grid h-[300px]"
                        >
                            <span class="text-white text-lg">Paypal</span>
                            <p class="text-white">
                                A popular payment Gateway Worldwide
                            </p>
                            <img
                                src="/img/paypal.png"
                                alt="Paypal"
                                width="300px"
                                height="300px"
                            />
                        </div>
                    </div>
                </div>
            </div>
            <slot />
        </main>
    </div>
</template>
