<?php
namespace Gajus\Paggern;

/**
 * @link https://github.com/gajus/paggern for the canonical source repository
 * @license https://github.com/gajus/paggern/blob/master/LICENSE BSD 3-Clause
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
     * Generate a set of random codes based on Paggern pattern.
     * Codes are guaranteed to be unique within the set.
     *
     * @param string $pattern Paggern pattern.
     * @param int $amount Number of codes to generate.
     * @param int $safeguard Number of additional codes generated in case there are duplicates that need to be replaced.
     * @return array
     */
    public function generateFromPattern ($pattern, $amount = 1, $safeguard = 100) {
        $lexer = new \Gajus\Paggern\Lexer();
        $tokens = $lexer->tokenise($pattern, true);

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