<?php
namespace Gajus\Parsley;

/**
 * @link https://github.com/gajus/parsley for the canonical source repository
 * @license https://github.com/gajus/parsley/blob/master/LICENSE BSD 3-Clause
 */
class Generator {
    private
        $generator;

    /**
     * @param RandomLib\Generator $generator
     */
    public function __construct (\RandomLib\Generator $generator = null) {
        if ($generator === null) {
            $factory = new \RandomLib\Factory;
            $this->generator = $factory->getGenerator(new \SecurityLib\Strength(\SecurityLib\Strength::MEDIUM));
        }
    }

    /**
     * The returned codes are guaranteed to be unique in the set.
     *
     * @param string $pattern Parsley pattern.
     * @param int $amount Number of codes to generate.
     * @param int $safeguard Number of additional codes generated in case there are duplicates that need to be replaced.
     */
    public function generateFromPattern ($pattern, $amount = 1, $safeguard = 100) {
        $parser = new \Gajus\Parsley\Parser();
        $tokens = $parser->tokenise($pattern, true);

        $codes = array_fill(0, $amount + $safeguard, '');

        foreach ($tokens as &$token) {
            if ($token['type'] !== 'literal') {
                // Use RandomLib\Generator to populate token pool with random characters matching the pattern.
                // Pool is pre-generated for each token use. This is done to reduce the number of generator invocations.
                $token['pool'] = $this->generator->generateString($token['repetition'] * ($amount + $safeguard), $token['haystack']);
            }

            unset($token);
        }

        // Itterate through each code appending the value derived from the token.
        // In case of the range or class token, offset the value from the pre-generated pattern matching pool.
        foreach ($codes as $i => &$code) {
            foreach ($tokens as $token) {
                if ($token['type'] === 'literal') {
                    $code .= $token['string'];
                } else {
                    $code .= mb_substr($token['pool'], $token['repetition'] * $i, $token['repetition']);
                }
            }

            unset($code);
        }

        $codes = array_slice(array_unique($codes), 0, $amount);

        if (count($codes) < $amount) {
            throw new Exception\RuntimeException('Unique combination pool exhausted.');
        }

        return $codes;
    }
}