<?php

declare(strict_types=1);

namespace Testvendor\SortedLinkedList;

use Testvendor\SortedLinkedList\Enum\ListTypeEnum;
use Testvendor\SortedLinkedList\Exception\MixedContentException;
use Testvendor\SortedLinkedList\Exception\UnsupportedTypeException;

class SortedLinkedList implements \Countable
{
    private ?Node $head = null;
    private ?Node $tail = null;
    private ?ListTypeEnum $typeEnum = null;

    public function __construct()
    {
    }

    public function add(mixed $value): void
    {
        $selectedTypeEnum = $this->checkValueType($value);

        $node = new Node($value);

        if($this->head === null) {
            $this->head = $node;
            $this->tail = $node;
        } else {
            $current = $this->head;
            $prev = null;
            $inPlace = false;

            while ($current) {
                if ($this->shouldInjectNodeBefore($node, $current, $selectedTypeEnum)) {
                    $prev?->setNext($node);
                    $node->setNext($current);
                    if($this->head === $current) {
                        $this->head = $node;
                    }
                    $inPlace = true;
                    break;
                }

                $prev = $current;
                $current = $current->getNext();
            }


            if ($inPlace === false) {
                $prev?->setNext($node);
                $this->tail = $node;
            }

        }
    }

    public function remove(mixed $value): ?Node
    {
        $this->checkValueType($value);

        $current = $this->head;
        $prev = null;

        while ($current) {
            if($current->getValue() === $value) {
                if($prev) {
                    $prev->setNext($current->getNext());
                } else {
                    $this->head = $current->getNext();
                }

                if($current->getNext() === null) {
                    $this->tail = $prev;
                }
                return $current;
            }

            $prev = $current;
            $current = $current->getNext();
        }
        return null;
    }

    public function getHead(): ?Node
    {
        return $this->head;
    }

    public function getTail(): ?Node
    {
        return $this->tail;
    }

    /**
     * @return array<int|string>
     */
    public function getAllValues(): array
    {
        $current = $this->head;
        $values = [];
        while ($current) {
            $values[] = $current->getValue();
            $current = $current->getNext();
        }
        return $values;
    }

    public function count(): int
    {
        $current = $this->head;

        $counter = 0;
        while ($current) {
            ++$counter;
            $current = $current->getNext();
        }

        return $counter;
    }


    public function getTypeEnum(): ?ListTypeEnum
    {
        return $this->typeEnum;
    }

    private function setTypeEnum(ListTypeEnum $typeEnum): void
    {
        $this->typeEnum = $typeEnum;
    }

    private function checkValueType(mixed $value): ListTypeEnum
    {
        $newValueTypeEnum = $this->getAllowedTypeEnumFromValue($value);
        if($this->getTypeEnum() === null) {
            $this->setTypeEnum($newValueTypeEnum);
        } elseif($newValueTypeEnum != $this->getTypeEnum()) {
            throw new MixedContentException('Type of new value (' . $newValueTypeEnum->value . ') is different from the sortedList value (' . $this->getTypeEnum()->value . ')');
        }

        return $newValueTypeEnum;
    }

    private function getAllowedTypeEnumFromValue(mixed $value): ListTypeEnum
    {

        if (is_int($value)) {
            return ListTypeEnum::INTEGER;
        } elseif (is_string($value)) {
            return ListTypeEnum::STRING;
        } else {
            throw new UnsupportedTypeException('Type ' . gettype($value) . ' is not supported');
        }

    }

    private function shouldInjectNodeBefore(Node $node, Node $current, ListTypeEnum $listTypeEnum): bool
    {
        if ($listTypeEnum === ListTypeEnum::INTEGER) {
            return $node->getValue() < $current->getValue();
        } else {
            return  0 > strcmp((string) $node->getValue(), (string) $current->getValue());
        }
    }
}
