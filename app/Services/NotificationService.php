<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    public function sendToAdmin(string $message = 'Laporan baru telah diterima dan memerlukan verifikasi.'): void
    {
        Log::info("Notification to Admin: " . $message);
        Session::flash('notification_admin', $message);
    }

    public function sendToOfficer(string $message = 'Tugas penanganan baru telah ditugaskan kepada Anda.'): void
    {
        Log::info("Notification to Officer: " . $message);
        Session::flash('notification_officer', $message);
    }

    public function sendToReporter(string $message = 'Status laporan Anda telah diperbarui.'): void
    {
        Log::info("Notification to Reporter: " . $message);
        Session::flash('notification_reporter', $message);
    }
}
