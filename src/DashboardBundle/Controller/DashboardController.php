<?php

namespace DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use DashboardBundle\Entity\Dashboard;
use UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
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
     * @Route("/view/{courseid}", name="dash_view")
     */
    public function viewAction($courseid)
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

        //if a faculty dash, get all dashboards of all enrolled students
        if($dash->getRole() == $dash::ROLE_INSTRUCTOR) {
            $dashList = $this->getDoctrine()->getRepository('DashboardBundle:Dashboard')->findBy(array('course' => $course, 'role' => array(0, 1)));
            $newSubmissionsList = array();
            foreach($dashList as $userdash){
                foreach($userdash->getUser()->getSubmissions() as $submission){
                    if($submission->getPoints() == null){
                        $newSubmissionsList[] = $submission;
                    }
                }
            }
        }
        else{
            $newSubmissionsList = null;
            $dashList = null;
        }


        return $this->render('DashboardBundle:Default:view.html.twig', array('dash'=>$dash, 'dashlist'=>$dashList, 'newsubmissionlist'=>$newSubmissionsList));
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

        return $this->redirectToRoute('dash_view', array('courseid' => $courseid));
    }
}
