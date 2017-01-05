<?php

namespace AssignmentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CategoryProgress
 *
 * This exists to track a students progress through a category, so that earned points don't have to be summed up from all submissions to render each dashboard
 *
 *
 * @ORM\Table(name="category_progress")
 * @ORM\Entity(repositoryClass="AssignmentBundle\Repository\CategoryProgressRepository")
 */
class CategoryProgress
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
     * @var int
     *
     * @ORM\Column(name="pointsEarned", type="integer")
     */
    private $pointsEarned = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="notification", type="integer")
     */
    private $notification = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="submissions", type="integer")
     */
    private $submissions = 0;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="categoryProgresses")
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="AssignmentBundle\Entity\Category", inversedBy="categoryProgresses")
     */
    protected $category;

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
     * Set pointsEarned
     *
     * @param integer $pointsEarned
     *
     * @return CategoryProgress
     */
    public function setPointsEarned($pointsEarned)
    {
        $this->pointsEarned = $pointsEarned;

        return $this;
    }

    /**
     * Get pointsEarned
     *
     * @return int
     */
    public function getPointsEarned()
    {
        return $this->pointsEarned;
    }

    /**
     * Set notification
     *
     * @param integer $notification
     *
     * @return CategoryProgress
     */
    public function setNotification($notification)
    {
        $this->notification = $notification;

        return $this;
    }

    /**
     * Get notification
     *
     * @return int
     */
    public function getNotification()
    {
        return $this->notification;
    }

    /**
     * Set user
     *
     * @param \UserBundle\Entity\User $user
     *
     * @return CategoryProgress
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
     * Set category
     *
     * @param \AssignmentBundle\Entity\Category $category
     *
     * @return CategoryProgress
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
     * Set submissions
     *
     * @param integer $submissions
     *
     * @return CategoryProgress
     */
    public function setSubmissions($submissions)
    {
        $this->submissions = $submissions;

        return $this;
    }

    /**
     * Get submissions
     *
     * @return integer
     */
    public function getSubmissions()
    {
        return $this->submissions;
    }
}
