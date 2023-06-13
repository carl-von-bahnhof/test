<?php

declare(strict_types=1);

namespace Testvendor\tests;

use PHPUnit\Framework\TestCase;
use Testvendor\SortedLinkedList\Exception\MixedContentException;
use Testvendor\SortedLinkedList\Exception\UnsupportedTypeException;
use Testvendor\SortedLinkedList\SortedLinkedList;

/**
 * @covers \Testvendor\SortedLinkedList\SortedLinkedList
 */
class SortedLinkedListTest extends TestCase
{
    /**
     *@covers \Testvendor\SortedLinkedList\SortedLinkedList::add
     *@covers \Testvendor\SortedLinkedList\SortedLinkedList::getHead
     *@covers \Testvendor\SortedLinkedList\SortedLinkedList::getTail
     *@covers \Testvendor\SortedLinkedList\SortedLinkedList::getAllValues
     *@covers \Testvendor\SortedLinkedList\SortedLinkedList::count
     *@dataProvider sortedLinkedListDataProvider
     *
     * @param array<int|string> $items
     * @param array<int|string> $expectedSortedItems
     */
    public function testAddingItems(array $items, array $expectedSortedItems): void
    {
        $this->assertTrue(true);
        $sortedLinkedList = new SortedLinkedList();
        foreach ($items as $value) {
            $sortedLinkedList->add($value);
        }

        $this->assertEquals(count($items), $sortedLinkedList->count());
        $this->assertEquals(reset($expectedSortedItems), $sortedLinkedList->getHead()?->getValue());
        $this->assertEquals(end($expectedSortedItems), $sortedLinkedList->getTail()?->getValue());
        $this->assertSame($expectedSortedItems, $sortedLinkedList->getAllValues());

    }

    /**
     * @return  array<int, array<int, array<int, int|string>>>
     */
    public static function sortedLinkedListDataProvider(): array
    {
        return [
            [
                [],
                [],
            ],
            [
                [1, 2, 3],
                [1, 2, 3],
            ],
            [
                [3, 2, 1],
                [1, 2, 3],
            ],
            [
                [3, 2, 1, 80, 120, 3, 3],
                [1, 2, 3, 3, 3, 80, 120],
            ],
            [
                ['c', 'b', 'a'],
                ['a', 'b', 'c'],
            ],
            [
                ['cc_bb', 'bb_aa', 'aa_cc'],
                ['aa_cc', 'bb_aa', 'cc_bb'],
            ],
            [
                ['Ca', 'ca', 'Ba', 'ba'],
                ['Ba', 'Ca', 'ba', 'ca'],
            ],
        ];
    }

    /**
     *@covers \Testvendor\SortedLinkedList\SortedLinkedList::remove
     *@covers \Testvendor\SortedLinkedList\SortedLinkedList::add
     *@covers \Testvendor\SortedLinkedList\SortedLinkedList::getHead
     *@covers \Testvendor\SortedLinkedList\SortedLinkedList::getTail
     *@covers \Testvendor\SortedLinkedList\SortedLinkedList::getAllValues
     *@covers \Testvendor\SortedLinkedList\SortedLinkedList::count
     *@dataProvider sortedLinkedListRemovingItemDataProvider
     *
     * @param array<int|string> $items
     * @param array<int|string> $expectedSortedItems
     */
    public function testRemovingItems(array $items, int|string $valueToRemove, array $expectedSortedItems): void
    {
        $sortedLinkedList = new SortedLinkedList();
        foreach ($items as $value) {
            $sortedLinkedList->add($value);
        }

        $removedItem = $sortedLinkedList->remove($valueToRemove);
        if($removedItem) {
            $this->assertEquals($valueToRemove, $removedItem->getValue());
        }

        $this->assertEquals(count($expectedSortedItems), $sortedLinkedList->count());
        $this->assertEquals(reset($expectedSortedItems), $sortedLinkedList->getHead()?->getValue());
        $this->assertEquals(end($expectedSortedItems), $sortedLinkedList->getTail()?->getValue());
        $this->assertSame($expectedSortedItems, $sortedLinkedList->getAllValues());

    }

    /**
     * @return  array<int, array<int, array<int, int|string>|int|string>>
     */
    public static function sortedLinkedListRemovingItemDataProvider(): array
    {
        return [
            [
                [1, 2, 3],
                2,
                [1, 3],
            ],
            [
                [3, 2, 1],
                1,
                [2, 3],
            ],
            [
                [3, 2, 1],
                10,
                [1, 2, 3],
            ],
            [
                ['a', 'b', 'c'],
                'b',
                ['a', 'c'],
            ],
        ];
    }

    /**
     * @covers \Testvendor\SortedLinkedList\SortedLinkedList::getAllowedTypeEnumFromValue()
     * @testWith [true, "boolean"]
     *           [1.5, "double"]
     *           [null, "NULL"]
     */
    public function testUnsupportedTypes(mixed $value, string $type): void
    {
        $this->expectException(UnsupportedTypeException::class);
        $this->expectExceptionMessage('Type ' . $type . ' is not supported');
        $sortedLinkedList = new SortedLinkedList();
        $sortedLinkedList->add($value);
    }

    /**
     * @covers \Testvendor\SortedLinkedList\SortedLinkedList::checkValueType
     * @testWith [[1,"string"], "integer", "string"]
     *           [["string","string2", 1], "string", "integer"]
     *
     * @param array<int|string> $items
     */
    public function testMixedTypes(array $items, string $sortedListType, string $newValueType): void
    {
        $this->expectException(MixedContentException::class);
        $this->expectExceptionMessage('Type of new value (' . $newValueType . ') is different from the sortedList value (' . $sortedListType . ')');
        $sortedLinkedList = new SortedLinkedList();
        foreach($items as $item) {
            $sortedLinkedList->add($item);
        }
    }

}
