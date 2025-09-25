import { computed } from "vue";

export function useGroupedChildren(children) {
    const groupedChildren = computed(() => {
        return children.value.reduce((acc, child) => {
            const grade = child.grade.id; // Получаем grade_id
            if (!acc[grade]) {
                acc[grade] = [];
            }
            acc[grade].push(child);
            return acc;
        }, {});
    });

    return { groupedChildren };
}
