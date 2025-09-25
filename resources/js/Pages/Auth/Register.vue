<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/components/BreezeAuth/InputError.vue';
import InputLabel from '@/components/BreezeAuth/InputLabel.vue';
import PrimaryButton from '@/components/BreezeAuth/PrimaryButton.vue';
import TextInput from '@/components/BreezeAuth/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import MainLayout from "@/Layouts/MainLayout.vue";

const form = useForm({
    name: '',
    phone: '',
    email: '',
    child_name: '',
    grade_id: '',
    attestation_type: '',
    password: '',
    password_confirmation: '',
})

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <MainLayout>
    <GuestLayout>
        <Head title="Register" />

        <form @submit.prevent="submit" >
            <div>
                <InputLabel for="name" value="ФИО родителя (представителя)" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    placeholder="Например: Иванов Иван Иванович"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div class="mt-4">
                <InputLabel for="phone" value="Телефон" />

                <TextInput
                    id="phone"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.phone"
                    v-mask="'+7 (###) ###-##-##'"
                    placeholder="+7 (___) ___-__-__"
                    required
                    autocomplete="tel"
                />

                <InputError class="mt-2" :message="form.errors.phone" />
            </div>

            <div class="mt-4">
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    placeholder="Например: for-example@example.ru"
                    required
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="child_name" value="ФИО ученика" />

                <TextInput
                    id="child_name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.child_name"
                    placeholder="Например: Иванов Иван Иванович"
                    required
                />

                <InputError class="mt-2" :message="form.errors.child_name" />
            </div>
            <div class="mt-4">
                <InputLabel for="grade" value="Класс" />
                <TextInput id="grade" type="number" min="1" max="11" v-model="form.grade_id" required />
            </div>
            <div class="mt-4">
                <InputLabel for="exam_type" value="Тип аттестации" />
                <select v-model="form.attestation_type" required class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 mt-1 block">
                    <option value="ПА">ПА</option>
                    <option value="ГИА">ГИА</option>
                </select>
            </div>


            <div class="mt-4">
                <InputLabel for="password" value="Пароль" />

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
                <InputLabel for="password_confirmation" value="Подтверждение пароля" />

                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                />

                <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>
            <p
                class="text-red-700 mt-2 p-2 text-center border-red-700 border rounded-xl "
            >
                После прохождения регистрации на email вы получите письмо с данными для входа в личный кабинет представителя и ученика!
            </p>

            <div class="mt-4 flex items-center justify-end">
                <Link
                    :href="route('login')"
                    class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Уже зарегистрированы?
                </Link>

                <primary-button
                    class="ms-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Зарегистрироваться
                </primary-button>
            </div>
        </form>
    </GuestLayout>
    </MainLayout>
</template>

