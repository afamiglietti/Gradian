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


class LoadAssignmentData extends AbstractFixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {

        for($i = 1; $i<=6; $i++){

            $name = "Reading Summary " . strval($i);
            $category ='readingSummaries';
            $course = 'testCourse';
            $required = true;
            $points = 100;
            $instructions = 'Project Purpose and Goals: A summary emerges in the process of a reader’s coming to understand a text. This process might take very little time, or it might take several read-throughs and other steps, like looking up unknown words and annotating thoughts and ideas in the margins. Ultimately strong summaries for this course will reflect your understanding of the articles’ main ideas, quoting one or two passages you deem particularly important. A summary is not the practice of replacing words with synonyms.';
            $duedate = new \DateTime('2017-06-0'.strval($i).'T00:00:00Z');
            $displayorder = $i;
            $rubriclink = 'https://docs.google.com/document/d/1oGs99Y83oUieh3p0jqzX6LojqsTLa2vrs_iddl8iWIo/edit';

            $summaryAssignment = $this->createAssignment($name, $category, $course, $required, $points, $instructions, $duedate, $displayorder, $rubriclink);

            $manager->persist($summaryAssignment);

            $this->addReference('summaryAssign' . strval($i), $summaryAssignment);

        }

        for($i = 1; $i<=12; $i++){

            $name = "Optional Summary" . strval($i);
            $category ='readingSummaries';
            $course = 'testCourse';
            $required = false;
            $points = 50;
            $instructions = 'Project Purpose and Goals: A summary emerges in the process of a reader’s coming to understand a text. This process might take very little time, or it might take several read-throughs and other steps, like looking up unknown words and annotating thoughts and ideas in the margins. Ultimately strong summaries for this course will reflect your understanding of the articles’ main ideas, quoting one or two passages you deem particularly important. A summary is not the practice of replacing words with synonyms.';
            $duedate = null;
            $displayorder = $i + 6;
            $rubriclink = 'https://docs.google.com/document/d/1oGs99Y83oUieh3p0jqzX6LojqsTLa2vrs_iddl8iWIo/edit';

            $summaryAssignment = $this->createAssignment($name, $category, $course, $required, $points, $instructions, $duedate, $displayorder, $rubriclink);

            $manager->persist($summaryAssignment);

            $this->addReference('optionalsummaryAssign' . strval($i), $summaryAssignment);

        }

        for($i = 1; $i<=9; $i++){

            $name = "Bibliography Annotation " . strval($i);
            $category ='annotatedBibliography';
            $course = 'testCourse';
            $required = true;
            $points = 50;
            $instructions = 'When complete, your multimedia annotated bibliography should contain annotations of 150-250 words each for at least 10 sources. You should have a balance of academic and non-academic sources, and text sources (articles, books, blog posts) should be balanced by multimedia sources (video, images, podcasts, etc.).
As described on the University of Cornell Library website on “How to Prepare an Annotated Bibliography,” “the purpose of the annotation is to inform the reader of the relevance, accuracy, and quality of the sources cited.” In addition to MLA citations and annotations, your multimedia annotated bibliography will include links to your sources or to web references that identify where your sources can be located (e.g., in the library, on Amazon.com, on Netflix, etc.).
Ideally each annotation should briefly and concisely answer the following five questions about each source:
What is this source about? When summarizing, keep in mind for whom the source was intended and why this source is relevant to your project.
What information or evidence have you drawn from this source that helps you to understand better the rhetoric of the built environment and how it has taken shape within the city of Atlanta?
Why did you choose this source? Your reasons might include one or more of the following: It is more comprehensive or detailed than other available sources. It specifically mentions or responds to one of our other readings for class. It is the only available source on the particular topic for which you are using it. The author seems to have views sympathetic to those of some of the other readings, or he/she offers an alternative viewpoint from those we have considered in our class discussions.
Does this source have any flaws or weaknesses that you have had to take into consideration while using it? When answering this question, you should consider when and in what venue this source was published, and whether it shows the influence of bias or outdated/disfavored ideas, political views, research methods, etc.
What is the relationship between this source and the other sources you’ve uncovered in your research? For example, does it offer an alternative viewpoint? Is the author in conversation with or does he/she draw upon the work of another author relevant to your project?';
            $duedate = new \DateTime('2017-06-0'.strval($i).'T00:00:00Z');
            $displayorder = $i;
            $rubriclink = 'https://docs.google.com/document/d/1DAAm4JQL4mkQ-3sHgIXdLLbQsikn7DSjLNeOmk7pC4M/edit';

            $summaryAssignment = $this->createAssignment($name, $category, $course, $required, $points, $instructions, $duedate, $displayorder, $rubriclink);

            $manager->persist($summaryAssignment);

            $this->addReference('bibliographyAssign' . strval($i), $summaryAssignment);

        }

        for($i = 1; $i<=3; $i++){

            $name = "Built Environment Description " . strval($i);
            $category = 'environmentDescription';
            $course = 'testCourse';
            $required = true;
            $instructions = "You are required to spend at least one hour observing each of the three spaces you’re writing about for this project. If you choose a private site (i.e. a business) for the interior or exterior site description, you should get permission from the owner or manager to conduct your observation. You should explain the purpose of the project, and that it is a class project.
You will choose your site from the site list (link to spreadsheet for interior, exterior, digital). First choice of sites will go to the top five points earners from each section. Then, the remaining students in the top earning section will select their sites. Then site selection will open to the remaining students. If a site already has a name next to it in the spreadsheet, then it has been claimed and you need to pick another site.
In an ideal world, you would make many trips to your site. For this project, you are only required to make one trip to your site, spending one hour taking photos or video, and writing or recording notes.
During your visit, you are required to document the site in two ways:
Create at least five digital records to document the location you’ve chosen. Post these digital records to your blog–each as a separate blog post–with a brief (50-100 words) description of what they are. You can take pictures, create video, make sound recordings, scan brochures/menus/flyers
Take written or recorded voice notes in which you create an inventory or catalog of everything that you see, hear, smell, touch, or taste at the site";
            $duedate = new \DateTime('2017-07-0'.strval($i).'T00:00:00Z');
            $displayorder = $i;
            $rubriclink = 'https://docs.google.com/document/d/1vOm-X-Ex4YQhGTu53m36MORViWz3HIgsoC8lucfNINk/edit';

            $summaryAssignment = $this->createAssignment($name, $category, $course, $required, 200, $instructions, $duedate, $displayorder, $rubriclink);

            $manager->persist($summaryAssignment);

            $this->addReference('descriptionAssign' . strval($i), $summaryAssignment);

        }

        $name = "Built Environment Analysis ";
        $category = 'environmentAnalysis';
        $course = 'testCourse';
        $required = true;
        $instructions = "Your built environment analysis, like most of the other work you’ve completed so far, will be posted on your sites.gsu.edu WordPress blog. It might take the form of a single blog post, or you might choose to create a new page or set of pages for your built environment analysis. If the analysis comprises more than a single post or page, you will need a menu or other aid for navigating through the different parts of your analysis.
You should draw on your research for the annotated bibliography in making your argument about the built environment in Atlanta. Cite and document all sources using MLA parenthetical documentation and a works cited list. If you draw on the work of your peers, you should cite and document those sources as well. In addition to using MLA citation style, you can also link to sources of information that are available digitally, including the work of your peers.
Your built environment analysis will be composed in at least three stages, with a first draft, a second revised draft, and a third and final draft. We will complete workshops in class, and I encourage you to organize extra peer review groups outside of class for extra points.";
        $duedate = new \DateTime('2017-08-01T00:00:00Z');
        $displayorder = 1;
        $rubriclink = 'https://docs.google.com/document/d/1vOm-X-Ex4YQhGTu53m36MORViWz3HIgsoC8lucfNINk/edit';

        $analysisAssignment = $this->createAssignment($name, $category, $course, $required, 600, $instructions, $duedate, $displayorder, $rubriclink);

        $manager->persist($analysisAssignment);

        $this->addReference('analysisAssign' , $analysisAssignment);

        $manager->flush();

    }

    private function createAssignment($name, $category, $course, $required, $points, $instructions, $duedate, $displayorder, $rubriclink, $recurring = false)
    {
        $assignment = new Assignment();
        $assignment->setName($name);
        $assignment->setCategory($this->getReference($category));
        $assignment->setCourse($this->getReference($course));
        $assignment->setRequired($required);
        $assignment->setPoints($points);
        $assignment->setInstructions($instructions);
        $assignment->setDueDate($duedate);
        $assignment->setDisplayOrder($displayorder);
        $assignment->setRubricLink($rubriclink);
        $assignment->setRecurring($recurring);
        $assignment->setActive(new \DateTime());

        return $assignment;
    }

    public function getOrder()
    {
        return 5;
    }

}