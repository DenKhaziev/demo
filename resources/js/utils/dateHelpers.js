// utils/date.js
import dayjs from 'dayjs';
import utc from 'dayjs/plugin/utc';
import timezone from 'dayjs/plugin/timezone';

// Подключаем плагины
dayjs.extend(utc);
dayjs.extend(timezone);

// ✅ Форматирование даты без смещения (если дата в БД хранится в локальном времени)
export const formatRawDate = (dateStr) => {
    return dayjs.utc(dateStr).format('DD.MM.YYYY HH:mm');
};

// ✅ Форматирование в нужной таймзоне (если дата в БД в UTC)
export const formatAsMoscowTime = (dateStr) => {
    return dayjs.utc(dateStr).tz('Europe/Moscow').format('DD.MM.YYYY HH:mm');
};

// ✅ Универсальный формат в локальном времени пользователя
export const formatLocalDate = (dateStr) => {
    return dayjs(dateStr).format('DD.MM.YYYY HH:mm');
};
