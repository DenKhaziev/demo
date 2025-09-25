<?php

use App\Events\TicketCreated;
use App\Http\Controllers\Admin\AdminActivateAccountController;
use App\Http\Controllers\Admin\AdminCertificateController;
use App\Http\Controllers\Admin\AdminChildController;
use App\Http\Controllers\Admin\AdminDocumentByGradeController;
use App\Http\Controllers\Admin\AdminMainController;
use App\Http\Controllers\Admin\AdminMessageController;
use App\Http\Controllers\Admin\AdminParentController;
use App\Http\Controllers\Admin\AdminPassedTestController;
use App\Http\Controllers\Admin\AdminPersonalAffairController;
use App\Http\Controllers\Admin\AdminQuestionController;
use App\Http\Controllers\Admin\AdminSubjectController;
use App\Http\Controllers\Admin\AdminTestController;
use App\Http\Controllers\Admin\AdminTicketController;
use App\Http\Controllers\Auth\StudentLoginController;
use App\Http\Controllers\ChildController;
use App\Http\Controllers\Document\DocumentController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\Policy\PolicyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketTestController;
use App\Models\Child;
use App\Notifications\StudentRegisteredNotification;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;


Route::get('/', function () {
    return Inertia::render('Main', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
        'prices' => config('prices')
    ]);
});


// тикеты
//Route::get('/ticket-test', [TicketTestController::class, 'create']);
//Route::post('/ticket-test', [TicketTestController::class, 'store']);
//
////сообщение тикетов
//Route::get('/ticket-message-test', [TicketTestController::class, 'createMessage']);
//Route::post('/ticket/message-test', [TicketTestController::class, 'storeMessage']);;
//
//// запрос на формирование справки
//Route::get('/certificate-request', [ParentController::class, 'create'])->name('certificates.request-send');
Route::post('/certificate-request', [ParentController::class, 'storeCertificate'])->name('certificates.request-send');
//
//// запрос на формирование личного дела
//Route::get('/personal-affair-request', [AdminPersonalAffairController::class, 'create'])->name('personal-affair.request-send');
Route::post('/personal-affair-request', [AdminPersonalAffairController::class, 'store'])->name('personal-affair.request-send');
//
//// запрос на активацию личного кабинета ученика
//Route::get('/child/activate', [ChildController::class, 'create'])->name('create.access');
Route::post('/child/activate', [ChildController::class, 'upload'])->name('documents.upload');




Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Общие роуты
Route::middleware(['web'])->group(function () {
    Route::post('/upload/{child}/{grade}/{field}', [AdminDocumentByGradeController::class, 'upload'])->name('documents.upload');
    Route::get('/download-document', [DocumentController::class, 'downloadDocument'])->name('documents.download');
    Route::get('/partnership', [DocumentController::class, 'partnership'])->name('partnership');
    Route::get('/policy', [PolicyController::class, 'index'])->name('policy.index');
    Route::get('/family_edu', [PolicyController::class, 'familyEdu'])->name('policy.family_edu');


});

Route::middleware(['web', 'auth', 'is_admin'])->prefix('admin_panel')->group(function () {
// главная страница админки
    Route::get('/', [AdminMainController::class, 'index'])->name('admin.main')->middleware(['auth', 'verified']);

    //родители
    Route::get('/parents', [AdminParentController::class, 'index'])->name('parents.index');
    Route::get('/parents/{parent}/children', [AdminParentController::class, 'children'])->name('parents.children');
    Route::patch('/parents/children/{child}/edit', [AdminParentController::class, 'gradeEdit'])->name('parents.children.grade.edit');

    // ученики
    Route::get('/children', [AdminChildController::class, 'index'])->name('children.index');
    Route::get('/children/active-count', [AdminChildController::class, 'count'])->name('children.active-count');
    Route::get('/children/{child}', [AdminChildController::class, 'show'])->name('children.show');
    Route::put('/children/{child}/pay', [AdminChildController::class, 'pay'])->name('children.pay');
    Route::put('/children/{child}/up_grade', [AdminChildController::class, 'gradeUp'])->name('children.up.grade');
    Route::put('/children/{child}/edit', [AdminChildController::class, 'update'])->name('children.update');
    Route::delete('/children/{child}', [AdminChildController::class, 'destroy'])->name('children.destroy');



    // тесты
    Route::get('/tests', [AdminTestController::class, 'index'])->name('tests');
    Route::get('/test/create', [AdminTestController::class, 'create'])->name('tests.create');
    Route::post('/test', [AdminTestController::class, 'store'])->name('tests.store');
    Route::get('/test/{test}', [AdminTestController::class, 'show'])->name('tests.show');
    Route::delete('/test/{test}', [AdminTestController::class, 'destroy'])->name('tests.destroy');
    Route::get('/tests/with_trashed', [AdminTestController::class, 'trashed'])->name('tests.trashed');
    Route::put('/tests/with_trashed/{test}', [AdminTestController::class, 'restore'])->withTrashed()->name('tests.restore');

    // пройденные тесты
    Route::put('/passed_tests/{passedTest}/attempts_up', [AdminPassedTestController::class, 'attemptsUp'])->name('passedTest.up.attempts');
    Route::delete('/passed_tests/{passedTest}', [AdminPassedTestController::class, 'destroy'])->name('passedTest.destroy');

    // Вопросы
    Route::delete('/questions/{question}', [AdminQuestionController::class, 'destroy'])->name('questions.destroy');
    Route::get('/tests/{test}/questions/with_trashed', [AdminQuestionController::class, 'trashed'])->name('questions.trashed');
    Route::put('/tests/questions/with_trashed/{question}', [AdminQuestionController::class, 'restore'])->withTrashed()->name('questions.restore');
    Route::post('/test/{test}/questions', [AdminQuestionController::class, 'store'])->name('questions.store');

    // предметы
    Route::get('/subjects', [AdminSubjectController::class, 'index'])->name('subjects.index');
    Route::delete('/subjects/{subject}', [AdminSubjectController::class, 'destroy'])->name('subjects.destroy');
    Route::put('/subjects/{subject}', [AdminSubjectController::class, 'update'])->name('subjects.update');
    Route::get('/subjects/create', [AdminSubjectController::class, 'create'])->name('subjects.create');
    Route::post('/subjects', [AdminSubjectController::class, 'store'])->name('subjects.store');
    Route::get('/subjects/with_trashed', [AdminSubjectController::class, 'trashed'])->name('subjects.trashed');
    Route::put('/subjects/with_trashed/{subject}', [AdminSubjectController::class, 'restore'])->withTrashed()->name('subjects.restore');

    // обращения
    Route::get('/tickets', [AdminTicketController::class, 'index'])->name('tickets.index');
    Route::put('/tickets/{ticket}/close', [AdminTicketController::class, 'toggle'])->name('tickets.toggle');
    Route::get('/ticket/{ticket}/messages', [AdminMessageController::class, 'index'])->name('messages.index');
    Route::post('/ticket/{ticket}/messages', [AdminMessageController::class, 'store'])->name('messages.store');
    Route::delete('/ticket/messages/{message}', [AdminMessageController::class, 'destroy'])->name('messages.destroy');
    Route::get('/ticket/unread-count', [AdminTicketController::class, 'unreadCount'])->name('tickets.unread-count');
    Route::get('/ticket/create/{user}', [AdminTicketController::class, 'ticketCreate'])->name('tickets.create');
    Route::get('/ticket/{ticket}', [AdminTicketController::class, 'markUnread'])->name('tickets.mark-unread');
    Route::put('/ticket/{ticket}/mark-read', [AdminTicketController::class, 'markRead'])->name('tickets.mark-read');
    Route::post('/ticket', [AdminTicketController::class, 'storeTicket'])->name('tickets.store');


    // справки
    Route::get('/certificates/requests', [AdminCertificateController::class, 'index'])->name('cert_requests.index');
    Route::put('/certificates/requests/{certificate}', [AdminCertificateController::class, 'update'])->name('cert_requests.update');
    Route::post('/certificates/{child}/generate', [AdminCertificateController::class, 'generate'])->name('certificates.generate');
    Route::get('/certificates/{child}/download', [AdminCertificateController::class, 'download'])->name('certificates.download');
    Route::get('/certificates/requests-count', [AdminCertificateController::class, 'count'])->name('certificates.requests-count');
    // Route::get('/documents/download-all/{child}', [DocumentController::class, 'downloadAll'])->name('admin.documents.downloadAll');


// документы учеников
//    Route::post('/upload/{child}/{grade}/{field}', [AdminDocumentByGradeController::class, 'upload'])->name('documents.upload');

    // личные дела учеников 1 класса
    Route::get('/personal_affairs/requests', [AdminPersonalAffairController::class, 'index'])->name('personal.affairs.index');
    Route::get('/personal_affairs/requests-count', [AdminPersonalAffairController::class, 'count'])->name('personal.affairs.requests-count');
    Route::put('/personal_affairs/requests/{documentByGrade}', [AdminPersonalAffairController::class, 'update'])->name('personal.affairs.update');

    //запросы на активацию ЛК
    Route::get('/activate/requests', [AdminActivateAccountController::class, 'activateAccess'])->name('activate.access.index');
    Route::get('/activate/requests-count', [AdminActivateAccountController::class, 'count'])->name('activate.requests-count');
    Route::put('/activate/requests/{documentByGrade}', [AdminActivateAccountController::class, 'update'])->name('activate.access.update');
    Route::put('/activate/requests/{documentByGrade}/delete', [AdminActivateAccountController::class, 'destroy'])->name('activate.access.delete');
    Route::put('/activate/requests/{documentByGrade}/unactive', [AdminActivateAccountController::class, 'unactive'])->name('activate.access.unactive');

});

Route::middleware(['auth', 'usertype:parent'])->group(function () {
    Route::get('/parent', [ParentController::class, 'index'])->name('parent.index');
    Route::post('/parent/personal_infos', [ParentController::class, 'personalInfosStore'])->name('store.personal_infos');
    Route::get('/personal-infos/preview/{childId}', [ParentController::class, 'preview'])->name('personal-infos.preview');
    Route::get('/parent/child/show/{child}', [ParentController::class, 'childShow'])->name('parent.child.show');
    Route::get('/parent/child/add', [ParentController::class, 'addChild'])->name('parent.child.add');
    Route::post('/parent/child', [ParentController::class, 'storeChild'])->name('store.parent.child');
    Route::put('/personal_affairs/requests/{documentByGrade}', [ParentController::class, 'personalAffairUpdate'])->name('parent.personal.affair.request');
    Route::get('/parent/ticket/create', [ParentController::class, 'ticketCreate'])->name('parent.ticket.create');
    Route::post('/parent/ticket', [ParentController::class, 'storeTicket'])->name('parent.ticket.store');
    Route::delete('/parent/messages/{message}', [ParentController::class, 'destroy'])->name('parent.messages.destroy');
    Route::get('/parent/payment', [ParentController::class, 'payment'])->name('parent.payment');
    Route::get('/parent/messages', [ParentController::class, 'ticketMessages'])->name('parent.ticket');
    Route::post('/parent/{ticket}/messages', [ParentController::class, 'storeMessages'])->name('parent.messages.store');
    Route::put('/parent/{ticket}/mark-read', [ParentController::class, 'markAsRead'])->name('parent.ticket.mark-read');
    Route::get('/tickets/unread-check', [ParentController::class, 'unreadTicket'])->name('tickets.unread-check');

});

Route::get('/student/login', [StudentLoginController::class, 'show'])->name('student.login');
Route::post('/student/login', [StudentLoginController::class, 'login']);
Route::post('/student/logout', [StudentLoginController::class, 'logout'])->name('student.logout');

Route::middleware(['web', 'child'])->group(function () {
    Route::get('/student', [ChildController::class, 'index'])->name('student.index');
    Route::get('/student/testing/{test}', [ChildController::class, 'testShow'])->name('student.test.show');
    Route::post('/student/testing', [ChildController::class, 'submit'])->name('student.test.submit');
});



Route::get('/sitemap.xml', function () {
    return Sitemap::create()
        ->add(Url::create('/'))
        ->add(Url::create('/partnership'))
        ->add(Url::create('/register'))
        ->add(Url::create('/login'));

});



require __DIR__.'/auth.php';
