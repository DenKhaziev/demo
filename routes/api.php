<?php

use App\Http\Controllers\Api\Admin\AdminChildrenController;
use App\Http\Controllers\Api\Admin\AdminParentsController;
use App\Http\Controllers\Api\Admin\CertificateController;
use App\Http\Controllers\Api\Admin\SubjectController;
use App\Http\Controllers\Api\Admin\testController;
use App\Http\Controllers\Test\TestingController;
use App\Http\Controllers\Api\Child\StudentController;
use App\Http\Controllers\Api\Parent\ParentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/test', [TestingController::class, 'test']);
// admin account
/*
 * вкладка Родители - заявки и активные (в обои случаях список юзеров - либо дети оплачены, либо нет) - далее короткая карточка ученика
 * вкладка Ученики - не оплачено, оплачено, со справкой (список учеников по классам) - далее обычная карточка ученика с тестами
 * вкладка Предметы - список всех предметов для всех классов
 * вкладка Тесты - список всех тестирований по классам + форма для добавления тестов (предмет, класс, тип, длительность)
 * вкладка предметы - список всех школьных предметов
 * вкладка Отчет по классу - выбор класса и выпадает список всех учеников с оценками и с наличием справок
 * вкладка Запросы на справки - список учеников, которые запросили справку (надо сделать так, чтобы нотис вылезал в админке)
 */
// Родители
Route::get('/admin', [TestingController::class, 'index'])->name('admin.welcome'); // картинка админа за компом
Route::get('/admin/parents/new', [AdminParentsController::class, 'newParents'])->name('admin.parents.new');
Route::get('/admin/parents/active', [AdminParentsController::class, 'activeParents'])->name('admin.parents.active');
Route::get('/admin/parents/{user}/children', [AdminParentsController::class, 'showChildren'])->name('admin.parents.children');
// Ученики
Route::get('admin/children/new', [AdminChildrenController::class, 'newChildren'])->name('admin.children.new');
Route::get('admin/children/active', [AdminChildrenController::class, 'activeChildren'])->name('admin.children.active');
Route::get('admin/children/active/{child}', [AdminChildrenController::class, 'showChild'])->name('admin.children.active');
Route::get('admin/children/withCertificate', [AdminChildrenController::class, 'withCertificateChildren'])->name('admin.children.withCertificate');
// тесты - можно через таблицу grade_subject_test сделать и вытаскивать через зависимости $test->subject
Route::get('/admin/tests', [TestController::class, 'index'])->name('admin.tests');
// предметы - можно добавлять и удалять предметы
Route::get('/admin/subjects', [SubjectController::class, 'index'])->name('admin.subjects');
// запросы на справки - получаем сразу массив с 2мя зависимостями - certificate-child->user
Route::get('/admin/certificates/requests', [CertificateController::class, 'index'])->name('admin.certificates.requests');


// parent account
/*
 * основная страница - список, если ребенок оплачен то фио активно, иначе активна кнопка - "Оплатить"
 * карточка ученика - см выше, надо чтобы фио было активно. На этой странице список пройденных и не пройденных тестов - внизу кнопка "завершить уч год"
 */
Route::get('/parent', [ParentController::class, 'index'])->name('parent.main');
Route::get('/parent/{child}', [ParentController::class, 'showChild'])->name('parent.showChild');


// child account
/*
 * основная страница - список пройденных и не пройденных тестов
 * страница с рабочим тестом - последний этап, когда все остальное будет готово
 */
Route::get('/student/{child}', [StudentController::class, 'show'])->name('student.main');


