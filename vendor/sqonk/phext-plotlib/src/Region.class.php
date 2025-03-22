<?php
namespace sqonk\phext\plotlib;

/**
*
* Plotting Library
* 
* @package		phext
* @subpackage	plotlib
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

/**
 * A visual rectangular region on a graph that represents an area between data points.
 * 
 * This class is used by BulkPlot, which provides an interface to creating regions through
 * the configuration options when adding a plot. 
 * 
 * You do not need to work with this class directly when working with BulkPlot.
 */
class Region
{
    protected int $xleft;
    protected ?int $ytop;
    protected int $xright;
    protected ?int $ybottom;
    protected int|string $colour;
    
    /**
     * Construct a new Region at the given co-ordinates.
     * 
     * -- parameters:
     * @param int $xstart left-most point x-axis.
     * @param ?int $ystart top-most point on the y-axis. Pass NULL to have it use the max. value of the y-scale.
     * @param int $xend right-most point on the x-axis.
     * @param ?int $yend bottom-most point on the y-axis. Pass NULL to have it use the min. value of the y-scale.
     * @param int|string $colour The colour to be used for the fill.
     */
    public function __construct(int $xstart, ?int $ystart, int $xend, ?int $yend, int|string $colour)
    {
        $this->xleft = $xstart;
        $this->ytop = $ystart;
        $this->xright = $xend;
        $this->ybottom = $yend;
        $this->colour = $colour;
    }
    
    // Framework function to allow the object to adjust the scale
    public function PrescaleSetup(object $aGraph): void {
        // Nothing to do
    }
    
    public function PreStrokeAdjust(object $aGraph): void {
        // Nothing to do
    }
    
    public function DoLegend(object $graph): void {
        
    }
    
    /**
     * @return array{NULL, NULL}
     */
    public function Min(): array {
        return array(null,null);
    }
    
    /**
     * @return array{NULL, NULL}
     */
    public function Max(): array {
        return array(null,null);
    }
    
    public function StrokeMargin(object $aImg): void {
        // Nothing to do
    }
    
    public function Stroke(object $img, object $xscale, object $yscale): void
    {
        $img->SetColor($this->colour);
        $img->SetLineWeight(1);
        
        $ytop = ($this->ytop !== null) ? $this->ytop : $yscale->GetMaxVal();
        $ybottom = ($this->ybottom !== null) ? $this->ybottom : $yscale->GetMinVal();
        
        $x1 = $xscale->Translate($this->xleft);
        $x2 = $xscale->Translate($this->xright);
        $y1 = $yscale->Translate($ytop);
        $y2 = $yscale->Translate($ybottom);
        $img->FilledRectangle($x1, $y1, $x2, $y2);
    }
}