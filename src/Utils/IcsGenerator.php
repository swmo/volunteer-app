<?php

/*
 */

namespace App\Utils;

use App\Entity\Mission;
use Symfony\Component\Filesystem\Filesystem;

class IcsGenerator
{

    public function generateMissionIcs(Mission $mission)
    {
        $fs = new Filesystem();

        //temporary folder, it has to be writable
        $tmpFolder = '/tmp/';

        //the name of your file to attach
        $fileName = 'Kalendereintrag_'.$mission->getId().'.ics';
//todo: trim??
    $icsContent = "BEGIN:VCALENDAR
PRODID:Burgdorfer Stadtlauf
VERSION:2.0
CALSCALE:GREGORIAN
BEGIN:VTIMEZONE
TZID:Europe/Zurich
BEGIN:DAYLIGHT
TZOFFSETFROM:+0100
TZOFFSETTO:+0200
TZNAME:CEST
END:DAYLIGHT
END:VTIMEZONE
";

$icsContent = $icsContent . "BEGIN:VEVENT
UID:".$mission->getId()."@helfer.burgdorfer-stadtlauf.ch
SUMMARY:HelferInneneinsatz Stadtlauf Burgdorf - ".$mission->getName()."
DTSTAMP:".$mission->getStart()->format('Ymd\THis')."
DTSTART;TZID=Europe/Zurich:".$mission->getStart()->format('Ymd\THis')."
DTEND;TZID=Europe/Zurich:".$mission->getEnd()->format('Ymd\THis')."
LOCATION:".$mission->getMeetingPoint()."
DESCRIPTION:".$mission->getCalendarEventDescription()."
STATUS:CONFIRMED
SEQUENCE:0
END:VEVENT
";
        $icsContent =  str_replace("\n","\r\n",$icsContent . "END:VCALENDAR");
        //creation of the file on the server
        $icsFile= $fs->dumpFile($tmpFolder.$fileName, $icsContent);

        return $tmpFolder.$fileName;
    }

}
