<?php

namespace Database\Seeders;

use App\Models\Answer;
use App\Models\ModxTestingItem;
use App\Models\Question;
use App\Models\Subject;
use App\Models\Test;
use App\Models\TestType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransferQuestionsByTestsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /* полностью рабочий код, но без указаний типов тестов */
//        $oldRecords = ModxTestingItem::all();
//
//        DB::transaction(function () use ($oldRecords) {
//            foreach ($oldRecords as $record) {
//                // Найти предмет по названию
//                $subject = Subject::where('subject', $record->subject)->first();
//
//                if (!$subject) {
//                    continue; // Пропускаем запись, если предмет не найден
//                }
//
//                // Найти или создать тест
//                $test = Test::firstOrCreate([
//                    'subject_id' => $subject->id,
//                    'grade_id' => $record->klass,
//                    'test_type_id' => 1
//                ], [
////                    'duration' => $record->long,
//                    'number_of_attempts' => $record->count_peresdach,
//                    'allotted_time' => 60,
////                    'version' => $record->version,
////                    'is_mandatory' => $record->obyazatelnyi,
//                ]);
//
//                // Проверяем, есть ли вопросы
//                if (!isset($record->questions['all_quest']) || !is_array($record->questions['all_quest'])) {
//                    continue;
//                }
//
//                foreach ($record->questions['all_quest'] as $questionData) {
//                    // Создаем вопрос
//                    $question = Question::create([
//                        'test_id' => $test->id,
////                        'num' => $questionData['num'],
////                        'type' => $questionData['type'],
//                        'question' => $questionData['text'],
//                        'has_image' => $questionData['hasImage'],
//                        'image_path' => $questionData['photo'] ?? null,
//                    ]);
//
//                    // Добавляем ответы в таблицу answers
//                    foreach ($questionData['answers'] as $answerText) {
//                        Answer::create([
//                            'question_id' => $question->id,
//                            'answer' => $answerText,
//                            'is_correct' => in_array($answerText, $questionData['correct']), // Проверяем, является ли ответ правильным
//                        ]);
//                    }
//                }
//            }
//        });

        $oldRecords = ModxTestingItem::all();

//        DB::transaction(function () use ($oldRecords) {
            foreach ($oldRecords as $record) {
                // Найти предмет по названию
                $subject = Subject::where('subject', $record->subject)->first();

                if (!$subject) {
                    continue; // Пропускаем запись, если предмет не найден
                }

                $testType = TestType::where('type', $record->version)->first();

                if (!$testType) {
                    // Если версия не "demo", устанавливаем по obyazatelnyi
                    $testTypeId = ($record->obyazatelnyi == 1) ? 3 : 2; // 3 = required, 2 = optional
                } else {
                    $testTypeId = $testType->id; // Если нашли test_type, используем его ID
                }

                // Найти или создать тест
                $test = Test::firstOrCreate([
                    'subject_id' => $subject->id,
                    'grade_id' => $record->klass,
                    'test_type_id' => $testTypeId,
                ], [
//                    'duration' => $record->long,
                    'number_of_attempts' => $record->count_peresdach,
                    'allotted_time' => 60,
//                    'version' => $record->version,
//                    'is_mandatory' => $record->obyazatelnyi,
                ]);

                // Проверяем, есть ли вопросы
                if (!isset($record->questions['all_quest']) || !is_array($record->questions['all_quest'])) {
                    continue;
                }

                foreach ($record->questions['all_quest'] as $questionData) {
                    // Создаем вопрос
                    $question = Question::create([
                        'test_id' => $test->id,
//                        'num' => $questionData['num'],
//                        'type' => $questionData['type'],
                        'question' => $questionData['text'],
                        'has_image' => $questionData['hasImage'],
                        'image_path' => $questionData['photo'] ?? null,
                    ]);

                    // Добавляем ответы в таблицу answers
                    foreach ($questionData['answers'] as $answerText) {
                        Answer::create([
                            'question_id' => $question->id,
                            'answer' => $answerText,
                            'is_correct' => in_array($answerText, $questionData['correct']), // Проверяем, является ли ответ правильным
                        ]);
                    }
                }
            }
//        });
    }
}
