<?php
class PasswordGenerator
{
    /**
     * @var string $password This is the password that will be generated
     */
    protected static $password = '';

    /**
     * @var int $length This is the length of the requested password
     */
    protected static $length;

    /**
     * @var bool $lowercase Should the password include lowercase chars
     */
    protected static $lowercase;

    /**
     * @var bool $uppercase Should the password include uppercase chars
     */
    protected static $uppercase;

    /**
     * @var int $numbers Should the password include numbers
     */
    protected static $numbers;

    /**
     * @var int $special Should the password include special characters
     */
    protected static $special;

    /**
     * @var int $lowercaseCharsVal The amount of lowercase letters required in the password
     */
    public static $lowercaseCharsVal;

    /**
     * @var int $uppercaseCharsVal The amount of uppercase letters required in the password
     */
    public static $uppercaseCharsVal;

    public function __construct(int $length = 10, bool $lowercase = true, bool $uppercase = true, int $numbers = 1, int $special = 1)
    {
        self::$length = $length;
        self::$uppercase = $uppercase;
        self::$lowercase = $lowercase;
        self::$numbers = $numbers;
        self::$special = $special;
        self::$lowercaseCharsVal = 0;
        self::$uppercaseCharsVal = 0;
    }

    /**
     * This is master function called to generate a password
     *
     * @return string
     */
    final static public function generatePassword(): string
    {
        self::validatePasswordGenerationConfiguration();

        self::determineExactNumberOfLetter();

        self::setPasswordCharacters();

        self::jumblePassword();

        return self::$password;
    }

    /**
     * This validates that the password generator configuration are possible
     *
     * @return void
     */
    static protected function validatePasswordGenerationConfiguration(): void
    {
        //Firstly check min amount of a-zA-Z chars required
        $charsReqValue = 0;
        $charsReqValue = (self::$lowercase === true) ? $charsReqValue++ : $charsReqValue;
        $charsReqValue = (self::$uppercase === true) ? $charsReqValue++ : $charsReqValue;

        //Check if the request password length can accommodate the requested requirements and throw error if not
        if(self::$numbers + self::$special + $charsReqValue > self::$length
            || self::$numbers < 0
            || self::$special < 0
        ) {
            throw new InvalidArgumentException('Your password generator configurations are not possible');
        }

        //This check if there is enough requested characters to full-fill the requested password length
        if((self::$lowercase === false && self::$uppercase === false)
            && (self::$numbers + self::$special < self::$length)) {
            throw new InvalidArgumentException('Your password generator configurations do not full-fill requested length');
        }

    }

    /**
     * This determines the exact number of upper and lowercase letters required in the password
     *
     * @return void
     */
    static protected function determineExactNumberOfLetter(): void
    {
        $charsAvailable = self::$length - self::$numbers - self::$special;

        //Check if this step is required first
        if($charsAvailable <= 0){
            return;
        }

        if (self::$lowercase === true && self::$uppercase === true){

            //Check if available chars are even to share equally otherwise allocate lowercase remainder
            if( $charsAvailable % 2 === 0 ){
                self::$lowercaseCharsVal = $charsAvailable / 2;
                self::$uppercaseCharsVal = $charsAvailable / 2;
            }else{
                self::$lowercaseCharsVal = ($charsAvailable - 1) / 2;
                self::$lowercaseCharsVal++;
                self::$uppercaseCharsVal = ($charsAvailable - 1) / 2;
            }

        } elseif (self::$lowercase === true){
            self::$lowercaseCharsVal = $charsAvailable;
        } else{
            self::$uppercaseCharsVal = $charsAvailable;
        }
    }

    /**
     * @return void
     */
    static protected function setPasswordCharacters(): void
    {
        if(self::$numbers > 0){
            self::$password .= substr(str_shuffle(str_repeat('0123456789', mt_rand(1,10))), 0, self::$numbers);
        }

        if(self::$special > 0){
            self::$password .= substr(str_shuffle(str_repeat('@%!?*^&', mt_rand(1,10))), 0, self::$special);
        }

        if(self::$lowercaseCharsVal > 0){
            self::$password .= substr(str_shuffle(str_repeat('abcdefghijklmnopqrstuvwxyz', mt_rand(1,10))), 0, self::$lowercaseCharsVal);
        }

        if(self::$uppercaseCharsVal > 0){
            self::$password .= substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZ', mt_rand(1,10))), 0, self::$uppercaseCharsVal);
        }
    }

    /**
     * This jumbles the remaining string of chars to create the final password
     *
     * @return void
     */
    static protected function jumblePassword(): void
    {
        self::$password = str_shuffle(self::$password);
    }

}

$pass = new PasswordGenerator(16 , true, true, 4, 4);

$password = $pass::generatePassword();

echo $password . PHP_EOL;