<?php
namespace AppBundle\Entity;

class Answers
{
    public function __construct($answerId, $questionId, $answer, $chosen)
    {
        $this->answerId = $answerId;
        $this->questionId = $questionId;
        $this->answer = $answer;
        $this->chosen = $chosen;
    }

    private $answerId;

    private $questionId;

    private $answer;

    private $chosen;

}