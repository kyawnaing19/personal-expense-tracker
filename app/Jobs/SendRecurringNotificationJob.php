<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Transaction;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SendRecurringNotificationJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public User $user,
        public string $transactionId,
        public string $title,
        public string $body,
        public array $data = []
    ) {}

    public function handle(NotificationService $notificationService): void
    {
        $transaction = Transaction::find($this->transactionId);
        if (!$transaction || $transaction->status !== 'pending') {
            Log::info("Skipped recurring notification for transaction #{$this->transactionId} - Status is no longer pending.");
            return;
        }
        $notificationService->sendToUser($this->user, $this->title, $this->body, $this->data);
    }
}
