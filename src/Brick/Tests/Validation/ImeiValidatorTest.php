<?php

namespace Brick\Tests\Validation;

use Brick\Validation\Validator\ImeiValidator;

/**
 * Unit tests for the IMEI number validator.
 */
class ImeiValidatorTest extends AbstractTestCase
{
    public function testImeiValidator()
    {
        $validator = new ImeiValidator();

        $this->doTestValidator($validator, [
            ''               => ['validator.imei.invalid'],
            '0'              => ['validator.imei.invalid'],
            '00'             => ['validator.imei.invalid'],
            '000'            => ['validator.imei.invalid'],
            '0000'           => ['validator.imei.invalid'],
            '00000'          => ['validator.imei.invalid'],
            '000000'         => ['validator.imei.invalid'],
            '0000000'        => ['validator.imei.invalid'],
            '00000000'       => ['validator.imei.invalid'],
            '000000000'      => ['validator.imei.invalid'],
            '0000000000'     => ['validator.imei.invalid'],
            '00000000000'    => ['validator.imei.invalid'],
            '000000000000'   => ['validator.imei.invalid'],
            '0000000000000'  => ['validator.imei.invalid'],
            '00000000000000' => ['validator.imei.invalid'],

            ' 000000000000000' => ['validator.imei.invalid'],
            '000000000000000 ' => ['validator.imei.invalid'],

            '000000000000000' => [],
            '000000000000018' => [],
            '000000000000026' => [],
            '000000000000034' => [],
            '000000000000042' => [],
            '000000000000059' => [],
            '000000000000067' => [],
            '000000000000075' => [],
            '000000000000083' => [],
            '000000000000091' => [],

            '000000000000001' => ['validator.imei.invalid'],
            '000000000000002' => ['validator.imei.invalid'],
            '000000000000003' => ['validator.imei.invalid'],
            '000000000000004' => ['validator.imei.invalid'],
            '000000000000005' => ['validator.imei.invalid'],
            '000000000000006' => ['validator.imei.invalid'],
            '000000000000007' => ['validator.imei.invalid'],
            '000000000000008' => ['validator.imei.invalid'],
            '000000000000009' => ['validator.imei.invalid'],

            '0000000000000000' => ['validator.imei.invalid']
        ]);
    }
}
