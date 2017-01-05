<?php

namespace AssignmentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AssignmentBundle\Form\AssignmentType;
use AssignmentBundle\Form\CategoryType;
use AssignmentBundle\Form\SubmitAssignmentType;
use AssignmentBundle\Form\EvaluateSubmissionType;
use AssignmentBundle\Entity\Assignment;
use AssignmentBundle\Entity\Category;
use AssignmentBundle\Entity\Submission;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;



/**
 * @Route("/submission")
 */
class SubmissionController extends Controller
{

    /**
     * @Route("/new/{categoryid}", name="submission_new")
     *
     */
    public function newAction(Request $request, $categoryid)
    {
        $category = $this->getDoctrine()->getRepository('AssignmentBundle:Category')->find($categoryid);
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $catProgress = $this->getDoctrine()->getRepository('AssignmentBundle:CategoryProgress')->findOneBy(array('category'=>$category, 'user'=>$user));

        $assignments = $category->getAssignments();

        $submission = new Submission();
        $submission->setUser($user);

        $form = $this->createForm(SubmitAssignmentType::class, $submission, array('assignments' => $assignments));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $submission = $form->getData();
            $submission->setSubmitted(new \DateTime());
            $currentSubmissions = $catProgress->getSubmissions();
            $catProgress->setSubmissions($currentSubmissions + 1);
            $em = $this->getDoctrine()->getManager();
            $em->persist($submission);
            $em->persist($catProgress);
            $em->flush();
            return $this->redirectToRoute('dash_view', array('courseid' => $category->getCourse()->getId()));
        }

        return $this->render('AssignmentBundle:Submission:new.html.twig', array('form' => $form->createView(), 'category' => $category,));
    }

    /**
     * Evaluate a student's submission to an assignment
     *
     * @Route("/evaluate/{submissionid}", name="submission_evaluate")
     */
    public function evaluateAction(Request $request, $submissionid)
    {
        $submission = $this->getDoctrine()->getRepository('AssignmentBundle:Submission')->find($submissionid);

        $course = $submission->getAssignment()->getCourse();
        $categoryProgress = $this->getDoctrine()->getRepository('AssignmentBundle:CategoryProgress')->findOneBy(array('user' => $submission->getUser(), 'category' => $submission->getAssignment()->getCategory()));

        $form = $this->createForm(EvaluateSubmissionType::class, $submission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $submission = $form->getData();

            $currentPoints = $course->getMaxPoints();
            $course->setMaxpoints($currentPoints + $submission->getPoints());
            $currentPoints = $categoryProgress->getPointsEarned();
            $categoryProgress->setPointsEarned($currentPoints + $submission->getPoints());

            $em = $this->getDoctrine()->getManager();
            $em->persist($course);
            $em->persist($categoryProgress);
            $em->persist($submission);
            $em->flush();
            return $this->redirectToRoute('dash_view', array('courseid' => $submission->getAssignment()->getCourse()->getId()));
        }

        return $this->render('AssignmentBundle:Submission:evaluate.html.twig', array('form' => $form->createView(), 'submission' =>$submission));
    }

}
