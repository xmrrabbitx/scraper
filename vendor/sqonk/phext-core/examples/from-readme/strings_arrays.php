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


use sqonk\phext\core\{strings,arrays,numbers};

$modified = strings::shiftex("doug,30,manager", ',', $item);
// return '30,manager' with 'doug' stored in $item

if (strings::ends_with('What a nice day', 'day')) 
	println('There is a day in this string');
// will print out 'There is a day in this string'.

$numbers = [1,2,3,4,5,6,7,8,9,10];
$choice = arrays::choose($numbers);
// return a random selection from provided array.

$value = 20;
if (numbers::is_within($value, 10, 30))
	println('The number is within range');
// will print out 'There number is within range'.