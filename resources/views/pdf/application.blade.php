<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Заявление</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; line-height: 1.6; font-size: 12px; }
        .title { text-align: center; font-weight: bold; font-size: 16px; margin-bottom: 15px; }
        .section { margin-bottom: 12px; }
    </style>
</head>
<body>
<div class="section" >
    <div style="text-align: center; margin-bottom: 10px;">
        Директору ОАНО СОШ «Пенаты» Веретильной Е.А.
    </div>

    <table style="margin-left: auto; margin-right: 0; width: 80%;">
        <tr>
            <td style="text-align: left;">от</td>
            <td style="text-align: right;"><strong>{{ $parent_full_name }}</strong></td>
        </tr>
        <tr>
            <td style="text-align: left;">дата рождения</td>
            <td style="text-align: right;"><strong>{{ $parent_birth_date }}</strong></td>
        </tr>
        <tr>
            <td style="text-align: left;">паспорт</td>
            <td style="text-align: right;"><strong>{{ $parent_passport_series }} {{ $parent_passport_number }}</strong></td>
        </tr>
        <tr>
            <td style="text-align: left;">выдан</td>
            <td style="text-align: right;"><strong>{{ $parent_passport_issued_by }}</strong></td>
        </tr>
        <tr>
            <td style="text-align: left;">дата выдачи</td>
            <td style="text-align: right;"><strong>{{ $parent_passport_issued_at }}</strong></td>
        </tr>
        <tr>
            <td style="text-align: left;">код подразделения</td>
            <td style="text-align: right;"><strong>{{ $parent_passport_department_code }}</strong></td>
        </tr>
        <tr>
            <td style="text-align: left;">проживающего(ей) по адресу</td>
            <td style="text-align: right;"><strong>{{ $parent_address }}</strong></td>
        </tr>
        <tr>
            <td style="text-align: left;">электронная почта</td>
            <td style="text-align: right;"><strong>{{ $parent_email }}</strong></td>
        </tr>
        <tr>
            <td style="text-align: left;">телефон</td>
            <td style="text-align: right;"><strong>{{ $parent_phone }}</strong></td>
        </tr>
    </table>
</div>


<div class="title">Заявление</div>

<div class="section" style="text-align: center;">
    Прошу зачислить в качестве экстерна меня (моего(ю) сына(дочь))<br><br>

    <strong style="text-decoration: underline; display: inline-block; margin: 4px 0;">
        {{ $child_full_name }}
    </strong><br>
<hr style="margin: 0 0; border: 1px solid #000;">
    <small>(ФИО полностью)</small>
</div>


<div class="section" style="margin-left: 20px;">
    <div style="margin-bottom: 8px;">
        <span style="display: inline-block; width: 20px; height: 20px; border: 1px solid black; margin-right: 10px;"></span>
        для прохождения промежуточной аттестации за курс {{ $grade ?? '___' }}-го класса
    </div>
    <div style="margin-bottom: 8px;">
        <span style="display: inline-block; width: 20px; height: 20px; border: 1px solid black; margin-right: 10px;"></span>
        государственной итоговой аттестации за курс основного общего образования;
    </div>
    <div style="margin-bottom: 8px;">
        <span style="display: inline-block; width: 20px; height: 20px; border: 1px solid black; margin-right: 10px;"></span>
        государственной итоговой аттестации за курс среднего общего образования.
    </div>
</div>

<div class="section" style="margin-top: 20px;">
    <div style="margin-left: 40px; margin-bottom: 10px;">
        <span style="display: inline-block; width: 20px; height: 20px; border: 1px solid black; margin-right: 10px;"></span>
        Требуются специализированные условия для сдачи ГИА*
    </div>

    <hr style="margin: 0 15px 0 0; border: 1px solid #000;">

    <div style="text-align: center; font-size: 12px;">указать необходимые специализированные условия</div>

    <div style="font-size: 11px; margin-top: 6px;">
        <small>(*для обучающихся с ОВЗ, заключением ЦМППК, статусом «ребенок-инвалид»)</small>
    </div>
</div>

<div class="section">
    С лицензией на осуществление образовательной деятельности, свидетельством о государственной аккредитации,
    Уставом, Образовательной программой ОАНО СОШ «Пенаты», порядком проведения промежуточной аттестации,
    Положением о порядке и формах проведения государственной итоговой аттестации,
    условиями договора-оферты ознакомлен(а).
</div>

<div class="section" style="margin-top: 15px; display: flex; justify-content: space-between">
    <div>Дата __________________</div>
    <div style="margin-top: 10px;">
        Подпись __________________ / ___________________________ /
    </div>
</div>


</body>
</html>
