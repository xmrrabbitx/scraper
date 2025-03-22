###### PHEXT > [Plotlib](../README.md) > [API Reference](index.md) > Region
------
### Region
A visual rectangular region on a graph that represents an area between data points.

This class is used by BulkPlot, which provides an interface to creating regions through the configuration options when adding a plot.

You do not need to work with this class directly when working with BulkPlot.
#### Methods
[__construct](#__construct)
[PrescaleSetup](#prescalesetup)
[PreStrokeAdjust](#prestrokeadjust)
[DoLegend](#dolegend)
[Min](#min)
[Max](#max)
[StrokeMargin](#strokemargin)
[Stroke](#stroke)

------
##### __construct
```php
public function __construct(int $xstart, int $ystart, int $xend, int $yend, string|int $colour) 
```
Construct a new Region at the given co-ordinates.

- **int** $xstart left-most point x-axis.
- **?int** $ystart top-most point on the y-axis. Pass `NULL` to have it use the max. value of the y-scale.
- **int** $xend right-most point on the x-axis.
- **?int** $yend bottom-most point on the y-axis. Pass `NULL` to have it use the min. value of the y-scale.
- **int|string** $colour The colour to be used for the fill.


------
##### PrescaleSetup
```php
public function PrescaleSetup(object $aGraph) : void
```
No documentation available.


------
##### PreStrokeAdjust
```php
public function PreStrokeAdjust(object $aGraph) : void
```
No documentation available.


------
##### DoLegend
```php
public function DoLegend(object $graph) : void
```
No documentation available.


------
##### Min
```php
public function Min() : array
```
**Returns:**  array{`NULL`, `NULL`}


------
##### Max
```php
public function Max() : array
```
**Returns:**  array{`NULL`, `NULL`}


------
##### StrokeMargin
```php
public function StrokeMargin(object $aImg) : void
```
No documentation available.


------
##### Stroke
```php
public function Stroke(object $img, object $xscale, object $yscale) : void
```
No documentation available.


------
