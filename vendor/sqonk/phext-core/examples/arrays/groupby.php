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
	This example demonstrates the use of arrays::group_by, which is a subsumarisation 
	routine for grouping a flat list of rows into related sets.

	The steps are:
		- acquire the flat list of records.
		- sort the array by the same keys that we wish to group by.
		- run the group_by to transform the array into a heirarchy.

	Transform a flat set of weekday/month/day information into month -> weekdays -> days.
*/

// First build our sample dataset, a range of date information starting from yesterday and going back
// by a little more than two months.
$days = [];
foreach (sequence(70) as $i)
{
	$time = strtotime("-$i day");
	$days[] = ['weekday' => date('D', $time), 'month' => date('M', $time), 'day' => date('d', $time)];
}

// sort the rows according to the keys we wish to 
arrays::key_sort($days, ['month', 'weekday', 'day']);

foreach (arrays::group_by($days, ['month', 'weekday']) as $month => $weekdays)
{
	println('===========', $month, '');
	foreach ($weekdays as $wd => $calendarDays)
	{
		// $calendarDays is an subset of the relevant original rows/records in $days.
		println("$wd:", implode(',', array_column($calendarDays, 'day')));
	}
}