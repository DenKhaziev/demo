<script setup>
import {router} from '@inertiajs/vue3'
import {computed} from "vue";

const props = defineProps({
    links: Array
})

const visit = (url) => {
    if (!url) return
    router.visit(url, {
        preserveScroll: true,
        preserveState: true,
    })
}
const uniqueLinks = computed(() => {
    const seen = new Set()
    return props.links.filter(link => {
        const id = `${String(link.label)}-${link.url ?? 'empty'}`
        if (seen.has(id)) {
            return false
        }
        seen.add(id)
        return true
    })
})
</script>

<template>
    <div class="flex justify-center mt-6 space-x-1 select-none">
        <button
            v-for="(link, index) in uniqueLinks"
            :key="link.url ?? index"
            :disabled="!link.url"
            @click="() => visit(link.url)"
            class="px-3 py-1 border rounded"
            :class="{
                'bg-gray-300 text-gray-500 cursor-not-allowed': !link.url,
                'bg-blue-500 text-white font-bold': link.active,
                'hover:bg-blue-200': link.url && !link.active
            }"
        >
            <!-- Стрелки вместо Previous и Next -->
            <span v-if="link.label.includes('Previous')">←</span>
            <span v-else-if="link.label.includes('Next')">→</span>
            <span v-else v-html="link.label"/>
        </button>
    </div>
</template>
