<?php

namespace AssignmentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Notification
 *
 * @ORM\Table(name="notification")
 * @ORM\Entity(repositoryClass="AssignmentBundle\Repository\NotificationRepository")
 */
class Notification
{
    //Using constants to define set message types, we may want dynamic types later but that will have to wait
    const MISSING_WORK = 0;
    const GOOD_WORK = 1;
    const CHECK_FEEDBACK = 2;
    const OTHER = 3;

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
     * @ORM\Column(name="type", type="integer")
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text", nullable=true)
     */
    private $message;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateRead", type="datetime", nullable=true)
     */
    private $dateRead;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreated", type="datetime")
     */
    private $dateCreated;

    /**
     * @ORM\ManyToOne(targetEntity="AssignmentBundle\Entity\CategoryProgress", inversedBy="notifications")
     * @ORM\JoinColumn(name="categoryprogress_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     *
     */
    protected $categoryProgress;


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
     * Set type
     *
     * @param integer $type
     *
     * @return Notification
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set message
     *
     * @param string $message
     *
     * @return Notification
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set dateRead
     *
     * @param \DateTime $dateRead
     *
     * @return Notification
     */
    public function setDateRead($dateRead)
    {
        $this->dateRead = $dateRead;

        return $this;
    }

    /**
     * Get dateRead
     *
     * @return \DateTime
     */
    public function getDateRead()
    {
        return $this->dateRead;
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     *
     * @return Notification
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * Get dateCreated
     *
     * @return \DateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * Set categoryProgress
     *
     * @param \AssignmentBundle\Entity\CategoryProgress $categoryProgress
     *
     * @return Notification
     */
    public function setCategoryProgress(\AssignmentBundle\Entity\CategoryProgress $categoryProgress)
    {
        $this->categoryProgress = $categoryProgress;

        return $this;
    }

    /**
     * Get categoryProgress
     *
     * @return \AssignmentBundle\Entity\CategoryProgress
     */
    public function getCategoryProgress()
    {
        return $this->categoryProgress;
    }
}
