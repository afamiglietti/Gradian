<?php

namespace DashboardBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Dashboard
 *
 * @ORM\Table(name="dashboard")
 * @ORM\Entity(repositoryClass="DashboardBundle\Repository\DashboardRepository")
 */
class Dashboard
{
    const ROLE_INACTIVE = 0;
    const ROLE_STUDENT = 1;
    const ROLE_INSTRUCTOR = 2;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     * @ORM\Column(name="role", type="integer")
     */
    private $role;

    /**
     * The student's current score in the class. Tracked here to make course stats easier to compute
     *
     * @var int
     * @ORM\Column(name="course_score", type="integer")
     */
    private $courseScore = 0;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="dashboards")
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="CourseBundle\Entity\Course", inversedBy="dashboards")
     *
     */
    protected $course;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set user
     *
     * @param \UserBundle\Entity\User $user
     *
     * @return Dashboard
     */
    public function setUser(\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set course
     *
     * @param \CourseBundle\Entity\Course $course
     *
     * @return Dashboard
     */
    public function setCourse(\CourseBundle\Entity\Course $course = null)
    {
        $this->course = $course;

        return $this;
    }

    /**
     * Get course
     *
     * @return \CourseBundle\Entity\Course
     */
    public function getCourse()
    {
        return $this->course;
    }

    /**
     * Set role
     *
     * @param integer $role
     *
     * @return Dashboard
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return integer
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set courseScore
     *
     * @param integer $courseScore
     *
     * @return Dashboard
     */
    public function setCourseScore($courseScore)
    {
        $this->courseScore = $courseScore;

        return $this;
    }

    /**
     * Get courseScore
     *
     * @return integer
     */
    public function getCourseScore()
    {
        return $this->courseScore;
    }
}
