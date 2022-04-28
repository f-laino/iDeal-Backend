<?php

namespace App\Traits;

trait DateUtils
{
    public static $MONTHS = [
        1 => 'Gennaio',
        'Febbraio',
        'Marzo',
        'Aprile',
        'Maggio',
        'Giugno',
        'Luglio',
        'Agosto',
        'Settembre',
        'Ottobre',
        'Novembre',
        'Dicembre',
    ];

    /**
     * @return array
     */
    public static function getMonthsYearsFromToday($withSlugKeys = false): array
    {
        $months = [];
        $start = date_create_from_format("m/Y", date("m/Y"))
            ->modify("first day of this month");
        $end = date_create_from_format(
                "m/Y",
                date("m/Y", mktime(0, 0, 0, date("m") + 12, 1, date("Y")))
            )
            ->modify("first day of this month");
        $timespan = date_interval_create_from_date_string("1 month");

        while ($start <= $end) {
            $monthName = self::$MONTHS[$start->format("n")];
            $value = $monthName . $start->format(" Y");
            $key = $withSlugKeys ? strtolower(str_replace(' ', '-', $value)) : $value;
            $months[$key] = $value;
            $start = $start->add($timespan);
        }

        return $months;
    }

    /**
     * @return array
     */
    public function getMonthsYearsFromRange($start, $end): array
    {
        $months = [];
        list($startMonth, $startYear) = explode(' ', $start);
        list($endMonth, $endYear) = explode(' ', $end);
        $startMonth = array_search($startMonth, self::$MONTHS);
        $endMonth = array_search($endMonth, self::$MONTHS);

        $start = date_create_from_format("m/Y", $startMonth . '/' . $startYear)
            ->modify("first day of this month");
        $end = date_create_from_format("m/Y", $endMonth . '/' . $endYear)
            ->modify("first day of this month");
        $timespan = date_interval_create_from_date_string("1 month");

        while ($start <= $end) {
            $monthName = self::$MONTHS[$start->format("n")];
            $value = $monthName . $start->format(" Y");
            $key = $value;
            $months[$key] = $value;
            $start = $start->add($timespan);
        }

        return $months;
    }
}
