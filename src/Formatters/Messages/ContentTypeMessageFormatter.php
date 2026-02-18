<?php

namespace Keerill\HttpLogger\Formatters\Messages;

use Psr\Http\Message\MessageInterface;

class ContentTypeMessageFormatter implements MessageFormatterInterface
{
    public function getContent(MessageInterface $message): mixed
    {
        $contentType = $message->getHeader('Content-Type')[0] ?? 'unknown';

        return match (true) {
            str_starts_with($contentType, 'application/json') => (new JsonMessageFormatter())->getContent($message),
            str_starts_with($contentType, 'multipart/form-data') => (new FormDataMessageFormatter())->getContent($message),
            str_starts_with($contentType, 'application/x-www-form-urlencoded') => (new FormUrlEncodedMessageFormatter())->getContent($message),
            str_starts_with($contentType, 'text/') => (new StringMessageFormatter())->getContent($message),

            // Бинарные типы
            str_starts_with($contentType, 'image/') => '(binary)',
            str_starts_with($contentType, 'audio/') => '(binary)',
            str_starts_with($contentType, 'video/') => '(binary)',
            str_starts_with($contentType, 'application/pdf') => '(binary)',
            str_starts_with($contentType, 'application/zip') => '(binary)',
            str_starts_with($contentType, 'application/gzip') => '(binary)',
            str_starts_with($contentType, 'application/octet-stream') => '(binary)',
            str_starts_with($contentType, 'application/vnd.') => '(binary)',
            str_starts_with($contentType, 'application/msword') => '(binary)',

            default => 'unknown'
        };
    }
}
