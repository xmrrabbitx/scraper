<?php

declare(strict_types=1);

namespace Macocci7\PhpScatterplot;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Macocci7\PhpScatterplot\Analyzer;

/**
 * @SuppressWarnings(PHPMD.CamelCaseMethodName)
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
final class AnalyzerTest extends TestCase
{
    public static function provide_mean_can_return_mean_correctly(): array
    {
        return [
            ['data' => [], 'expect' => null, ],
            ['data' => [[]], 'expect' => null, ],
            ['data' => [null], 'expect' => null, ],
            ['data' => [true], 'expect' => null, ],
            ['data' => [false], 'expect' => null, ],
            ['data' => ['0'], 'expect' => null, ],
            ['data' => [1], 'expect' => 1, ],
            ['data' => [1,2], 'expect' => 1.5, ],
            ['data' => [1,2,'3'], 'expect' => null, ],
            ['data' => [1.5,2.5,3.5], 'expect' => 2.5, ],
            ['data' => [1,2,3], 'expect' => 2, ],
            ['data' => [1.5,2.5,3.5,4.5], 'expect' => 3.0, ],
        ];
    }

    #[DataProvider('provide_mean_can_return_mean_correctly')]
    public function test_mean_can_retrun_mean_correctly(array $data, int|float|null $expect): void
    {
        $a = new Analyzer();
        $this->assertSame($expect, $a->mean($data));
    }

    public static function provide_variance_can_return_variance_correctly(): array
    {
        return [
            ['data' => [], 'expect' => null, ],
            ['data' => [[]], 'expect' => null, ],
            ['data' => [null], 'expect' => null, ],
            ['data' => [true], 'expect' => null, ],
            ['data' => [false], 'expect' => null, ],
            ['data' => [0], 'expect' => 0, ],
            ['data' => [1.2], 'expect' => 0.0, ],
            ['data' => ['0'], 'expect' => null, ],
            ['data' => [1], 'expect' => 0, ],
            ['data' => [1,2], 'expect' => 0.25, ],
            ['data' => [-1,1,3,5], 'expect' => 5, ],
        ];
    }

    #[DataProvider('provide_variance_can_return_variance_correctly')]
    public function test_variance_can_return_variance_correctly(array $data, int|float|null $expect): void
    {
        $a = new Analyzer();
        $this->assertSame($expect, $a->variance($data));
    }

    public static function provide_covariance_can_return_covariance_correctly(): array
    {
        return [
            ['x' => [], 'y' => [1], 'expect' => null, ],
            ['x' => [null], 'y' => [1], 'expect' => null, ],
            ['x' => [true], 'y' => [1], 'expect' => null, ],
            ['x' => [false], 'y' => [1], 'expect' => null, ],
            ['x' => ['1'], 'y' => [1], 'expect' => null, ],
            ['x' => [[]], 'y' => [1], 'expect' => null, ],
            ['x' => [1], 'y' => [], 'expect' => null, ],
            ['x' => [1], 'y' => [null], 'expect' => null, ],
            ['x' => [1], 'y' => [true], 'expect' => null, ],
            ['x' => [1], 'y' => [false], 'expect' => null, ],
            ['x' => [1], 'y' => ['1'], 'expect' => null, ],
            ['x' => [1], 'y' => [[]], 'expect' => null, ],
            ['x' => [1], 'y' => [1], 'expect' => 0, ],
            ['x' => [1.5], 'y' => [1.5], 'expect' => 0.0, ],
            ['x' => [1,2], 'y' => [1], 'expect' => null, ],
            ['x' => [1,2,3], 'y' => [4,5,6], 'expect' => 2 / 3, ],
        ];
    }

    #[DataProvider('provide_covariance_can_return_covariance_correctly')]
    public function test_covariance_can_return_covariance_correctly(array $x, array $y, int|float|null $expect): void
    {
        $a = new Analyzer();
        $this->assertSame($expect, $a->covariance($x, $y));
    }

    public static function provide_standardDeviation_can_return_standard_deviation_correctly(): array
    {
        return [
            ['data' => [], 'expect' => null, ],
            ['data' => [null], 'expect' => null, ],
            ['data' => [true], 'expect' => null, ],
            ['data' => [false], 'expect' => null, ],
            ['data' => ['1'], 'expect' => null, ],
            ['data' => [[]], 'expect' => null, ],
            ['data' => [1], 'expect' => 0.0, ],
            ['data' => [1,2], 'expect' => 0.5, ],
            ['data' => [1,2,'3'], 'expect' => null, ],
            ['data' => [-2,-1,0,1,2], 'expect' => sqrt(2), ],
        ];
    }

    #[DataProvider('provide_standardDeviation_can_return_standard_deviation_correctly')]
    public function test_standardDeviation_can_return_standard_deviation_correctly(array $data, float|null $expect): void
    {
        $a = new Analyzer();
        $this->assertSame($expect, $a->standardDeviation($data));
    }

    public static function provide_correlationCoefficient_can_return_correlation_coefficient_correctly(): array
    {
        return [
            ['x' => [], 'y' => [1], 'expect' => null, ],
            ['x' => [null], 'y' => [1], 'expect' => null, ],
            ['x' => [true], 'y' => [1], 'expect' => null, ],
            ['x' => [false], 'y' => [1], 'expect' => null, ],
            ['x' => ['1'], 'y' => [1], 'expect' => null, ],
            ['x' => [[]], 'y' => [1], 'expect' => null, ],
            ['x' => [1], 'y' => [], 'expect' => null, ],
            ['x' => [1], 'y' => [null], 'expect' => null, ],
            ['x' => [1], 'y' => [true], 'expect' => null, ],
            ['x' => [1], 'y' => [false], 'expect' => null, ],
            ['x' => [1], 'y' => ['0'], 'expect' => null, ],
            ['x' => [1], 'y' => [[]], 'expect' => null, ],
            ['x' => [1], 'y' => [1], 'expect' => null, ],
            ['x' => [1,2], 'y' => [1], 'expect' => null, ],
            ['x' => [1,2], 'y' => [1,2], 'expect' => 1.0, ],
            ['x' => [1,2,3,4], 'y' => [3,1,4,2], 'expect' => 0.0, ],
            ['x' => [1,2], 'y' => [2,1], 'expect' => -1.0, ],
        ];
    }

    #[DataProvider('provide_correlationCoefficient_can_return_correlation_coefficient_correctly')]
    public function test_correlationCoefficient_can_return_correlation_coefficient_correctly(array $x, array $y, float|null $expect): void
    {
        $a = new Analyzer();
        $this->assertSame($expect, $a->correlationCoefficient($x, $y));
    }

    public static function provide_regressionLineFormula_can_return_values_correctly(): array
    {
        return [
            ['x' => [], 'y' => [1], 'expect' => null, ],
            ['x' => [null], 'y' => [1], 'expect' => null, ],
            ['x' => [true], 'y' => [1], 'expect' => null, ],
            ['x' => [false], 'y' => [1], 'expect' => null, ],
            ['x' => [0], 'y' => [1], 'expect' => null, ],
            ['x' => [1.2], 'y' => [1], 'expect' => null, ],
            ['x' => ['1'], 'y' => [1], 'expect' => null, ],
            ['x' => [[]], 'y' => [1], 'expect' => null, ],
            ['x' => [1], 'y' => [], 'expect' => null, ],
            ['x' => [1], 'y' => [null], 'expect' => null, ],
            ['x' => [1], 'y' => [true], 'expect' => null, ],
            ['x' => [1], 'y' => [false], 'expect' => null, ],
            ['x' => [1], 'y' => ['1'], 'expect' => null, ],
            ['x' => [1], 'y' => [[]], 'expect' => null, ],
            ['x' => [1], 'y' => [1], 'expect' => null, ],
            ['x' => [1,2], 'y' => [1,2], 'expect' => ['a' => 1.0, 'b' => 0.0], ],
            ['x' => [1,2], 'y' => [1,2,3], 'expect' => null, ],
        ];
    }

    #[DataProvider('provide_regressionLineFormula_can_return_values_correctly')]
    public function test_regressionLineFormula_can_return_values_correctly(array $x, array $y, array|null $expect): void
    {
        $a = new Analyzer();
        $this->assertSame($expect, $a->regressionLineFormula($x, $y));
    }

    public static function provide_getUcl_can_return_ucl_correctly(): array
    {
        return [
            ['data' => [], 'expect' => null, ],
            ['data' => [null], 'expect' => null, ],
            ['data' => [true], 'expect' => null, ],
            ['data' => [false], 'expect' => null, ],
            ['data' => ['1'], 'expect' => null, ],
            ['data' => [[]], 'expect' => null, ],
            ['data' => [1], 'expect' => 1.0, ],
            ['data' => [1,2], 'expect' => 3.5, ],
            ['data' => [1,2,3,4,5], 'expect' => 9.0, ],
        ];
    }

    #[DataProvider('provide_getUcl_can_return_ucl_correctly')]
    public function test_getUcl_can_return_ucl_correctly(array $data, float|null $expect): void
    {
        $a = new Analyzer();
        $this->assertSame($expect, $a->getUcl($data));
    }

    public static function provide_getLcl_can_return_lcl_correctly(): array
    {
        return [
            ['data' => [], 'expect' => null, ],
            ['data' => [null], 'expect' => null, ],
            ['data' => [true], 'expect' => null, ],
            ['data' => [false], 'expect' => null, ],
            ['data' => ['1'], 'expect' => null, ],
            ['data' => [[]], 'expect' => null, ],
            ['data' => [1], 'expect' => 1.0, ],
            ['data' => [1,2], 'expect' => -0.5, ],
            ['data' => [1,2,3,4,5], 'expect' => -3.0, ],
        ];
    }

    #[DataProvider('provide_getLcl_can_return_lcl_correctly')]
    public function test_getLcl_can_return_lcl_correctly(array $data, float|null $expect): void
    {
        $a = new Analyzer();
        $this->assertSame($expect, $a->getLcl($data));
    }

    public static function provide_outliers_can_return_outliers_correctly(): array
    {
        return [
            ['data' => [], 'expect' => null, ],
            ['data' => [null], 'expect' => null, ],
            ['data' => [true], 'expect' => null, ],
            ['data' => [false], 'expect' => null, ],
            ['data' => ['1'], 'expect' => null, ],
            ['data' => [[]], 'expect' => null, ],
            ['data' => [1], 'expect' => [], ],
            ['data' => [1,2], 'expect' => [], ],
            ['data' => [1,2,3,4,5,100], 'expect' => [100], ],
            ['data' => [1,90,92,94,96,98,100], 'expect' => [1], ],
            ['data' => [1,50,51,52,53,54,55,100], 'expect' => [1,100], ],
        ];
    }

    #[DataProvider('provide_outliers_can_return_outliers_correctly')]
    public function test_outliers_can_return_outliers_correctly(array $data, array|null $expect): void
    {
        $a = new Analyzer();
        $this->assertSame($expect, $a->outliers($data));
    }
}
