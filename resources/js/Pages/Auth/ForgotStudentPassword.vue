<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import MainLayout from '@/Layouts/MainLayout.vue';
import InputError from '@/components/BreezeAuth/InputError.vue';
import InputLabel from '@/components/BreezeAuth/InputLabel.vue';
import PrimaryButton from '@/components/BreezeAuth/PrimaryButton.vue';
import TextInput from '@/components/BreezeAuth/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('student.password.email'));
};
</script>

<template>
    <MainLayout>
    <GuestLayout>
        <Head title="Forgot Password" />

        <div class="mb-4 text-sm text-gray-600">
            Забыли пароль? Не проблема. Просто укажите логин ученика, и мы отправим вам ссылку для сброса пароля, с помощью которой вы сможете установить новый пароль.
        </div>

        <div
            v-if="status"
            class="mb-4 text-sm font-medium text-green-600"
        >
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Логин ученика" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4 flex items-center justify-end">
                <PrimaryButton
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Сбросить пароль
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
    </MainLayout>

</template>
