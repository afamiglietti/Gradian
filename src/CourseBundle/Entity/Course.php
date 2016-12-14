<?php

namespace CourseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Course
 *
 * @ORM\Table(name="course")
 * @ORM\Entity(repositoryClass="CourseBundle\Entity\CourseRepository")
 */
class Course
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="startDate", type="datetime")
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="endDate", type="datetime")
     */
    private $endDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="meetingTime", type="datetime")
     */
    private $meetingTime;

    /**
     * A total points field, keeping track of total points available in all assignments
     * This way we can compute it once and not over and over every time a dashboard is rendered
     *
     * @var int
     * @ORM\Column(name="points", type="integer")
     */
    private $points = 0;

    /**
     * A maxpoints field, keeping track of the maximum points that can be earned in all categories
     * Since categories may have more points available than can actually be earned this needs to be track seperately
     *
     * @var int
     * @ORM\Column(name="maxpoints", type="integer")
     */
    private $maxPoints = 0;

    /**
     * A deleted field marks deleted courses
     * They aren't deleted, of course, just hidden. Actual deletion to be carried out by an admin
     *
     * @var boolean
     * @ORM\Column(name="deleted", type="boolean")
     */
    private $deleted = false;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="courses")
     */
    protected $owner;

    /**
     *@ORM\OneToMany(targetEntity="AssignmentBundle\Entity\Category", mappedBy="course")
     */
    protected $categories;

    /**
     * @ORM\OneToMany(targetEntity="AssignmentBundle\Entity\Assignment", mappedBy="course")
     */
    protected $assignments;

    /**
     * @ORM\OneToMany(targetEntity="DashboardBundle\Entity\Dashboard", mappedBy="course")
     */
    protected $dashboards;

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
     * Set name
     *
     * @param string $name
     *
     * @return Course
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Course
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     *
     * @return Course
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     *
     * @return Course
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set owner
     *
     * @param \UserBundle\Entity\User $owner
     *
     * @return Course
     */
    public function setOwner(\UserBundle\Entity\User $owner = null)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return \UserBundle\Entity\User
     */
    public function getOwner()
    {
        return $this->owner;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
        $this->assignments = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add category
     *
     * @param \AssignmentBundle\Entity\Category $category
     *
     * @return Course
     */
    public function addCategory(\AssignmentBundle\Entity\Category $category)
    {
        $this->categories[] = $category;

        return $this;
    }

    /**
     * Remove category
     *
     * @param \AssignmentBundle\Entity\Category $category
     */
    public function removeCategory(\AssignmentBundle\Entity\Category $category)
    {
        $this->categories->removeElement($category);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Add assignment
     *
     * @param \AssignmentBundle\Entity\Assignment $assignment
     *
     * @return Course
     */
    public function addAssignment(\AssignmentBundle\Entity\Assignment $assignment)
    {
        $this->assignments[] = $assignment;

        return $this;
    }

    /**
     * Remove assignment
     *
     * @param \AssignmentBundle\Entity\Assignment $assignment
     */
    public function removeAssignment(\AssignmentBundle\Entity\Assignment $assignment)
    {
        $this->assignments->removeElement($assignment);
    }

    /**
     * Get assignments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAssignments()
    {
        return $this->assignments;
    }

    /**
     * Add dashboard
     *
     * @param \DashboardBundle\Entity\Dashboard $dashboard
     *
     * @return Course
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
     * Set totalPoints
     *
     * @param integer $points
     *
     * @return Course
     */
    public function setPoints($points)
    {
        $this->points = $points;

        return $this;
    }

    /**
     * Get totalPoints
     *
     * @return integer
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Set deleted
     *
     * @param boolean $deleted
     *
     * @return Course
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * Get deleted
     *
     * @return boolean
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * Set meetingTime
     *
     * @param \DateTime $meetingTime
     *
     * @return Course
     */
    public function setMeetingTime($meetingTime)
    {
        $this->meetingTime = $meetingTime;

        return $this;
    }

    /**
     * Get meetingTime
     *
     * @return \DateTime
     */
    public function getMeetingTime()
    {
        return $this->meetingTime;
    }

    /**
     * Set maxPoints
     *
     * @param integer $maxPoints
     *
     * @return Course
     */
    public function setMaxPoints($maxPoints)
    {
        $this->maxPoints = $maxPoints;

        return $this;
    }

    /**
     * Get maxPoints
     *
     * @return integer
     */
    public function getMaxPoints()
    {
        return $this->maxPoints;
    }
}