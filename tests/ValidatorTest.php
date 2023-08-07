<?php

namespace Tests;

use LaravelShine\ValidatorExtension\ValidatorExtensionServiceProvider;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{
    /**
     * @var Illuminate\Container\Container
     */
    protected $container;

    /**
     * @var Illuminate\Translation\Translator
     */
    protected $translator;

    /**
     * @var Illuminate\Validation\Factory
     */
    protected $validator;

    protected function setUp(): void
    {
        $loader = new \Illuminate\Translation\ArrayLoader();
        $this->translator = new \Illuminate\Translation\Translator($loader, 'en');
        $this->validator = new \Illuminate\Validation\Factory($this->translator);

        $this->container = new \Illuminate\Container\Container;
        $this->container->instance('translator', $this->translator);
        $this->container->instance('validator', $this->validator);

        $service_provider = new ValidatorExtensionServiceProvider($this->container);
        $service_provider->boot();
    }

    public function testAlpha(): void
    {
        $rules = [
            'v1' => 'alpha',
            'v2' => 'x_alpha',
        ];

        $v = $this->validator->make(['v1' => 'abcdefghijklmnopqustruvwxyz'], $rules);
        $this->assertTrue($v->passes());

        $v = $this->validator->make(['v1' => 'ABCDEFGHIJKLMNOPQUSTRUVWXYZ'], $rules);
        $this->assertTrue($v->passes());

        $v = $this->validator->make(['v2' => 'abcdefghijklmnopqustruvwxyz'], $rules);
        $this->assertTrue($v->passes());

        $v = $this->validator->make(['v2' => 'ABCDEFGHIJKLMNOPQUSTRUVWXYZ'], $rules);
        $this->assertTrue($v->passes());

        $v = $this->validator->make(['v1' => '中文'], $rules);
        $this->assertTrue($v->passes());

        $v = $this->validator->make(['v2' => '中文'], $rules);
        $this->assertTrue($v->fails());

        $v = $this->validator->make(['v1' => ','], $rules);
        $this->assertTrue($v->fails());

        $v = $this->validator->make(['v2' => ','], $rules);
        $this->assertTrue($v->fails());

        $v = $this->validator->make(['v1' => '，'], $rules);
        $this->assertTrue($v->fails());

        $v = $this->validator->make(['v2' => '，'], $rules);
        $this->assertTrue($v->fails());

        $v = $this->validator->make(['v1' => '0123'], $rules);
        $this->assertTrue($v->fails());

        $v = $this->validator->make(['v2' => '0123'], $rules);
        $this->assertTrue($v->fails());
    }

    public function testAlphaNum(): void
    {
        $rules = [
            'v1' => 'alpha_num',
            'v2' => 'x_alpha_num',
        ];

        $v = $this->validator->make(['v1' => 'abc123'], $rules);
        $this->assertTrue($v->passes());

        $v = $this->validator->make(['v2' => 'abc123'], $rules);
        $this->assertTrue($v->passes());

        $v = $this->validator->make(['v1' => 'abcdefghijklmnopqustruvwxyz1234567890'], $rules);
        $this->assertTrue($v->passes());

        $v = $this->validator->make(['v2' => 'abcdefghijklmnopqustruvwxyz1234567890'], $rules);
        $this->assertTrue($v->passes());

        $v = $this->validator->make(['v1' => 'ABCDEFGHIJKLMNOPQUSTRUVWXYZ1234567890'], $rules);
        $this->assertTrue($v->passes());

        $v = $this->validator->make(['v2' => 'ABCDEFGHIJKLMNOPQUSTRUVWXYZ1234567890'], $rules);
        $this->assertTrue($v->passes());

        $v = $this->validator->make(['v1' => '中文01'], $rules);
        $this->assertTrue($v->passes());

        $v = $this->validator->make(['v2' => '中文01'], $rules);
        $this->assertTrue($v->fails());

        $v = $this->validator->make(['v1' => ','], $rules);
        $this->assertTrue($v->fails());

        $v = $this->validator->make(['v2' => ','], $rules);
        $this->assertTrue($v->fails());

        $v = $this->validator->make(['v1' => '，'], $rules);
        $this->assertTrue($v->fails());

        $v = $this->validator->make(['v2' => '，'], $rules);
        $this->assertTrue($v->fails());

        $v = $this->validator->make(['v1' => 'http://g232oogle.com'], $rules);
        $this->assertTrue($v->fails());

        $v = $this->validator->make(['v2' => 'http://g232oogle.com'], $rules);
        $this->assertTrue($v->fails());

        $v = $this->validator->make(['v1' => '१२३'], $rules); // numbers in Hindi
        $this->assertTrue($v->passes());

        $v = $this->validator->make(['v2' => '१२३'], $rules); // numbers in Hindi
        $this->assertTrue($v->fails());

        $v = $this->validator->make(['v1' => '٧٨٩'], $rules); // eastern arabic numerals
        $this->assertTrue($v->passes());

        $v = $this->validator->make(['v2' => '٧٨٩'], $rules); // eastern arabic numerals
        $this->assertTrue($v->fails());

        $v = $this->validator->make(['v1' => 'नमस्कार'], $rules);
        $this->assertTrue($v->passes());

        $v = $this->validator->make(['v2' => 'नमस्कार'], $rules);
        $this->assertTrue($v->fails());
    }

    public function testAlphaDash(): void
    {
        $rules = [
            'v1' => 'alpha_dash',
            'v2' => 'x_alpha_dash',
        ];

        $v = $this->validator->make(['v' => 'abcdefghijklmnopqustruvwxyz1234567890_-'], $rules);
        $this->assertTrue($v->passes());

        $v = $this->validator->make(['v2' => 'abcdefghijklmnopqustruvwxyz1234567890_-'], $rules);
        $this->assertTrue($v->passes());

        $v = $this->validator->make(['v1' => 'ABCDEFGHIJKLMNOPQUSTRUVWXYZ1234567890_-'], $rules);
        $this->assertTrue($v->passes());

        $v = $this->validator->make(['v2' => 'ABCDEFGHIJKLMNOPQUSTRUVWXYZ1234567890_-'], $rules);
        $this->assertTrue($v->passes());

        $v = $this->validator->make(['v1' => 'abc_123'], $rules);
        $this->assertTrue($v->passes());

        $v = $this->validator->make(['v2' => 'abc_123'], $rules);
        $this->assertTrue($v->passes());

        $v = $this->validator->make(['v1' => 'abc-123'], $rules);
        $this->assertTrue($v->passes());

        $v = $this->validator->make(['v2' => 'abc-123'], $rules);
        $this->assertTrue($v->passes());

        $v = $this->validator->make(['v1' => '-_'], $rules);
        $this->assertTrue($v->passes());

        $v = $this->validator->make(['v2' => '-_'], $rules);
        $this->assertTrue($v->passes());

        $v = $this->validator->make(['v1' => '_-'], $rules);
        $this->assertTrue($v->passes());

        $v = $this->validator->make(['v2' => '_-'], $rules);
        $this->assertTrue($v->passes());

        $v = $this->validator->make(['v1' => '中-文'], $rules);
        $this->assertTrue($v->passes());

        $v = $this->validator->make(['v2' => '中-文'], $rules);
        $this->assertTrue($v->fails());
    }

    public function testHex(): void
    {
        $rules = ['x' => 'x_hex'];

        $v = $this->validator->make(['x' => '0123456789abcdefABCDEF'], $rules);
        $this->assertTrue($v->passes());

        $v = $this->validator->make(['x' => 'defDEF-_xyzXYZ'], $rules);
        $this->assertTrue($v->fails());
    }

    public function testDigits(): void
    {
        $rules = ['x' => 'x_digits'];

        $v = $this->validator->make(['x' => '0123456789'], $rules);
        $this->assertTrue($v->passes());

        $v = $this->validator->make(['x' => 'abc0123456789'], $rules);
        $this->assertTrue($v->fails());

        $v = $this->validator->make(['x' => '0123456789-'], $rules);
        $this->assertTrue($v->fails());

        $v = $this->validator->make(['x' => ['0123456789']], $rules);
        $this->assertTrue($v->fails());

        $v = $this->validator->make(['x' => '-1'], $rules);
        $this->assertTrue($v->fails());

        $v = $this->validator->make(['x' => 'aa00'], $rules);
        $this->assertTrue($v->fails());

        $v = $this->validator->make(['x' => 'aaaa'], $rules);
        $this->assertTrue($v->fails());
    }

    public function testDigitsWithLength(): void
    {
        $rules = ['x' => 'x_digits:4'];

        $v = $this->validator->make(['x' => '0123'], $rules);
        $this->assertTrue($v->passes());

        $v = $this->validator->make(['x' => ['0123']], $rules);
        $this->assertTrue($v->fails());

        $v = $this->validator->make(['x' => '123'], $rules);
        $this->assertTrue($v->fails());

        $v = $this->validator->make(['x' => '12345'], $rules);
        $this->assertTrue($v->fails());

        $v = $this->validator->make(['x' => '123456'], $rules);
        $this->assertTrue($v->fails());

        $v = $this->validator->make(['x' => '1234567'], $rules);
        $this->assertTrue($v->fails());

        $v = $this->validator->make(['x' => '-1'], $rules);
        $this->assertTrue($v->fails());

        $v = $this->validator->make(['x' => 'aa00'], $rules);
        $this->assertTrue($v->fails());
    }

    public function testDigitsBetween(): void
    {
        $rules = ['x' => 'x_digits:4,6'];

        $v = $this->validator->make(['x' => '0123'], $rules);
        $this->assertTrue($v->passes());

        $v = $this->validator->make(['x' => ['0123']], $rules);
        $this->assertTrue($v->fails());

        $v = $this->validator->make(['x' => '123'], $rules);
        $this->assertTrue($v->fails());

        $v = $this->validator->make(['x' => '12345'], $rules);
        $this->assertTrue($v->passes());

        $v = $this->validator->make(['x' => '123456'], $rules);
        $this->assertTrue($v->passes());

        $v = $this->validator->make(['x' => '1234567'], $rules);
        $this->assertTrue($v->fails());

        $v = $this->validator->make(['x' => '-1'], $rules);
        $this->assertTrue($v->fails());

        $v = $this->validator->make(['x' => 'aa00'], $rules);
        $this->assertTrue($v->fails());

        $v = $this->validator->make(['x' => 'aaaa'], $rules);
        $this->assertTrue($v->fails());
    }

    public function testDigitsReplacer(): void
    {
        $this->translator->addLines([
            'validation.numeric' => 'The :attribute must be a number.',
            'validation.digits' => 'The :attribute must be :digits digits.',
            'validation.digits_between' => 'The :attribute must be between :min and :max digits.',
        ], 'en');

        $size = 4;
        $min = 8;
        $max = 12;

        $v = $this->validator->make([
            'x' => '-',
            'y' => '-',
            'z' => '-',
        ], [
            'x' => 'x_digits',
            'y' => "x_digits:{$size}",
            'z' => "x_digits:{$min},{$max}",
        ]);

        $this->assertEquals([
            'x' => ['The x must be a number.'],
            'y' => ["The y must be {$size} digits."],
            'z' => ["The z must be between {$min} and {$max} digits."],
        ], $v->errors()->getMessages());
    }

    public function testFloat(): void
    {
        $this->assertTrue($this->floatValidator('3.14')->passes());
        $this->assertTrue($this->floatValidator('+3.14')->passes());
        $this->assertTrue($this->floatValidator('-3.14')->passes());
        $this->assertTrue($this->floatValidator('0.123')->passes());
        $this->assertTrue($this->floatValidator('-0.123')->passes());
        $this->assertTrue($this->floatValidator('1234.567')->passes());
        $this->assertTrue($this->floatValidator('3')->passes());
        $this->assertTrue($this->floatValidator('+3')->passes());
        $this->assertTrue($this->floatValidator('-3')->passes());
        $this->assertTrue($this->floatValidator('1e3')->passes());
        $this->assertTrue($this->floatValidator('+1e3')->passes());
        $this->assertTrue($this->floatValidator('-1e3')->passes());
        $this->assertTrue($this->floatValidator('1.2e3')->passes());
        $this->assertTrue($this->floatValidator('-1.2e-3')->passes());
        $this->assertTrue($this->floatValidator('1.2e-3')->passes());

        $this->assertTrue($this->floatValidator('0')->passes());
        $this->assertTrue($this->floatValidator('00')->passes());
        $this->assertTrue($this->floatValidator('0.0')->passes());
        $this->assertTrue($this->floatValidator('-0.0')->passes());
        $this->assertTrue($this->floatValidator('0.000')->passes());
        $this->assertTrue($this->floatValidator('-0.000')->passes());
        $this->assertTrue($this->floatValidator('00.00')->passes());
        $this->assertTrue($this->floatValidator('00.0')->passes());
        $this->assertTrue($this->floatValidator('.0')->passes());
        $this->assertTrue($this->floatValidator('.00')->passes());
        $this->assertTrue($this->floatValidator('0.')->passes());
        $this->assertTrue($this->floatValidator('00.')->passes());

        $this->assertTrue($this->floatValidator('.1')->passes());
        $this->assertTrue($this->floatValidator('0.1')->passes());
        $this->assertTrue($this->floatValidator('1.')->passes());

        $this->assertTrue($this->floatValidator('abc')->fails());
        $this->assertTrue($this->floatValidator('abc123')->fails());
        $this->assertTrue($this->floatValidator('123abc')->fails());

        $this->assertSame(
            ['f' => ['The f field must be a float number.']],
            $this->floatValidator('123abc')->errors()->toArray()
        );
    }

    private function floatValidator($v)
    {
        return $this->validator->make(['f' => $v], ['f' => 'float']);
    }
}
