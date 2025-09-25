<table>
    <thead>
        <tr>
            <th>№ п/п</th>
            <th>ФИО</th>
            <th>Пол</th>
            <th>Дата рождения</th>
            <th>Свидетельство о рождении серия</th>
            <th>Свидетельство о рождении номер</th>
            <th>Дата выдачи</th>
            <th>Кем выдано</th>
            <th>паспорт серия</th>
            <th>паспорт номер</th>
            <th>дата выдачи</th>
            <th>Кем выдано</th>

            <th>Адрес регистрации</th>
            <th>Класс</th>
            <th>Дата оплаты</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($children as $index => $child)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $child->name }}</td>
                <td></td>
                <td>{{ optional($child->userInfo)->child_birth_date 
                    ? \Illuminate\Support\Carbon::parse($child->userInfo->child_birth_date)->format('d.m.Y')
                        : ''     
                    }}
                </td>
                <td>{{ optional($child->userInfo)->child_birth_cert_number }} </td>
                <td></td>
                <td>{{ optional($child->userInfo)->child_birth_cert_issued_at 
                        ? \Illuminate\Support\Carbon::parse($child->userInfo->child_birth_cert_issued_at)->format('d.m.Y')
                        : ''
                    }}
                </td>
                <td>{{ optional($child->userInfo)->child_birth_cert_issued_by }}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>{{ optional($child->userInfo)->parent_address }}</td>
                <td>{{ $child->grade_id }}</td>
                <td>{{ $child->payment_date 
                    ? \Illuminate\Support\Carbon::parse($child->payment_date)->format('d.m.Y')
                        : ''
                }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
