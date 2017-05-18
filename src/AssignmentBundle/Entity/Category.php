<?php

namespace AssignmentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="AssignmentBundle\Repository\CategoryRepository")
 */
class Category
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
     * The points field tracks the number of points AVAILABLE in the category, based on all the points that can be earned in all the assignments.
     *
     * @var int
     *
     * @ORM\Column(name="points", type="integer")
     */
    private $points = 0;

    /**
     * The max points field tracks the maximum number of points the student can earn towards the course score in this category, regardless of the number of points available
     *
     * @var int
     *
     * @ORM\Column(name="max_points", type="integer")
     */
    private $maxPoints;

    /**
     * The max score earned field tracks the maximum score earned by any student to date in this category
     *
     * @var int
     *
     * @ORM\Column(name="max_score_earned", type="integer")
     */
    private $maxScoreEarned = 0;

    /**
     * The median score earned field tracks the median of all scores earned by students to date in this category
     *
     * @var int
     *
     * @ORM\Column(name="median_score_earned", type="integer")
     */
    private $medianScoreEarned = 0;

    /**
     * The min score earned field tracks the minimum score earned by any student to date in this category
     *
     * @var int
     *
     * @ORM\Column(name="min_score_earned", type="integer")
     */
    private $minScoreEarned = 0;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="display_order", type="integer")
     */
    private $displayOrder;

    /**
     * A total assignments field, since the length of the assignments array does not necessarily track the number of assignments
     * because some assignments may be repeated...
     *
     * @var int
     * @ORM\Column(name="totalassignments", type="integer")
     */
    private $totalAssignments = 0;

    /**
     * @ORM\ManyToOne(targetEntity="CourseBundle\Entity\Course", inversedBy="categories")
     */
    protected $course;

    /**
     * @ORM\OneToMany(targetEntity="AssignmentBundle\Entity\Assignment", mappedBy="category")
     */
    protected $assignments;

    /**
     * @ORM\OneToMany(targetEntity="AssignmentBundle\Entity\CategoryProgress", mappedBy="category")
     *
     */
    protected $categoryProgresses;

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
     * Set points
     *
     * @param integer $points
     *
     * @return Category
     */
    public function setPoints($points)
    {
        $this->points = $points;

        return $this;
    }

    /**
     * Get points
     *
     * @return int
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return Category
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return bool
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Category
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
     * Set displayOrder
     *
     * @param integer $displayOrder
     *
     * @return Category
     */
    public function setDisplayOrder($displayOrder)
    {
        $this->displayOrder = $displayOrder;

        return $this;
    }

    /**
     * Get displayOrder
     *
     * @return int
     */
    public function getDisplayOrder()
    {
        return $this->displayOrder;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->assignments = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set course
     *
     * @param \CourseBundle\Entity\Course $course
     *
     * @return Category
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
     * Add assignment
     *
     * @param \AssignmentBundle\Entity\Assignment $assignment
     *
     * @return Category
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
     * Set name
     *
     * @param string $name
     *
     * @return Category
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
     * Set maxPoints
     *
     * @param integer $maxPoints
     *
     * @return Category
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

    /**
     * Set totalAssignments
     *
     * @param integer $totalAssignments
     *
     * @return Category
     */
    public function setTotalAssignments($totalAssignments)
    {
        $this->totalAssignments = $totalAssignments;

        return $this;
    }

    /**
     * Get totalAssignments
     *
     * @return integer
     */
    public function getTotalAssignments()
    {
        return $this->totalAssignments;
    }

    /**
     * Add categoryProgress
     *
     * @param \AssignmentBundle\Entity\CategoryProgress $categoryProgress
     *
     * @return Category
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

    /**
     * Set maxScoreEarned
     *
     * @param integer $maxScoreEarned
     *
     * @return Category
     */
    public function setMaxScoreEarned($maxScoreEarned)
    {
        $this->maxScoreEarned = $maxScoreEarned;

        return $this;
    }

    /**
     * Get maxScoreEarned
     *
     * @return integer
     */
    public function getMaxScoreEarned()
    {
        return $this->maxScoreEarned;
    }

    /**
     * Set medianScoreEarned
     *
     * @param integer $medianScoreEarned
     *
     * @return Category
     */
    public function setMedianScoreEarned($medianScoreEarned)
    {
        $this->medianScoreEarned = $medianScoreEarned;

        return $this;
    }

    /**
     * Get medianScoreEarned
     *
     * @return integer
     */
    public function getMedianScoreEarned()
    {
        return $this->medianScoreEarned;
    }

    /**
     * Set minScoreEarned
     *
     * @param integer $minScoreEarned
     *
     * @return Category
     */
    public function setMinScoreEarned($minScoreEarned)
    {
        $this->minScoreEarned = $minScoreEarned;

        return $this;
    }

    /**
     * Get minScoreEarned
     *
     * @return integer
     */
    public function getMinScoreEarned()
    {
        return $this->minScoreEarned;
    }
}
