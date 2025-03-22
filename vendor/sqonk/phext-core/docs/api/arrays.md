###### PHEXT > [Core](../README.md) > [API Reference](index.md) > arrays
------
### arrays
A set of standard array functions designed to keep your code easier to read and remain obvious as to what is going on.
#### Methods
- [safe_value](#safe_value)
- [get](#get)
- [pop](#pop)
- [shift](#shift)
- [add_constrain](#add_constrain)
- [sorted](#sorted)
- [rsorted](#rsorted)
- [key_sort](#key_sort)
- [group_by](#group_by)
- [groupby](#groupby)
- [splitby](#splitby)
- [transpose](#transpose)
- [first](#first)
- [start](#start)
- [end](#end)
- [last](#last)
- [middle](#middle)
- [prune](#prune)
- [compact](#compact)
- [only_keys](#only_keys)
- [map](#map)
- [choose](#choose)
- [sample](#sample)
- [zip](#zip)
- [zipall](#zipall)
- [is_assoc](#is_assoc)
- [encapsulate](#encapsulate)
- [implode_assoc](#implode_assoc)
- [values](#values)
- [implode](#implode)
- [implode_only](#implode_only)
- [contains](#contains)
- [first_match](#first_match)
- [any](#any)
- [all](#all)
- [ends_with](#ends_with)
- [starts_with](#starts_with)
- [head](#head)
- [tail](#tail)

------
##### safe_value
```php
static public function safe_value(array $array, mixed $key, mixed $defaultValue = null) : mixed
```
Safely return the value from the given array under the given key. If the key is not set in the array then the value specified by $defaultValue is returned instead.

This method allows you to avoid potential errors caused by trying to directly access non-existent keys by normalising the result regardless of whether the key is not set or if the value is empty.

- **array<mixed>** $array The array to retrieve the value from.
- **mixed** $key The key of the value to extract from the array.
- **mixed** $defaultValue The value to use if either the key does not exist or the value equates to `NULL` / `FALSE`.

**Returns:**  mixed Either the value for the given key or the default value provided, depending on the conditions stated above.


------
##### get
```php
static public function get(array $array, mixed $key, mixed $defaultValue = null) : mixed
```
Alias for `safe_value`.

- **array<mixed>** $array The array to retrieve the value from.
- **mixed** $key The key of the value to extract from the array.
- **mixed** $defaultValue The value to use if either the key does not exist or the value equates to `NULL` / `FALSE`.

**Returns:**  mixed Either the value for the given key or the default value provided, depending on the conditions stated above.


------
##### pop
```php
static public function pop(array $array, int $amount, array &$poppedItems = []) : array
```
Pop elements off the end of the array to the number specified in the $amount parameter.

- **array<mixed>** $array The array to extract the value from.
- **int** $amount The amount of items to remove.
- **?array<mixed>** &$poppedItems An optional array to receive the items removed from the end of the first array.

**Returns:**  array<mixed> The shortened array.


**See:**  arrays::tail() if you are only interested in acquiring a sub-array of the items on the end.


------
##### shift
```php
static public function shift(array $array, int $amount, array &$shiftedItems = []) : array
```
Shift elements off the start of the array to the number specified in the $amount parameter.

- **array<mixed>** $array The array to extract the value from.
- **int** $amount The amount of items to remove.
- **?array<mixed>** &$shiftedItems An optional array to receive the items removed from the start of the first array.

**Returns:**  array<mixed> The shortened array.


**See:**  arrays::head() if you are only interested in acquiring a sub-array of the items at the start.


------
##### add_constrain
```php
static public function add_constrain(array &$array, mixed $value, int $maxItems) : array
```
Add an item to end of an array. If the array count exceeds maxItems then shift first item off.

- **array<mixed>** $array The array to add the new item to.
- **mixed** $value The new value to add to the end of the array.
- **int** $maxItems The maximum amount of items that the array may contain.

**Returns:**  array<mixed> The modified copy of the input array.


------
##### sorted
```php
static public function sorted(array $array, int $mode = BY_VALUE, int $sort_flags = SORT_REGULAR) : array
```
Sort the given array using a standard sort method. This method is intended as a wrapper for the in-built native sorting methods, which typically modify the original array by reference instead of returning a modified copy.

- **array<mixed>** $array The array to sort.
- **int** $mode The sort mode. See below for options. Defaults to BY_VALUE.
- **int** $sort_flags The sorting behaviour to use. See PHP docs for sort methods to see possible values.

**Returns:**  array<mixed> The sorted copy of the input array.


$mode can have three possible values:
- `BY_VALUE` (default): standard sort of the array values.
- `BY_KEY`: Sort based on the array indexes.
- `MAINTAIN_ASSOC`: Standard sort of the array values but maintaining index association.

Refer to the PHP documentation for all possible values on the $sort_flags.

Depending on the value of $mode this method will utilise either `sort`, `asort` or `ksort`


------
##### rsorted
```php
static public function rsorted(array $array, int $mode = BY_VALUE, int $sort_flags = SORT_REGULAR) : array
```
Sort the given array in reverse order using a standard sort method. This method is intended as a wrapper for the in-built native sorting methods, which typically modify the original array by reference instead of returning a modified copy.

- **array<mixed>** $array The array to sort.
- **int** $mode The sort mode. See below for options. Defaults to BY_VALUE.
- **int** $sort_flags The sorting behaviour to use. See PHP docs for sort methods to see possible values.

**Returns:**  array<mixed> The sorted copy of the input array.


$mode can have three possible values:
- `BY_VALUE` (default): standard sort of the array values.
- `BY_KEY`: Sort based on the array indexes.
- `MAINTAIN_ASSOC`: Standard sort of the array values but maintaining index association.

Refer to the PHP documentation for all possible values on the $sort_flags.

Depending on the value of $mode this method will utilise either `rsort`, `arsort` or `krsort`


------
##### key_sort
```php
static public function key_sort(array &$array, array|string|int|float $key, bool $maintainKeyAssoc = false) : array
```
Sort an array of arrays or objects based on the value of a key inside of the sub-array/object.

If $key is an array then this method will perform a multi-sort, ordering by each key with sort priority given in ascending order.

As per the native sorting methods, the array passed in will be modified directly. As an added convenience the array is also returned to allow method chaining.

Internally this function will use either usort or uasort depending on whether $maintainKeyAssoc is set to `TRUE` or `FALSE`. Setting it to `TRUE` will ensure the array indexes are maintained.

- **array<mixed>** $array The array to sort.
- **string|int|float|list<string>** $key The key to sort by.
- **bool** $maintainKeyAssoc If `TRUE` then main key / index association of the supplied array.

**Returns:**  array<mixed> The sorted copy of the input array.


------
##### group_by
```php
static public function group_by(array $items, array|string $keys, bool $keepEmptyKeys = false) : array
```
Takes a flat array of elements and splits them into a tree of associative arrays based on the keys passed in.

You need to ensure the array is sorted by the same order as the set of keys being used prior to calling this method. If only one key is required to split the array then a singular string may be provided, otherwise pass in an array.

Unless $keepEmptyKeys is set to `TRUE` then any key values that are empty will be omitted.

This method operates in a recursive fashion and the last parameter $pos is used internally when in operation. You should never need to pass in a custom value to $pos yourself.

- **array<mixed>** $items The flat array of items to be arranged into subsets.
- **array<string>|string** $keys The set of keys used to break the flat array into subsets.
- **bool** $keepEmptyKeys When `FALSE` any value that equates to `NULL` / `FALSE` will be omitted from the results.

**Returns:**  array<mixed> The grouped copy of the input array.


------
##### groupby
```php
static public function groupby(array $items, $keys, bool $keepEmptyKeys = false) : array
```
Alias of group_by.

- **array<mixed>** $items The flat array of items to be arranged into subsets.
- **array<string>|string** $keys The set of keys used to break the flat array into subsets.
- **bool** $keepEmptyKeys When `FALSE` any value that equates to `NULL` / `FALSE` will be omitted from the results.

**Returns:**  array<mixed> The grouped copy of the input array.


------
##### splitby
```php
static public function splitby(array $array, callable $callback) : array
```
Split an array into a series of arrays based the varying results returned from a supplied callback.

This method differs from `groupby` in that it does not care about the underlying elements within the array and relies solely on the callback to determine how the elements are divided up, where as `groupby` is explicity designed to work with an array of objects or entities that respond to key lookups. Further to this, `groupby` can produce a tree structure of nested arrays where as `splitby` will only ever produce one level of arrays.

The values returned from the callback must be capable of being used as an array key (e.g. strings, numbers). This is done by a `var_is_stringable` check. `NULL` values are allowed but used to omit the associated item from any of the sets.

- **array<mixed>** $array The flat array of items to be arranged into subsets.
- **callable** $callback A callback method that will produce the varying results used to sort each element into its own set.

Callback format: `myFunc($value, $index) -> mixed`


**Throws:**  \UnexpectedValueException If the value returned from the callback is not capable of being used as an array key.

**Returns:**  array<mixed> An array of arrays, one each for each different result returned from the callback.

Example Usage:

``` php
$numbers = [1,2,3,4,5,6,7,8,9,10];
$sets = arrays::splitby($numbers, fn($v) => ($v % 2 == 0) ? 'even' : 'odd');
println($sets);
// array (
//   'odd' =>
//   array (
//     0 => 1,
//     1 => 3,
//     2 => 5,
//     3 => 7,
//     4 => 9,
//   ),
//   'even' =>
//   array (
//     0 => 2,
//     1 => 4,
//     2 => 6,
//     3 => 8,
//     4 => 10,
//   ),
// )
```


------
##### transpose
```php
static public function transpose(array $array, string $groupKey, array $mergeMap) : array
```
Transform a set of rows and columns with vertical data into a horizontal configuration where the resulting array contains a column for each different value for the given fields in the merge map (associative array).

- **array<string|int,** mixed> $array Associative (keyed) array of values.
- **string** $groupKey Used to specify which key in the $array will be used to flatten multiple rows into one.
- **array<string,** string> $mergeMap Associative (keyed) array specifying pairs of columns that will be merged into header -> value.

**Returns:**  array<array<mixed>> The transformed input array.

Example:

``` php
$data = [
['character' => 'Actor A', 'decade' => 1970, 'appearances' => 1],
['character' => 'Actor A', 'decade' => 1980, 'appearances' => 2],
['character' => 'Actor A', 'decade' => 1990, 'appearances' => 2],
['character' => 'Actor A', 'decade' => 2000, 'appearances' => 1],
['character' => 'Actor A', 'decade' => 2010, 'appearances' => 1],
['character' => 'Actor B', 'decade' => 1980, 'appearances' => 1],
['character' => 'Actor B', 'decade' => 1990, 'appearances' => 1],
['character' => 'Actor B', 'decade' => 2000, 'appearances' => 1],
];
println(strings::columnize($data, ['decade', 'character', 'appearances']));
//          decade    character    appearances
// _____    ______    _________    ___________
// 0          1970      Actor A              1
// 1          1980      Actor A              2
// 2          1990      Actor A              2
// 3          2000      Actor A              1
// 4          2010      Actor A              1
// 5          1980      Actor B              1
// 6          1990      Actor B              1
// 7          2000      Actor B              1
// TAKE NOTE: The $data array is pre-sorted by the group key prior to being transposed, this is critical for correct behaviour.
$data = arrays::key_sort($data, 'decade');
// Transform the matrix using transpose() so that each character becomes a column
// with their resulting appearances listed alongside the decade.
$transformed = arrays::transpose($data, 'decade', ['character' => 'appearances']);
println(strings::columnize($transformed, ['decade', 'Actor A', 'Actor B']));
//          decade    Actor A    Actor B
// _____    ______    _______    _______
// 0          1970          1
// 1          1980          2          1
// 2          1990          2          1
// 3          2000          1          1
// 4          2010          1
```


------
##### first
```php
static public function first(array $array) : mixed
```
Return the first object in the array or null if array is empty.

@param array<mixed> $array The array to get the first element of.

**Returns:**  mixed The first element of the array or `NULL` if the array is empty.


------
##### start
```php
static public function start(array $array) : mixed
```
Alias for self::first.

@param array<mixed> $array The array to get the first element of.

**Returns:**  mixed The first element of the array or `NULL` if the array is empty.


------
##### end
```php
static public function end(array $array) : mixed
```
Return the last object in the array or null if array is empty.

@param array<mixed> $array The array to get the last element of.

**Returns:**  mixed The last element of the array or `NULL` if the array is empty.


------
##### last
```php
static public function last(array $array) : mixed
```
Alias for self::end.

@param array<mixed> $array The array to get the last element of.

**Returns:**  mixed The last element of the array or `NULL` if the array is empty.


------
##### middle
```php
static public function middle(array $array, bool $weightedToFront = true) : mixed
```
Return the object closest to the middle of the array.


- If the array is empty, returns null.
- If the array has less than 3 items, then return the first or last item depending
on the value of $weightedToFront.
- Otherwise return the object closest to the centre. When dealing with arrays containing
and even number of items then it will use the value of $weightedToFront to determine if it
picks the item closer to the start or closer to the end.

- **array<mixed>** $array The array containing the items.
- **bool** $weightedToFront `TRUE` to favour centre items closer to the start of the array and `FALSE` to prefer items closer to the end.

**Returns:**  mixed The object closest to the middle of the array.


------
##### prune
```php
static public function prune(array $array, mixed $empties = '') : array
```
Creates a copy of the provided array where all values corresponding to 'empties' are omitted.

- **array<mixed>** $array The array to filter empty items from.
- **mixed** $empties That value that corresponds to whatever should be considered an empty value. Default to an empty string.

**Returns:**  array<mixed> The modified copy of the input array.


------
##### compact
```php
static public function compact(array $array) : array
```
Creates a copy of the provided array where all `NULL` values are omitted.

- **array<mixed>** $array The array to compact.

**Returns:**  array<mixed> The modified copy of the input array.


------
##### only_keys
```php
static public function only_keys(array $array, mixed ...$keys) : array
```
Return a copy of an array containing only the values for the specified keys, with index association being maintained.

This method is primarily designed for associative arrays. It should be noted that if a key is not present in the provided array then it will not be present in the resulting array.

- **array<mixed>** $array The array to retrieve the values from.
- **mixed** ...$keys One or more keys to obtain values for.

**Returns:**  array<mixed> A keyed array containing only the values (with corresponding keys) that were requested.


------
##### map
```php
static public function map(array $array, callable $callback) : array
```
Apply a callback function to the supplied array. This version will optionally supply the corresponding index/key of the value when needed (unlike the built-in array_map() method).

Callback format: `myFunc($value, $index) -> mixed`

- **array<mixed>** $array The array to walk through.
- **callable** $callback The callback method.

**Returns:**  array<mixed> The modified copy of the input array containing the results of the callback.


------
##### choose
```php
static public function choose(array $array) : mixed
```
Randomly choose an item from the given array.

- **array<mixed>** $array The array to select an element from.

**Returns:**  mixed The randomly selected value.

Example:

``` php
$numbers = [1,2,3,4,5,6,7,8,9,10];
$choice = arrays::choose($numbers);
// return a random selection from provided array.
```


------
##### sample
```php
static public function sample(int $min, int $max, int $amount) : array
```
Generate an array of random numbers between the given $min and $max. The array will be $amount long.

- **int** $min The lower bracket the randomiser can use to generate a number.
- **int** $max The upper bracket the randomiser can use to generate a number.
- **int** $amount The total number of numbers to generate.

**Returns:**  array<int> The resulting array of numbers.


------
##### zip
```php
static public function zip(array ...$arrays) : Generator
```
Iterate through a series of arrays, yielding the value of the corresponding index in each a sequential array to your own loop.

This method can handle both associative and non-associative arrays.

- **array<mixed>** ...$arrays The arrays to walk through.

 Example usage:

``` php
$array1 = ['a', 'b', 'c'];
$array2 = [1, 2, 3, 4];
$array3 = ['#', '?'];
foreach (arrays::zip($array1, $array2, $array3) as [$v1, $v2, $v3])
println($v1, $v2, $v3);
// Prints:
// a 1 #
// b 2 ?
// c 3
//   4
```


------
##### zipall
```php
static public function zipall(array ...$arrays) : Generator
```
Iterate through a series of arrays, yielding the values for every possible combination of values.

For example, with 2 arrays this function will yield for every element in array 2 with the value in the first index of array 1. It will then yield for every element in array 2 with the value in the second index of array 1, etc.

This method can handle both associative and non-associative arrays.

- **array<mixed>** ...$arrays The arrays to walk through.

Example usage:

``` php
$array1 = ['a', 'b', 'c'];
$array2 = [1, 2, 3, 4];
$array3 = ['#', '?'];
foreach (arrays::zipall($array1, $array2, $array3) as [$v1, $v2, $v3])
println($v1, $v2, $v3);
// a 1 #
// a 1 ?
// a 2 #
// a 2 ?
// a 3 #
// a 3 ?
// a 4 #
// a 4 ?
// b 1 #
// b 1 ?
// b 2 #
// b 2 ?
// b 3 #
// b 3 ?
// b 4 #
// b 4 ?
// c 1 #
// c 1 ?
// c 2 #
// c 2 ?
// c 3 #
// c 3 ?
// c 4 #
// c 4 ?
```


------
##### is_assoc
```php
static public function is_assoc(array $array) : bool
```
Attempt to determine if the given array is either sequential or hashed.

In PHP 8.1 or later this method return the inverse of `array_is_list`.

In PHP 8, this method works by extracting the keys of the array and performing a comparison of the keys of the given array and the indexes of the extracted key array to see if they match. If they do not then the provided array is likely associative.

- **array<mixed>** $array The array to assess.

@deprecated Use `array_is_list` instead.


------
##### encapsulate
```php
static public function encapsulate(array $array, string $startToken, string $endToken = null) : array
```
Return a copy of an array with every item wrapped in the provided tokens. If no end token is provided then the $startToken is used on both ends.

NOTE: This function expects all items in the array to convertible to a string.

- **list<string>** $array The array to encapsulate.
- **non-empty-string** $startToken The token placed on the start of each element.
- **?string** $endToken The token placed on the end of the each element. If not given then it defaults to the provided start token.

**Returns:**  list<string> The modified array.


------
##### implode_assoc
```php
static public function implode_assoc(string $delim, array $array, string $keyValueDelim) : string
```
Implode an associate array into a string where each element of the array is imploded with a given delimiter and each key/value pair is imploding using a different delimiter.

- **non-empty-string** $delim The boundary string
- **array<mixed>** $array The input array.
- **non-empty-string** $keyValueDelim Join each key & value pair together with this string.


------
##### values
```php
static public function values(array $array, mixed ...$keys) : array
```
Return the values in the provided array belonging to the specified keys.

This method is primarily designed for associative arrays.

Example:

``` php
$info = ['name' => 'Doug', 'age' => 30, 'job' => 'Policeman'];
println(arrays::values($info, 'name', 'age'));
// Prints: array (
//  0 => 'Doug',
//  1 => 30,
//)
```

- **array<mixed>** $array The input array.
- **mixed** ...$keys The keys for the values to retrieve.

**Returns:**  array<mixed> The array of values for the given keys. If no keys were supplied an empty array will be returned.


------
##### implode
```php
static public function implode(string $delimiter, array $array, string $subDelimiter = null) : string
```
This method acts in a similar fashion to the native 'implode', however in addition it will recursively implode any sub-arrays found within the parent.

You may optionally provide a $subDelimiter to be applied to any inner arrays. If nothing is supplied then it will default to the primary delimiter.

- **string** $delimiter The boundary string
- **array<mixed>** $array The input array.
- **?string** $subDelimiter If supplied then join each key & value pair together with this string.

**Returns:**  string The resulting string.


------
##### implode_only
```php
static public function implode_only(string $delimiter, array $array, mixed ...$keys) : string
```
Implode the given array using the desired delimiter. This method differs from the built-in implode in that it will only implode the values associated with the specified keys/indexes.

Empty values are automatically removed prior to implosion.

- **non-empty-string** $delimiter The boundary string
- **array<mixed>** $array The input array.
- **mixed** ...$keys The keys of the input array for the corresponding values to implode.

**Returns:**  string The resulting string.


------
##### contains
```php
static public function contains(array $haystack, mixed $needle, bool $strict = false) : bool
```
Search an array for the given needle (subject). If the needle is a callable reference then each value is provided to the callback and expects to receive a `TRUE`/`FALSE` answer.

If the needle is anything else then this method utilises `in_array` for determining the answer.

- **array<mixed>** $haystack The input array.
- **mixed** $needle The element to search for.
- **bool** $strict If `TRUE` then all comparisons are performed with strict comparison. Defaults to `FALSE`.

**Returns:**  bool `TRUE` if the needle occurs at least once within the array.


------
##### first_match
```php
static public function first_match(array $haystack, callable $callback) : mixed
```
Search the array for an item that matches an arbitrary condition specified by a callback method.

This method can be useful for searching multi-dimensional arrays to locate a specific item.

- **array<mixed>** $haystack The array to search.
- **callable** $callback The callback method that will examine each item within the array.

Callback format: `myFunc($value, $index) -> bool`

**Returns:**  mixed The first item where $callback returns `TRUE` will be returned as the result, `NULL` if there are no matches.


------
##### any
```php
static public function any(array $haystack, mixed $needle, bool $strict = false) : bool
```
Alias of contains().

- **array<mixed>** $haystack The array to search.
- **mixed** $needle The value to search for.
- **bool** $strict If `TRUE` then comparisons will do strict type checks.

**Returns:**  bool `TRUE` if at least one of the elements in the array matches the given value.


------
##### all
```php
static public function all(array $haystack, mixed $needle, bool $strict = false) : bool
```
Returns `TRUE` if all of the values within the array are equal to the value provided, `FALSE` otherwise.

A callback may be provided as the match to perform more complex testing.

Callback format: `myFunc($value) -> bool`

For basic (non-callback) matches, setting $strict to `TRUE` will enforce type-safe comparisons.

- **array<mixed>** $haystack The input array.
- **mixed** $needle The element to search for.
- **bool** $strict If `TRUE` then all comparisons are performed with strict comparison. Defaults to `FALSE`.

**Returns:**  bool `TRUE` if the needle occurs as every element within the array.


------
##### ends_with
```php
static public function ends_with(array $haystack, mixed $needle) : bool
```
Determines if the given haystack ends with the needle. The comparison is non-strict.

- **array<mixed>** $haystack The input array.
- **mixed** $needle The element to search for.

**Returns:**  bool `TRUE` if the needle is the last element in the array, `FALSE` otherwise.


------
##### starts_with
```php
static public function starts_with(array $haystack, mixed $needle) : bool
```
Determines if the given haystack starts with the needle. The comparison is non-strict.

- **array<mixed>** $haystack The input array.
- **mixed** $needle The element to search for.

**Returns:**  bool `TRUE` if the needle is the first element in the array, `FALSE` otherwise.


------
##### head
```php
static public function head(array $array, int $amount) : array
```
Return the first part of the given array containing up to $amount of items from the start. If the given amount is greater than the size of the input array then the whole array is returned.

@param array<mixed> $array The array to extract the subarray from. @param positive-int $amount The amount of items in the resulting array.

**Returns:**  array<mixed> The selected portion of the array.


------
##### tail
```php
static public function tail(array $array, int $amount) : array
```
Return the last part of the given array containing up to $amount of items from the end. If the given amount is greater than the size of the input array then the whole array is returned.

@param array<mixed> $array The array to extract the subarray from. @param positive-int $amount The amount of items in the resulting array.

**Returns:**  array<mixed> The selected portion of the array.


------
