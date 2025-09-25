<?php

namespace Database\Seeders;


// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

//        User::factory()->create([
//            'name' => 'Test User',
//            'email' => 'test@example.com',
//        ]);

        $this->call([
//            ImportModxSqlSeeder::class,
            SubjectSeeder::class,
            GradesSeeder::class,
            TestTypeSeeder::class,
            UserSeeder::class,
            ChildSeeder::class,
            TransferQuestionsByTestsSeeder::class,
            FixImagePathsSeeder::class,
//            TransferAdditionalQuestionsByTestSeeder::class,
//            TestSeeder::class,  /* включен в TransferQuestionsByTestsSeeder */
//            GradeSubjectTestSeeder::class,
//            QuestionSeeder::class, /* включен в TransferQuestionsByTestsSeeder */
//            PassedTestSeeder::class, /* проверка будет в тестовом проекте путем прохождения тестов */
//            TicketSeeder::class,
//            MessageSeeder::class,
//            CertificateSeeder::class,
//            AnswerSeeder::class, /* включен в TransferQuestionsByTestsSeeder */
//            AnswerSeeder::class,
        ]);
    }
}
