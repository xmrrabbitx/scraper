###### PHEXT > [Core](../README.md) > [API Reference](index.md) > strings
------
### strings
A set of standard string functions designed to keep your code easier to read and remain obvious as to what is going on.
#### Methods
- [matches](#matches)
- [pop](#pop)
- [shift](#shift)
- [popex](#popex)
- [pop_ex](#pop_ex)
- [shiftex](#shiftex)
- [shift_ex](#shift_ex)
- [contains_word](#contains_word)
- [replace_word](#replace_word)
- [replace_words](#replace_words)
- [clean](#clean)
- [one_space](#one_space)
- [truncate](#truncate)
- [strip_non_alpha_numeric](#strip_non_alpha_numeric)
- [columnize](#columnize)

------
##### matches
```php
static public function matches(string $pattern, string $subject) : array
```
Wrapper for `preg_match` to gather the match array. Works more elegantly for inline operations.

**Returns:**  list<string>


------
##### pop
```php
static public function pop(string $string, string $delimiter, int $amount) : string
```
Modify a string by splitting it by the given delimiter and popping $amount of elements off of the end.

- **string** $string The string to operate on.
- **non-empty-string** $delimiter The sequence of characters to split the string by.
- **int** $amount The number of elements to shift off of the split string.

**Returns:**  string The modified copy of the inputer string.


------
##### shift
```php
static public function shift(string $string, string $delimiter, int $amount) : string
```
Modify a string by splitting it by the given delimiter and shifting $amount of elements off of the start.

- **string** $string The input string.
- **non-empty-string** $delimiter The boundary string.
- **int** $amount The amount of elements to remove.

**Returns:**  string A modified copy of the input string, with the given number of elements removed.


------
##### popex
```php
static public function popex(string $string, string $delimiter, string &$poppedItem = null) : string
```
Split the string by the delimiter and return the shortened input string, providing the popped item as output via the third parameter.

If the delimiter was not found and no item was popped then this method returns the original string.

Example:

``` php
$modified = strings::popex("doug,30,manager", ',', $item);
// return 'doug,30' with 'manager' stored in $item
```

- **string** $string The input string.
- **non-empty-string** $delimiter The boundary string.
- **string** &$poppedItem An optional variable to receive the item removed from the end.

**Returns:**  string A modified copy of the input string.


------
##### pop_ex
```php
static public function pop_ex(string &$string, string $delimiter) : string
```
Split the string by the delimiter, both shortening the input and returning the last element as the result.

 Example:

``` php
$str = "doug,30,manager";
$item = strings::pop_ex($str, ',');
// return 'manager' with $str being shortened to 'doug,30'.
```

- **string** &$string The input string.
- **non-empty-string** $delimiter The boundary string.

**Returns:**  string The element that was removed from the input string. If the delimiter was not found then an empty string is returned.


------
##### shiftex
```php
static public function shiftex(string $string, string $delimiter, string &$shiftedItem = null) : string
```
Split the string by the delimiter and return the shortened input string, providing the shifted item as output via the third parameter.

If the delimiter was not found and no item was shifted then this method returns the original string.

Example:

``` php
$modified = strings::shiftex("doug,30,manager", ',', $item);
// return '30,manager' with 'doug' stored in $item
```

- **string** $string The input string.
- **non-empty-string** $delimiter The boundary string.
- **string** &$shiftedItem An optional variable to receive the item removed from the start.

**Returns:**  string A modified copy of the input string.


------
##### shift_ex
```php
static public function shift_ex(string &$string, string $delimiter) : string
```
Split the string by the delimiter, both shortening the input and returning the first element as the result.

Example:

``` php
$str = "doug,30,manager";
$item = strings::shift_ex($str, ',');
// return 'doug' with $str being shortened to '30,manager'.
```

- **string** &$string The input string.
- **non-empty-string** $delimiter The boundary string.

**Returns:**  string The element that was removed from the input string. If the delimiter was not found then an empty string is returned.


------
##### contains_word
```php
static public function contains_word(string $haystack, string $word) : bool
```
Perform a search for a word in a string.


------
##### replace_word
```php
static public function replace_word(string $haystack, string $word, string $replacement) : string
```
Perform a find & replace on a word in a string.


------
##### replace_words
```php
static public function replace_words(string $haystack, array $wordMap) : string
```
Replace a series of words with their counterpart provided in an associative array.

@param array<string, string> $wordMap A set of words to be replaced by their counterparts.


------
##### clean
```php
static public function clean(array|string $text) : array|string
```
Translate the given text to a clean representation by removing all control or UTF characters that can produce unreadable artefacts on various mediums of output such as HTML or PDF.

The common characters corrected to standard ASCII are: - single quotes - double quotes - hyphens - double hyphens - ellipsis

It also assumes the desired output is a UTF-8 string. If you are working with a different character set you will need to employ an alternative cleaning system.

Passing in an array will cycle through and return a copy with all elements cleaned.

This method requires both mbstring and inconv extensions to be installed.

- **string|list<string>** $text The string, or array of strings, to be cleaned.

**Returns:**  string|list<string> The cleaned string or strings.


------
##### one_space
```php
static public function one_space(string $str) : string
```
To replace all types of whitespace with a single space.


------
##### truncate
```php
static public function truncate(string $value, int $maxLength, string $position = 'r') : string
```
Truncate a string if it's length exceeds the specified maximum value. Strings can be truncated from the left, middle or right.


Position options:
- `l`: truncate left
- `c`: truncate middle
- `r`: truncate right


------
##### strip_non_alpha_numeric
```php
static public function strip_non_alpha_numeric(string $string, int $min = null, int $max = null) : string|bool
```
Filter out all non alpha-numeric characters. Optionally pass in a minimum and maximum string length to invalidate any resulting string that does not meet the given boundaries.


------
##### columnize
```php
static public function columnize(array $array, array $headers, bool $printHeaders = true, bool $printNumericIndexes = true) : string
```
Format and print out a series of rows and columns using the provided array of headers as the table header.

The data array provided should be in an array of rows, each row being an associative array of the column names (corresponding to those passed in as the header) and the related value.

- **list<array<mixed>>** $array The series of rows. Each element should in turn be a keyed array of values.
- **list<string>** $headers The headers should correspond to keys that reside within each row.
- **bool** $printHeaders If `TRUE` then then output the column headers.
- **$printNumericIndexes** If `TRUE` then then output the row indexes as the left-most column.

**Returns:**  string The formatted table.


------
