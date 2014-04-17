<?php
class GeneratorTest extends PHPUnit_Framework_TestCase {
    /*public function testLiteral () {
        $generator = new \Gajus\Parsley\Generator();
        $vouchers = $generator->generateFromPattern('abc');

        $this->assertCount(1, $vouchers);
        $this->assertSame(['abc'], $vouchers);
    }*/

    public function testRange () {
        $generator = new \Gajus\Parsley\Generator();
        $vouchers = $generator->generateFromPattern('[a-z]');

        die(var_dump( $vouchers ));

        $this->assertCount(1, $vouchers);
        #$this->assertSame(['abc'], $vouchers);
    }

    /**
     * @expectedException Gajus\Parsley\Exception\RuntimeException
     * @expectedExceptionMessage Unique combination pool exhausted.
     */
    public function testUniquePoolExhaustion () {
        $generator = new \Gajus\Parsley\Generator();
        $vouchers = $generator->generateFromPattern('abc', 2);
    }
}