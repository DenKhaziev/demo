<script setup>
import {computed} from "vue";

const props = defineProps({
    currentPage: {
        type: Number,
        required: true
    },
    totalPages: {
        type: Number,
        required: true
    },
    windowSize: {
        type: Number,
        default: 2 // Сколько страниц показывать рядом с текущей
    }
})

const emit = defineEmits(['change'])

const changePage = (page) => {
    if (page < 1 || page > props.totalPages) return
    emit('change', page)
}

const pages = computed(() => {
    const pages = []

    for (let i = 1; i <= props.totalPages; i++) {

        // Всегда показываем 1 и последнюю
        if (i === 1 || i === props.totalPages) {
            pages.push(i)
            continue
        }

        // Показываем страницы рядом с текущей
        if (Math.abs(props.currentPage - i) <= props.windowSize) {
            pages.push(i)
            continue
        }

        // Добавляем "..." если предыдущий элемент не был "..."
        if (pages[pages.length - 1] !== '...') {
            pages.push('...')
        }
    }

    return pages
})
</script>

<template>
    <div class="flex justify-center mt-6 space-x-1 select-none">

        <!-- Prev Button -->
        <button
            :disabled="currentPage === 1"
            @click="() => changePage(currentPage - 1)"
            class="px-3 py-2 border rounded hover:bg-gray-100 disabled:bg-gray-200 disabled:text-gray-400"
        >
            ←
        </button>

        <!-- Pages and Dots -->
        <template v-for="(page, index) in pages" :key="index">
            <span
                v-if="page === '...'"
                class="px-3 py-2"
            >
                ...
            </span>

            <button
                v-else
                @click="() => changePage(page)"
                class="px-3 py-2 border rounded hover:bg-gray-100"
                :class="{
                    'bg-blue-500 text-white font-bold': currentPage === page
                }"
            >
                {{ page }}
            </button>
        </template>

        <!-- Next Button -->
        <button
            :disabled="currentPage === totalPages"
            @click="() => changePage(currentPage + 1)"
            class="px-3 py-2 border rounded hover:bg-gray-100 disabled:bg-gray-200 disabled:text-gray-400"
        >
            →
        </button>
    </div>
</template>
