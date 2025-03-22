###### PHEXT > [Core](../README.md) > [API Reference](index.md) > numbers
------
### numbers
Utility methods for dealing with numerical values.
#### Methods
- [constrain](#constrain)
- [is_within](#is_within)
- [rand_float](#rand_float)

------
##### constrain
```php
static public function constrain(int|float $value, int|float $min, int|float $max) : int|float
```
Clip a numeric value, if necessary, to the given min and max boundaries.

Example:

``` php
$value = 4.9;
println("value:", numbers::constrain($value, 5.0, 5.5));
// will print out '5'.
```


------
##### is_within
```php
static public function is_within(int|float $value, int|float $min, int|float $max) : bool
```
Check if the given numeric value is in range.

Example:

``` php
$value = 20;
if (numbers::is_within($value, 10, 30))
println('The number is within range');
// will print out 'The number is within range'.
```


------
##### rand_float
```php
static public function rand_float(float $min = 0, float $max = 1, int $mul = 1000000) : float
```
Random Float Generator.

Generate a random number between $start and $end to a series of decimal places.

- **float** $min Optional lowest value to be returned (default: 0)
- **float** $max Optional highest value to be returned (default: 1.0)
- **int** $mul Optional multiplier that will determine the number of decimal places (default: 1000000)


**Throws:**  \InvalidArgumentException If $min is greater than $max.

**Returns:**  float A random float between `$min` and `$max`.


------
