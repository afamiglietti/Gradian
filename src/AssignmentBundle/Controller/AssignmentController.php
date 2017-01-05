<?php

namespace AssignmentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AssignmentBundle\Form\AssignmentType;
use AssignmentBundle\Form\CategoryType;
use AssignmentBundle\Entity\Assignment;
use AssignmentBundle\Entity\Category;
use AssignmentBundle\Entity\CategoryProgress;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


/**
 * @Route("/assignment")
 */
class AssignmentController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->render('AssignmentBundle:Default:index.html.twig');
    }

    /**
     * @Route("/new/{courseid}", name="assignment_new")
     */
    public function newAssignmentAction(Request $request, $courseid)
    {
        $course = $this->getDoctrine()->getRepository('CourseBundle:Course')->find($courseid);
        $assignment = new Assignment();

        $assignment->setCourse($course);
        $categories = $course->getCategories();

        $form = $this->createForm(AssignmentType::class, $assignment, array('categories' => $categories));
        $form->remove('course');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $assignment = $form->getData();
            $category = $assignment->getCategory();
            $course = $assignment->getCourse();
            //add points from new assignment to category and course totals
            if($assignment->getRecurring()){
                $currentpoints = $category->getPoints();
                $category->setPoints($currentpoints + $assignment->getPoints() * $assignment->getMaxSubmissions());
                $currentpoints = $course->getPoints();
                $course->setPoints($currentpoints + $assignment->getPoints() * $assignment->getMaxSubmissions());

                $currentAssignments = $category->getTotalAssignments();
                $category->setTotalAssignments($currentAssignments + $assignment->getMaxSubmissions());
        }
            else {
                $currentpoints = $category->getPoints();
                $category->setPoints($currentpoints + $assignment->getPoints());
                $currentpoints = $course->getPoints();
                $course->setPoints($currentpoints + $assignment->getPoints());

                $currentAssignments = $category->getTotalAssignments();
                $category->setTotalAssignments($currentAssignments + 1);
            }
            //persist everything
            $em = $this->getDoctrine()->getManager();
            $em->persist($assignment);
            //$em->persist($course);
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('course_view', array('courseid' => $courseid));
        }
        return $this->render('AssignmentBundle:Assignment:new.html.twig', array('form' => $form->createView(), 'courseid' => $courseid,));
    }

    /**
     * @Route("/newcat/{courseid}", name="category_new")
     */
    public function newCategoryAction(Request $request, $courseid)
    {
        $course = $this->getDoctrine()->getRepository('CourseBundle:Course')->find($courseid);
        $category = new Category();

        $category->setCourse($course);

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();

            //Add max points from this category to course max points
            $course = $category->getCourse();
            $currentPoints = $course->getMaxPoints();
            $course->setMaxPoints($currentPoints + $category->getMaxPoints());

            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->persist($course);

            $dashList = $this->getDoctrine()->getRepository('DashboardBundle:Dashboard')->findBy(array('course' => $course, 'role' => array(0, 1)));
            if($dashList) {
                foreach ($dashList as $dash) {
                    $categoryprogress = new CategoryProgress();
                    $categoryprogress->setCategory($category);
                    $categoryprogress->setUser($dash->getUser());
                    $em->persist($categoryprogress);
                }
            }
            $em->flush();
            return $this->redirectToRoute('assignment_new', array('courseid' => $courseid));
        }
        return $this->render('AssignmentBundle:Assignment:newcat.html.twig', array('form' => $form->createView(), 'courseid' => $courseid));
    }

    /**
     * @Route("/delete/{assignmentid}", name="assignment_delete")
     */
    public function deleteAction($assignmentid)
    {
        $assignment = $this->getDoctrine()->getRepository('AssignmentBundle:Assignment')->find($assignmentid);
        $category = $assignment->getCategory();
        $course = $assignment->getCourse();
        $courseid = $course->getId();

        if($assignment->getRecurring()){
            $currentpoints = $category->getPoints();
            $category->setPoints($currentpoints - $assignment->getPoints() * $assignment->getMaxSubmissions());
            $currentpoints = $course->getPoints();
            $course->setPoints($currentpoints - $assignment->getPoints() * $assignment->getMaxSubmissions());
        }
        else {
            $currentpoints = $category->getPoints();
            $category->setPoints($currentpoints - $assignment->getPoints());
            $currentpoints = $course->getPoints();
            $course->setPoints($currentpoints - $assignment->getPoints());
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($category);
        $em->remove($assignment);
        $em->flush();

        return $this->redirectToRoute('course_view', array('courseid' => $courseid));

    }

    /**
     * @Route("/edit/{assignmentid}", name="assignment_edit")
     */
    public function editAction($assignmentid, Request $request)
    {
        $assignment = $this->getDoctrine()->getRepository('AssignmentBundle:Assignment')->find($assignmentid);

        $form = $this->createForm(AssignmentType::class, $assignment);
        $form->remove('course');

        //Delete out the existing point totals before editing, in case they change
        $category = $assignment->getCategory();
        $course = $assignment->getCourse();
        $courseid = $course->getId();

        if($assignment->getRecurring()){
            $currentpoints = $category->getPoints();
            $category->setPoints($currentpoints - $assignment->getPoints() * $assignment->getMaxSubmissions());
            $currentpoints = $course->getPoints();
            $course->setPoints($currentpoints - $assignment->getPoints() * $assignment->getMaxSubmissions());

            $currentAssignments = $category->getTotalAssignments();
            $category->setTotalAssignments($currentAssignments - $assignment->getMaxSubmissions());
        }
        else {
            $currentpoints = $category->getPoints();
            $category->setPoints($currentpoints - $assignment->getPoints());
            $currentpoints = $course->getPoints();
            $course->setPoints($currentpoints - $assignment->getPoints());

            $currentAssignments = $category->getTotalAssignments();
            $category->setTotalAssignments($currentAssignments - 1);
        }


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $assignment = $form->getData();
            $category = $assignment->getCategory();
            $course = $assignment->getCourse();
            //add points from new assignment to category and course totals
            if($assignment->getRecurring()){
                $currentpoints = $category->getPoints();
                $category->setPoints($currentpoints + $assignment->getPoints() * $assignment->getMaxSubmissions());
                $currentpoints = $course->getPoints();
                $course->setPoints($currentpoints + $assignment->getPoints() * $assignment->getMaxSubmissions());

                $currentAssignments = $category->getTotalAssignments();
                $category->setTotalAssignments($currentAssignments + $assignment->getMaxSubmissions());
            }
            else {
                $currentpoints = $category->getPoints();
                $category->setPoints($currentpoints + $assignment->getPoints());
                $currentpoints = $course->getPoints();
                $course->setPoints($currentpoints + $assignment->getPoints());

                $currentAssignments = $category->getTotalAssignments();
                $category->setTotalAssignments($currentAssignments + 1);
            }
            //persist everything
            $em = $this->getDoctrine()->getManager();
            $em->persist($assignment);
            //$em->persist($course);
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('course_view', array('courseid' => $courseid));
        }
        return $this->render('AssignmentBundle:Assignment:new.html.twig', array('form' => $form->createView(), 'courseid' => $courseid,));
    }

    /**
     * View a category from a modal
     *
     * @Route("/view_category/{categoryid}", name="category_view_modal")
     */
    public function viewCategoryModalAction($categoryid)
    {
        $category = $this->getDoctrine()->getRepository('AssignmentBundle:Category')->find($categoryid);

        return $this->render('AssignmentBundle:Category:viewmodal.html.twig', array('category' => $category));
    }
}
