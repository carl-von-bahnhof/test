# Task
Implement a library providing SortedLinkedList
(linked list that keeps values sorted). It should be
able to hold string or int values, but not both. Try to
think about what you'd expect from such library as a
user in terms of usability and best practices, and  apply those.

# Solution

I have implemented Singly linked sorted list. U have used phpstan for static analysis and have covered the the functionality with test.

<img src="img/phpstan.png" width="1000px" />
<img src="img/test_result.png" width="1000px" />


## Examples
```php
$list = new \Testvendor\SortedLinkedList\SortedLinkedList();

$list->add(1);
$list->add(5);
$list->add(2);
$list->getAllValues(); // [1, 2 ,5]

$list->getHead()->getValue(); // 1
$list->getTail()->getValue(); // 5

$list->remove(2);

$list->count(); // 2

$list->getAllValues(); // [1, 5]

... etc.
```

## Further extensions
Next steps could be to
* implement following interfaces `\Iterator`, `\ArrayAccess` or `\Serializable`
* implement Doubly linked list
* refactor SortedLinkedList and introduce `AbstractList` so we can then split `int` and `string` implementation in children classes
* add additional type to handle ...



