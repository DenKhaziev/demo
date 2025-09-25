<script setup>
import Checkbox from '@/components/BreezeAuth/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/components/BreezeAuth/InputError.vue';
import InputLabel from '@/components/BreezeAuth/InputLabel.vue';
import PrimaryButton from '@/components/BreezeAuth/PrimaryButton.vue';
import TextInput from '@/components/BreezeAuth/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import UserFilledButton from "@/components/ui/UserFilledButton.vue";
import MainLayout from "@/Layouts/MainLayout.vue";

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <MainLayout>
    <GuestLayout>
        <Head title="Log in" />
        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
            {{ status }}
        </div>
        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Логин (ваш email)" />

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
                <InputLabel for="password" value="Пароль" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4 block">
                <label class="flex items-center">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ms-2 text-sm text-gray-600"
                        >Запомнить меня</span
                    >
                </label>
            </div>

            <div class="mt-4 flex items-center justify-end">
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="rounded-md text-sm text-[#800020] underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Забыли пароль?
                </Link>
                <Link
                    :href="route('register')"
                    class="rounded-md text-sm underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 ml-4 text-[#800020]"
                >
                    Зарегистрироваться
                </Link>

            </div>
            <div class="block mx-auto w-fit mt-5">

                <primary-button
                    class="px-6 py-2 rounded-2xl text-white font-semibold bg-gradient-to-r from-[#800020] to-[#a3243a] shadow-md transition-all duration-300 hover:brightness-110 hover:scale-[1.02] focus:outline-none"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Войти
                </primary-button>
            </div>
        </form>
    </GuestLayout>
    </MainLayout>
</template>
