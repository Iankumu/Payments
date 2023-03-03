<template>
    <div
        class="w-auto p-6 bg-white border h-[480px] border-gray-200 rounded-lg shadow-md dark:bg-gray-800 dark:border-gray-700"
    >
        <h2 class="items-center text-white text-2xl">Response</h2>
        <vue-json-pretty
            v-if="text !== null"
            class="mb-3 font-normal text-gray-500 dark:text-gray-400"
            :data="text"
            :show-icon="true"
            :show-double-quotes="true"
            :show-line="false"
            :collapsed-on-click-brackets="true"
            :show-length="true"
        >
        </vue-json-pretty>
        <p v-else class="mb-3 font-normal text-gray-500 dark:text-gray-400">
            Send a Request to View the Response Here
        </p>
    </div>
</template>

<script setup>
import VueJsonPretty from 'vue-json-pretty';
import 'vue-json-pretty/lib/styles.css';
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
