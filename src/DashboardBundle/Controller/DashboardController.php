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
use AssignmentBundle\Entity\Notification;
use AssignmentBundle\Form\NotificationType;
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

        $submissionRepo = $this->getDoctrine()->getRepository('AssignmentBundle:Submission');

        $submissions = $submissionRepo->findBy(array('user' =>$user));
        $assignments = $this->getDoctrine()->getRepository('AssignmentBundle:Assignment')->findBy(array('course' => $course), array('dueDate' => 'ASC'));


          /**$query = $submissionRepo->createQueryBuilder('s')
            ->add('select', 's', 'a')
            ->where('s.user = :user')
            ->setParameter('user', $user)
            ->join('s.assignment', 'a')
            ->getQuery();

        $submissions = $query->getResult();
        **/

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
        $catProgresses = $user->getCategoryProgresses();

        foreach($catProgresses as $catprogress){
            $catProgressList[$catprogress->getCategory()->getId()] = $catprogress;
            $totalScore = $totalScore + $catprogress->getPointsEarned();
        }

        $totalScore = $totalScore + $dash->getQuickPoints();

        $newNotificationList = array();
        foreach($catProgresses as $catprogress){
            $notificationSubList = array();
            foreach($catprogress->getNotifications() as $notification){
                if($notification->getDateRead() == null){
                    $notificationSubList[$notification->getType()][] = $notification;
                }
            }
            $newNotificationList[$catprogress->getCategory()->getId()] = $notificationSubList;
        }

        $categoryLookUp = array();
        $assignmentsByCategory = array();

        foreach($assignments as $assignment){
            $catId = $assignment->getCategory()->getId();
            $assignmentsByCategory[$catId][] = $assignment;
            $categoryLookUp[$assignment->getId()]= $catId;
        }

        $submissionsByAssignment = array();

        foreach($submissions as $submission){
            $assignmentId = $submission->getAssignment()->getId();
            $submissionsByAssignment[$assignmentId][] = $submission;
        }



        return $this->render('DashboardBundle:Default:studentview.html.twig', array('dash'=>$dash, 'catprogresslist'=>$catProgressList, 'totalscore'=>$totalScore, 'submissions' => $submissionsByAssignment, 'assignments' =>$assignmentsByCategory, 'assignmentsByDate' => $assignments, 'notificationlist' => $newNotificationList));
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
     * @Route("/add_quickpoints/{dashboardid}", name="add_quickpoints")
     */
    public function addQuickpointsAction($dashboardid, Request $request)
    {
        $dashboard = $this->getDoctrine()->getRepository('DashboardBundle:Dashboard')->find($dashboardid);
        $course = $dashboard->getCourse();

        $defaultData = array('quickPoints' => $dashboard->getQuickPoints());

        $form = $this->createFormBuilder($defaultData)
            ->add('quickPoints', IntegerType::class)
            ->add('update', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted()){
            $data = $form->getData();
            $quickPoints = $data['quickPoints'];

            //clear old quick points from current point total, then add new quick point figure

            $currentTotalPoints = $dashboard->getCourseScore();
            $currentTotalPoints = $currentTotalPoints - $dashboard->getQuickPoints();
            $currentTotalPoints = $currentTotalPoints + $quickPoints;

            $dashboard->setQuickPoints($quickPoints);
            $dashboard->setCourseScore($currentTotalPoints);

            $em = $this->getDoctrine()->getManager();
            $em->persist($dashboard);
            $em->flush();

            return $this->redirectToRoute('instr_dash_view', array('courseid' => $course->getId()));
        }

        return $this->render('DashboardBundle:Default:quickpoints.html.twig', array ('form' => $form->createView(), 'dashboardid' => $dashboardid));
    }

    /**
     * Update student project link from the dashboard
     *
     * @Route("/updateProjectLink/{dashboardid}", name="project_update")
     */
    public function updateProjectLinkAction($dashboardid, Request $request)
    {
        $dashboard = $this->getDoctrine()->getRepository('DashboardBundle:Dashboard')->find($dashboardid);
        $course = $dashboard->getCourse();

        $defaultData = array('projectLink' => $dashboard->getProjectLink());

        $form = $this->createFormBuilder($defaultData)
            ->add('projectLink', TextType::class)
            ->add('update', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted()){
            $data = $form->getData();
            $projectLink = $data['projectLink'];

            $dashboard->setProjectLink($projectLink);

            $em = $this->getDoctrine()->getManager();
            $em->persist($dashboard);
            $em->flush();

            return $this->redirectToRoute('student_dash_view', array('courseid' => $course->getId()));
        }

        return $this->render('DashboardBundle:Default:projectlink.html.twig', array ('form' => $form->createView(), 'dashboardid' => $dashboardid));
    }

    /**
     * Update student project link from the dashboard
     *
     * @Route("/updateFeedbackDoc/{dashboardid}", name="feedback_update")
     */
    public function updateFeedbackDocAction($dashboardid, Request $request)
    {
        $dashboard = $this->getDoctrine()->getRepository('DashboardBundle:Dashboard')->find($dashboardid);
        $course = $dashboard->getCourse();

        $defaultData = array('feedbackLink' => $dashboard->getFeedbackLink());

        $form = $this->createFormBuilder($defaultData)
            ->add('feedbackLink', TextType::class)
            ->add('update', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted()){
            $data = $form->getData();
            $feedbackLink = $data['feedbackLink'];

            $dashboard->setFeedbackLink($feedbackLink);

            $em = $this->getDoctrine()->getManager();
            $em->persist($dashboard);
            $em->flush();

            return $this->redirectToRoute('instr_dash_view', array('courseid' => $course->getId()));
        }

        return $this->render('DashboardBundle:Default:feedbacklink.html.twig', array ('form' => $form->createView(), 'dashboardid' => $dashboardid));
    }

    /**
     * Notify a Student
     *
     * @Route("/create_notification/{categoryprogressid}", name="create_notification")
     */
    public function createNotificationAction($categoryprogressid, Request $request)
    {
        $categoryProgress = $this->getDoctrine()->getRepository('AssignmentBundle:CategoryProgress')->find($categoryprogressid);
        $courseId = $categoryProgress->getCategory()->getCourse()->getId();

        $notification = new Notification();

        $notification->setCategoryProgress($categoryProgress);

        $form = $this->createForm(NotificationType::class, $notification);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $notification = $form->getData();

            $notification->setDateCreated(new \DateTime());
            $currentUnread = $categoryProgress->getUnreadNotifications();
            $categoryProgress->setUnreadNotifications($currentUnread + 1);
            $em = $this->getDoctrine()->getManager();
            $em->persist($notification);
            $em->persist($categoryProgress);
            $em->flush();

            return $this->redirectToRoute('instr_dash_view', array('courseid' => $courseId));

        }

        return $this->render('DashboardBundle:Default:notify.html.twig', array ('form' => $form->createView(), 'categoryprogressid' => $categoryprogressid));
    }

    /**
     * View Student Notifications
     *
     * @Route("/view_notifications/{categoryprogressid}", name="view_notifications_by_category")
     */
    public function viewNotificationsByCategoryAction($categoryprogressid)
    {
        $categoryProgress = $this->getDoctrine()->getRepository('AssignmentBundle:CategoryProgress')->find($categoryprogressid);
        $notifications = $categoryProgress->getNotifications();

        return $this->render('DashboardBundle:Default:notificationsByCategory.html.twig', array('notifications' => $notifications));
    }

    /**
     * View Student Notifications by category and type (for student dash)
     *
     * @Route("/view_notifications_by_type/{categoryprogressid}/{type}", name="view_notifications_by_category_type")
     */
    public function viewNotificationsByCategoryTypeAction($categoryprogressid, $type)
    {
        $categoryProgress = $this->getDoctrine()->getRepository('AssignmentBundle:CategoryProgress')->find($categoryprogressid);
        $notifications = $this->getDoctrine()->getRepository('AssignmentBundle:Notification')->findBy(array('categoryProgress' => $categoryProgress, 'type' => $type));


        return $this->render('DashboardBundle:Default:notificationsByCategoryType.html.twig', array('notifications' => $notifications));
    }

    /**
     * View Unread Student Notifications by category and type (for student dash)
     *
     * @Route("/view_unread_notifications_by_type/{categoryprogressid}/{type}", name="view_notifications_unread")
     */
    public function viewNotificationsUnread($categoryprogressid, $type)
    {
        $categoryProgress = $this->getDoctrine()->getRepository('AssignmentBundle:CategoryProgress')->find($categoryprogressid);
        $notifications = $this->getDoctrine()->getRepository('AssignmentBundle:Notification')->findBy(array('categoryProgress' => $categoryProgress, 'type' => $type, 'dateRead' => null));

        $em = $this->getDoctrine()->getManager();

        foreach($notifications as $notification){
            $notification->setDateRead(new \DateTime());
            $em->persist($notification);
        }

        $em->flush();

        return $this->render('DashboardBundle:Default:notificationsUnread.html.twig', array('notifications' => $notifications));
    }

    /**
     * Recompute Course Statistics
     *
     * @Route("/recompute_course_stats/{courseid}", name="recompute_course_stats")
     */
    public function recomputeCourseStatsAction($courseid)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $course = $this->getDoctrine()->getRepository('CourseBundle:Course')->find($courseid);

        if($user != $course->getOwner()){
            throw new AccessDeniedException('You are not the instructor of this course');
        }

        $em = $this->getDoctrine()->getManager();

        foreach($course->getCategories() as $category){
            $catProgresses = $this->getDoctrine()->getRepository('AssignmentBundle:CategoryProgress')->findBy(array('category' => $category), array('pointsEarned'=>'DESC'));
            $category->setMaxScoreEarned($catProgresses[0]->getPointsEarned());
            $category->setMinScoreEarned(end($catProgresses)->getPointsEarned());
            $em->persist($category);
        }

        $dashList = $this->getDoctrine()->getRepository('DashboardBundle:Dashboard')->findBy(array('course' => $course, 'role' => 1), array('courseScore' => 'DESC'));

        $course->setMaxScoreEarned($dashList[0]->getCourseScore());
        $course->setMinScoreEarned(end($dashList)->getCourseScore());

        $em->persist($course);

        $em->flush();

        return $this->redirectToRoute('instr_dash_view', array('courseid' => $courseid));
    }

}
