<?php

namespace Keerill\HttpLogger\Formatters\Messages;

use Psr\Http\Message\MessageInterface;

interface MessageFormatterInterface
{
    public function getContent(MessageInterface $message): mixed;
}
