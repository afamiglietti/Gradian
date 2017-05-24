<?php


namespace UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use UserBundle\Entity\User;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $testInstrUser = new User();
        $testInstrUser->setUsername('testTeacher');
        $testInstrUser->setPlainPassword('testtest');
        $testInstrUser->setFirstname('Roberta');
        $testInstrUser->setLastname('Roberts');
        $testInstrUser->setEmail('test@example.com');
        $testInstrUser->setRoles(array('ROLE_INSTRUCTOR'));
        $testInstrUser->setEnabled(True);

        $userFirstNames = array('Susan', 'Kristen', 'Kirsten', 'Rachel', 'Mike', 'Shaun', 'Matthew', 'Emily', 'Nick', 'Sam');
        $userLastNames = array('King', 'Famiglietti', 'Woods', 'Stuart', 'Smith', 'Jones', 'Sandoval', 'Ngyuen', 'Lee');

        for($i = 1; $i<=25; $i++){
            $firstName = $userFirstNames[array_rand($userFirstNames)];
            $lastName = $userLastNames[array_rand($userLastNames)];

            $studentUser = new User();
            $studentUser->setUsername($firstName . $lastName . strval($i));
            $studentUser->setPlainPassword('studenttest');
            $studentUser->setFirstname($firstName);
            $studentUser->setLastname($lastName);
            $studentUser->setEmail($firstName . $lastName . strval($i) . '@example.com');
            $studentUser->setEnabled(true);

            $manager->persist($studentUser);

            $this->addReference('studentUser' . strval($i), $studentUser);
        }


        $manager->persist($testInstrUser);
        $manager->flush();

        $this->addReference('teacherUser', $testInstrUser);


    }

    public function getOrder()
    {
        return 1;
    }

}