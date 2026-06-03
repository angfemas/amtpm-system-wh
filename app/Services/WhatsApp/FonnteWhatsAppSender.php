<?php

namespace App\Services\WhatsApp;

use App\Contracts\WhatsAppSender;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteWhatsAppSender implements WhatsAppSender
{
    public function __construct(
        protected ?string $token = null,
        protected ?string $baseUrl = null,
    ) {
        $this->token ??= (string) config('services.fonnte.token', '');
        $this->baseUrl ??= rtrim((string) config('services.fonnte.base_url', 'https://api.fonnte.com'), '/');
    }

    public function sendText(string $to, string $message): bool
    {
        if ($this->token === '') {
            Log::warning('WhatsApp (Fonnte): token kosong, pesan tidak dikirim.', ['to' => $to]);

            return false;
        }

        $response = Http::asForm()
            ->withHeaders(['Authorization' => $this->token])
            ->timeout(15)
            ->post($this->baseUrl.'/send', [
                'target' => $to,
                'message' => $message,
            ]);

        if (! $response->successful()) {
            Log::error('WhatsApp (Fonnte): permintaan gagal.', [
                'to' => $to,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return false;
        }

        return true;
    }
}
