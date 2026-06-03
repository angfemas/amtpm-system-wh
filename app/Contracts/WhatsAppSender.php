<?php

namespace App\Contracts;

interface WhatsAppSender
{
    /**
     * @param  string  $to  Nomor WA (62...) atau ID grup sesuai dokumentasi provider
     */
    public function sendText(string $to, string $message): bool;
}
