<?php

namespace Keerill\HttpLogger\Formatters\Messages;

use Psr\Http\Message\MessageInterface;

class FormUrlEncodedMessageFormatter implements MessageFormatterInterface
{
    public function getContent(MessageInterface $message): array
    {
        $data = [];

        parse_str($message->getBody()->__toString(), $data);

        return $data;
    }
}
