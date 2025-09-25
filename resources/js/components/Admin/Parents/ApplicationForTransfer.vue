<script setup>
import BaseInput from "@/components/ui/BaseInput.vue";
import {useForm} from '@inertiajs/vue3'
import CardWrapper from "@/components/Admin/Layout/CardWrapper.vue";
import SaveButton from "@/components/ui/SaveButton.vue";
const props = defineProps({
    child: Object,
})
    const form = useForm({
    // child_birth_date: '12.12.2000',
    // child_birth_cert_number: 'x-cs 324534',
    // child_birth_cert_issued_at: '01.01.2001',
    // child_birth_cert_issued_by: 'Отделением ЗАГС гор. Москвы по району проспект Вернадского',
    // parent_birth_date: '',
    // parent_passport_series: '1234',
    // parent_passport_number: '123456',
    // parent_passport_issued_at: '12.12.2011',
    // parent_passport_issued_by: 'Отделением УФМС России по гор. Москве по району проспект Вернадского',
    // parent_passport_department_code: '123-123',
    // parent_address: 'г. Мытищи, Московская область, ул. Борисовка, д.18, кв.130',
    // child_id: props.child?.id ?? null

        parent_name: '',
        child_birth_date: '',
        child_birth_cert_number: '',
        child_birth_cert_issued_at: '',
        child_birth_cert_issued_by: '',
        parent_birth_date: '',
        parent_passport_series: '',
        parent_passport_number: '',
        parent_passport_issued_at: '',
        parent_passport_issued_by: '',
        parent_passport_department_code: '',
        parent_address: '',
        child_id: props.child?.id ?? null
})



    const submit = () => {
    form.post(route('store.personal_infos'), {
        onSuccess: () => {
            window.open(route('personal-infos.preview', form.child_id), '_blank')
        }
        })
    }
</script>

<template>
    <form @submit.prevent="submit" class="space-y-4 pb-16">
        <h2 class="text-xl font-semibold text-gray-800 mb-2 text-center">Данные ребёнка</h2>

        <CardWrapper class="grid grid-cols-2 gap-4">
            <BaseInput
                v-model="form.child_birth_date"
                v-mask="'##.##.####'"
                type="date"
                label="Дата рождения ребёнка"
                placeholder="ДД.ММ.ГГГГ"
                :error="form.errors.child_birth_date"
                required
            />
            <BaseInput
                v-model="form.child_birth_cert_number"
                label="Номер свидетельства о рождении"
                placeholder="Например: IV-ЯЮ №123456"
                :error="form.errors.child_birth_cert_number"
                required
            />
            <BaseInput
                v-model="form.child_birth_cert_issued_at"
                v-mask="'##.##.####'"
                type="date"
                label="Дата выдачи свидетельства"
                placeholder="ДД.ММ.ГГГГ"
                :error="form.errors.child_birth_cert_issued_at"
                required
            />
            <BaseInput
                v-model="form.child_birth_cert_issued_by"
                label="Кем выдано свидетельство"
                placeholder="например: ЗАГС Центрального района"
                :error="form.errors.child_birth_cert_issued_by"
                required

            />
        </CardWrapper>

        <h2 class="text-xl font-semibold text-gray-800 mt-6 mb-2 text-center">Данные родителя</h2>

        <CardWrapper class="grid grid-cols-2 gap-4">
            <BaseInput
                v-model="form.parent_name"
                label="ФИО родителя"
                placeholder="Например: Горбунков Семен Семенович"
                :error="form.errors.parent_name"

            />
            <BaseInput
                v-model="form.parent_birth_date"
                v-mask="'##.##.####'"
                type="date"
                label="Дата рождения родителя"
                placeholder="ДД.ММ.ГГГГ"
                :error="form.errors.parent_birth_date"
            />
            <BaseInput
                v-model="form.parent_passport_series"
                label="Серия паспорта"
                placeholder="РФ или иностранного гос."
                :error="form.errors.parent_passport_series"

            />
            <BaseInput
                v-model="form.parent_passport_number"
                label="Номер паспорта"
                placeholder="Например: 567890"
                :error="form.errors.parent_passport_number"

            />
            <BaseInput
                v-model="form.parent_passport_issued_at"
                v-mask="'##.##.####'"
                type="date"
                label="Дата выдачи паспорта"
                placeholder="ДД.ММ.ГГГГ"
                :error="form.errors.parent_passport_issued_at"

            />
            <BaseInput
                v-model="form.parent_passport_issued_by"
                label="Кем выдан паспорт"
                placeholder="Например: УФМС России по Республике Татарстан"
                :error="form.errors.parent_passport_issued_by"

            />
            <BaseInput
                v-model="form.parent_passport_department_code"
                v-mask="'###-###'"
                label="Код подразделения"
                placeholder="если есть, например: 123-456"
                :error="form.errors.parent_passport_department_code"

            />
            <BaseInput
                v-model="form.parent_address"
                label="Адрес проживания"
                placeholder="Город, улица, дом, квартира"
                :error="form.errors.parent_address"

            />
        </CardWrapper>
        <CardWrapper class="text-center text-[#800020]">
            <p>
            Пожалуйста, проверьте правильность введенных вами данных перед формированием заявления!
            </p>
            <br/>
            <p>
            После формирования заявления сохраните его и распечатайте, затем подписанный скан или фотографию прикрепите по кнопке ниже!
            </p>
            <br/>
            <p>
            В случае если в заявлении вы ошибочно укажете некорректные данные - просто заново сформируйте заявление, указав корректные!
            </p>
        </CardWrapper>
        <div class=" mx-auto block w-fit">
            <SaveButton type="submit">Сформировать заявление</SaveButton>
        </div>
    </form>
</template>


<style scoped>

</style>
