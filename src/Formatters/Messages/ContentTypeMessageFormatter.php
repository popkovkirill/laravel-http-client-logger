<?php

namespace Keerill\HttpLogger\Formatters\Messages;

use Psr\Http\Message\MessageInterface;

class ContentTypeMessageFormatter implements MessageFormatterInterface
{

    public function getContent(MessageInterface $message): mixed
    {
        $contentType = $message->getHeader('Content-Type')[0] ?? null;

        return match (true) {
            str_starts_with($contentType, 'application/json') => (new JsonMessageFormatter())->getContent($message),
            str_starts_with($contentType, 'multipart/form-data') => (new FormDataMessageFormatter())->getContent($message),
            str_starts_with($contentType, 'application/octet-stream') => '(file)',
            str_starts_with($contentType, 'image') => '(image)',
            str_starts_with($contentType, 'application/x-www-form-urlencoded') => (new FormUrlEncodedMessageFormatter())->getContent($message),
            default => (new StringMessageFormatter())->getContent($message)
        };
    }
}
