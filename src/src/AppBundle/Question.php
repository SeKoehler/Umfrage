<?php
namespace AppBundle;

class Question
{
    public $questionId = 0;

    public $question = "";

    public $answers = array();

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