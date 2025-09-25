<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import MainLayout from '@/Layouts/MainLayout.vue';
import InputError from '@/components/BreezeAuth/InputError.vue';
import InputLabel from '@/components/BreezeAuth/InputLabel.vue';
import PrimaryButton from '@/components/BreezeAuth/PrimaryButton.vue';
import TextInput from '@/components/BreezeAuth/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('student.password.update'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <MainLayout>
        <GuestLayout>
            <Head title="Сброс пароля ученика" />

            <form @submit.prevent="submit">
                <div>
                    <InputLabel for="email" value="Email ученика" />

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

                <div class="mt-4">
                    <InputLabel for="password" value="Новый пароль" />

                    <TextInput
                        id="password"
                        type="password"
                        class="mt-1 block w-full"
                        v-model="form.password"
                        required
                        autocomplete="new-password"
                    />

                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div class="mt-4">
                    <InputLabel
                        for="password_confirmation"
                        value="Подтвердить пароль"
                    />

                    <TextInput
                        id="password_confirmation"
                        type="password"
                        class="mt-1 block w-full"
                        v-model="form.password_confirmation"
                        required
                        autocomplete="new-password"
                    />

                    <InputError
                        class="mt-2"
                        :message="form.errors.password_confirmation"
                    />
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
