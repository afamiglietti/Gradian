<?php

namespace AssignmentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Submission
 *
 * @ORM\Table(name="submission")
 * @ORM\Entity(repositoryClass="AssignmentBundle\Repository\SubmissionRepository")
 */
class Submission
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
     * @var \DateTime
     *
     * @ORM\Column(name="submitted", type="datetime")
     */
    private $submitted;

    /**
     * @var int
     *
     * @ORM\Column(name="points", type="integer", nullable=true)
     */
    private $points;

    /**
     * @var string
     *
     * @ORM\Column(name="link", type="string", length=255)
     */
    private $link;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text", nullable=true)
     */
    private $comment;

    /**
     * @var string
     *
     * @ORM\Column(name="feedbackLink", type="string", length=255, nullable=true)
     */
    private $feedbackLink;

    /**
     * @var string
     *
     * @ORM\Column(name="feedbackComments", type="text", nullable=true)
     */
    private $feedbackComments;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="submissions")
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="AssignmentBundle\Entity\Assignment", inversedBy="submissions")
     */
    protected $assignment;

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
     * Set submitted
     *
     * @param \DateTime $submitted
     *
     * @return Submission
     */
    public function setSubmitted($submitted)
    {
        $this->submitted = $submitted;

        return $this;
    }

    /**
     * Get submitted
     *
     * @return \DateTime
     */
    public function getSubmitted()
    {
        return $this->submitted;
    }

    /**
     * Set points
     *
     * @param integer $points
     *
     * @return Submission
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
     * Set link
     *
     * @param string $link
     *
     * @return Submission
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return Submission
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set feedbackLink
     *
     * @param string $feedbackLink
     *
     * @return Submission
     */
    public function setFeedbackLink($feedbackLink)
    {
        $this->feedbackLink = $feedbackLink;

        return $this;
    }

    /**
     * Get feedbackLink
     *
     * @return string
     */
    public function getFeedbackLink()
    {
        return $this->feedbackLink;
    }

    /**
     * Set feedbackComments
     *
     * @param string $feedbackComments
     *
     * @return Submission
     */
    public function setFeedbackComments($feedbackComments)
    {
        $this->feedbackComments = $feedbackComments;

        return $this;
    }

    /**
     * Get feedbackComments
     *
     * @return string
     */
    public function getFeedbackComments()
    {
        return $this->feedbackComments;
    }

    /**
     * Set user
     *
     * @param \UserBundle\Entity\User $user
     *
     * @return Submission
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
     * Set assignment
     *
     * @param \AssignmentBundle\Entity\Assignment $assignment
     *
     * @return Submission
     */
    public function setAssignment(\AssignmentBundle\Entity\Assignment $assignment = null)
    {
        $this->assignment = $assignment;

        return $this;
    }

    /**
     * Get assignment
     *
     * @return \AssignmentBundle\Entity\Assignment
     */
    public function getAssignment()
    {
        return $this->assignment;
    }
}
