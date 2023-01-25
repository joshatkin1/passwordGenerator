<?php

namespace Pixled\PasswordGenerator\Tests;

use InvalidArgumentException;
use Orchestra\Testbench\TestCase;
use Pixled\PasswordGenerator\Models\PasswordGenerator;

class PasswordGeneratorTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    public function testPasswordGeneratorReturnsString()
    {
        $this->passwordGenerator = new PasswordGenerator(10, true, true, 1, 1);

        $this->assertIsString($this->passwordGenerator::generatePassword()
        , 'password generator not returning string');
    }

    public function testPasswordRetrunsCorrectStringLength()
    {
        $this->passwordGenerator = new PasswordGenerator(10, true, true, 1, 1);

        $this->assertEquals(10, strlen($this->passwordGenerator::generatePassword())
            , 'password string length is not as requested');
    }

    public function testThePasswordGeneratorThrowInvalidExceptionWithInvalidConfig()
    {
        $passwordGenerator = new PasswordGenerator(10, true, true, 8, 1);
        $this->assertThrows($passwordGenerator::generatePassword(), new InvalidArgumentException('Your password generator configurations are not possible'));

        $passwordGenerator = new PasswordGenerator(10, true, false, 8, 2);
        $this->assertThrows($passwordGenerator::generatePassword(), new InvalidArgumentException('Your password generator configurations are not possible'));

        $passwordGenerator = new PasswordGenerator(10, false, false, 8, 3);
        $this->assertThrows($passwordGenerator::generatePassword(), new InvalidArgumentException('Your password generator configurations are not possible'));
    }

    public function testIfTheConfigurationCanFullFillPasswordLength()
    {
        $passwordGenerator = new PasswordGenerator(10, false, false, 8, 1);
        $this->assertThrows($passwordGenerator::generatePassword(), new InvalidArgumentException('Your password generator configurations do not full-fill requested length'));

        $passwordGenerator = new PasswordGenerator(10, false, false, 2, 7);
        $this->assertThrows($passwordGenerator::generatePassword(), new InvalidArgumentException('Your password generator configurations do not full-fill requested length'));
    }

    public function testThatThePasswordGeneratorDeterminesLetterCharLengthsCorrectly()
    {
        $passwordGenerator = new PasswordGenerator(10, false, false, 8, 2);
        $passwordGenerator::generatePassword();
        $this->assertEquals(0, $passwordGenerator::$lowercaseCharsVal);
        $this->assertEquals(0, $passwordGenerator::$uppercaseCharsVal);

        $passwordGenerator = new PasswordGenerator(10, true, true, 4, 3);
        $passwordGenerator::generatePassword();
        $this->assertEquals(2, $passwordGenerator::$lowercaseCharsVal);
        $this->assertEquals(1, $passwordGenerator::$uppercaseCharsVal);

        $passwordGenerator = new PasswordGenerator(10, true, true, 3, 3);
        $passwordGenerator::generatePassword();
        $this->assertEquals(2, $passwordGenerator::$lowercaseCharsVal);
        $this->assertEquals(2, $passwordGenerator::$uppercaseCharsVal);
    }

}