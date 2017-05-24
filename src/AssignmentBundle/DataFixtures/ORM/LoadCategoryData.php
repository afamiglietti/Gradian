<?php

namespace AssignmentBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UserBundle\Entity\User;
use CourseBundle\Entity\Course;
use DashboardBundle\Entity\Dashboard;
use AssignmentBundle\Entity\Category;

class LoadCategoryData extends AbstractFixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {

        $readingSummaries = new Category();
        $readingSummaries->setCourse($this->getReference('testCourse'));
        $readingSummaries->setName('Reading Summaries');
        $readingSummaries->setDescription('For this project you will read all of the assigned readings for each unit, but choose only two per unit to summarize (50-100 points each).  Compose 500-750 words max per summary. Compose more summaries for more points (up to 50 points per submission, for a max total of 600 points).
Your reading summaries will be created as blog posts on your WordPress site, in the category “Reading Summaries,” and tagged appropriately with the title of the reading you have summarized. You will submit links to your reading summaries using the form on your WordPress site.');
        $readingSummaries->setTotalAssignments(6);
        $readingSummaries->setDisplayOrder(0);

        $manager->persist($readingSummaries);
        $this->addReference('readingSummaries', $readingSummaries);

        $annotatedBib = new Category();
        $annotatedBib->setCourse($this->getReference('testCourse'));
        $annotatedBib->setName('Annotated Bibliography');
        $annotatedBib->setDescription('For this project, you will compose an annotated bibliography using Zotero. Your annotated bibliography will comprise ten complete bibliographic entries and ten annotations. An annotated bibliography is a list of sources. It provides a complete bibliographic entry for each source in MLA format, and then for each bibliographic entry, gives a brief annotation (150-200 words) that evaluates the source and identifies why it is relevant to our ongoing study of the rhetoric of built environments.
Each bibliography annotation (bibliographic entry + annotation) is worth 25-50 points. Compose more bibliography annotations for more points (up to 25 points per submission, for a max total of 500 points on this project). You will use Zotero to create your bibliography and submit links to each bibliography annotation using the form on your WordPress site.');
        $annotatedBib->setTotalAssignments(10);
        $annotatedBib->setDisplayOrder(1);

        $manager->persist($annotatedBib);
        $this->addReference('annotatedBibliography', $annotatedBib);

        $environmentDesc = new Category();
        $environmentDesc->setCourse($this->getReference('testCourse'));
        $environmentDesc->setName('Built Environment Description');
        $environmentDesc->setDescription('For this project, you will write 3 detailed built environment descriptions (100-200 points each):
Description of an interior site
Description of an exterior site
Description of a digital site
Compose more descriptions for more points (up to 100 points per submission, for a max total of 600 points for this project).
Your site descriptions will be created as blog posts on your WordPress site, in the category “Built Environment Descriptions,” and tagged appropriately (“Interior,” “Exterior,” or “Digital,” and “[Site Name]”). You will submit links to your built environment descriptions using the form on your WordPress site.');
        $environmentDesc->setTotalAssignments(6);
        $environmentDesc->setDisplayOrder(2);

        $manager->persist($environmentDesc);
        $this->addReference('environmentDescription', $environmentDesc);

        $environmentAnalysis = new Category();
        $environmentAnalysis->setCourse($this->getReference('testCourse'));
        $environmentAnalysis->setName('Build Environment Analysis');
        $environmentAnalysis->setDescription('The final product is a detailed and evidence-based analysis of the built environment in Atlanta. Your analysis should be at least 1,500 words, and should integrate images, sounds, graphs and other media as necessary and relevant to make an appealing, effective multimodal argument.
In this analysis, you make one argument that you support with evidence. For example, you might argue that the rhetoric of the built environment suggests that a particular neighborhood in Atlanta is becoming racially segregated as it undergoes gentrification. Or you might argue that the rhetoric of the built environment in a museum makes it unwelcoming to children, even though it is a space that its history and advertising suggest has been created for a young audience.
Students who submit their required built environment analysis early by April 15 may submit one extra built environment analysis (for up to 300 extra points, for a max total of 600 points for this project).
Your site analysis will be created on your sites.gsu.edu WordPress site, in the category “Built Environment Analyses,” and tagged appropriately (“Interior,” “Exterior,” or “Digital,” and “[Site Name]”). You will submit links to your built environment analysis using the standard submission form.');
        $environmentAnalysis->setTotalAssignments(2);
        $environmentAnalysis->setDisplayOrder(3);

        $manager->persist($environmentAnalysis);
        $this->addReference('environmentAnalysis', $environmentAnalysis);

        $participation = new Category();
        $participation->setCourse($this->getReference('testCourse'));
        $participation->setName('Participation');
        $participation->setDescription('During the course of the semester we invite you to engage with the course material and assignments, with your peers and with your instructors, consistently and in inventive ways. We will assign points to your work reflecting the level of your participation both inside and outside of class. We will also subtract points for failing to participate (e.g., missing class) so as to fairly reflect your level of engagement with the course concepts. Your goal is to accrue as many points as possible during the semester.');
        $participation->setDisplayOrder(4);

        $manager->persist($participation);
        $this->addReference('participation', $participation);

        $manager->flush();

    }

    public function getOrder()
    {
        return 4;
    }
}