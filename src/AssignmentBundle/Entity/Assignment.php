<?php

namespace AssignmentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Assignment
 *
 * @ORM\Table(name="assignment")
 * @ORM\Entity(repositoryClass="AssignmentBundle\Repository\AssignmentRepository")
 */
class Assignment
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
     * @var bool
     *
     * @ORM\Column(name="recurring", type="boolean")
     */
    private $recurring;

    /**
     * @var int
     *
     * @ORM\Column(name="max_submissions", type="integer", nullable=true)
     */
    private $maxSubmissions;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="due_date", type="datetime", nullable=true)
     */
    private $dueDate;

    /**
     * @var bool
     *
     * @ORM\Column(name="required", type="boolean")
     */
    private $required;

    /**
     * @var int
     *
     * @ORM\Column(name="points", type="integer", nullable=true)
     */
    private $points;

    /**
     * @var string
     *
     * @ORM\Column(name="instructions", type="text", nullable=true)
     */
    private $instructions;

    /**
     * @var string
     *
     * @ORM\Column(name="rubric_link", type="string", length=255, nullable=true)
     */
    private $rubricLink;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="active", type="datetime")
     */
    private $active;

    /**
     * @var int
     *
     * @ORM\Column(name="display_order", type="integer")
     */
    private $displayOrder;

    /**
     * @ORM\ManyToOne(targetEntity="CourseBundle\Entity\Course", inversedBy="assignments")
     */
    protected $course;

    /**
     * @ORM\ManyToOne(targetEntity="AssignmentBundle\Entity\Category", inversedBy="assignments")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    protected $category;

    /**
     * @ORM\OneToMany(targetEntity="AssignmentBundle\Entity\Submission", mappedBy="assignment")
     */
    protected $submissions;


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
     * Set recurring
     *
     * @param boolean $recurring
     *
     * @return Assignment
     */
    public function setRecurring($recurring)
    {
        $this->recurring = $recurring;

        return $this;
    }

    /**
     * Get recurring
     *
     * @return bool
     */
    public function getRecurring()
    {
        return $this->recurring;
    }


    /**
     * Set dueDate
     *
     * @param \DateTime $dueDate
     *
     * @return Assignment
     */
    public function setDueDate($dueDate)
    {
        $this->dueDate = $dueDate;

        return $this;
    }

    /**
     * Get dueDate
     *
     * @return \DateTime
     */
    public function getDueDate()
    {
        return $this->dueDate;
    }

    /**
     * Set required
     *
     * @param boolean $required
     *
     * @return Assignment
     */
    public function setRequired($required)
    {
        $this->required = $required;

        return $this;
    }

    /**
     * Get required
     *
     * @return bool
     */
    public function getRequired()
    {
        return $this->required;
    }

    /**
     * Set quickPoints
     *
     * @param boolean $quickPoints
     *
     * @return Assignment
     */
    public function setQuickPoints($quickPoints)
    {
        $this->quickPoints = $quickPoints;

        return $this;
    }

    /**
     * Get quickPoints
     *
     * @return bool
     */
    public function getQuickPoints()
    {
        return $this->quickPoints;
    }

    /**
     * Set points
     *
     * @param integer $points
     *
     * @return Assignment
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
     * Set instructions
     *
     * @param string $instructions
     *
     * @return Assignment
     */
    public function setInstructions($instructions)
    {
        $this->instructions = $instructions;

        return $this;
    }

    /**
     * Get instructions
     *
     * @return string
     */
    public function getInstructions()
    {
        return $this->instructions;
    }

    /**
     * Set active
     *
     * @param \DateTime $active
     *
     * @return Assignment
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return \DateTime
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set displayOrder
     *
     * @param integer $displayOrder
     *
     * @return Assignment
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
     * Set course
     *
     * @param \CourseBundle\Entity\Course $course
     *
     * @return Assignment
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
     * Set category
     *
     * @param \AssignmentBundle\Entity\Category $category
     *
     * @return Assignment
     */
    public function setCategory(\AssignmentBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \AssignmentBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Assignment
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
     * Constructor
     */
    public function __construct()
    {
        $this->submissions = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add submission
     *
     * @param \AssignmentBundle\Entity\Submission $submission
     *
     * @return Assignment
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
     * Set maxSubmissions
     *
     * @param integer $maxSubmissions
     *
     * @return Assignment
     */
    public function setMaxSubmissions($maxSubmissions)
    {
        $this->maxSubmissions = $maxSubmissions;

        return $this;
    }

    /**
     * Get maxSubmissions
     *
     * @return integer
     */
    public function getMaxSubmissions()
    {
        return $this->maxSubmissions;
    }

    /**
     * Set rubricLink
     *
     * @param string $rubricLink
     *
     * @return Assignment
     */
    public function setRubricLink($rubricLink)
    {
        $this->rubricLink = $rubricLink;

        return $this;
    }

    /**
     * Get rubricLink
     *
     * @return string
     */
    public function getRubricLink()
    {
        return $this->rubricLink;
    }
}
