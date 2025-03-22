###### PHEXT > [Core](../README.md) > [API Reference](index.md) > global_functions
------
### global_functions
#### Methods
[println](#println)
[printstr](#printstr)
[ask](#ask)
[objectify](#objectify)
[named_objectify](#named_objectify)
[dump_stack](#dump_stack)
[sequence](#sequence)
[var_is_stringable](#var_is_stringable)
[starts_with](#starts_with)
[ends_with](#ends_with)
[contains](#contains)
[boolstr](#boolstr)
[on_exit_scope](#on_exit_scope)

------
##### println
```php
function println(mixed ...$values) : void
```
Print a value to the output, adding a newline character at the end. If the value passed in is an array or an object then the text representation will be parsed and output.

This method can also take a variable number of arguments.

NOTE: This method can cause a performance hit in CPU intensive tasks due to its flexible intake of parameters and its automatic handling of data types. If you need to print in such situations you should instead use `printstr()`

Example:

``` php
println('This is an array:', [1,2,3]);
// prints:
// This is an array: array (
//   0 => 1,
//   1 => 2,
//   2 => 3,
// )
```


------
##### printstr
```php
function printstr(string $str = '') : void
```
Convenience method for printing a string with a line ending.


------
##### ask
```php
function ask(string $prompt = '', bool $newLineAfterPrompt = false, bool $allowEmptyReply = true, array $allowedResponses = []) : string
```
Read the user input from the command prompt. Optionally pass a question/prompt to the user, to be printed before input is read.

NOTE: This method is intended for use with the CLI.

- **string**  $prompt The optional prompt to be displayed to the user prior to reading input.
- **bool**  $newLineAfterPrompt If `TRUE`, add a new line in after the prompt.
- **bool**  $allowEmptyReply If `TRUE`, the prompt will continue to cycle until a non-empty answer is provided by the user. White space is trimmed to prevent pseudo empty answers. This option has no affect when using $allowedResponses.
- **list<string>** $allowedResponses An array of acceptable replies. The prompt will cycle until one of the given replies is received by the user.

**Returns:**  string The response from the user in string format.

Example:

``` php
$name = ask('What is your name?');
// Input your name.. e.g. John
println('Hello', $name);
// prints 'Hello John' (or whatever you typed into the input).
```


------
##### objectify
```php
function objectify(array $data) : object
```
Convert an associative array into an object.

This method works by creating an instance of a generic class and extracting the provided data array into its variable namespace.

Example Usage:

``` php
$var = objectify(['a' => 2, 'b' => 5]);
println($var);
// prints (a:2,b:5)
println($var->a);
// prints 2
```

- **array<string,** mixed> $data The associative array to convert into an object.


------
##### named\_objectify
```php
function named_objectify(array|string ...$prototype) : Closure
```
Create a object template that can be instantiated multiple times. The given array takes a sequential list of variable names that will later represent the supplied data.

You can either pass in an array of keys or each key as a seperate parameter.

Example usage:

``` php
$Point = named_objectify('x', 'y');
$p = $Point(2, 4);
println($p);
// prints '(x:2,y:4)'
```

- **list<string>|string** ...$prototype The key or array of keys that declare the class member names that will exist for each instance.


------
##### dump\_stack
```php
function dump_stack(string $message = '') : void
```
Print a stack trace (with an optional prefix message) at the current point in the code.


------
##### sequence
```php
function sequence(int $start, int $end = null, int $step = 1) : Generator
```
A memory efficient alternative to range(). Loop through $start and $end and yield the result to your own foreach.

If $end is not supplied then a sequence is auto constructed either ranging from 0 (when $start is positive) or approaching 0 (when start is negative).


------
##### var\_is\_stringable
```php
function var_is_stringable(mixed $value) : bool
```
Is the supplied variable capable of being transformed into a string?


------
##### starts\_with
```php
function starts_with(array|string $haystack, mixed $needle) : bool
```
Does the haystack start with the needle? Accepts either an array or string as the haystack and routes to the equivalent method in `strings` or `arrays`.

- **array<mixed>|string** $haystack The string or array to search.
- **mixed** $needle The item to look for.

**Returns:**  bool `TRUE` if the needle was found within the haystack, `FALSE` otherwise.


------
##### ends\_with
```php
function ends_with(array|string $haystack, mixed $needle) : bool
```
Does the haystack end with the needle? Accepts either an array or string as the haystack and routes to the equivalent method in `strings` or `arrays`.

- **array<mixed>|string** $haystack The string or array to search.
- **mixed** $needle The item to look for.

**Returns:**  bool `TRUE` if the needle was found within the haystack, `FALSE` otherwise.


------
##### contains
```php
function contains(array|string $haystack, mixed $needle) : bool
```
Does the needle occur within the haystack? Accepts either an array or string as the haystack and routes to the equivalent method in `strings` or `arrays`.

- **array<mixed>|string** $haystack The string or array to search.
- **mixed** $needle The item to look for.

**Returns:**  bool `TRUE` if the needle was found within the haystack, `FALSE` otherwise.


------
##### boolstr
```php
function boolstr(bool $value) : string
```
Return a text representation of a boolean value.

- **bool** $value The input value to test.

**Returns:**  string The word "true" if the boolean is `TRUE`, "false" if not.


------
##### on\_exit\_scope
```php
function on_exit_scope(array &$stack, callable $callback) : void
```
Defer execution of a given callback until the current scope is cleared by the garbage collector.

This works by pushing a wrapper class to the end of a given stack (held by the reference variable $stack).

- **?array<callable>** &$stack Container for the callback to be stored within.
- **callable** $callback The method to be called at a later point in time.


------
