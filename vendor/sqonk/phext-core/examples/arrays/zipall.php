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

use sqonk\phext\core\arrays;

/*
	Zipall is a function for iterating through every possible combination of a set of arrays.
*/

$ar1 = ['Jess', 'Jamie', 'Alex', 'Cameron'];
$ar2 = ['girl', 'boy or girl', 'boy', 'cyborg'];
$ar3 = ['a' => 'fasion', 'b' => 'getting out', 'c' => 'time traveling', 'd' => 'being mysterious'];

foreach (arrays::zipall($ar1, $ar2, $ar3) as [$name, $gender, $action])
    println("$name is a $gender and enjoys $action.");

