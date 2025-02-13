<?php

// Based on: https://davidhancock.co/2013/05/generating-a-list-of-timezones-with-php/

/**
 * Return an array of timezones
 * 
 * @return array
 */

function timezoneList()
{
    $timezoneIdentifiers = DateTimeZone::listIdentifiers();
    $utcTime = new DateTime('now', new DateTimeZone('UTC'));

    $tempTimezones = array();
    foreach ($timezoneIdentifiers as $timezoneIdentifier) {
        $currentTimezone = new DateTimeZone($timezoneIdentifier);

        $tempTimezones[] = array(
            'offset' => (int)$currentTimezone->getOffset($utcTime),
            'identifier' => $timezoneIdentifier
        );
    }

    // Sort the array by offset,identifier ascending
    usort($tempTimezones, function($a, $b) {
		return ($a['offset'] == $b['offset'])
			? strcmp($a['identifier'], $b['identifier'])
			: $a['offset'] - $b['offset'];
    });

	$timezoneList = array();
    foreach ($tempTimezones as $tz) {
		$sign = ($tz['offset'] > 0) ? '+' : '-';
		$offset = gmdate('H:i', abs($tz['offset']));
		$tz['identifier'] = str_replace('_', ' ', $tz['identifier']);
        $timezoneList[$tz['identifier']] = $tz['identifier'] . ' (UTC ' . $sign . $offset . ')';
    }

    return $timezoneList;
}

$timezoneList = timezoneList();
echo '<select name="timezone">';
foreach ($timezoneList as $value => $label) {
    echo '<option value="' . $value . '">' . $label . '</option>';
}
echo '</select>';