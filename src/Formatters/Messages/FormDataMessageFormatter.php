<?php

namespace Keerill\HttpLogger\Formatters\Messages;

use GuzzleHttp\Psr7\MultipartStream;
use Keerill\HttpLogger\Parsers\MultipartFormDataParser;
use Psr\Http\Message\MessageInterface;

class FormDataMessageFormatter implements MessageFormatterInterface
{
    public function __construct(
        protected MultipartFormDataParser $parser = new MultipartFormDataParser
    ) {
    }

    public function getContent(MessageInterface $message): array
    {
        /** @var MultipartStream $stream */
        $stream = $message->getBody();

        return $this->parser
            ->parse($stream->__toString(), $stream->getBoundary());
    }
}
