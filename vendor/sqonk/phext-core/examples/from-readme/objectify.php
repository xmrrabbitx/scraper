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

$var = objectify(['a' => 2, 'b' => 5]);

println($var);
// return (a:2,b:5)

println($var->a);
// return 2


$Point = named_objectify('x', 'y');
$p = $Point(2, 4);

println($p);
// return '(x:2,y:4)'