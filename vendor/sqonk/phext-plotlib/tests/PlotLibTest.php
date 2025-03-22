<?php
declare(strict_types=1);
/**
*
* Plotting Library
* 
* @package		phext
* @subpackage	detach
* @version		1
* 
* @license		MIT see license.txt
* @copyright	2019 Sqonk Pty Ltd.
*
*
* This file is distributed
* on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either
* express or implied. See the License for the specific language governing
* permissions and limitations under the License.
*/

use PHPUnit\Framework\TestCase;
use sqonk\phext\plotlib\BulkPlot;
use sqonk\phext\core\arrays;

require_once __DIR__.'/TCFuncs.php';

class PlotLibTest extends TestCase
{    
    protected function pixels($img)
    {
        $width = imagesx($img);
        $height = imagesy($img);
        $pixels = [];
    
        foreach (sequence($height-1) as $y) {
            foreach (sequence($width-1) as $x)
            {
                $rgb = imagecolorat($img, $x, $y);
                $colours = imagecolorsforindex($img, $rgb);
                $pixels[$y][$x] = $colours;
            }
        }
        return $pixels;    
    }
    
    public function testEnv()
    {
        $this->assertSame(true, function_exists('imagecreatefrompng'));
        $this->assertSame(true, function_exists('imagecreatefromstring'));
    }
    
    protected function compare(string $rendered, string $example)
    {
        $rendered = imagecreatefromstring($rendered);
        $example = imagecreatefrompng(__DIR__."/$example.png");
        
        $rpixels = $this->pixels($rendered);
        $epixels = $this->pixels($example);
        
        foreach (sequence(0, 499) as $y) {
            foreach (sequence(0, 699) as $x) {
                $this->assertEquals($epixels[$y][$x], $rpixels[$y][$x], "Coords: (x:$x,y:$y)");
            }
        }  
    }
    
    public function testMultiLine()
    {
        $this->compare(multiLines(), 'lines');      
    }    
    
    public function testLineFill()
    {
        $this->compare(lineFills(), 'line_fills');      
    } 
    
    public function testBars()
    {
        $this->compare(bars(), 'bars');      
    }    

    public function testBarsAndAuxlines()
    {
        $this->compare(stackedBarsAndAuxlines(), 'stack_bars');
    }
    
    public function testBackgroundBars()
    {
        $this->compare(backgroundBars(), 'auxbars');
    }
    
    public function testInfiniteLines()
    {
        $this->compare(infiniteLines(), 'Infinite_Lines');
    }
    
    public function testRegions()
    {
        $this->compare(regions(), 'regions'); 
    }
    
    public function testStockplotAndConfigCallback()
    {
        $this->compare(stockplot(), 'candlesticks'); 
    }
    
    public function testBoxplot()
    {
        $this->compare(boxplot(), 'box'); 
    }
    
    public function testBasicScatter()
    {
        $this->compare(basicScatter(), 'scatter'); 
    }
    
    public function testSquareScatter()
    {
        $this->compare(squareScatter(), 'square_scatter'); 
    }
    
    public function testScatterLine()
    {
        $this->compare(scatterLine(), 'scatter_lines'); 
    }
    
    public function testScatterImpulse()
    {
        $this->compare(scatterImpulse(), 'scatter_impulse'); 
    }
}