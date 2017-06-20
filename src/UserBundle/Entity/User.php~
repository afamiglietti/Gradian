<?php
/**
 * Created by PhpStorm.
 * User: afamiglietti
 * Date: 9/15/16
 * Time: 7:53 PM
 */

namespace UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="firstname", type="string", length=255)
     */
    protected $firstname;

    /**
     * @ORM\Column(name="lastname", type="string", length=255)
     */
    protected $lastname;



    /**
     * @ORM\OneToMany(targetEntity="CourseBundle\Entity\Course", mappedBy="owner")
     */
    protected $courses;

    /**
     * @ORM\OneToMany(targetEntity="DashboardBundle\Entity\Dashboard", mappedBy="user")
     */
    protected $dashboards;

    /**
     * @ORM\OneToMany(targetEntity="AssignmentBundle\Entity\Submission", mappedBy="user")
     */
    protected $submissions;

    /**
     * @ORM\OneToMany(targetEntity="AssignmentBundle\Entity\CategoryProgress", mappedBy="user")
     *
     */
    protected $categoryProgresses;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    /**
     * Add course
     *
     * @param \CourseBundle\Entity\Course $course
     *
     * @return User
     */
    public function addCourse(\CourseBundle\Entity\Course $course)
    {
        $this->courses[] = $course;

        return $this;
    }

    /**
     * Remove course
     *
     * @param \CourseBundle\Entity\Course $course
     */
    public function removeCourse(\CourseBundle\Entity\Course $course)
    {
        $this->courses->removeElement($course);
    }

    /**
     * Get courses
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCourses()
    {
        return $this->courses;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     *
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Add dashboard
     *
     * @param \DashboardBundle\Entity\Dashboard $dashboard
     *
     * @return User
     */
    public function addDashboard(\DashboardBundle\Entity\Dashboard $dashboard)
    {
        $this->dashboards[] = $dashboard;

        return $this;
    }

    /**
     * Remove dashboard
     *
     * @param \DashboardBundle\Entity\Dashboard $dashboard
     */
    public function removeDashboard(\DashboardBundle\Entity\Dashboard $dashboard)
    {
        $this->dashboards->removeElement($dashboard);
    }

    /**
     * Get dashboards
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDashboards()
    {
        return $this->dashboards;
    }

    /**
     * Add submission
     *
     * @param \AssignmentBundle\Entity\Submission $submission
     *
     * @return User
     */
    public function addSubmission(\AssignmentBundle\Entity\Submission $submission)
    {
        $this->submissions[] = $submission;

        return $this;
    }

    /**
     * Remove submission
     *
     * @param \AssignmentBundle\Entity\Submission $submission
     */
    public function removeSubmission(\AssignmentBundle\Entity\Submission $submission)
    {
        $this->submissions->removeElement($submission);
    }

    /**
     * Get submissions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubmissions()
    {
        return $this->submissions;
    }

    /**
     * Add categoryProgress
     *
     * @param \AssignmentBundle\Entity\CategoryProgress $categoryProgress
     *
     * @return User
     */
    public function addCategoryProgress(\AssignmentBundle\Entity\CategoryProgress $categoryProgress)
    {
        $this->categoryProgresses[] = $categoryProgress;

        return $this;
    }

    /**
     * Remove categoryProgress
     *
     * @param \AssignmentBundle\Entity\CategoryProgress $categoryProgress
     */
    public function removeCategoryProgress(\AssignmentBundle\Entity\CategoryProgress $categoryProgress)
    {
        $this->categoryProgresses->removeElement($categoryProgress);
    }

    /**
     * Get categoryProgresses
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategoryProgresses()
    {
        return $this->categoryProgresses;
    }
    
}
