<script setup>
import { useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import BlueButton from "@/components/ui/BlueButton.vue";
import { computed } from 'vue';

const props = defineProps({
    child: Array,
    childId: Number,
    gradeId: Number,
    field: String,
    label: {
        type: String,
        default: 'Загрузить файл'
    },
    subLabel: {
        type: String,
        default: '' // пусто, если не передали
    },
    routeName: {
        type: String,
        default: 'documents.upload'
    },
    maxSizeKB: {
        type: Number,
        default: 5120
    }
});

const inputRef = ref(null);
const maxSize = props.maxSizeKB * 1024;
const file = ref(null);
const fileTooLarge = ref(false);
const fileTypeInvalid = ref(false);
const allowedTypes = ['application/pdf', 'image/jpeg', 'image/png'];


const handleFileChange = (event) => {
    const selectedFile = event.target.files[0];
    fileTooLarge.value = false;
    fileTypeInvalid.value = false;
    file.value = null;

    if (!selectedFile) return;
    // Проверка типа файла
    if (!allowedTypes.includes(selectedFile.type)) {
        fileTypeInvalid.value = true;
        return;
    }
    // Проверка размера
    if (selectedFile.size > maxSize) {
        fileTooLarge.value = true;
        return;
    }
    file.value = selectedFile;

    const form = useForm({ file: selectedFile });

    form.post(route(props.routeName, {
        child: props.childId,
        grade: props.gradeId,
        field: props.field
    }), {
        forceFormData: true,
        onSuccess: () => {
            const documentForGrade = props.child.documents?.find(d => d.grade_id === props.gradeId);

            if (documentForGrade) {
                // Обновляем существующее поле
                documentForGrade[props.field] = file.value.name;
            } else {
                // Создаём новый объект для текущего grade_id
                props.child.documents.push({
                    grade_id: props.gradeId,
                    [props.field]: file.value.name
                });
            }
        }
    });
};
const triggerFileInput = () => {
    inputRef.value.click();
};


// const isLoadedFile = computed(() => {
//   return !!props.child.documents?.find(d => d.grade_id === props.gradeId)?.[props.field];
// });
const isLoadedFile = computed(() => {
  const docs = props.child?.documents
  if (!Array.isArray(docs)) return false

  const match = docs.find(d => d.grade_id === props.gradeId)
  return !!match?.[props.field]
});
</script>

<template>
    <div class="flex justify-between">
        <input
            type="file"
            ref="inputRef"
            class="hidden"
            @change="handleFileChange"
            accept=".pdf,.jpg,.png"
        />
        <div class="flex gap-2 items-center mb-2">
            <BlueButton
            @click="triggerFileInput"
            class="flex gap-2"

            >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20" id="Download-File--Streamline-Core-Remix" height="20" width="20">
                <desc>
                    Download File Streamline Icon: https://streamlinehq.com
                </desc>
                <g id="Free Remix/Interface Essential/download-file">
                    <path id="Union" fill="#ffffff" fill-rule="evenodd" d="M3.3711857142857147 1.9426142857142858c0.10047142857142857 -0.10045714285714286 0.23672857142857143 -0.1569 0.37881428571428577 -0.1569h8.737314285714286L16.785714285714285 6.0841142857142865V17.67857142857143c0 0.14214285714285715 -0.05642857142857143 0.2782857142857143 -0.15685714285714286 0.37885714285714284 -0.10057142857142858 0.10042857142857144 -0.2367142857142857 0.15685714285714286 -0.37885714285714284 0.15685714285714286h-12.5c-0.1420857142857143 0 -0.27834285714285717 -0.05642857142857143 -0.37881428571428577 -0.15685714285714286 -0.10045714285714286 -0.10057142857142858 -0.1569 -0.2367142857142857 -0.1569 -0.37885714285714284V2.3214285714285716c0 -0.1420857142857143 0.05644285714285715 -0.27834285714285717 0.1569 -0.37881428571428577ZM3.75 0c-0.6156857142857143 0 -1.2061428571428572 0.24457857142857142 -1.6415 0.67993C1.6731428571428573 1.1152828571428572 1.4285714285714286 1.7057428571428572 1.4285714285714286 2.3214285714285716v15.357142857142858c0 0.6157142857142858 0.24457142857142855 1.2061428571428572 0.6799285714285714 1.6414285714285715 0.4353571428571429 0.43542857142857144 1.0258142857142858 0.6799999999999999 1.6415 0.6799999999999999h12.5c0.6157142857142858 0 1.2061428571428572 -0.24457142857142855 1.6414285714285715 -0.6799999999999999 0.43542857142857144 -0.43528571428571433 0.6799999999999999 -1.0257142857142858 0.6799999999999999 -1.6414285714285715V5.714285714285714c0 -0.23679999999999998 -0.094 -0.46390000000000003 -0.2615714285714286 -0.6313428571428572L13.488485714285716 0.26151142857142856C13.321042857142858 0.0940687142857143 13.093942857142858 0 12.857142857142858 0H3.75Zm5.357142857142858 6.428571428571429 0 4.107142857142858H7.142857142857143c-0.36112857142857147 0 -0.6867 0.21754285714285715 -0.8248857142857143 0.5511714285714285 -0.13820000000000002 0.3336428571428572 -0.06181428571428572 0.7176714285714285 0.19354285714285713 0.9730285714285714l2.857142857142857 2.8570857142857142c0.16744285714285714 0.16757142857142857 0.39454285714285714 0.2615714285714286 0.6313428571428572 0.2615714285714286 0.23679999999999998 0 0.46390000000000003 -0.094 0.6313428571428572 -0.2615714285714286l2.857142857142857 -2.8570857142857142c0.25535714285714284 -0.25535714285714284 0.3317428571428572 -0.6393857142857143 0.19355714285714287 -0.9730285714285714C13.543842857142856 10.753257142857143 13.218271428571427 10.535714285714286 12.857142857142858 10.535714285714286H10.892857142857142l0 -4.107142857142858c0 -0.4931142857142857 -0.3997428571428572 -0.8928571428571429 -0.8928571428571429 -0.8928571428571429s-0.8928571428571429 0.3997428571428572 -0.8928571428571429 0.8928571428571429Z" clip-rule="evenodd" stroke-width="1.4286"></path>
                </g>
            </svg>

                <span class="flex flex-col leading-tight text-left">
                    <span class="whitespace-pre-line">{{ label }}</span>
                    <span v-if="subLabel" class="text-[10px] italic text-red-100">{{ subLabel }}</span>
                </span>
            </BlueButton>
            <div
            v-if="isLoadedFile"
            >
                <span class="bg-green-200 text-green-700 font-semibold rounded px-2 py-1 inline-block">
                    <svg class="w-5 h-5 text-green-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12.5L10 17L19 7" stroke-linecap="square" stroke-linejoin="miter" />
                    </svg>
                </span>
            </div>
        </div>
        <p
        v-if="fileTooLarge"
            class="my-2 text-center text-sm text-red-700 bg-red-100 border border-red-400 rounded-lg px-4 py-2 w-fit mx-auto">
            ⚠️ Размер файла превышает {{ props.maxSizeKB / 1024}} MB
        </p>
        <p
            v-if="fileTypeInvalid"
            class="mt-2 text-center text-sm text-red-700 bg-red-100 border border-red-400 rounded-lg px-4 py-2 w-fit mx-auto"
        >
            ⚠️ Недопустимый формат файла. Разрешены: PDF, JPG, PNG
        </p>
    </div>
</template>

<style scoped>
</style>
