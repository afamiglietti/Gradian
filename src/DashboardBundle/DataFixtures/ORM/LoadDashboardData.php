<?php

namespace CourseBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UserBundle\Entity\User;
use CourseBundle\Entity\Course;
use DashboardBundle\Entity\Dashboard;

class LoadDashboardData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $instrDash = new Dashboard();
        $instrDash->setUser($this->getReference('teacherUser'));
        $instrDash->setCourse($this->getReference('testCourse'));
        $instrDash->setRole(2);

        $manager->persist($instrDash);

        for($i = 1; $i<=25; $i++){
            $studentDash = new Dashboard();
            $studentDash->setUser($this->getReference('studentUser' . strval($i)));
            $studentDash->setCourse($this->getReference('testCourse'));
            $studentDash->setRole(1);

            $manager->persist($studentDash);

            $this->addReference('studentDash' . strval($i), $studentDash);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 3;
    }
}