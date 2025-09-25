<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/components/BreezeAuth/InputError.vue';
import InputLabel from '@/components/BreezeAuth/InputLabel.vue';
import PrimaryButton from '@/components/BreezeAuth/PrimaryButton.vue';
import TextInput from '@/components/BreezeAuth/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import CardWrapper from "@/components/Admin/Layout/CardWrapper.vue";
import UserOutlineButton from "@/components/ui/UserOutlineButton.vue";
import MainLayout from "@/Layouts/MainLayout.vue";
import GoBackbutton from "@/components/ui/GoBackbutton.vue";
import Header from "@/components/Main/Header.vue";

const form = useForm({
    child_name: '',
    grade_id: '',
    attestation_type: '',
})

const submit = () => {
    form.post(route('store.parent.child'), {
        onFinish: () => form.reset('child_name', 'grade_id', 'attestation_type'),
    });
};
</script>

<template>
    <MainLayout>
    <AuthenticatedLayout>
        <div class="min-h-screen py-12">
            <Header class="text-5xl flex justify-center items-center">Добавить ученика</Header>
            <GoBackbutton class="mx-auto"/>
            <CardWrapper class="w-2/3 sm:w-1/2 md:w-1/3 lg:w-1/3 xl:w-1/5 mx-auto text-center">
                    <form @submit.prevent="submit">
                        <div class="space-y-4 sm:space-y-6">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:gap-4">
                                <div class="w-full">
                                    <InputLabel for="child_name" value="ФИО ученика" />
                                    <TextInput
                                    id="child_name"
                                    type="text"
                                    class="mt-1 w-full"
                                    placeholder="Например: Иванов Иван Иванович"
                                    v-model="form.child_name"
                                    required
                                />
                                <InputError class="mt-2" :message="form.errors.child_name" />
                            </div>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row sm:gap-4">
                            <div class="w-full sm:w-1/2">
                                <InputLabel for="grade_id" value="Класс" />
                                <select
                                id="grade_id"
                                v-model="form.grade_id"
                                required
                                class="mt-1 w-full rounded-md border border-gray-300 bg-white shadow-sm
                                focus:border-indigo-500 focus:ring-indigo-500 focus:outline-none
                                text-sm sm:text-base"
                                >
                                <option disabled value="">Выберите класс</option>
                                <option v-for="grade in 11" :key="grade" :value="grade">
                                    {{ grade }}
                                </option>
                            </select>
                            <InputError class="mt-2" :message="form.errors.grade_id" />
                        </div>
                        
                        
                        <div class="w-full sm:w-1/2">
                            <InputLabel for="exam_type" value="Тип аттестации" />
                            <select
                            id="exam_type"
                            v-model="form.attestation_type"
                            required
                            class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                            <option value="ПА">ПА</option>
                            <option value="ГИА">ГИА</option>
                        </select>
                    </div>
                </div>
                <p 
                class="text-red-700 mt-2 p-2 text-center border-red-700 border rounded-xl "
                >
                После добавления нового ученика на свой email вы получите письмо с данными для входа в личный кабинет ученика!
            </p>
            <div class="sm:pt-6">
                            <primary-button
                                type="submit"
                                class="px-6 py-2 rounded-2xl text-white font-semibold bg-gradient-to-r from-[#800020] to-[#a3243a] shadow-md transition-all duration-300 hover:brightness-110 hover:scale-[1.02] focus:outline-none"
                                >
                                Добавить
                            </primary-button>
                        </div>
                    </div>
                </form>
            </CardWrapper>
        </div>
    </AuthenticatedLayout>
</MainLayout>
</template>



<style>

</style>
