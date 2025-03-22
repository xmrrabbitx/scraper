###### PHEXT > [Plotlib](../README.md) > [API Reference](index.md) > jputils
------
### jputils
A utilities class for working on a JPGraph object.

Current use is for reliably outputting the chart render while catching internal errors.
#### Methods
[namespace](#namespace)
[class](#class)
[render](#render)

------
##### namespace
```php
static public function namespace(string $newNamespace = null) : ?string
```
Set or return the namespace of the underlying JPGraph library that will be used by PlotLib.

You can use this method to effectively change the version of JPGraph being used to render the output.

NOTE: When setting your own namespace, this will currently only work with copies of the library where all classes and methods exist within the <u>one</u> namespace. The various unoffical composer packages that split the classes into sub-namespaces are <u>not</u> compatible.

Also note that as this method is responsible for loading the internal library on demand, the namespace can only be changed if the internal copy has not already been used.

To obtain the current namespace being used pass `NULL` or omit the parameter.


------
##### class
```php
static public function class(string $className) : ReflectionClass
```
Used by BulkPlot to instantiate both the graph and the various plot classes from within whatever namespace has been set for the JPGraph library.


------
##### render
```php
static public function render(object $chart) : ?string
```
Force the provided JPGraph object to render its contents, capturing the output and returning it to the caller.


------
