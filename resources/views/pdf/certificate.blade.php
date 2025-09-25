<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Справка</title>
    <style>
        @font-face {
            font-family: 'TimesNewRoman';
            src: url('{{ resource_path("fonts/times-new-roman-regular.ttf") }}') format("truetype");
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'TimesNewRoman';
            src: url('{{ resource_path("fonts/times-new-roman-bold.ttf") }}') format("truetype");
            font-weight: bold;
            font-style: normal;
        }

        body {
            font-family: 'TimesNewRoman', serif;
            font-size: 16px;
        }
    </style>
</head>
<body>
<div style="margin-left: 20px;">
    <div style="color: #9E1815; position:relative; margin-top: -35px;">
        <img src="{{ storage_path('app/public/essentials/certificate_logo.bmp') }}" style="float:left; width: 100px; height: 87px; margin-top: 30px;"  alt="logo"/>
        <p style="font-size: 18px; text-align: center; font-weight: bold; margin-bottom:10px; margin-left:-15px">
            Общеобразовательная автономная некоммерческая организация <br/>
            средняя общеобразовательная школа <br/>
            <span style="font-size:30px; font-weight: bold; text-transform: uppercase;">« П Е Н А Т Ы » </span>
        </p>
        <div style="height:12px; border-top: 1px solid #9E1815; border-bottom: 1px solid #9E1815; margin-bottom: 10px"></div>

        <div style="text-align: center; font-size: 11px; font-weight: bold;">
            ИНН 9715283917, КПП 771501001, <br>
            127562, город Москва, Алтуфьевское шоссе, дом 12-Б, +7(936)555-55-09, +7(499)745-50-90, so@penaty.ru
        </div>
    </div>
    <p style="font-size: 15px; font-weight:normal;">
        № б/н от {{ \Carbon\Carbon::now()->format('d.m.Y') }}
    </p>

    <div style="color: #000; text-align: center; margin-top: -30px;">
        <p>
            <span style="font-size: 26px; font-weight:bold;">СПРАВКА</span> <br/>
            <span style="font-size: 14px; font-weight: bold; text-transform: uppercase;">о результатах промежуточной аттестации</span>
        </p>

        <div style="margin-bottom: 10px;">
            <div style="font-size: 24px; color:#000; font-weight:bold">{{ $child->name }}</div>
            <div style="font-weight: bold; font-size: 18px; margin-top: 15px;">
                прошёл(ла) промежуточную аттестацию за курс {{ $child->grade->class_number }} класса по предметам
            </div>
        </div>

        @php
            // 10–11 классы показываем в одной строке: I и II полугодие
            $isHighSchool = in_array($child->grade->class_number ?? $child->grade_id, [10, 11]);

            if ($isHighSchool) {
                $grouped = collect($passedTests ?? [])
                    ->filter(fn($t) => optional($t->subject)->subject) // защита от null
                    // базовое имя предмета без хвоста "(полугодие 1|2)"
                    ->groupBy(function ($t) {
                        return trim(preg_replace('/\s*\(полугодие\s*[12]\)\s*$/iu', '', $t->subject->subject));
                    })
                    ->map(function ($tests) {
                        $first  = $tests->first(fn($t) => preg_match('/\(полугодие\s*1\)/iu', $t->subject->subject ?? ''));
                        $second = $tests->first(fn($t) => preg_match('/\(полугодие\s*2\)/iu', $t->subject->subject ?? ''));
                        $base   = trim(preg_replace('/\s*\(полугодие\s*[12]\)\s*$/iu', '', $tests->first()->subject->subject ?? ''));
                        return [
                            'subject' => $base ?: 'Без названия',
                            'first'   => $first->score  ?? null,
                            'second'  => $second->score ?? null,
                        ];
                    })
                    ->values();
            }

            // подпись по баллу
            $gradeLabel = fn ($v) => match((int) $v) {
                2 => 'неудовлетворительно',
                3 => 'удовлетворительно',
                4 => 'хорошо',
                5 => 'отлично',
                default => 'неизвестно',
            };
        @endphp

        <table cellspacing="0"
               style="width: 100%; font-size: 16px; border-collapse: collapse; border: 1px solid #000; text-align: center;">
            <tr>
                <td style="font-weight: bold; border: 2px solid #000; vertical-align: middle;">№ п/п</td>
                <td style="font-weight: bold; border: 2px solid #000; vertical-align: middle;">Наименование учебных предметов</td>
                @if ($isHighSchool)
                    <td style="font-weight: bold; border: 2px solid #000; vertical-align: middle;">Отметка за I полугодие</td>
                    <td style="font-weight: bold; border: 2px solid #000; vertical-align: middle;">Отметка за II полугодие</td>
                    <td style="font-weight: bold; border: 2px solid #000; vertical-align: middle;">Итоговая отметка</td>
                @else
                    <td style="font-weight: bold; border: 2px solid #000; vertical-align: middle;">Отметка</td>
                @endif
            </tr>

            @if ($isHighSchool)
                @foreach ($grouped as $item)
                    @php
                        $first  = is_numeric($item['first'] ?? null)  ? (int) $item['first']  : null;
                        $second = is_numeric($item['second'] ?? null) ? (int) $item['second'] : null;

                        // Итог: среднее с округлением к ближайшему (4.5 -> 5)
                        $final = null;
                        if (!is_null($first) && !is_null($second)) {
                            $final = (int) round(($first + $second) / 2, 0, PHP_ROUND_HALF_UP);
                        } elseif (!is_null($first)) {
                            $final = $first;
                        } elseif (!is_null($second)) {
                            $final = $second;
                        }
                    @endphp
                    <tr>
                        <td style="border: 2px solid #000; vertical-align: middle;">{{ $loop->iteration }}</td>
                        <td style="border: 2px solid #000; vertical-align: middle;">{{ $item['subject'] }}</td>
                        <td style="border: 2px solid #000; vertical-align: middle;">
                            {{ $first ?? '-' }}
                            @if(!is_null($first)) ({{ $gradeLabel($first) }}) @endif
                        </td>
                        <td style="border: 2px solid #000; vertical-align: middle;">
                            {{ $second ?? '-' }}
                            @if(!is_null($second)) ({{ $gradeLabel($second) }}) @endif
                        </td>
                        <td style="border: 2px solid #000; vertical-align: middle;">
                            {{ $final ?? '-' }}
                            @if(!is_null($final)) ({{ $gradeLabel($final) }}) @endif
                        </td>
                    </tr>
                @endforeach
            @else
                @foreach ($passedTests as $index => $test)
                    <tr>
                        <td style="border: 2px solid #000; vertical-align: middle;">{{ $index + 1 }}</td>
                        <td style="border: 2px solid #000; vertical-align: middle;">{{ optional($test->subject)->subject ?? 'Без названия' }}</td>
                        <td style="border: 2px solid #000; vertical-align: middle;">
                            {{ $test->score }}
                            ({{ match($test->score) {
                        2 => 'неудовлетворительно',
                        3 => 'удовлетворительно',
                        4 => 'хорошо',
                        5 => 'отлично',
                        default => 'неизвестно'
                    }
                    }})
                        </td>
                    </tr>
                @endforeach
            @endif
        </table>

    </div>

    <div style="margin-top: 10px; font-size: 12px; font-weight: bold;">
        Пройдено с применением контроля условий проведения промежуточной аттестации с целью выявления нарушений.
        <br>
        @if ($child->grade_id != 11 && $child->grade_id != 9)
            @php
                $nextGrade = $child->grade_id + 1;
                $prefix = $nextGrade === 2 ? 'во' : 'в';
            @endphp
            По результатам прохождения промежуточной аттестации переведен(а) {{ $prefix }} {{ $nextGrade }} класс.
        @endif
    </div>

    <div style="margin-top: 5px;">
        <p style="font-size: 15px; font-weight:bold;">
            Директор ОАНО СОШ «Пенаты»_____________________ /Е.А. Веретильная/
        </p>


    </div>
</div>
</body>
</html>





