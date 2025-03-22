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

function _jputils_handle_exception(int $errorNo, string $errMsg, string $fileName, int $lineNum): bool
{
  static $nonCritical = [
    E_WARNING,
    E_NOTICE,
    E_CORE_WARNING,
    E_COMPILE_WARNING,
    E_USER_WARNING,
    E_USER_NOTICE,
    E_DEPRECATED
  ];

  if (! (error_reporting() & $errorNo) || in_array(haystack: $nonCritical, needle: $errorNo)) {
    // This error code is not included in error_reporting or is not critical.
    return false;
  }
  throw new \ErrorException($errMsg, 0, $errorNo, $fileName, $lineNum);
}

define('INTERNAL_JPGRAPH', "\sqonk\phext\plotlib\internal\jpgraph");

/**
 * A utilities class for working on a JPGraph object.
 * 
 * Current use is for reliably outputting the chart render while catching
 * internal errors.
 */
class jputils
{
  static private string $namespace = INTERNAL_JPGRAPH;

  /**
   * Set or return the namespace of the underlying JPGraph library that will be used by PlotLib.
   * 
   * You can use this method to effectively change the version of JPGraph being used to render
   * the output.
   * 
   * NOTE: When setting your own namespace, this will currently only work with copies of the 
   * library where all classes and methods exist within the <u>one</u> namespace. The 
   * various unoffical composer packages that split the classes into sub-namespaces are 
   * <u>not</u> compatible.
   * 
   * *Also note that as this method is responsible for loading the internal library on demand,
   * the namespace can only be changed if the internal copy has not already been used.*
   * 
   * To obtain the current namespace being used pass NULL or omit the parameter.
   */
  static public function namespace(?string $newNamespace = null): ?string
  {
    if ($newNamespace === null)
      return self::$namespace;

    self::$namespace = $newNamespace;
    return null;
  }

  static public function loadInternalJPGraphLibrary(): void
  {
    require_once __DIR__ . '/internal/jpgraph/src/jpgraph.php';
    require_once __DIR__ . '/internal/jpgraph/src/jpgraph_line.php';
    require_once __DIR__ . '/internal/jpgraph/src/jpgraph_bar.php';
    require_once __DIR__ . '/internal/jpgraph/src/jpgraph_scatter.php';
    require_once __DIR__ . '/internal/jpgraph/src/jpgraph_stock.php';
    require_once __DIR__ . '/internal/jpgraph/src/jpgraph_plotline.php';
    require_once __DIR__ . '/internal/jpgraph/src/jpgraph_rgb.inc.php';
    require_once __DIR__ . '/internal/jpgraph/src/jpgraph_ttf.inc.php';
  }

  /**
   * Used by BulkPlot to instantiate both the graph and the various
   * plot classes from within whatever namespace has been set for the 
   * JPGraph library.
   */
  static public function class(string $className): \ReflectionClass
  {
    static $firstLoad = true;
    if ($firstLoad && self::$namespace == INTERNAL_JPGRAPH) {
      # load the internal version of JPGraph.
      self::loadInternalJPGraphLibrary();
    }

    if ($prefix = self::$namespace ?? '')
      $prefix .= '\\';

    $path = "{$prefix}$className";
    return new \ReflectionClass($path);
  }

  /**
   * Force the provided JPGraph object to render its contents, capturing
   * the output and returning it to the caller.
   */
  static public function render(object $chart): ?string
  {
    $error = null;
    set_error_handler("\sqonk\phext\plotlib\_jputils_handle_exception");
    ob_start();
    try {
      $chart->Stroke();
      $img = ob_get_contents();
    } catch (\Throwable $e) {
      $error = $e;
    } finally {
      ob_end_clean();
      restore_error_handler();
    }

    if ($error) {
      error_log($error);
      throw new \Exception($error->getMessage());
    }

    return $img;
  }
}
