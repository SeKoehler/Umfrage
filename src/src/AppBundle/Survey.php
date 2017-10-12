<?php
namespace AppBundle;

use AppBundle\Entity\Answers;
use AppBundle\Entity\Questions;
use AppBundle\Entity\SurveyCategory;


class Survey
{
    public function buildSurvey($survey, array $questions, array $allanswers)
    {
        foreach ($questions as $question)
        {
            $this->addAnswersToQuestion($question,$allanswers);
        }
        $this->addQuestionsToSurvey($survey,$questions);
    }

    public function newEditSurvey($survey,array $array)
    {
        $newsurvey=new SurveyCategory();
        $newsurvey->setSurveyname($survey->getSurveyname());
        $survey->setSurveyname($array['surveyname']);
        $questions = $survey->getQuestions();
        $newquestions = array();
        foreach ($questions as $question)
        {
            $answers = $question->getAnswers();
            $newquestion=new Questions();
            $newquestion->setQuestion($question->getQuestion());
            $question->setQuestion($array['question'. $question->getQuestionid()]);
            $newanswers = array();
            foreach ($answers as $answer)
            {
                $newanswer=new Answers();
                $newanswer->setAnswer($answer->getAnswer());
                $answer->setAnswer($array[$answer->getAnswerid()]);
                $newanswers[]=$newanswer;
            }
            $newquestion->setAnswers($newanswers);
            $newquestions[]=$newquestion;

        }
        $newsurvey->setQuestions($newquestions);
        return $newsurvey;
    }

    public function addAnswersToQuestion(Questions $myquestion,array $allanswers)
    {
        $zaehler=0;
        $asked=$this->calcAsked($myquestion,$allanswers);
        $answers = array();


        foreach ($allanswers as $answer)
        {
           if ((($answer->getQuestionid()->getQuestionid()) == ($myquestion->getQuestionid())))
           {
               if ($asked>0)
               {
                   $average=round($answer->getChosen() / $asked * 100, 2);
               }
               else
               {
                   $average=0;
               }

               $answer->setAverage($average);
               $answers[$zaehler++]=$answer;
           }
        }


        $myquestion->setAnswers($answers);
        $myquestion->setAsked($asked);
    }

    public function calcAsked($question,$answers)
    {
        $asked=0;

        foreach ($answers as $answer)
        {
            if (($answer->getQuestionid()->getQuestionid()) == ($question->getQuestionid()))
            {
                $asked+=$answer->getChosen();
            }
        }
        return $asked;
    }

    public function addQuestionsToSurvey($survey,array $questions)
    {
        $survey->setQuestions($questions);
    }

    public function addNewAnswer($answer, $question, $number)
    {
        $newanswer = new Answers();
        $newanswer->setAnswer($answer['answer'. $number]);
        $newanswer->setChosen(0);
        $newanswer->setQuestionid($question);

        return $newanswer;
    }

    public function addNewQuestion($array,$surveyId,$answernumber)
    {
        $newquestion = new Questions();
        $newquestion->setQuestion($array['question']);
        $newquestion->setSurveyId($surveyId);
        $answer = array();
        for ($i=1; $i <= $answernumber;$i++)
        {
            $answer[] = $this->addNewAnswer($array,$newquestion,$i);
        }
        $newquestion->setAnswers($answer);
        return $newquestion;
    }

};



