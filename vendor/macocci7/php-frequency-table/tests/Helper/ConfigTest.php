<?php   // phpcs:ignore

declare(strict_types=1);

namespace Macocci7\PhpFrequencyTable\Helper;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Macocci7\PhpFrequencyTable\Helper\Config;
use Nette\Neon\Neon;

/**
 * @SuppressWarnings(PHPMD.CamelCaseMethodName)
 */
final class ConfigTest extends TestCase
{
    // phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    // phpcs:disable Generic.Files.LineLength.TooLong

    public string $baseConf = __DIR__ . '/../../conf/FrequencyTable.neon';
    public string $testConf = __DIR__ . '/../../conf/ConfigTest.neon';

    public static function setUpBeforeClass(): void
    {
        $baseConf = __DIR__ . '/../../conf/FrequencyTable.neon';
        $testConf = __DIR__ . '/../../conf/ConfigTest.neon';
        copy($baseConf, $testConf);
    }

    public function test_load_can_load_config_file_correctly(): void
    {
        Config::load();
        $r = new \ReflectionClass(Config::class);
        $p = $r->getProperty('conf');
        $p->setAccessible(true);
        $this->assertSame(
            Neon::decodeFile($this->testConf),
            $p->getValue()[$this::class]
        );
    }

    public function return_class_name_from_config(): string|null
    {
        return Config::class();
    }

    public function test_class_can_return_class_name_correctly(): void
    {
        $this->assertSame($this::class, $this->return_class_name_from_config());
    }

    public static function provide_className_can_return_class_name_correctly(): array
    {
        return [
            "Fully Qualified" => [ 'class' => '\Macocci7\PhpFrequencyTable\Helper\ConfigTest', 'expect' => 'ConfigTest', ],
            "Relative" => [ 'class' => 'Helper\ConfigTest', 'expect' => 'ConfigTest', ],
            "Only Class Name" => [ 'class' => 'ConfigTest', 'expect' => 'ConfigTest', ],
        ];
    }

    #[DataProvider('provide_className_can_return_class_name_correctly')]
    public function test_className_can_return_class_name_correctly(string $class, string $expect): void
    {
        $this->assertSame($expect, Config::className($class));
    }

    public function test_get_can_return_value_correctly(): void
    {
        Config::load();
        foreach (Neon::decodeFile($this->testConf) as $key => $value) {
            $this->assertSame(
                $value,
                Config::get($key)
            );
        }
    }

    public static function provide_support_object_like_keys_correctly(): array
    {
        $testConf = __DIR__ . '/../../conf/ConfigTest.neon';
        return [
            "null" => [ 'key' => null, 'expect' => null, ],
            "empty string" => [ 'key' => '', 'expect' => null, ],
            "dot" => [ 'key' => '.', 'expect' => null, ],
            "units" => [ 'key' => 'units', 'expect' => Neon::decodeFile($testConf)['units']],
            "units.eng" => [ 'key' => 'units.eng', 'expect' => Neon::decodeFile($testConf)['units']['eng'], ],
            "units.ja.speed" => [ 'key' => 'units.ja.speed', 'expect' => Neon::decodeFile($testConf)['units']['ja']['speed'], ],
            "units.eng.speed.K" => [ 'key' => 'units.eng.speed.K', 'expect' => Neon::decodeFile($testConf)['units']['eng']['speed']['K'], ],
            "units.ja.speed.K.M" => [ 'key' => 'units.ja.speed.K.M', 'expect' => null, ],
        ];
    }

    #[DataProvider('provide_support_object_like_keys_correctly')]
    public function get_can_support_object_like_keys_correctly(string $key, array|null $expect): void
    {
        $this->assertSame($expect, Config::get($key));
    }

    public static function tearDownAfterClass(): void
    {
        $testConf = __DIR__ . '/../../conf/ConfigTest.neon';
        unlink($testConf);
    }
}
