<script setup>

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Card from "@/components/Admin/Layout/Card.vue";

import MainLayout from "@/Layouts/MainLayout.vue";
import CardSubheader from "@/components/Admin/Layout/CardSubheader.vue";
import CardWrapper from "@/components/Admin/Layout/CardWrapper.vue";
import BaseInput from "@/components/ui/BaseInput.vue";
import {router, useForm, usePage} from "@inertiajs/vue3";
import UserFilledButton from "@/components/ui/UserFilledButton.vue";
import GoBackbutton from "@/components/ui/GoBackbutton.vue";

const page = usePage()
const form = useForm({
    subject: "",
    message: "",
    user_id: page.props.targetUserId
});
console.log(form)

const createTicket = () => {
    form.post(route("tickets.store"));
};

</script>

<template>
    <MainLayout>
        <AuthenticatedLayout>
            <div class="w-full max-w-4xl mx-auto mt-5">
                <GoBackbutton/>
                <CardSubheader class="text-5xl flex justify-center items-center">Создать новое обращение</CardSubheader>
                <Card>
                    <CardWrapper class="bg-gray-100 p-8 chat-scroll max-h-[1200px] overflow-y-auto rounded-xl">
                    <BaseInput
                        v-model="form.subject"
                        class="border p-2 w-full rounded-lg"
                        placeholder="Введите тему обращения..."
                    />
                    <BaseInput
                        v-model="form.message"
                        class="border p-2 w-full rounded-lg mt-5"
                        placeholder="Введите сообщение..."
                    />
                    </CardWrapper>
                    <UserFilledButton @click="createTicket" class="mt-4">Отправить</UserFilledButton>
                </Card>
            </div>
        </AuthenticatedLayout>
    </MainLayout>
</template>

<style scoped>

</style>
