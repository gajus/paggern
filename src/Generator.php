<?php
namespace Gajus\Parsley;

/**
 * @link https://github.com/gajus/parsley for the canonical source repository
 * @license https://github.com/gajus/parsley/blob/master/LICENSE BSD 3-Clause
 */
class Generator {
    /**
     * The returned codes are guaranteed to be unique in the set.
     *
     * @param string $pattern Parsley recognised pattern.
     * @param int $amount Number of codes to generate.
     * @param int $safeguard Number of additional codes to generate to replace duplicates in the generated batch. This does not affect the number of returned codes.
     */
    public function generateFromPattern ($pattern, $amount = 1, $safeguard = 100) {
        $parser = new \Gajus\Parsley\Parser();
        $tokens = $parser->tokenise($pattern, true);

        $codes = array_fill(0, $amount + $safeguard, '');

        $token_pool = array_fill(0, count($tokens), []);

        $factory = new \RandomLib\Factory;
        $generator = $factory->getGenerator(new \SecurityLib\Strength(\SecurityLib\Strength::MEDIUM));
        
        foreach ($tokens as $i => $token) {
            if ($token['type'] === 'literal') {
                #$token_pool[$i][] = $token['string'];
            } else {
                $token_pool[$i] = $generator->generateString($token['repetition'] * ($amount + $safeguard), $token['haystack']);
            }
        }

        die(var_dump( $token_pool ));

        /*foreach ($codes as &$code) {
            foreach ($tokens as $token) {
                if ($token['type'] === 'literal') {
                    $code .= $token['string'];
                } else if ($token['type'] === 'range') {
                    die(var_dump( $token ));
                } else {
                    throw new Exception\UnexpectedValueException('Unexpected token type.');
                }
            }
        }

        $codes = array_slice(array_unique($codes), 0, $amount);

        if (count($codes) < $amount) {
            throw new Exception\RuntimeException('Unique combination pool exhausted.');
        }

        return $codes;*/
    }
}