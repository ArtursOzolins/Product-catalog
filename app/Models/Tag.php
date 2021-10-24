<?php

namespace App\Models;

class Tag
{
    private string $tag_id;
    private string $tag;

    public function __construct(string $tag_id, string $tag)
    {
        $this->tag_id = $tag_id;
        $this->tag = $tag;
    }

    public function getTag(): string
    {
        return $this->tag;
    }

    public function getTagId(): string
    {
        return $this->tag_id;
    }
}
