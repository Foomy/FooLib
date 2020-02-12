<?php

namespace FooLib;

/**
 * This class provides several methods for string manipulation.
 *
 * The class represents the string which is given to the create
 * method. Once initalised several methods can be applied by use
 * of chaining.
 *
 * Example:
 *    $lowerCamelCase = FooLib\String::create(' this_is_snake_case')
 *                          ->extendedTrim()
 *                          ->snake2lowerCamel();
 *    echo $lowerCamelCase;
 *
 *    Output: thisIsSnakeCase; // Of course it isn't anymore ;-)
 *
 * @author Sascha Schneider <sir.foomy@googlemail.com>
 *
 * @category library
 * @package  String
 */
class Str
{
    /**
     * The string value which the object is representing.
     *
     * @var string
     */
    private $string;

    /**
     * Determines, if the value string is UTF-8.
     *
     * @var bool
     */
    private $isMultiByte = false;

    /**
     * Constructor
     *
     * @param Str $string
     */
    public function __construct($string = '')
    {
        $this->setString($string);
        $this->isMultiByte = (mb_detect_encoding($this->string) === 'UTF-8');
    }

    /**
     * Factory
     *
     * @param  Str $string
     *
     * @return Str
     */
    public static function create($string)
    {
        return new self($string);
    }

    /**
     * Sets the given input to the internal value. All input
     * will be strictly casted to a string.
     *
     * @param Str $input
     *
     * @return Str
     */
    public function setString($input)
    {
        $this->string = (string)$input;

        return $this;
    }

    /**
     * @return bool
     */
    public function isMultiByte()
    {
        return $this->isMultiByte;
    }

    /**
     * Appends the given input to the string value.
     *
     * @param  Str $appendix
     *
     * @return Str
     */
    public function append($appendix)
    {
        $this->string = $this->string . $appendix;

        return $this;
    }

    /**
     * Prepends the given input to the string value.
     *
     * @param  Str $prefix
     *
     * @return Str
     */
    public function prepend($prefix)
    {
        $this->string = $prefix . $this->string;

        return $this;
    }

    /**
     * Inserts the given value at the given position into
     * represented string. Note: The position count starts
     * with zero.
     *
     * @param  Str $value
     * @param  int $position
     *
     * @return Str
     */
    public function insert($value, $position)
    {
        $prefix   = substr($this->string, 0, $position);
        $appendix = substr($this->string, $position);

        $this->string = $prefix . $value . $appendix;

        return $this;
    }

    /**
     * Shortens the string to the given character count.
     *
     * @param  int $maxCharacters
     * @param  bool $obeyWordBoundaries
     *
     * @return Str
     */
    public function shorten($maxCharacters, $obeyWordBoundaries = false)
    {
        if (strlen($this->string) > $maxCharacters) {
            $this->string = substr($this->string, 0, $maxCharacters);

            if ($obeyWordBoundaries) {
                if (($pos = strrpos($this->string, ' ')) > 0) {
                    $this->string = substr_replace($this->string, '', $pos);
                }
            }
        }

        return $this;
    }

    /**
     * Converts the value from snake_case to UpperCamelCase.
     *
     * @return Str
     */
    public function snake2upperCamel()
    {
        $this->string = str_replace('_', ' ', $this->string);
        $this->string = ucwords($this->string);
        $this->string = str_replace(' ', '', $this->string);

        return $this;
    }

    /**
     * Converts the value from snake_case to lowerCamelCase.
     *
     * @return Str
     */
    public function snake2lowerCamel()
    {
        $this->snake2upperCamel();
        $this->string = lcfirst($this->string);

        return $this;
    }

    /**
     * Converts the value string from camelCase to snake_case.
     */
    public function camel2snake()
    {
        throw new \BadMethodCallException('Method not implemented yet.');
    }

    /**
     * Like normal trim, but also removes non breaking spaces.
     *
     * @return Str
     */
    public function extendedTrim()
    {
        $charList     = " \t\n\r\0\x0B" . chr(0xC2).chr(0xA0);
        $this->string = trim($this->string, $charList);

        return $this;
    }

    /**
     * Replaces the first occurrence of $search with $replace.
     *
     * @param string $search
     * @param string $replace
     *
     * @return Str
     */
    private function replaceFirstOccurrence(string $search, string $replace): Str
    {
        $pos = strpos($this->string, $search);
        $len = strlen($search);

        if (false !== $pos) {
            $this->string = substr_replace($this->string, $replace, $pos, $len);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->string;
    }

    /**
     * @deprecated Use the non magic method toString() instead!
     *
     * @return string
     */
    public function __toString()
    {
        return $this->string;
    }
}