<script setup>

import UserFilledButton from "@/components/ui/UserFilledButton.vue";
import PaymentQR from "@/components/Parent/PaymentQR.vue";
import SlideToggle from "@/components/ui/SlideToggle.vue";
import PaymentTransfer from "@/components/Parent/PaymentTransfer.vue";
import Card from "@/components/Admin/Layout/Card.vue";
import GoBackbutton from "@/components/ui/GoBackbutton.vue";
import CardSubheader from "@/components/Admin/Layout/CardSubheader.vue";
import {ref} from "vue";

const isOpenQR = ref(false);
const isOpenTransfer = ref(false);

const props = defineProps({
    prices: Object,
})

const toggleQR = () => {
    isOpenQR.value = !isOpenQR.value;
};
const toggleTransfer = () => {
    isOpenTransfer.value = !isOpenTransfer.value;
};
</script>

<template>
    <div class="w-full sm:w-4/5 md:w-2/3 lg:w-1/2 xl:w-1/3 mx-auto mt-12 px-4">
        <GoBackbutton class="mb-4" />

        <CardSubheader class="mx-auto block w-fit mb-2">Стоимость аттестации</CardSubheader>
        <Card>
            <p class="text-center leading-relaxed">
                <strong>ПА (промежуточная аттестация)</strong> — {{ props.prices.PA_attestation }} руб<br />
                <strong>ГИА (ОГЭ, ЕГЭ)</strong> — {{ props.prices.GIA }} руб
            </p>
        </Card>

        <CardSubheader class="mx-auto block w-fit my-4">Способы оплаты</CardSubheader>
        <Card>
            <UserFilledButton
                @click="toggleQR"
                class="my-2 mx-auto block w-fit"
                :isOpen="isOpenQR"
            >
                {{ isOpenQR ? 'Свернуть' : 'Оплата с помощью QR кода из приложения банка' }}
            </UserFilledButton>
            <SlideToggle :isOpen="isOpenQR">
                <PaymentQR />
            </SlideToggle>

            <UserFilledButton
                @click="toggleTransfer"
                class="my-2 mx-auto block w-fit"
                :isOpen="isOpenTransfer"
            >
                {{ isOpenTransfer ? 'Свернуть' : 'Оплата банковским переводом' }}
            </UserFilledButton>
            <SlideToggle :isOpen="isOpenTransfer">
                <PaymentTransfer />
            </SlideToggle>
        </Card>

        <Card class="mt-6 font-semibold">
            <p class="text-center text-gray-700">
                Не забудьте загрузить чек об оплате вместе с остальными документами!
            </p>
        </Card>
    </div>

</template>

<style scoped>

</style>
