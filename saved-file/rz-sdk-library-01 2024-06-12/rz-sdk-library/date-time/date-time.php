<?php
namespace RzSDK\DateTime;
use RzSDK\DateTime\DateDiffType;

class DateTime {
    public static $defaultDateFormat = "Y-m-d H:i:s";

    public static function getCurrentDateTime($dateFormat = "Y-m-d H:i:s") {
        return date($dateFormat, time());
    }

    public static function getMicrotime() {
        return round(microtime(true) * 1000);
    }

    public static function getMicroToDate($microtime, $dateFormat = "Y-m-d H:i:s") {
        return date($dateFormat, $microtime);
    }

    public static function getIntervalInSeconds($newDate, $oldDate) {
        if(!self::isValidDate($newDate) || !self::isValidDate($oldDate)) {
            return null;
        }

        return strtotime($newDate) - strtotime($oldDate);
    }

    public static function getDifferent($newDate, $oldDate, DateDiffType $dateDiffType = DateDiffType::seconds) {
        if(!self::isValidDate($newDate) || !self::isValidDate($oldDate)) {
            return null;
        }

        /* echo $dateDiffType->name;
        echo $dateDiffType->value; */

        $dateDiff = date_diff(date_create($newDate), date_create($oldDate))->s;
        if($dateDiffType == DateDiffType::years) {
            $dateDiff = date_diff(date_create($newDate), date_create($oldDate))->y;
        } else if($dateDiffType == DateDiffType::months) {
            $dateDiff = date_diff(date_create($newDate), date_create($oldDate))->m;
        } else if($dateDiffType == DateDiffType::days) {
            $dateDiff = date_diff(date_create($newDate), date_create($oldDate))->days;
        } else if($dateDiffType == DateDiffType::hours) {
            $dateDiff = date_diff(date_create($newDate), date_create($oldDate))->h;
        } else if($dateDiffType == DateDiffType::minutes) {
            $dateDiff = date_diff(date_create($newDate), date_create($oldDate))->i;
        } else {
            $dateDiff = date_diff(date_create($newDate), date_create($oldDate))->s;
        }
        return $dateDiff;
    }

    public static function addDateTime($date, $add, $dateDiffType = DateDiffType::days) {
        if(!self::isValidDate($date)) {
            return null;
        }

        if($dateDiffType == DateDiffType::years) {
            $date = date(self::$defaultDateFormat, strtotime($date . " +{$add} year"));
        } else if($dateDiffType == DateDiffType::months) {
            $date = date(self::$defaultDateFormat, strtotime($date . " +{$add} month"));
        } else if($dateDiffType == DateDiffType::days) {
            $date = date(self::$defaultDateFormat, strtotime($date . " +{$add} day"));
        } else if($dateDiffType == DateDiffType::hours) {
            $date = date(self::$defaultDateFormat, strtotime($date . " +{$add} hour"));
        } else if($dateDiffType == DateDiffType::minutes) {
            $date = date(self::$defaultDateFormat, strtotime($date . " +{$add} minute"));
        } else if($dateDiffType == DateDiffType::seconds) {
            $date = date(self::$defaultDateFormat, strtotime($date . " +{$add} second"));
        }
        return $date;
    }

    public static function subtractDateTime($date, $add, $dateDiffType = DateDiffType::days) {
        if(!self::isValidDate($date)) {
            return null;
        }

        if($dateDiffType == DateDiffType::years) {
            $date = date(self::$defaultDateFormat, strtotime($date . " -{$add} year"));
        } else if($dateDiffType == DateDiffType::months) {
            $date = date(self::$defaultDateFormat, strtotime($date . " -{$add} month"));
        } else if($dateDiffType == DateDiffType::days) {
            $date = date(self::$defaultDateFormat, strtotime($date . " -{$add} day"));
        } else if($dateDiffType == DateDiffType::hours) {
            $date = date(self::$defaultDateFormat, strtotime($date . " -{$add} hour"));
        } else if($dateDiffType == DateDiffType::minutes) {
            $date = date(self::$defaultDateFormat, strtotime($date . " -{$add} minute"));
        } else if($dateDiffType == DateDiffType::seconds) {
            $date = date(self::$defaultDateFormat, strtotime($date . " -{$add} second"));
        }
        return $date;
    }

    /* public static function isValidDateByFormat($date, $format = self::$defaultDateFormat) {
        $dateTime = DateTime::createFromFormat($format, $date);
        return $dateTime && $dateTime->format($format) === $date;
    } */

    public static function isValidDate($date) {
        return (strtotime($date) !== false);
    }
}

/* enum DateDiffType {
    case years;
    case months;
    case days;
    case hours;
    case minutes;
    case seconds;
} */