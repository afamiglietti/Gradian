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
     * @Route("/new/{assignmentid}", name="submission_new")
     *
     */
    public function newAction(Request $request, $assignmentid)
    {

        $user = $this->get('security.token_storage')->getToken()->getUser();
        $assignment = $this->getDoctrine()->getRepository('AssignmentBundle:Assignment')->find($assignmentid);
        $category = $assignment->getCategory();
        $catProgress = $this->getDoctrine()->getRepository('AssignmentBundle:CategoryProgress')->findOneBy(array('user' => $user, 'category' => $category));

        $submission = new Submission();
        $submission->setUser($user);
        $submission->setAssignment($assignment);

        $form = $this->createForm(SubmitAssignmentType::class, $submission, array());
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
            return $this->redirectToRoute('student_dash_view', array('courseid' => $assignment->getCourse()->getId()));
        }

        return $this->render('AssignmentBundle:Submission:new.html.twig', array('form' => $form->createView(), 'assignment' => $assignment));
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
        $dashboard = $this->getDoctrine()->getRepository('DashboardBundle:Dashboard')->findOneBy(array('user' => $submission->getUser(), 'course' => $course));

        if($submission->getPoints() === null){
            $newSubmit = true;
        }
        else {
            $newSubmit = false;
            $oldPoints = $submission->getPoints();
        }

        $form = $this->createForm(EvaluateSubmissionType::class, $submission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $submission = $form->getData();

            if($newSubmit){
                $currentUnread = $categoryProgress->getUnread();
                $categoryProgress->setUnread($currentUnread - 1);

                $currentPoints = $categoryProgress->getPointsEarned();
                $categoryProgress->setPointsEarned($currentPoints + $submission->getPoints());

                $currentTotalPoints = $dashboard->getCourseScore();
                $dashboard->setCourseScore($currentTotalPoints + $submission->getPoints());
            }
            else {
                $currentPoints = $categoryProgress->getPointsEarned();
                $currentPoints = $currentPoints - $oldPoints;
                $categoryProgress->setPointsEarned($currentPoints + $submission->getPoints());

                $currentTotalPoints = $dashboard->getCourseScore();
                $currentTotalPoints = $currentTotalPoints - $oldPoints;
                $dashboard->setCourseScore($currentTotalPoints + $submission->getPoints());
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($course);
            $em->persist($categoryProgress);
            $em->persist($submission);
            $em->persist($dashboard);
            $em->flush();
            return $this->redirectToRoute('instr_dash_view', array('courseid' => $submission->getAssignment()->getCourse()->getId()));
        }

        return $this->render('AssignmentBundle:Submission:evaluate.html.twig', array('form' => $form->createView(), 'submission' =>$submission));
    }

    /**
     * Present a list of student submissions in a given category so they can review scores and comments
     *
     * @Route("/student_review/{categoryid}", name="student_review")
     */
    public function studentReviewAction($categoryid)
    {
        $category = $this->getDoctrine()->getRepository('AssignmentBundle:Category')->find($categoryid);
        $assignments = $category->getAssignments();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $submissions = $this->getDoctrine()->getRepository('AssignmentBundle:Submission')->findBy(array('user'=>$user, 'assignment'=> $assignments->toArray()));

        return $this->render('AssignmentBundle:Submission:studentreview.html.twig', array('submissions' => $submissions, 'category' =>$category));
    }

}
