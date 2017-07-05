<?php


namespace AssignmentBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UserBundle\Entity\User;
use CourseBundle\Entity\Course;
use DashboardBundle\Entity\Dashboard;
use AssignmentBundle\Entity\Category;
use AssignmentBundle\Entity\Assignment;
use AssignmentBundle\Entity\Submission;

class LoadSubmissionData extends AbstractFixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        /** for($i = 1; $i<=25; $i++)
        {
            $student = 'studentUser' . strval($i);

            for($j = 1; $j<=6; $j++)
            {
                $assignment = 'summaryAssign' . strval($j);
                $link = 'http://www.smbc-comics.com/';
                $summarySubmission = $this->createSubmission($student, $assignment, $link);

                $manager->persist($summarySubmission);

            }

            $summaryProgress = $this->getReference('summaryProgress' . strval($i));
            $summaryProgress->setSubmissions(6);
            $summaryProgress->setUnread(6);
            $manager->persist($summaryProgress);

            for($j = 1; $j<=9; $j++)
            {
                $assignment = 'bibliographyAssign' . strval($j);
                $link = 'http://boingboing.net/';
                $summarySubmission = $this->createSubmission($student, $assignment, $link);

                $manager->persist($summarySubmission);

            }

            $progress = $this->getReference('bibliographyProgress' . strval($i));
            $progress->setSubmissions(9);
            $progress->setUnread(9);
            $manager->persist($progress);

            for($j = 1; $j<=3; $j++)
            {
                $assignment = 'descriptionAssign' . strval($j);
                $link = 'http://talkingpointsmemo.com/';
                $summarySubmission = $this->createSubmission($student, $assignment, $link);

                $manager->persist($summarySubmission);

            }

            $progress = $this->getReference('descriptionProgress' . strval($i));
            $progress->setSubmissions(3);
            $progress->setUnread(3);
            $manager->persist($progress);

            $assignment='analysisAssign';
            $link='http://www.philly.com/';
            $analysisSubmission = $this->createSubmission($student, $assignment, $link);
            $manager->persist($analysisSubmission);

            $progress = $this->getReference('analysisProgress' . strval($i));
            $progress->setSubmissions(1);
            $progress->setUnread(1);
            $manager->persist($progress);


        }

        $manager->flush();**/
    }

    private function createSubmission($user, $assignment, $link)
    {
        $submission = new Submission();

        $submission->setUser($this->getReference($user));
        $submission->setAssignment($this->getReference($assignment));
        $submission->setLink($link);
        $submission->setSubmitted(new \DateTime());

        return $submission;

    }

    public function getOrder()
    {
        return 7;
    }

}