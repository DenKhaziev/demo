<table>
    <thead>
        <tr>
            <th>№ п/п</th>
            <th>ФИО ученика</th>
            <th>Пол</th>
            <th>Дата рождения</th>
            <th>Класс</th>
            <th>свидетельство</th>
            <th>свидетельство номер</th>

            <th>дата выдачи</th>
            <th>кем выдано</th>


            <th>паспорт серия</th>
            <th>паспорт номер</th>
            <th>дата выдачи</th>
            <th>Кем выдано</th>

            <th>Адрес регистрации</th>
            <th>ФИО представителя (родителя)</th>
            <th>Дата рождения представителя (родителя)</th>
            <th>Паспорт серия</th>
            <th>Паспорт номер</th>
            <th>Дата выдачи</th>
            <th>Кем выдан</th>
            <th>Email</th>
            <th>Телефон</th>


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
                <td>{{ $child->grade_id }}</td>
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
                <td>{{ $child->user->name }}</td>
                <td>{{ optional($child->userInfo)->parent_birth_date
                    ? \Illuminate\Support\Carbon::parse($child->userInfo->parent_birth_date)->format('d.m.Y')
                    : '' }}
                </td>
                <td>{{ optional($child->userInfo)->parent_passport_series }}</td>
                <td>{{ optional($child->userInfo)->parent_passport_number }}</td>
                <td>{{ optional($child->userInfo)->parent_passport_issued_at
                    ? \Illuminate\Support\Carbon::parse($child->userInfo->parent_passport_issued_at)->format('d.m.Y')
                        : ''
                    }}
                </td>
                <td>{{ optional($child->userInfo)->parent_passport_issued_by}}</td>
                <td>{{ $child->user->email }}</td>
                <td>{{ $child->user->phone }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

                    