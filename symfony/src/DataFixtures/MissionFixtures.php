<?php

namespace App\DataFixtures;

use App\Entity\Mission;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class MissionFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {

        $mission = new Mission();
        $mission->setName('Chip- Entnahme');
        $mission->setShortDescription(' 
            Aufgabe: Du bist beim Zieleingang und nimmst jedem Läufer den Zeitmessungs- Chip ab.
            <br />
            Anforderung: Du kannst schnell und ohne Berührungsängsten anpacken.
        ');
        $mission->setImage('chip_entnahme.jpg');
        $mission->setStart(new \DateTime('2019-09-14 14:00'));
        $mission->setEnd(new \DateTime('2019-09-14 19:30'));
        $mission->setRequiredVolunteers(6);
        $mission->setProject($this->getReference('Project_Stadtlauf_2019'));

        $manager->persist($mission);
        $manager->flush();

        $mission = new Mission();
        $mission->setName('Aufbau');
        $mission->setShortDescription('
            Aufgabe: Du hilfst die Strecke aufbauen inkl. Werbeplakate aufhängen.
            <br />
            Anforderung: Du betätigst dich gerne körperlich und fühlst dich fit.
            <br />
            Dieser Poste kann gut im Anschluss mit dem Streckenposten (17:00-19.:30) kombiniert werden.
        ');
        
        $mission->setImage('aufbau.jpg');
        $mission->setStart(new \DateTime('2019-09-14 13:00'));
        $mission->setEnd(new \DateTime('2019-09-14 17:00'));
        $mission->setRequiredVolunteers(13);
        $mission->setProject($this->getReference('Project_Stadtlauf_2019'));
        $manager->persist($mission);
        $manager->flush();


        $mission = new Mission();
        $mission->setName('Streckenposten');
        $mission->setShortDescription('
            Aufgabe: Du stehst bei einem Posten auf der Strecke und bist verantwortlich für die Sicherheit
            der Laufstrecke.
            <br />
            Anforderung: Du behälst den Überblick und bist min. 25 Jahre alt.
        ');
        $mission->setImage('streckenposten.jpg');
        $mission->setStart(new \DateTime('2019-09-14 17:00'));
        $mission->setEnd(new \DateTime('2019-09-14 19:30'));
        $mission->setRequiredVolunteers(19);
        $mission->setProject($this->getReference('Project_Stadtlauf_2019'));
        $manager->persist($mission);
        $manager->flush();

        $mission = new Mission();
        $mission->setName('Anmeldung');
        $mission->setShortDescription('
            Aufgabe: Du hilfst bei der Startnummernausgabe, Nachmeldung oder dem Wertsachen- Depot.
            <br />
            Anforderung: Du bringst ein schnelles Auffassungsvermögen und deine Freundlichkeit mit.
        ');
        $mission->setImage('anmeldung.jpg');
        $mission->setStart(new \DateTime('2019-09-14 11:45'));
        $mission->setEnd(new \DateTime('2019-09-14 17:45'));
        $mission->setRequiredVolunteers(9);
        $mission->setProject($this->getReference('Project_Stadtlauf_2019'));
        $manager->persist($mission);
        $manager->flush();

        $mission = new Mission();
        $mission->setName('Verpflegung Läufer');
        $mission->setShortDescription('
            Aufgabe: Du stellst den Läufern Getränke zur Verfügung.
            Ihr seid jeweils zu dritt an einem Verpflegungsposten.
            <br />
            Anforderung: Selbstständiges Arbeiten.
        ');
        $mission->setImage('verpflegung.jpg');
        $mission->setStart(new \DateTime('2019-09-14 13:00'));
        $mission->setEnd(new \DateTime('2019-09-14 19:00'));
        $mission->setRequiredVolunteers(10);
        $mission->setProject($this->getReference('Project_Stadtlauf_2019'));
        $manager->persist($mission);
        $manager->flush();

        $mission = new Mission();
        $mission->setName('Festwirtschaft');
        $mission->setShortDescription('
            Aufgabe: Du verkaufst entweder Getränke, machst Hot Dogs oder Pommes Frites, bist Grilleur,
            schenkst Bier aus oder bist für die Mehrweggeschirr- Rücknahme verantwortlich.
            <br />
            Anforderung: Gib an welche Aufgabe dir liegt.
        ');
        $mission->setImage('festwirtschaft.jpg');
        $mission->setStart(new \DateTime('2019-09-14 13:00'));
        $mission->setEnd(new \DateTime('2019-09-14 20:00'));
        $mission->setRequiredVolunteers(12);
        $mission->setProject($this->getReference('Project_Stadtlauf_2019'));
        $manager->persist($mission);
        $manager->flush();

        $mission = new Mission();
        $mission->setName('Abbau');
        $mission->setShortDescription('
            Aufgabe: Du hilfst beim Abbau der Strecke, Absperrgitter, Werbeplakate und Festbänke wegräumen.
            <br />
            Anforderung: Du fühlst dich körperlich fit und bist bei Regen wetterresistent.
        ');
        $mission->setImage('abbau.jpg');
        $mission->setStart(new \DateTime('2019-09-14 18:30'));
        $mission->setEnd(new \DateTime('2019-09-14 21:30'));
        $mission->setRequiredVolunteers(10);
        $mission->setProject($this->getReference('Project_Stadtlauf_2019'));
        $manager->persist($mission);
        $manager->flush();

        $mission = new Mission();
        $mission->setName('Maskottchen "Cooly"');
        $mission->setShortDescription('
            Aufgabe: Du umrahmst die gute Stimmung auf dem Kronenplatz und bist der Superheld der Kinder.
            <br />
            Anforderung: Spass am Spass haben und warme Temperaturen aushalten können.
            <br><br>
            Einsatz jeweils im Halbstundentakt
            ');
        $mission->setImage('cooli.jpg');
        $mission->setStart(new \DateTime('2019-09-14 14:30'));
        $mission->setEnd(new \DateTime('2019-09-14 19:00'));
        $mission->setRequiredVolunteers(2);
        $mission->setProject($this->getReference('Project_Stadtlauf_2019'));
        $manager->persist($mission);
        $manager->flush();

        $mission = new Mission();
        $mission->setName('Verpacken der Läufergeschenke');
        $mission->setShortDescription('
            Aufgabe: Du verpackst die Läufergeschenke in Taschen und hilfst die Ware bereits an den richtigen
            Ort zu tragen.
            <br />  
            Anforderung: Effizient anpacken können.
        ');
        $mission->setImage('geschenke.jpg');
        $mission->setStart(new \DateTime('2019-09-13 17:00'));
        $mission->setEnd(new \DateTime('2019-09-14 21:30'));
        $mission->setRequiredVolunteers(5);
        $mission->setProject($this->getReference('Project_Stadtlauf_2019'));
        $manager->persist($mission);
        $manager->flush();

    }

    public function getDependencies()
    {
        return array(
            ProjectFixtures::class,
        );
    }
}
