<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="questions")
*/
class Question
{

    public function __construct($questionId, $question, $answers)
    {
        $this->questionId = $questionId;
        $this->question = $question;
        $this->answers = $answers;
    }

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $questionId = 0;

    public function getquestionId()
    {
        return $this->questionId;
    }

    public function setquestionId($questionId)
    {
        $this->questionId = $questionId;
    }

    /**
     * @ORM\Column(type="string", length=200)
     */
    private $question = "";

    public function getquestion()
    {
        return $this->question;
    }

    public function setquestion($question)
    {
        $this->question = $question;
    }

    /*private $answers = array();

    public function getanswers()
    {
        return $this->answers;
    }

    public function setanswers($answers)
    {
        $this->answers = $answers;
    }

    public $asked = 0;*/
}