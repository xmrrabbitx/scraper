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

// Prompt the user for their name and say hello, all in one line...
println("Hi", ask('What is your name:'));
