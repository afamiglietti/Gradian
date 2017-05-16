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
     * @ORM\Column(name="unread_notification", type="integer")
     */
    private $unreadNotifications = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="quickpoints", type="integer")
     */
    private $quickPoints;

    /**
     * @var int
     *
     * @ORM\Column(name="submissions", type="integer")
     */
    private $submissions = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="required", type="integer")
     */
    private $required = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="unread", type="integer")
     */
    private $unread = 0;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="categoryProgresses")
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="AssignmentBundle\Entity\Category", inversedBy="categoryProgresses")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $category;

    /**
     * @ORM\OneToMany(targetEntity="AssignmentBundle\Entity\Notification", mappedBy="categoryProgress")
     *
     */
    protected $notifications;

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

    /**
     * Set unread
     *
     * @param integer $unread
     *
     * @return CategoryProgress
     */
    public function setUnread($unread)
    {
        $this->unread = $unread;

        return $this;
    }

    /**
     * Get unread
     *
     * @return integer
     */
    public function getUnread()
    {
        return $this->unread;
    }

    /**
     * Set quickPoints
     *
     * @param integer $quickPoints
     *
     * @return CategoryProgress
     */
    public function setQuickPoints($quickPoints)
    {
        $this->quickPoints = $quickPoints;

        return $this;
    }

    /**
     * Get quickPoints
     *
     * @return integer
     */
    public function getQuickPoints()
    {
        return $this->quickPoints;
    }

    /**
     * Set required
     *
     * @param integer $required
     *
     * @return CategoryProgress
     */
    public function setRequired($required)
    {
        $this->required = $required;

        return $this;
    }

    /**
     * Get required
     *
     * @return integer
     */
    public function getRequired()
    {
        return $this->required;
    }

    /**
     * Set unreadNotification
     *
     * @param boolean $unreadNotification
     *
     * @return CategoryProgress
     */
    public function setUnreadNotification($unreadNotification)
    {
        $this->unreadNotification = $unreadNotification;

        return $this;
    }

    /**
     * Get unreadNotification
     *
     * @return boolean
     */
    public function getUnreadNotification()
    {
        return $this->unreadNotification;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->notifications = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set unreadNotifications
     *
     * @param integer $unreadNotifications
     *
     * @return CategoryProgress
     */
    public function setUnreadNotifications($unreadNotifications)
    {
        $this->unreadNotifications = $unreadNotifications;

        return $this;
    }

    /**
     * Get unreadNotifications
     *
     * @return integer
     */
    public function getUnreadNotifications()
    {
        return $this->unreadNotifications;
    }

    /**
     * Add notification
     *
     * @param \AssignmentBundle\Entity\Notification $notification
     *
     * @return CategoryProgress
     */
    public function addNotification(\AssignmentBundle\Entity\Notification $notification)
    {
        $this->notifications[] = $notification;

        return $this;
    }

    /**
     * Remove notification
     *
     * @param \AssignmentBundle\Entity\Notification $notification
     */
    public function removeNotification(\AssignmentBundle\Entity\Notification $notification)
    {
        $this->notifications->removeElement($notification);
    }

    /**
     * Get notifications
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNotifications()
    {
        return $this->notifications;
    }
}
