<script setup>
const documents = [
    { name: 'Согласие на обработку персональных данных родителя/законного представителя несовершеннолетнего обучающегося или совершеннолетнего обучающегося', file: 'soglasie_OPD.pdf' },
    { name: 'Реквизиты для оплаты', file: 'Rekvizity_IP_2025.pdf' },
    { name: 'Образец заполнения платёжного поручения за сопровождение промежуточной аттестации (ПА)', file: 'obrazetc_pp_PA_2025.pdf' },
    { name: 'Образец заполнения платёжного поручения за сопровождение государственной итоговой аттестации (ГИА)', file: 'obrazetc_pp_GIA_2025.pdf' },
    { name: 'Положение об организации прохождения аттестации обучающимися, получающими образование в форме семейного образования и самообразования', file: 'polozh_ob_organiz_prohozhd_attestatcii_semeyn_i_samoobr_02.09.2024.pdf' },
    { name: 'Договор-оферта об организации подготовки к прохождению промежуточной аттестации обучающегося, получающего образование в форме семейного образования/самообразования', file: 'oferta_PA.pdf' },
    { name: 'Договор-оферта об организации подготовки к прохождению государственной итоговой аттестации обучающегося, получающего образование в форме семейного образования/самообразования', file: 'oferta_GIA.pdf' },
    { name: 'Договор о сотрудничестве', file: 'dogovor_o_sotrud.pdf' },
    { name: 'Учебный план. Начальное общее образование 2025-2029', file: 'study_map_elementary.pdf' },
    { name: 'Учебный план. Основное общее образование 2025-2026', file: 'study_map_main.pdf' },
    { name: 'Учебный план. Среднее общее образование 2025-2027', file: 'study_map_basic.pdf' },
]

function downloadFile(filename) {
    window.open(route('documents.download', { file: filename }), '_blank')
}
</script>
<template>
    <div class="container mx-auto">
        <ul class="space-y-3">
            <li v-for="doc in documents" :key="doc.file" class="flex items-center gap-2 mb-4">
                <span class="text-[#800020] mt-1">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-9 h-9 fill-current" viewBox="0 0 24 24"><path d="M13 5h-2v6H8l4 4 4-4h-3z"/><path d="M20 20H4v-2h16v2z"/></svg>
                </span>
                <button @click="downloadFile(doc.file)" class="text-left text-[#800020] hover:underline text-xl font-semibold leading-snug ml-10">
                    {{ doc.name }}
                </button>
            </li>
        </ul>
    </div>
</template>



<style scoped>
.documents-section ul li {
    list-style: none;
}
</style>
