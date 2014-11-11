<?php

namespace Brick\Tests\Form;

use Brick\Form\Form;

/**
 * Unit tests for Brick\Form.
 */
class FormTest extends \PHPUnit_Framework_TestCase
{
    public function testSingleSelect()
    {
        // Setup some drinks, and which one should be checked.
        $drinks = ['water', 'beer', 'wine'];
        $selectedDrink = 'beer';

        $form = new Form();
        $select = $form->addSingleSelect('drink');

        foreach ($drinks as $drink) {
            $select->addOption($drink, $drink);
        }

        try {
            $form->populate([
                'drink' => []
            ]);

            $this->fail('Should complain that SingleSelect does not accept an array');
        } catch (\InvalidArgumentException $e) {
            // Works as expected!
        }

        $this->assertTrue($form->populate([
            'drink' => $selectedDrink
        ])->isValid());

        $this->assertEquals($selectedDrink, $select->getValue());
    }

    public function testMultipleSelect()
    {
        // Setup some country codes, and whether they should be checked.
        $countries = [
            'US' => false,
            'GB' => true,
            'FR' => true
        ];

        $form = new Form();
        $select = $form->addMultipleSelect('countries');

        foreach ($countries as $code => $checked) {
            $select->addOption($code, $code);
        }

        try {
            $form->populate([
                'countries' => 'US'
            ]);

            $this->fail('Should complain that MultipleSelect does not accept a string');
        } catch (\InvalidArgumentException $e) {
            // Works as expected!
        }

        $expected = array_keys($countries, true);
        sort($expected);

        $this->assertTrue($form->populate([
            'countries' => $expected
        ])->isValid());

        $actual = $select->getValues();
        sort($actual);

        $this->assertEquals($expected, $actual);
    }

    public function testSelectOptionGroups()
    {
        $form = new Form();
        $country = $form->addSingleSelect('country');

        $country->addOptionGroup('Europe')->addOptions([
            'GB' => 'United Kingdom',
            'IE' => 'Ireland',
            'FR' => 'France'
        ]);

        $country->addOptionGroup('America')->addOptions([
            'US' => 'United States',
            'CA' => 'Canada'
        ]);

        $country->addOption('Antarctica', 'AQ');

        $country->setValue('US');

        $this->assertEquals('US', $country->getValue());

        $id = $country->getId();

        $html =
            '<select name="country" id="' . $id . '">' .
            '<optgroup label="Europe">' .
            '<option value="GB">United Kingdom</option>' .
            '<option value="IE">Ireland</option>' .
            '<option value="FR">France</option>' .
            '</optgroup>' .
            '<optgroup label="America">' .
            '<option value="US" selected="selected">United States</option>' .
            '<option value="CA">Canada</option>' .
            '</optgroup>' .
            '<option value="AQ">Antarctica</option>' .
            '</select>';

        $this->assertEquals($html, $country->render());
    }

    public function testRadioGroup()
    {
        // Setup some drinks, and which one should be checked.
        $drinks = ['water', 'beer', 'wine'];
        $selectedDrink = 'beer';

        $form = new Form();
        $radioButtons = $form->addRadioGroup('drink');

        foreach ($drinks as $drink) {
            $radioButtons->addRadio()->setValue($drink);
        }

        try {
            $form->populate([
                'drink' => []
            ]);

            $this->fail('Should complain that RadioGroup does not accept an array');
        } catch (\InvalidArgumentException $e) {
            // Works as expected!
        }

        $this->assertTrue($form->populate([
            'drink' => $selectedDrink
        ])->isValid());

        foreach ($radioButtons->getElements() as $radioButton) {
            $shouldBeChecked = ($radioButton->getValue() == $selectedDrink);
            $this->assertEquals($radioButton->isChecked(), $shouldBeChecked);
        }
    }

    public function testRequiredInput()
    {
        $form = new Form();
        $form->addInputText('city')->setRequired(true);

        $this->assertFalse($form->populate([])->isValid());
        $this->assertFalse($form->populate(['city' => ''])->isValid());
        $this->assertTrue($form->populate(['city' => 'London'])->isValid());
    }

    public function testCheckboxGroup()
    {
        // Setup some country codes, and whether they should be checked.
        $countries = [
            'US' => false,
            'GB' => true,
            'FR' => true
        ];

        $form = new Form();
        $countryCheckboxes = $form->addCheckboxGroup('countries');

        foreach ($countries as $code => $checked) {
            $countryCheckboxes->addCheckbox()->setValue($code);
        }

        try {
            $form->populate([
                'countries' => 'US'
            ]);

            $this->fail('Should complain that CheckboxGroup does not accept a string');
        } catch (\InvalidArgumentException $e) {
            // Works as expected!
        }

        $this->assertTrue($form->populate([
            'countries' => array_keys($countries, true)
        ])->isValid());

        foreach ($countryCheckboxes->getElements() as $checkbox) {
            $shouldBeChecked = $countries[$checkbox->getValue()];
            $this->assertEquals($checkbox->isChecked(), $shouldBeChecked);
        }
    }
}
