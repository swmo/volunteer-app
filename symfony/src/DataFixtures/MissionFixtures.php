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
        $mission->setImage('cooli.jpg');
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
        $mission->setImage('cooli.jpg');
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
        $mission->setImage('cooli.jpg');
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
        $mission->setImage('cooli.jpg');
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
        $mission->setImage('cooli.jpg');
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
        $mission->setImage('cooli.jpg');
        $mission->setProject($this->getReference('Project_Stadtlauf_2019'));
        $manager->persist($mission);
        $manager->flush();

        $mission = new Mission();
        $mission->setName('Maskottchen "Cooly"');
        $mission->setShortDescription('
            Aufgabe: Du umrahmst die gute Stimmung auf dem Kronenplatz und bist der Superheld der Kinder.
            <br />
            Anforderung: Spass am Spass haben und warme Temperaturen aushalten können.
        ');
        $mission->setImage('cooli.jpg');
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
        $mission->setImage('cooli.jpg');
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
