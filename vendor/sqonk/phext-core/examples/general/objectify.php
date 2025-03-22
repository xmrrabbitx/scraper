<?php
/**
*
* Core Utilities
* Example Code
* 
* @package		phext
* @subpackage	core
* @version		1
* 
* @license		MIT license.txt
* @copyright	2019 Sqonk Pty Ltd.
*
*
* This file is distributed
* on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either
* express or implied. See the License for the specific language governing
* permissions and limitations under the License.
*/

foreach (glob('../../src/*.php') as $file)
	require_once $file;


// Convert an associative array to an object then print out the variables.
println('-- basic conversion --');
$array = ['a' => 1, 'name' => 'Test Data'];
$obj = objectify($array);
println('This is the object printed as a string:', $obj);

println('This is the object as a var dump:');
var_dump($obj);


// Create a object template for repeated use.
println('', '', '-- object templates --');
$Point = named_objectify('x', 'y');

$p1 = $Point(2, 3);
$p2 = $Point(8, 1);
println($p1, $p2);