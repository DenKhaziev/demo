<?php

namespace App\Services;

use App\Models\User;
use App\Models\DocumentByGrade;
use App\Notifications\CertificateUploadedNotification;
use App\Notifications\AccessActivatedNotification;
use App\Notifications\AddNewStudentRegisteredNotification;
use App\Notifications\AdminMessageNotification;
use App\Notifications\DocumentAboutTransferUploadedNotification;

class NotificationService
{
    public function sendCertificateUploaded(User $parent, DocumentByGrade $document): void
    {
        $parent->notify(new CertificateUploadedNotification($document));
    }

    public function sendDocumentAboutTransferUploaded (User $parent, DocumentByGrade $document): void
    {
        $parent->notify(new DocumentAboutTransferUploadedNotification($document));
    }

    public function sendAccessActivated(User $parent): void
    {
        $parent->notify(new AccessActivatedNotification());
    }

    public function sendAdminMessage(User $parent, string $message): void
    {
        $parent->notify(new AdminMessageNotification($message));
    }

    public function sendNewStudentAccess(User $parent, string $login, string $password): void
    {
        $parent->notify(new AddNewStudentRegisteredNotification($login, $password));
    }
}

