<?php

namespace App\Models\Collections;

use App\Models\Tag;

class TagsCollection
{
    private array $tags = [];

    public function __construct(array $tags = null)
    {
        foreach ($tags as $tag) {
            $this->addToTagsCollection(new Tag($tag['tag_id'], $tag['tag']));
        }
    }

    public function addToTagsCollection(Tag $tag): void
    {
        $this->tags[] = $tag;
    }

    public function getTagsCollection(): array
    {
        return $this->tags;
    }
}
