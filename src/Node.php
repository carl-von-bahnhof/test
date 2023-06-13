<?php

namespace Testvendor\SortedLinkedList;

class Node
{
    private ?Node $next = null;

    public function __construct(private int|string $value)
    {
    }

    public function getValue(): int|string
    {
        return $this->value;
    }

    public function setNext(?Node $node): void
    {
        $this->next = $node;
    }

    public function getNext(): ?Node
    {
        return $this->next;
    }

}
