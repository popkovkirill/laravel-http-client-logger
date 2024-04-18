<?php

namespace Keerill\HttpLogger\Formatters\Messages;

use Psr\Http\Message\MessageInterface;

final class StringMessageFormatter implements MessageFormatterInterface
{
    public function getContent(MessageInterface $message): string
    {
        return $message->getBody()->__toString();
    }
}
