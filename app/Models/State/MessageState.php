<?php

namespace App\Models\State;

class MessageState
{
    public static string $MESSAGE_SESSION_KEY = 'message';

    public function __construct(
        public MessageStateType $type,
        public string $title,
        public string $content
    ) {}

    public function toArray(): array
    {
        return [
            'type' => $this->type->value,
            'title' => $this->title,
            'content' => $this->content,
        ];
    }

    public static function fromArray(array $data): MessageState
    {
        return new MessageState(
            MessageStateType::from($data['type']),
            $data['title'],
            $data['content']
        );
    }
}
