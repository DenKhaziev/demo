import { ref, computed } from 'vue';

export function useDocumentModal(child) {
    const showModal = ref(false);
    const modalTitle = ref('');
    const modalDocs = ref([]);

    const isDocumentsUploaded = computed(() => {
        if (!Array.isArray(child.documents)) return false;

        // Найдём текущий класс (grade_id)
        const gradeId = child.grade_id;

        const doc = child.documents.find(d => d.grade_id === gradeId);
        if (!doc) return false;

        // Проверяем наличие всех нужных документов
        const requiredFields = [
            'document_birth',
            'document_application_for_transfer',
            'document_personal_of_processing_data',
            'document_payment',
        ];

        // Для 9+ классов добавим аттестат
        if (gradeId > 8) {
            requiredFields.push('document_from_9_graduate');
        }

        return requiredFields.every(field => !!doc[field]);
    });
    const openModal = (title, docs) => {
        modalTitle.value = title;
        modalDocs.value = docs;
        showModal.value = true;
    };

    const buildLink = (filename, gradeId) => {
        return filename
            ? `/storage/documents/${child.id}/${gradeId}/${filename}`
            : null;
    };

    const groupedCertificates = computed(() => {
        if (!Array.isArray(child.documents)) return {};

        const result = {};
        child.documents.forEach(doc => {
            result[doc.grade_id] = [
                { label: 'Справка о прохождении промежуточной аттестации', link: buildLink(doc.document_certificate_by_grade, doc.grade_id) },
                { label: 'Справка о зачислении', link: buildLink(doc.document_about_transfer, doc.grade_id) },
            ];
        });
        return result;
    });

    const groupedDocs = computed(() => {
        if (!Array.isArray(child.documents)) return {};

        const result = {};
        child.documents.forEach(doc => {
            const base = [
                { label: 'Свидетельство о рождении', link: buildLink(doc.document_birth, doc.grade_id) },
                { label: 'Заявление на зачисление', link: buildLink(doc.document_application_for_transfer, doc.grade_id) },
                { label: 'Согласие на обработку персональных данных', link: buildLink(doc.document_personal_of_processing_data, doc.grade_id) },
                { label: 'Чек об оплате', link: buildLink(doc.document_payment, doc.grade_id) },
            ]

            if (doc.grade_id > 9) {
                base.push({
                    label: 'Аттестат за 9 классов',
                    link: buildLink(doc.document_from_9_graduate, doc.grade_id)
                })
            }

            result[doc.grade_id] = base
        });
        return result;
    });

    return {
        showModal,
        modalTitle,
        modalDocs,
        openModal,
        groupedCertificates,
        groupedDocs,
        isDocumentsUploaded
    };
}
