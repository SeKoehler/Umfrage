<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Answers
 *
 * @ORM\Table(name="answers", indexes={@ORM\Index(name="answers_questions_questionId_fk", columns={"questionId"})})
 * @ORM\Entity
 */
class Answers
{
    /**
     * @var integer
     *
     * @ORM\Column(name="answerId", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $answerId;

    /**
     * @return int
     */
    public function getAnswerid()
    {
        return $this->answerId;
    }

    /**
     * @param int $answerid
     */
    public function setAnswerid($answerid)
    {
        $this->answerId = $answerid;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="answer", type="text", length=65535, nullable=false)
     */
    private $answer;

    /**
     * @return string
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * @param string $answer
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;
    }

    /**
     * @var integer
     *
     * @ORM\Column(name="chosen", type="integer", nullable=true)
     */
    private $chosen;

    /**
     * @return int
     */
    public function getChosen()
    {
        return $this->chosen;
    }

    /**
     * @param int $chosen
     */
    public function setChosen($chosen)
    {
        $this->chosen = $chosen;
    }

    /**
     * @var \Questions
     *
     * @ORM\ManyToOne(targetEntity="Questions")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="questionId", referencedColumnName="questionId")
     * })
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

    private $average=0;

    /**
     * @return int
     */
    public function getAverage()
    {
        return $this->average;
    }

    /**
     * @param int $average
     */
    public function setAverage($average)
    {
        $this->average = $average;
    }


}

