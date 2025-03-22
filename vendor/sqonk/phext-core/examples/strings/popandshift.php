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

use sqonk\phext\core\strings;

/*
	This example demonstrates the methods for quickly moving items off of the end or start
	of delimitered blocks of text.
*/

$text = 'Larry,Doug,Dave,Tim';
println($text);

// pop 2 names off of the end.
$modified = strings::pop($text, ',', 2);
println(PHP_EOL, 'arrays::pop, text becomes:', $modified);

// shift 2 names off of the start.
$modified = strings::pop($text, ',', 2);
println(PHP_EOL, 'arrays::shift, text becomes:', $modified);

// pop the last element off of the end of text, returning the modified string.
$modified = strings::popex($text, ',', $removed);
println(PHP_EOL, 'arrays::popex, text becomes:', $modified, 'removed:', $removed);

// shift the first element off of the start of text, returning the modified string.
$modified = strings::shiftex($text, ',', $removed);
println(PHP_EOL, 'arrays::shiftex, text becomes:', $modified, 'removed:', $removed);