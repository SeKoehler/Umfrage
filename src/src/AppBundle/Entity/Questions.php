<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Questions
 *
 * @ORM\Table(name="questions")
 * @ORM\Entity
 */
class Questions
{
    /**
     * @var integer
     *
     * @ORM\Column(name="questionId", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $questionId;

    /**
     * @return int
     */
    public function getQuestionid()
    {
        return $this->questionId;
    }

    /**
     * @param int $questionid
     */
    public function setQuestionid($questionid)
    {
        $this->questionId = $questionid;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="question", type="text", length=65535, nullable=false)
     */
    private $question;

    /**
     * @return string
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * @param string $question
     */
    public function setQuestion($question)
    {
        $this->question = $question;
    }

    /**
     * @var \SurveyCategory
     *
     * @ORM\ManyToOne(targetEntity="SurveyCategory")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="surveyId", referencedColumnName="surveyId")
     * })
     */
    private $surveyId;

    /**
     * @return int
     */
    public function getSurveyId()
    {
        return $this->surveyId;
    }

    /**
     * @param int $surveyId
     */
    public function setSurveyId($surveyId)
    {
        $this->surveyId = $surveyId;
    }


    private $answers;

    /**
     * @return ArrayCollection
     */
    public function getAnswers()
    {
        return $this->answers;
    }

    /**
     * @param ArrayCollection $answers
     */
    public function setAnswers($answers)
    {
        $this->answers = $answers;
    }

    private $asked;

    /**
     * @return int
     */
    public function getAsked()
    {
        return $this->asked;
    }

    /**
     * @param int $asked
     */
    public function setAsked($asked)
    {
        $this->asked = $asked;
    }

    public function __construct()
    {
        $this->answers = new ArrayCollection();
    }
}

