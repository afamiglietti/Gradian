<?php


namespace AssignmentBundle\DataFixtures\ORM;

use AssignmentBundle\Entity\CategoryProgress;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UserBundle\Entity\User;
use CourseBundle\Entity\Course;
use DashboardBundle\Entity\Dashboard;
use AssignmentBundle\Entity\Category;


class LoadCategoryProgressData extends AbstractFixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        for($i = 1; $i<=25; $i++){
            $catProgress = new CategoryProgress();
            $catProgress->setCategory($this->getReference('readingSummaries'));
            $catProgress->setUser($this->getReference('studentUser' . strval($i)));


            $manager->persist($catProgress);

            $this->addReference('summaryProgress' . strval($i), $catProgress);
        }

        for($i = 1; $i<=25; $i++){
            $catProgress = new CategoryProgress();
            $catProgress->setCategory($this->getReference('annotatedBibliography'));
            $catProgress->setUser($this->getReference('studentUser' . strval($i)));


            $manager->persist($catProgress);

            $this->addReference('bibliographyProgress' . strval($i), $catProgress);
        }

        for($i = 1; $i<=25; $i++){
            $catProgress = new CategoryProgress();
            $catProgress->setCategory($this->getReference('environmentDescription'));
            $catProgress->setUser($this->getReference('studentUser' . strval($i)));

            $manager->persist($catProgress);

            $this->addReference('descriptionProgress' . strval($i), $catProgress);
        }

        for($i = 1; $i<=25; $i++){
            $catProgress = new CategoryProgress();
            $catProgress->setCategory($this->getReference('environmentAnalysis'));
            $catProgress->setUser($this->getReference('studentUser' . strval($i)));

            $manager->persist($catProgress);

            $this->addReference('analysisProgress' . strval($i), $catProgress);
        }

        for($i = 1; $i<=25; $i++){
            $catProgress = new CategoryProgress();
            $catProgress->setCategory($this->getReference('participation'));
            $catProgress->setUser($this->getReference('studentUser' . strval($i)));

            $manager->persist($catProgress);

            $this->addReference('participationProgress' . strval($i), $catProgress);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 6;
    }
}