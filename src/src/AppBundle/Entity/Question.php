<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

class Question
{
    /**
    * @ORM\Entity
    * @ORM\Table(name="questions")
    */

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

    /**
     * @ORM\Column(type="string", length=200)
     */
    private $question = "";

    private $answers = array();

    public $asked = 0;

    /*public function setQuestion($text)
    {
        $this->question = $text;
    }

    public function getQuestion()
    {
        $Question=$this->question;
        return $Question;
    }

    public function setAnswers($array)
    {
        $this->answers=$array;
    }

    public function getAnswers()
    {
        $Array[]=$this->answers;
        return $Array;
    }*/
}