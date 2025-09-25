<script setup>

import {Link} from "@inertiajs/vue3";
const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    label: String

});
</script>

<template>
    <button class="mt-3.5">
        <Link
            :href="href"
            class="px-2 py-2.5 bg-orange-500 text-white rounded hover:bg-orange-700 transition-colors font-semibold">
<!--            <slot/>-->
            {{label}}
        </Link>
    </button>
</template>

<style scoped>

</style>
