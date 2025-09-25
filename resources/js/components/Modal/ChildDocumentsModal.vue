<script setup>
import {computed, ref, toRef} from "vue";
import {useModalBehavior} from "@/composables/useModalBehavior.js";


const props = defineProps({
    title: String,
    documents: Array,
    show: Boolean
});
const emit = defineEmits(['close']);
const modalRef = ref(null);
const showRef = toRef(props, 'show');
useModalBehavior(showRef, emit );

const hasAnyDocument = computed(() =>
    Object.values(props.documents).some(docs =>
        docs.some(doc => !!doc.link)
    )
);

</script>

<template>
    <transition name="fade">
        <div
            v-if="show"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center backdrop-blur-sm z-40"
            @click="$emit('close')"
        >
            <div
                class="bg-white rounded-lg w-[600px] max-h-[80vh] overflow-y-auto shadow-lg p-6"
                ref="modalRef"
                @click.stop
            >
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold">{{ title }}</h2>
                    <button @click="$emit('close')" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
                </div>

                <div v-if="hasAnyDocument">
                    <div v-for="(docs, gradeId) in documents" :key="gradeId" class="mb-6">
                        <h3 class="block mx-auto text-lg font-semibold mb-2 text-[#800020] text-center">Класс: {{ gradeId }}</h3>
                        <ul>
                            <li v-for="(doc, index) in docs" :key="index" class="mb-2 grid place-items-center inline-block">
                                <a
                                    v-if="doc.link"
                                    :href="doc.link"
                                    target="_blank"
                                    class="px-4 py-2 text-sm font-semibold text-gray-800 border rounded-lg
                                    bg-transparent hover:bg-gray-700 hover:text-white shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-gray-600/50"
                                >
                                    {{ doc.label }}
                                </a>
                                <span v-else class="text-gray-400">{{ doc.label }} — не загружено</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div v-else class="text-center text-gray-500 text-xl py-8 font-semibold">
                    Нет загруженных документов
                </div>
            </div>
           
        </div>
    </transition>
</template>


<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
