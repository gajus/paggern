<?php
class GeneratorTest extends PHPUnit_Framework_TestCase {
    public function testLiteral () {
        $generator = new \Gajus\Paggern\Generator();
        $codes = $generator->generateFromPattern('abc');

        $this->assertCount(1, $codes);
        $this->assertSame(['abc'], $codes);
    }

    public function testRange () {
        $generator = new \Gajus\Paggern\Generator();
        $codes = $generator->generateFromPattern('[a-c]');

        $this->assertCount(1, $codes);
        $this->assertContains($codes[0], ['a', 'b', 'c']);
    }

    public function testClass () {
        $generator = new \Gajus\Paggern\Generator();
        $codes = $generator->generateFromPattern('\U{10}', 100);

        $this->assertCount(100, $codes);
        
        foreach ($codes as $code) {
            $this->assertRegExp('/^[ABCDEFGHKMNOPRSTUVWXYZ23456789]{10}$/', $code);
        }

    }

    /**
     * @expectedException Gajus\Paggern\Exception\RuntimeException
     * @expectedExceptionMessage Unique combination pool exhausted.
     */
    public function testUniquePoolExhaustion () {
        $generator = new \Gajus\Paggern\Generator();
        $codes = $generator->generateFromPattern('abc', 2);
    }
}