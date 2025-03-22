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

use sqonk\phext\core\{arrays,strings};

/*
	This example demonstrates the use of arrays::transpose, which can be used
	to shifting vertically aligned information in a 2-dimensional matrix
	into horizontal set.

	This hypothetical dataset includes two movie characters and their amount of appearances
	in movies over the decades.
*/

// create and print out the initial dataset.
println("------ Movie appearances ------");
$data = [
    ['character' => 'Actor A', 'decade' => 1970, 'appearances' => 1],
    ['character' => 'Actor A', 'decade' => 1980, 'appearances' => 2],
    ['character' => 'Actor A', 'decade' => 1990, 'appearances' => 2],
    ['character' => 'Actor A', 'decade' => 2000, 'appearances' => 1],
    ['character' => 'Actor A', 'decade' => 2010, 'appearances' => 1],
    
    ['character' => 'Actor B', 'decade' => 1980, 'appearances' => 1],
    ['character' => 'Actor B', 'decade' => 1990, 'appearances' => 1],
    ['character' => 'Actor B', 'decade' => 2000, 'appearances' => 1],
];
println(strings::columnize($data, ['decade', 'character', 'appearances']));

// Transform the matrix using arrays::transpose so that each character becomes a column
// with their resulting appearances listed alongside the decade.
println("\n\n", "------ Shifted into one column per character with resulting appearance count ------");
$transformed = arrays::transpose(arrays::key_sort($data, 'decade'), 'decade', ['character' => 'appearances']);
println(strings::columnize($transformed, ['decade', 'Actor A', 'Actor B']));