export const capitalizeFirstLetter = (event, form) => {
    const value = event.target.value;
    if (value.length > 0) {
        // Преобразует первую букву в заглавную, остальные — в строчные
        form.subject = value.charAt(0).toUpperCase() + value.slice(1).toLowerCase();
    }
};
