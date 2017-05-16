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
            $currentUnread = $catProgress->getUnread();
            $catProgress->setUnread($currentUnread + 1);
            $em = $this->getDoctrine()->getManager();
            $em->persist($submission);
            $em->persist($catProgress);
            $em->flush();
            return $this->redirectToRoute('student_dash_view', array('courseid' => $category->getCourse()->getId()));
        }

        return $this->render('AssignmentBundle:Submission:new.html.twig', array('form' => $form->createView(), 'category' => $category,));
    }

    /**
     * Return a list of not yet evaluated submissions to evaluate
     *
     * @Route("/unreadevaluate/{userid}/{categoryid}", name="unread_evaluate")
     */
    public function unreadEvalAction($userid, $categoryid){

        $catRepository = $this->getDoctrine()->getRepository('AssignmentBundle:Category');

        $category = $catRepository->find($categoryid);
        $assignments = $category->getAssignments();
        $assignmentIds = array();
        foreach($assignments as $assignment){
            $assignmentIds[] = $assignment -> getId();
        }

        $repository = $this->getDoctrine()->getRepository('AssignmentBundle:Submission');


        $submissions = $repository->findBy(array('user' => $userid, 'assignment' => $assignmentIds, 'points' => null));

        return $this->render('AssignmentBundle:Submission:choosesubmission.html.twig', array('submissions' =>$submissions));
    }

    /**
     * Return a list of already evaluated submissions so the instructor can choose to edit one
     *
     * @Route("/readevaluate/{userid}/{categoryid}", name="read_evaluate")
     */
    public function readEvalAction($userid, $categoryid){

        $catRepository = $this->getDoctrine()->getRepository('AssignmentBundle:Category');

        $category = $catRepository->find($categoryid);
        $assignments = $category->getAssignments();
        $assignmentIds = array();
        foreach($assignments as $assignment){
            $assignmentIds[] = $assignment -> getId();
        }

        $repository = $this->getDoctrine()->getRepository('AssignmentBundle:Submission');

        $submissions = $repository->findBy(array('user' => $userid, 'assignment' => $assignmentIds));

        return $this->render('AssignmentBundle:Submission:choosesubmission.html.twig', array('submissions' =>$submissions));
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

        if($submission->getPoints() === null){
            $newSubmit = true;
        }
        else {
            $newSubmit = false;
        }

        $form = $this->createForm(EvaluateSubmissionType::class, $submission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $submission = $form->getData();

            $currentPoints = $course->getMaxPoints();
            $course->setMaxpoints($currentPoints + $submission->getPoints());
            $currentPoints = $categoryProgress->getPointsEarned();
            $categoryProgress->setPointsEarned($currentPoints + $submission->getPoints());

            if($newSubmit){
                $currentUnread = $categoryProgress->getUnread();
                $categoryProgress->setUnread($currentUnread - 1);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($course);
            $em->persist($categoryProgress);
            $em->persist($submission);
            $em->flush();
            return $this->redirectToRoute('instr_dash_view', array('courseid' => $submission->getAssignment()->getCourse()->getId()));
        }

        return $this->render('AssignmentBundle:Submission:evaluate.html.twig', array('form' => $form->createView(), 'submission' =>$submission));
    }

}
