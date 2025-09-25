<script setup>
import {computed, onMounted, onBeforeUnmount, watch} from "vue";

// props
const props = defineProps({
  show: { type: Boolean, default: false },
  info: { type: Object, default: () => ({}) },
    userName: Array,
});

const emit = defineEmits(["close"]);

// утилита: безопасно вывести значение или "—"
const v = (val) => (val ?? val === 0 ? String(val) : "—");

// формат дат YYYY-MM-DD → DD.MM.YYYY
const fd = (val) => {
  if (!val) return "—";
  const [y, m, d] = String(val).split("T")[0].split("-");
  if (!y || !m || !d) return v(val);
  return `${d}.${m}.${y}`;
};

function onEsc(e) {
  if (e.key === "Escape") emit("close");
}
const toggleScrollLock = (enabled) => {
    document.body.classList.toggle('overflow-hidden', enabled);
};
watch(() => props.show, (val) => {
    if (val) {
        toggleScrollLock(true);
    } else {
        toggleScrollLock(false);
    }
});
onMounted(() => document.addEventListener("keydown", onEsc));
onBeforeUnmount(() => document.removeEventListener("keydown", onEsc));

</script>

<template>
  <transition name="fade">
    <div
      v-if="show"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center backdrop-blur-sm z-40"

    >

      <!-- modal -->
      <div
        class="relative z-10 w-full max-w-2xl bg-white rounded-xl shadow-xl p-5"
        role="dialog"
        aria-modal="true"

      >
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-semibold">Персональная информация</h3>
          <button
            class="px-2 py-1 text-sm rounded hover:bg-gray-100"
            @click="emit('close')"
            aria-label="Закрыть"
          >
            ✕
          </button>
        </div>

        <!-- Контент -->
        <div class="space-y-5">
          <!-- Ребёнок -->
          <section>
            <h4 class="font-semibold mb-2">Данные ребёнка</h4>
            <div class="grid grid-cols-2 gap-3 text-sm">
              <div class="text-gray-500">ID ребёнка</div>
              <div class="font-medium">{{ v(info.child_id) }}</div>

              <div class="text-gray-500">Дата рождения</div>
              <div class="font-medium">{{ fd(info.child_birth_date) }}</div>
            </div>
          </section>

          <!-- Свидетельство о рождении -->
          <section>
            <h4 class="font-semibold mb-2">Свидетельство о рождении</h4>
            <div class="grid grid-cols-2 gap-3 text-sm">
              <div class="text-gray-500">Номер</div>
              <div class="font-medium">{{ v(info.child_birth_cert_number) }}</div>

              <div class="text-gray-500">Выдано</div>
              <div class="font-medium">{{ v(info.child_birth_cert_issued_by) }}</div>

              <div class="text-gray-500">Дата выдачи</div>
              <div class="font-medium">{{ fd(info.child_birth_cert_issued_at) }}</div>
            </div>
          </section>

          <!-- Родитель -->
          <section>
            <h4 class="font-semibold mb-2">Родитель</h4>
            <div class="grid grid-cols-2 gap-3 text-sm">
                <div class="text-gray-500">ФИО</div>
                <div class="font-medium">
                    {{ v( (info.parent_name?.trim()) || userName || '—' ) }}
                </div>
              <div class="text-gray-500">User ID</div>
              <div class="font-medium">{{ v(info.user_id) }}</div>

              <div class="text-gray-500">Дата рождения</div>
              <div class="font-medium">{{ fd(info.parent_birth_date) }}</div>

              <div class="text-gray-500">Адрес</div>
              <div class="font-medium break-words">{{ v(info.parent_address) }}</div>
            </div>
          </section>

          <!-- Паспорт родителя -->
          <section>
            <h4 class="font-semibold mb-2">Паспорт родителя</h4>
            <div class="grid grid-cols-2 gap-3 text-sm">
              <div class="text-gray-500">Серия</div>
              <div class="font-medium">{{ v(info.parent_passport_series) }}</div>

              <div class="text-gray-500">Номер</div>
              <div class="font-medium">{{ v(info.parent_passport_number) }}</div>

              <div class="text-gray-500">Кем выдан</div>
              <div class="font-medium">{{ v(info.parent_passport_issued_by) }}</div>

              <div class="text-gray-500">Код подразделения</div>
              <div class="font-medium">{{ v(info.parent_passport_department_code) }}</div>

              <div class="text-gray-500">Дата выдачи</div>
              <div class="font-medium">{{ fd(info.parent_passport_issued_at) }}</div>
            </div>
          </section>

          <!-- Служебные -->
          <section>
            <h4 class="font-semibold mb-2">Служебная информация</h4>
            <div class="grid grid-cols-2 gap-3 text-sm">
              <div class="text-gray-500">ID записи</div>
              <div class="font-medium">{{ v(info.id) }}</div>

              <div class="text-gray-500">Создано</div>
              <div class="font-medium">{{ v(info.created_at) }}</div>

              <div class="text-gray-500">Обновлено</div>
              <div class="font-medium">{{ v(info.updated_at) }}</div>
            </div>
          </section>
        </div>

        <!-- footer -->
        <div class="mt-6 flex justify-end">
          <button
            class="px-4 py-2 text-sm rounded bg-gray-100 hover:bg-gray-200"
            @click="emit('close')"
          >
            Закрыть
          </button>
        </div>
      </div>
    </div>
  </transition>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
