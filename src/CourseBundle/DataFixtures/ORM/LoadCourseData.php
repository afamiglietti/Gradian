<?php


namespace CourseBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UserBundle\Entity\User;
use CourseBundle\Entity\Course;

class LoadCourseData extends AbstractFixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $testCourse = new Course();
        $testCourse->setOwner($this->getReference('teacherUser'));
        $testCourse->setName('ENG 3201');
        $testCourse->setDescription('A test course loosely based on Robins previous course. Loaded from fixtures');
        $testCourse->setGradeA(2340);
        $testCourse->setGradeB(2080);
        $testCourse->setGradeC(1475);
        $testCourse->setGradeD(800);
        $testCourse->setMaxPoints(2600);
        $testCourse->setMaxPoints(2600);
        $testCourse->setStartDate(new \DateTime);
        $testCourse->setMeetingTime(new \DateTime('2017-05-23T10:30:00Z'));
        $testCourse->setEndDate(new \DateTime('2017-08-01T00:00:00Z'));

        $manager->persist($testCourse);
        $manager->flush();

        $this->addReference('testCourse', $testCourse);

    }

    public function getOrder()
    {
        return 2;
    }

}