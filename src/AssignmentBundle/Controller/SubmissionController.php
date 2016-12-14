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

        $assignments = $category->getAssignments();

        $submission = new Submission();
        $submission->setUser($user);

        $form = $this->createForm(SubmitAssignmentType::class, $submission, array('assignments' => $assignments));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $submission = $form->getData();
            $submission->setSubmitted(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->persist($submission);
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

        $form = $this->createForm(EvaluateSubmissionType::class, $submission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $submission = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($submission);
            $em->flush();
            return $this->redirectToRoute('dash_view', array('courseid' => $submission->getAssignment()->getCourse()->getId()));
        }

        return $this->render('AssignmentBundle:Submission:evaluate.html.twig', array('form' => $form->createView(), 'submission' =>$submission));
    }

}
