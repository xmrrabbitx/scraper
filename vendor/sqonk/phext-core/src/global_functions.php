<?php
declare(strict_types = 0);

use sqonk\phext\core\strings;
use sqonk\phext\core\arrays;

/**
*
* Core Utilities
*
* @package		phext
* @subpackage	core
* @version		1
*
* @license		MIT see license.txt
* @copyright	2019 Sqonk Pty Ltd.
*
*
* This file is distributed
* on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either
* express or implied. See the License for the specific language governing
* permissions and limitations under the License.
*/

/*
    A collection of general purpose utility methods.

    NOTE: These functions import across the global name space to keep
    usability to the maximum.
*/

define('BY_VALUE', 0);
define('BY_KEY', 1);
define('MAINTAIN_ASSOC', 2);


/**
 * Print a value to the output, adding a newline character at the end. If the value
 * passed in is an array or an object then the text representation will be
 * parsed and output.
 *
 * This method can also take a variable number of arguments.
 *
 * NOTE: This method can cause a performance hit in CPU intensive tasks due to its
 * flexible intake of parameters and its automatic handling of data types. If you
 * need to print in such situations you should instead use `printstr()`
 *
 * Example:
 *
 * ``` php
 * println('This is an array:', [1,2,3]);
 * // prints:
 * // This is an array: array (
 * //   0 => 1,
 * //   1 => 2,
 * //   2 => 3,
 * // )
 * ```
 */
function println(mixed ...$values): void
{
  $out = [];
  foreach ($values as $v) {
    if (is_array($v) or (is_object($v) and ! method_exists($v, '__toString'))) {
      $v = var_export($v, true);
    } elseif ($v === true) {
      $v = 'true';
    } elseif ($v === false) {
      $v = 'false';
    }
                
    $out[] = $v;
  }
    
  print implode(' ', $out).PHP_EOL;
}

/**
 * Convenience method for printing a string with a line ending.
 */
function printstr(string $str = ''): void
{
  echo $str, PHP_EOL;
}

/**
 * Read the user input from the command prompt. Optionally pass a question/prompt to
 * the user, to be printed before input is read.
 *
 * NOTE: This method is intended for use with the CLI.
 *
 * -- parameters:
 * @param string  $prompt The optional prompt to be displayed to the user prior to reading input.
 * @param bool  $newLineAfterPrompt If TRUE, add a new line in after the prompt.
 * @param bool  $allowEmptyReply If TRUE, the prompt will continue to cycle until a non-empty answer is provided by the user. White space is trimmed to prevent pseudo empty answers. This option has no affect when using $allowedResponses.
 * @param list<string> $allowedResponses An array of acceptable replies. The prompt will cycle until one of the given replies is received by the user.
 *
 * @return string The response from the user in string format.
 *
 * Example:
 *
 * ``` php
 * $name = ask('What is your name?');
 * // Input your name.. e.g. John
 * println('Hello', $name);
 * // prints 'Hello John' (or whatever you typed into the input).
 * ```
 */
function ask(string $prompt = '', bool $newLineAfterPrompt = false, bool $allowEmptyReply = true, array $allowedResponses = []): string
{
  $sapi = php_sapi_name();
  if ($sapi != 'cli') {
    throw new RuntimeException("Attempt to call ask() from $sapi. It can only be used when run from the command line.");
  }
    
  if ($prompt) {
    $seperator = $newLineAfterPrompt ? PHP_EOL : ' ';
    if (! str_ends_with(haystack:$prompt, needle:$seperator)) {
      $prompt .= $seperator;
    }
  }
        
  $an = '';
  $fh = fopen("php://stdin", "r");
  try {
    while (true) {
      if ($prompt) {
        echo $prompt;
      }
      $an = trim(fgets($fh));

      if (count($allowedResponses) && in_array(needle:$an, haystack:$allowedResponses)) {
        break;
      } elseif (count($allowedResponses) == 0 && ($allowEmptyReply || $an)) {
        break;
      }
    }
  } finally {
    fclose($fh);
  }
    
  return $an;
}

/**
 * Convert an associative array into an object.
 *
 * This method works by creating an instance of a generic class and extracting
 * the provided data array into its variable namespace.
 *
 * Example Usage:
 *
 * ``` php
 * $var = objectify(['a' => 2, 'b' => 5]);
 * println($var);
 * // prints (a:2,b:5)
 * println($var->a);
 * // prints 2
 * ```
 *
 * -- parameters:
 * @param array<string, mixed> $data The associative array to convert into an object.
 */
function objectify(array $data): object
{
  return new class($data) {
    /** @var array<string, mixed> $data */
    private array $data;
        
    /**
     * @param array<string, mixed> $mappedVars
     */
    public function __construct(array $mappedVars)
    {
      $this->data = $mappedVars;
    }
        
    public function __get(string $name): mixed
    {
      if (array_key_exists($name, $this->data)) {
        return $this->data[$name];
      }
        
      throw new Exception("Undefined property: $name");
    }
    
    public function __set(string $name, mixed $value): void
    {
      $this->data[$name] = $value;
    }
        
    private function propToString(mixed $value): string
    {
      if (is_array($value)) {
        return implode(':', array_map(function ($v) {
          return $this->propToString($v);
        }, $value));
      }
      return $value;
    }
        
    public function __tostring(): string
    {
      return sprintf("(%s)", implode(',', arrays::map($this->data, function ($v, $k) {
        return $k . ':' . $this->propToString($v);
      })));
    }
  };
}

/**
 * Create a object template that can be instantiated multiple times. The given
 * array takes a sequential list of variable names that will later represent
 * the supplied data.
 *
 * You can either pass in an array of keys or each key as a seperate parameter.
 *
 * Example usage:
 *
 * ``` php
 * $Point = named_objectify('x', 'y');
 * $p = $Point(2, 4);
 * println($p);
 * // prints '(x:2,y:4)'
 * ```
 *
 * -- parameters:
 * @param list<string>|string ...$prototype The key or array of keys that declare the class member names that will exist for each instance.
 */
function named_objectify(array|string ...$prototype): \Closure
{
  if (count($prototype) == 0) {
    throw new \LengthException('You must supply at least one parameter.');
  } elseif (count($prototype) == 1 and is_array($prototype[0])) {
    $prototype = $prototype[0];
  }
    
  foreach ($prototype as $item) {
    if (! is_string($item)) {
      throw new \InvalidArgumentException('All parameters must be strings.');
    }
  }
    
    
  return function () use ($prototype) {
    return objectify(array_combine($prototype, func_get_args()));
  };
}

/**
 * Print a stack trace (with an optional prefix message) at the current point in the code.
 */
function dump_stack(string $message = ''): void
{
  if ($message) {
    println($message);
  }
  println((new Exception)->getTraceAsString());
}

/**
 * A memory efficient alternative to range(). Loop through $start and
 * $end and yield the result to your own foreach.
 *
 * If $end is not supplied then a sequence is auto constructed either
 * ranging from 0 (when $start is positive) or approaching 0 (when
 * start is negative).
 */
function sequence(int $start, ?int $end = null, int $step = 1): \Generator
{
  if ($end === null) {
    if ($start < 0) {
      $end = 0;
    } else {
      $end = $start;
      $start = 0;
    }
  }
  for ($i = $start; $i <= $end; $i += $step) {
    yield $i;
  }
}


/**
 * Is the supplied variable capable of being transformed into a string?
 */
function var_is_stringable(mixed $value): bool
{
  return is_string($value) or is_numeric($value) or
      (is_object($value) and method_exists($value, '__toString'));
}

// ----- Auto-route to specific class.
/*
    These functions present a conistent interface that will work on either
    strings or arrays.
*/

/**
 * Does the haystack start with the needle? Accepts either an array or string as the haystack
 * and routes to the equivalent method in `strings` or `arrays`.
 *
 * -- parameters:
 * @param array<mixed>|string $haystack The string or array to search.
 * @param mixed $needle The item to look for.
 *
 * @return bool TRUE if the needle was found within the haystack, FALSE otherwise.
 */
function starts_with(array|string $haystack, mixed $needle): bool
{
  return is_array($haystack) ? arrays::starts_with($haystack, $needle) :
      str_starts_with($haystack, $needle);
}

/**
 * Does the haystack end with the needle? Accepts either an array or string as the haystack
 * and routes to the equivalent method in `strings` or `arrays`.
 *
 * -- parameters:
 * @param array<mixed>|string $haystack The string or array to search.
 * @param mixed $needle The item to look for.
 *
 * @return bool TRUE if the needle was found within the haystack, FALSE otherwise.
 */
function ends_with(array|string $haystack, mixed $needle): bool
{
  return is_array($haystack) ? arrays::ends_with($haystack, $needle) :
      str_ends_with($haystack, $needle);
}

/**
 * Does the needle occur within the haystack? Accepts either an array or string as the haystack
 * and routes to the equivalent method in `strings` or `arrays`.
 *
 * -- parameters:
 * @param array<mixed>|string $haystack The string or array to search.
 * @param mixed $needle The item to look for.
 *
 * @return bool TRUE if the needle was found within the haystack, FALSE otherwise.
 */
function contains(array|string $haystack, mixed $needle): bool
{
  return is_array($haystack) ? arrays::contains($haystack, $needle) :
      str_contains($haystack, $needle);
}

/**
 * Return a text representation of a boolean value.
 *
 * -- parameters:
 * @param bool $value The input value to test.
 *
 * @return string The word "true" if the boolean is TRUE, "false" if not.
 */
function boolstr(bool $value): string
{
  return $value ? 'true' : 'false';
}

/**
 * Defer execution of a given callback until the current scope
 * is cleared by the garbage collector.
 *
 * This works by pushing a wrapper class to the end of a given
 * stack (held by the reference variable $stack).
 *
 * -- parameters:
 * @param ?array<callable> &$stack Container for the callback to be stored within.
 * @param callable $callback The method to be called at a later point in time.
 */
function on_exit_scope(?array &$stack, callable $callback): void
{
  $def = new class($callback) {
    /**
     * @var callable $callback;
     */
    protected $callback;
        
    /**
     * @param callable $callback
     */
    public function __construct(callable $callback)
    {
      $this->callback = $callback;
    }
    
    public function __destruct()
    {
      ($this->callback)();
    }
  };
    
  if ($stack === null) {
    $stack = [ $def ];
  } else {
    $stack[] = $def;
  }
}
