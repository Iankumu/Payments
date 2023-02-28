<template>
    <div
        class="w-auto p-6 bg-white border h-[480px] border-gray-200 rounded-lg shadow-md dark:bg-gray-800 dark:border-gray-700"
    >
        <h2 class="items-center text-white text-2xl">Response</h2>

        <pre
            v-if="text !== null"
            class="mb-3 font-normal text-gray-500 dark:text-gray-400"
        >

            {{ JSON.stringify(text) }}

        </pre>

        <p v-else class="mb-3 font-normal text-gray-500 dark:text-gray-400">
            Send a Request to View the Response Here
        </p>
    </div>
</template>

<script setup>
defineProps({
    text: {
        Type: String,
        default: null,
    },
});
</script>

<style scoped>

pre{
    white-space: pre-wrap;
}

</style>
