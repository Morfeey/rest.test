<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 17.12.2018
 * Time: 20:29
 */

namespace Helpers;


use DateTimeZone;

class DateTime extends \DateTime
{
    public function addSeconds(int $int): self
    {
        $this->adder($int, "S");
        return $this;
    }

    public function addMinutes(int $int): self
    {
        $this->adder($int, "i");
        return $this;
    }

    public function addHours(int $int): self
    {
        $this->adder($int, "H");
        return $this;
    }

    public function addDays(int $int): self
    {
        $this->adder($int, "D");
        return $this;
    }

    public function addWeeks(int $int): self
    {
        $int = $int * 7;
        $this->adder($int, "D");
        return $this;
    }

    public function addMonths(int $int): self
    {
        $this->adder($int, "M");
        return $this;
    }

    public function addYears(int $int): self
    {
        $this->adder($int, "Y");
        return $this;
    }


    public function subSeconds(int $int): self
    {
        $this->subber($int, "S");
        return $this;
    }

    public function subMinutes(int $int): self
    {
        $this->subber($int, "I");
        return $this;
    }

    public function subHours(int $int): self
    {
        $this->subber($int, "H");
        return $this;
    }

    public function subDays(int $int): self
    {
        $this->subber($int, "D");
        return $this;
    }

    public function subWeeks(int $int): self
    {
        $int = $int * 7;
        $this->subber($int, "D");
        return $this;
    }

    public function subMonths(int $int): self
    {
        $this->subber($int, "M");
        return $this;
    }

    public function subYears(int $int): self
    {
        $this->subber($int, "Y");
        return $this;
    }


    /*Days*/

    public function getDay() :int {
        return (int) $this->format("j");
    }

    public function getDayWithLeadingZero () :string {
        return (string) $this->format("d");
    }

    public function getNumberDayOfWeek(bool $isISO_8601 = true) :int
    {
        return (int)$this->format(($isISO_8601) ? "N" : "w");
    }

    public function getNameDayOfWeek(bool $isFull = true): string
    {
        return (string)$this->format(($isFull) ? "l" : "D");
    }

    public function getNumberWeek(): int
    {
        return (int)$this->format("W");
    }


    /*Month*/
    public function getNameMonth(bool $isFull = true): string
    {
        return (string)$this->format( ($isFull) ? "F" : "M" );
    }

    public function getDaysInMonth(): int
    {
        return (int)$this->format("t");
    }

    public function getMonth () : int {
        return (int) $this->format("n");
    }

    public function getMonthWithLeadingZero () :string {
        return (string) $this->format("m");
    }



    /*Year*/
    public function isLeapYear(): bool
    {
        return (bool)($this->format("L"));
    }

    public function getYear (bool $isFull = true) :int {
        return (int) $this->format( ($isFull) ? "Y" : "y" );
    }




    /*Time*/
    /**
     * Ante or Post
     * @param bool $isUpChars
     * @return string
     */
    public function getAnteOrPostMeridiem (bool $isUpChars = true) :string {
        return (string) $this->format( ($isUpChars) ? "A" : "a" );
    }

    public function getAlternativeTimeOfDaySystem () :string {
        return (string) $this->format("B");
    }

    public function getHours (bool $isTwentyFourFormatAndNotTwelve = true) :int {
        return (int) $this->format( ($isTwentyFourFormatAndNotTwelve) ? "G" : "g" );
    }

    public function getHoursWithLeadingZero (bool $isTwentyFourFormatAndNotTwelve = true) :string {
        return (string) $this->format( ($isTwentyFourFormatAndNotTwelve) ? "H" : "h" );
    }



    public function getMinutes () :int {
        return (int) $this->getMinutesWithLeadingZero();
    }

    public function getMinutesWithLeadingZero () :string {
        return (string) $this->format("i");
    }

    public function getSeconds () : int {
        return $this->getSecondsWithLeadingZero();
    }

    public function getSecondsWithLeadingZero () :string {
        return (string) $this->format("s");
    }

    public function gerMicroseconds () : int {
        return (int) $this->format("u");
    }

    public function getMilliseconds () : int {
        return (int) $this->format("v");
    }



    /*TimeZone*/
    public function getIndexTimezone () :string {
        return (string) $this->format("e");
    }

    public function isSummerTime () :bool {
        return (bool) $this->format("I");
    }

    public function getGTMinHours (bool $isWithColon = true) : string {
        return (string) $this->format( ($isWithColon) ? "P" : "O" );
    }

    public function getTimezoneAbbreviation () :string {
        return (string) $this->format("T");
    }

    public function getTimezoneOffsetInSeconds () : int {
        return (int) $this->format("Z");
    }


    public function inBetween(\DateTime $start, \DateTime $finish) :bool
    {
        $timeStart = $start->getTimestamp();
        $timeFinish = $finish->getTimestamp();
        $timeCurrent = $this->getTimestamp();

        $result = ($timeCurrent > $timeStart && $timeCurrent < $timeFinish);
        return $result;
    }




    private function getStrDateTime(int $value, string $char) :string
    {

        $result = [];
        $char = (new StringValue($char))
            ->toUp()
            ->getResult();
        $ListChars = [
            "P" => ["Y", "M", "D"],
            "T" => ["H", "I", "S"]
        ];

        foreach ($ListChars as $keyDate => $ItemDate) {

            $result[] = $keyDate;
            foreach ($ItemDate as $Char) {
                $result[] = ($char == $Char) ? $value : 0;
                $result[] = ($Char == "I") ? "M" : $Char;
            }


        }
        $result = implode("", $result);
        return $result;
    }

    private function adder(int $value, $nameChar): self
    {
        $stringDateInterval = $this->getStrDateTime($value, $nameChar);
        return $this->add(new \DateInterval($stringDateInterval));
    }

    private function subber(int $value, $nameChar): self
    {
        $stringDateInterval = $this->getStrDateTime($value, $nameChar);
        return $this->sub(new \DateInterval($stringDateInterval));
    }




    public function format($format = "d.m.Y H:i:s") :string
    {
        return parent::format($format); // TODO: Change the autogenerated stub
    }

    public function __construct(string $time = 'now', DateTimeZone $timezone = null)
    {
        parent::__construct($time, $timezone);
    }
}