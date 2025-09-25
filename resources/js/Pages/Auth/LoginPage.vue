<script setup>
import { ref } from 'vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputLabel from '@/components/BreezeAuth/InputLabel.vue';
import TextInput from '@/components/BreezeAuth/TextInput.vue';
import InputError from '@/components/BreezeAuth/InputError.vue';
import PrimaryButton from '@/components/BreezeAuth/PrimaryButton.vue';
import MainLayout from '@/Layouts/MainLayout.vue';

const mode = ref('parent'); // 'parent' или 'student'

const parentForm = useForm({
    email: '',
    password: '',
    remember: false,
});

const studentForm = useForm({
    email: '',
    password: '',
});

const submitParent = () => {
    parentForm.post(route('login'), {
        onFinish: () => parentForm.reset('password'),
    });
};

const submitStudent = () => {
    studentForm.post('/student/login', {
        onFinish: () => studentForm.reset('password'),
    });
};
</script>

<template>
    <MainLayout>
        <GuestLayout>
            <Head title="Вход" />

            <!-- Переключатель ролей -->
            <div class="flex justify-center mb-6 space-x-4">
                <button
                    class="px-4 py-2 rounded-2xl font-semibold transition-all duration-200"
                    :class="mode === 'parent' ? 'bg-[#800020] text-white shadow-md' : 'bg-gray-200 text-gray-600'"
                    @click="mode = 'parent'"
                >
                    Вход для родителя
                </button>
                <button
                    class="px-4 py-2 rounded-2xl font-semibold transition-all duration-200"
                    :class="mode === 'student' ? 'bg-[#800020] text-white shadow-md' : 'bg-gray-200 text-gray-600'"
                    @click="mode = 'student'"
                >
                    Вход для ученика
                </button>
            </div>

            <!-- Родительская форма -->
            <form v-if="mode === 'parent'" @submit.prevent="submitParent">
                <InputLabel for="email" value="Email родителя" />
                <TextInput v-model="parentForm.email" type="email" class="block w-full mt-1" required />
                <InputError :message="parentForm.errors.email" class="mt-2" />

                <div class="mt-4">
                    <InputLabel for="password" value="Пароль" />
                    <TextInput v-model="parentForm.password" type="password" class="block w-full mt-1" required />
                    <InputError :message="parentForm.errors.password" class="mt-2" />
                </div>

                <div class="mt-4">
                    <label class="flex items-center">
                        <input type="checkbox" v-model="parentForm.remember" class="mr-2" />
                        <span class="text-sm text-gray-600">Запомнить меня</span>
                    </label>
                </div>

                <div class="mt-4 flex justify-start space-x-4">
                    <Link v-if="true" :href="route('password.request')" class="text-sm text-[#800020] underline">
                        Забыли пароль родителя?
                    </Link>
                    <Link :href="route('register')" class="text-sm text-[#800020] underline">
                        Зарегистрироваться
                    </Link>
                </div>

                <div class="mt-6 text-center">
                    <PrimaryButton
                        class="px-6 py-2 rounded-2xl text-white font-semibold bg-gradient-to-r from-[#800020] to-[#a3243a] shadow-md transition-all duration-300 hover:brightness-110 hover:scale-[1.02] focus:outline-none"
                    >
                        Войти
                    </PrimaryButton>
                </div>
            </form>

            <!-- Форма ученика -->
            <form v-else @submit.prevent="submitStudent">
                <InputLabel for="email" value="Email ученика" />
                <TextInput v-model="studentForm.email" type="text" class="block w-full mt-1" required />
                <InputError :message="studentForm.errors.email" class="mt-2" />

                <div class="mt-4">
                    <InputLabel for="password" value="Пароль" />
                    <TextInput v-model="studentForm.password" type="password" class="block w-full mt-1" required />
                    <InputError :message="studentForm.errors.password" class="mt-2" />
                </div>

                <div class="mt-4">
                    <label class="flex items-center">
                        <input type="checkbox" v-model="parentForm.remember" class="mr-2" />
                        <span class="text-sm text-gray-600">Запомнить меня</span>
                    </label>
                </div>

                <div class="mt-4 flex justify-start space-x-4">
                    <Link v-if="true" :href="route('student.password.request')" class="text-sm text-[#800020] underline">
                        Забыли пароль ученика?
                    </Link>
                </div>

                <div class="mt-6 text-center">
                    <PrimaryButton
                        class="px-6 py-2 rounded-2xl text-white font-semibold bg-gradient-to-r from-[#800020] to-[#a3243a] shadow-md transition-all duration-300 hover:brightness-110 hover:scale-[1.02] focus:outline-none"
                    >
                        Войти
                    </PrimaryButton>
                </div>
            </form>
        </GuestLayout>
    </MainLayout>
</template>
