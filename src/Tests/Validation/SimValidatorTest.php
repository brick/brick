<?php

namespace Brick\Tests\Validation;

use Brick\Validation\Validator\SimValidator;

/**
 * Unit tests for the SIM card number validator.
 */
class SimValidatorTest extends AbstractTestCase
{
    public function testSimValidator()
    {
        $validator = new SimValidator();

        $this->doTestValidator($validator, [
            // not enough digits
            ''                   => ['validator.sim.invalid'],
            '0'                  => ['validator.sim.invalid'],
            '00'                 => ['validator.sim.invalid'],
            '000'                => ['validator.sim.invalid'],
            '0000'               => ['validator.sim.invalid'],
            '000000'             => ['validator.sim.invalid'],
            '0000000'            => ['validator.sim.invalid'],
            '00000000'           => ['validator.sim.invalid'],
            '000000000'          => ['validator.sim.invalid'],
            '0000000000'         => ['validator.sim.invalid'],
            '00000000000'        => ['validator.sim.invalid'],
            '000000000000'       => ['validator.sim.invalid'],
            '0000000000000'      => ['validator.sim.invalid'],
            '00000000000000'     => ['validator.sim.invalid'],
            '000000000000000'    => ['validator.sim.invalid'],
            '0000000000000000'   => ['validator.sim.invalid'],
            '00000000000000000'  => ['validator.sim.invalid'],

            // valid length, but non-digit chars
            '000000000000000000 ' => ['validator.sim.invalid'],
            ' 000000000000000000' => ['validator.sim.invalid'],

            // 18 digits: no check digit
            '000000000000000000' => [],
            '000000000000000001' => [],

            // 19 digits: in doubt on the presence of a check digit, it is not verified
            '0000000000000000000' => [],
            '0000000000000000001' => [],

            // 20 digits: check digit verified
            '00000000000000000000' => [],
            '00000000000000000001' => ['validator.sim.invalid'],
            '00000000000000000018' => [],
            '00000000000000000019' => ['validator.sim.invalid'],

            // too many digits
            '000000000000000000000' => ['validator.sim.invalid'],
            '0000000000000000000000' => ['validator.sim.invalid'],
        ]);
    }

    public function testSimValidatorWithCheckDigit()
    {
        $validator = new SimValidator(true);

        $this->doTestValidator($validator, [
            ''                   => ['validator.sim.invalid'],
            '0'                  => ['validator.sim.invalid'],
            '00'                 => ['validator.sim.invalid'],
            '000'                => ['validator.sim.invalid'],
            '0000'               => ['validator.sim.invalid'],
            '000000'             => ['validator.sim.invalid'],
            '0000000'            => ['validator.sim.invalid'],
            '00000000'           => ['validator.sim.invalid'],
            '000000000'          => ['validator.sim.invalid'],
            '0000000000'         => ['validator.sim.invalid'],
            '00000000000'        => ['validator.sim.invalid'],
            '000000000000'       => ['validator.sim.invalid'],
            '0000000000000'      => ['validator.sim.invalid'],
            '00000000000000'     => ['validator.sim.invalid'],
            '000000000000000'    => ['validator.sim.invalid'],
            '0000000000000000'   => ['validator.sim.invalid'],
            '00000000000000000'  => ['validator.sim.invalid'],
            '000000000000000000' => ['validator.sim.invalid'],

            ' 0000000000000000000' => ['validator.sim.invalid'],
            '0000000000000000000 ' => ['validator.sim.invalid'],

            '0000000000000000000' => [],
            '0000000000000000018' => [],
            '0000000000000000026' => [],
            '0000000000000000034' => [],
            '0000000000000000042' => [],
            '0000000000000000059' => [],
            '0000000000000000067' => [],
            '0000000000000000075' => [],
            '0000000000000000083' => [],
            '0000000000000000091' => [],

            '0000000000000000001' => ['validator.sim.invalid'],
            '0000000000000000002' => ['validator.sim.invalid'],
            '0000000000000000003' => ['validator.sim.invalid'],
            '0000000000000000004' => ['validator.sim.invalid'],
            '0000000000000000005' => ['validator.sim.invalid'],
            '0000000000000000006' => ['validator.sim.invalid'],
            '0000000000000000007' => ['validator.sim.invalid'],
            '0000000000000000008' => ['validator.sim.invalid'],
            '0000000000000000009' => ['validator.sim.invalid'],

            '00000000000000000000' => [],
            '00000000000000000018' => [],
            '00000000000000000026' => [],
            '00000000000000000034' => [],
            '00000000000000000042' => [],
            '00000000000000000059' => [],
            '00000000000000000067' => [],
            '00000000000000000075' => [],
            '00000000000000000083' => [],
            '00000000000000000091' => [],

            '00000000000000000001' => ['validator.sim.invalid'],
            '00000000000000000019' => ['validator.sim.invalid'],
            '00000000000000000027' => ['validator.sim.invalid'],
            '00000000000000000035' => ['validator.sim.invalid'],
            '00000000000000000043' => ['validator.sim.invalid'],
            '00000000000000000058' => ['validator.sim.invalid'],
            '00000000000000000068' => ['validator.sim.invalid'],
            '00000000000000000076' => ['validator.sim.invalid'],
            '00000000000000000084' => ['validator.sim.invalid'],
            '00000000000000000092' => ['validator.sim.invalid'],

            '000000000000000000000' => ['validator.sim.invalid']
        ]);
    }

    public function testSimValidatorWithoutCheckDigit()
    {
        $validator = new SimValidator(false);

        $this->doTestValidator($validator, [
            ''                      => ['validator.sim.invalid'],
            '0'                     => ['validator.sim.invalid'],
            '00'                    => ['validator.sim.invalid'],
            '000'                   => ['validator.sim.invalid'],
            '0000'                  => ['validator.sim.invalid'],
            '000000'                => ['validator.sim.invalid'],
            '0000000'               => ['validator.sim.invalid'],
            '00000000'              => ['validator.sim.invalid'],
            '000000000'             => ['validator.sim.invalid'],
            '0000000000'            => ['validator.sim.invalid'],
            '00000000000'           => ['validator.sim.invalid'],
            '000000000000'          => ['validator.sim.invalid'],
            '0000000000000'         => ['validator.sim.invalid'],
            '00000000000000'        => ['validator.sim.invalid'],
            '000000000000000'       => ['validator.sim.invalid'],
            '0000000000000000'      => ['validator.sim.invalid'],
            '00000000000000000'     => ['validator.sim.invalid'],
            '000000000000000000'    => [],
            '0000000000000000000'   => [],
            '00000000000000000000'  => ['validator.sim.invalid'],
            '000000000000000000000' => ['validator.sim.invalid'],

            ' 000000000000000000' => ['validator.sim.invalid'],
            '000000000000000000 ' => ['validator.sim.invalid'],
        ]);
    }
}
