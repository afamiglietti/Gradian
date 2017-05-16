<?php

namespace DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use DashboardBundle\Entity\Dashboard;
use UserBundle\Entity\User;
use AssignmentBundle\Entity\CategoryProgress;
use AssignmentBundle\Entity\Category;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Class DashboardController
 * @Route("/dash")
 */

class DashboardController extends Controller
//Dashboard bundle controls rendering of course dashboards. The Dashboard entity acts as a many-to-many join table to link users to courses.
//When an instructor creates a course an instructor dashboard is created for him/her at that time, when a student enrolls, a student dashboard is created
//Thus, dashboards track class enrollment, no "roll" entity or similar exists
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->render('DashboardBundle:Default:index.html.twig');
    }

    /**
     * Displays a student Dashboard
     * @Route("/studentview/{courseid}", name="student_dash_view")
     */
    public function studentViewAction($courseid)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $course = $this->getDoctrine()->getRepository('CourseBundle:Course')->find($courseid);

        $dash = $this->getDoctrine()->getRepository('DashboardBundle:Dashboard')->findOneBy(array('user' => $user, 'course' => $course));



        if($dash == null){
            $dash = new Dashboard();
            $dash->setCourse($course);
            $dash->setUser($user);
            if($course->getOwner() == $user){
                $dash->setRole($dash::ROLE_INSTRUCTOR);
            }
            else {
                $dash->setRole($dash::ROLE_STUDENT);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($dash);
            $em->flush();

        }

        $catProgressList = array();
        $totalScore = 0;
        foreach($user->getCategoryProgresses() as $catprogress){
            $catProgressList[$catprogress->getCategory()->getId()] = $catprogress;
            $totalScore = $totalScore + $catprogress->getPointsEarned();
        }

        return $this->render('DashboardBundle:Default:studentview.html.twig', array('dash'=>$dash, 'catprogresslist'=>$catProgressList, 'totalscore'=>$totalScore));
    }

    /**
     * Displays an instructor dashboard
     * @Route("/instrview/{courseid}", name="instr_dash_view")
     */
    public function instrViewAction($courseid)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $course = $this->getDoctrine()->getRepository('CourseBundle:Course')->find($courseid);

        if($user != $course->getOwner()){
            throw new AccessDeniedException('You are not the instructor of this course');
        }

        $dash = $this->getDoctrine()->getRepository('DashboardBundle:Dashboard')->findOneBy(array('user' => $user, 'course' => $course));

        if($dash == null){
            $dash = new Dashboard();
            $dash->setCourse($course);
            $dash->setUser($user);
            if($course->getOwner() == $user){
                $dash->setRole($dash::ROLE_INSTRUCTOR);
            }
            else {
                $dash->setRole($dash::ROLE_STUDENT);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($dash);
            $em->flush();

        }

        $dashList = $this->getDoctrine()->getRepository('DashboardBundle:Dashboard')->findBy(array('course' => $course, 'role' => array(0, 1)));
        $totalProgressList = array();
        foreach($dashList as $userdash){
            $totalScore = 0;
            $userProgressList = array();
            foreach($userdash->getUser()->getcategoryProgresses() as $progress){
                $userProgressList[$progress->getCategory()->getId()] = $progress;
                $totalScore = $totalScore + $progress->getPointsEarned();
                $totalScore = $totalScore + $progress->getQuickPoints();
            }
            $userInfo= array('totalScore' => $totalScore, 'progressList' => $userProgressList);
            $totalProgressList[$userdash->getUser()->getId()] = $userInfo;
        }

        return $this->render('DashboardBundle:Default:instrview.html.twig', array('dash'=>$dash, 'dashlist'=>$dashList, 'progresslist'=>$totalProgressList ));

    }

    /**
     * @Route("/enroll_search", name="enroll_search")
     */
    public function enrollListAction(Request $request)
    {

        $user = new User();
        $course_list = array();

        $form = $this->createFormBuilder($user)
            ->add('lastname', TextType::class)
            ->add('search', SubmitType::class, array('label' => 'Search for Classes'))
            ->getForm();

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()){

            $user = $form->getData();
            $lastname = $user->getLastName();
            $founduser = $this->getDoctrine()->getRepository('UserBundle:User')->findOneBy(array('lastname' => $lastname));
            $course_list = $this->getDoctrine()->getRepository('CourseBundle:Course')->findBy(array('owner' => $founduser));
            return $this->render('DashboardBundle:Default:enroll.html.twig', array ('form' => $form->createView(), 'possible_classes' => $course_list));

        }

        return $this->render('DashboardBundle:Default:enroll.html.twig', array ('form' => $form->createView(), 'possible_classes' => $course_list));
    }

    /**
     * @Route("/enroll_student/{courseid}", name="enroll_student")
     */
    public function enrollStudentAction($courseid)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $course = $this->getDoctrine()->getRepository('CourseBundle:Course')->find($courseid);

        $dash = new Dashboard();
        $dash->setCourse($course);
        $dash->setUser($user);
        $dash->setRole($dash::ROLE_INACTIVE);

        $em = $this->getDoctrine()->getManager();

        foreach($course->getCategories() as $category){
            $categoryProgress = new CategoryProgress();
            $categoryProgress->setCategory($category);
            $categoryProgress->setUser($user);
            $em->persist($categoryProgress);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($dash);
        $em->flush();

        return $this->render('DashboardBundle:Default:postenroll.html.twig', array ('course' => $course));
    }

    /**
     * Instructor approves pending students
     *
     * @Route("/approve_student/{userid}/{courseid}", name="approve_student")
     */
    public function approveStudentAction($userid, $courseid)
    {
        $user = $this->getDoctrine()->getRepository('UserBundle:User')->find($userid);
        $course = $this->getDoctrine()->getRepository('CourseBundle:Course')->find($courseid);
        $dash = $this->getDoctrine()->getRepository('DashboardBundle:Dashboard')->findOneBy(array('course'=>$course, 'user'=>$user));
        $dash->setRole(1);
        $em = $this->getDoctrine()->getManager();
        $em->persist($dash);
        $em->flush();

        return $this->redirectToRoute('instr_dash_view', array('courseid' => $courseid));
    }

    /**
     * Add quick points from the dashboard
     *
     * @Route("/add_quickpoints/{categoryprogressid}", name="add_quickpoints")
     */
    public function addQuickpointsAction($categoryprogressid, Request $request)
    {
        $categoryProgress = $this->getDoctrine()->getRepository('AssignmentBundle:CategoryProgress')->find($categoryprogressid);
        $courseId = $categoryProgress->getCategory()->getCourse()->getId();

        $defaultData = array('quickPoints' => $categoryProgress->getQuickPoints());

        $form = $this->createFormBuilder($defaultData)
            ->add('quickPoints', IntegerType::class)
            ->add('update', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted()){
            $data = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $categoryProgress->setQuickPoints($data['quickPoints']);
            $em->persist($categoryProgress);
            $em->flush();

            return $this->redirectToRoute('instr_dash_view', array('courseid' => $courseId));
        }

        return $this->render('DashboardBundle:Default:quickpoints.html.twig', array ('form' => $form->createView(), 'categoryprogressid' => $categoryprogressid));
    }

}
