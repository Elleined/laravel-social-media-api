<?php

namespace App;

use DateTimeImmutable;

readonly class PostParams
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public readonly int $page,
        public readonly int $perPage,
        public readonly ?string $authorId,
        public readonly ?string $titleSearch,
        public readonly ?Status $status,
        public readonly ?Direction $direction,
        public readonly ?DateTimeImmutable $from,
        public readonly ?DateTimeImmutable $to
    ) {}
}
