<?php

namespace Keerill\HttpLogger\Formatters\Messages;

use Psr\Http\Message\MessageInterface;

final class JsonMessageFormatter implements MessageFormatterInterface
{
    public function getContent(MessageInterface $message): mixed
    {
        return json_decode($message->getBody()->__toString(), true);
    }
}
