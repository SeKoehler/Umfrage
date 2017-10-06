<?php
namespace AppBundle;

use AppBundle\Entity\Answers;
use AppBundle\Entity\Questions;
use AppBundle\Entity\SurveyCategory;
use Doctrine\ORM\EntityManagerInterface;


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
        $newsurvey=new \AppBundle\Entity\Survey();
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
           if (($answer->getQuestionid()->getQuestionid()) == ($myquestion->getQuestionid()))
           {
               $average=$answer->getChosen() / $asked * 100;
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

    public function showAllSurvey()
    {
        /*$database = mysql_connect('mysql', 'root', 'test123') or die('no connection');
        mysql_select_db('survey', $database);

        $sql = 'Select * from survey';

        $result = mysql_query($sql,$database);



        if(!$result)
        {
            echo 'no valid result';
            return;
        }*/


        /*while($row =  mysql_fetch_array($result, MYSQL_ASSOC))
        {
            $results[]=$row;
        }*/
        //$repository = $this->getDoctrine()->getRepository(SurveyCategory::class);





        //$results = $repository->findAll();

        //return $results;

    }

    public function surveyQuestions($surveyId)
    {
        $database = mysql_connect('mysql', 'root', 'test123') or die('no connection');
        mysql_select_db('survey', $database);

        $sql = 'Select surveyname from survey.survey WHERE survey.survey.surveyId = ' . $surveyId;

        $result1 = mysql_query($sql,$database);

        if(!$result1)
        {
            echo 'no valid Survey-ID';
            return;
        }

        $allresults = array();

        while($row =  mysql_fetch_array($result1, MYSQL_ASSOC)) {
            echo '<h1>' . $row['surveyname'] . '</h1>';
        }

        $sql = 'Select * FROM questions INNER JOIN surveypackage ON questions.questionId = surveypackage.questionId
                WHERE surveypackage.surveyId = ' . $surveyId . ' ORDER BY surveypackage.ID';

        $result2 = mysql_query($sql,$database);

        if(!$result2)
        {
            echo 'no valid Survey-ID';
            return;
        }

        $zaehler = 0;

        while($row2 =  mysql_fetch_array($result2, MYSQL_ASSOC))
        {
            $cQuestion = new Question();

            $cQuestion->questionId = $row2['questionId'];
            $cQuestion->question = $row2['question'];


            $sql = 'Select answerId, answer FROM survey.answers WHERE survey.answers.questionId = ' . $row2['questionId'];
            $result3 = mysql_query($sql,$database);

            while($row3 = mysql_fetch_array($result3,MYSQL_ASSOC))
            {
                $cQuestion->answers[] = $row3;
            }

            $allresults[$zaehler++]=$cQuestion;
        }

        return $allresults;
    }

    public function surveyChoices($choices)
    {
        $database = mysql_connect('mysql', 'root', 'test123') or die('no connection');
        mysql_select_db('survey', $database);

        foreach ($choices AS $choice)
        {
            $sql = 'Select chosen FROM survey.answers WHERE answers.answerId = ' . $choice;
            $result = mysql_query($sql,$database);
            $row = mysql_fetch_array($result, MYSQL_ASSOC);
            $row['chosen']++;
            $sql = 'UPDATE answers SET chosen = ' . $row['chosen'] . ' WHERE answers.answerId = ' . $choice;
            mysql_query($sql,$database);
        }

        echo '<h1>Vielen Dank für Ihre Teilnahme an dieser Umfrage!</h1>';
    }

    public function surveyStatistics($surveyId)
    {
        $database = mysql_connect('mysql', 'root', 'test123') or die('no connection');
        mysql_select_db('survey', $database);

        if($surveyId==0)
        {
            return;
        }

        $allresults = array();

        $sql = 'Select surveyname from survey.survey WHERE survey.survey.surveyId = ' . $surveyId;

        $result1 = mysql_query($sql,$database);

        if(!$result1)
        {
            echo 'no valid Survey-ID';
            return;
        }

        while($row =  mysql_fetch_array($result1, MYSQL_ASSOC)) {
            echo '<h1>' . $row['surveyname'] . '</h1>';
        }


        $sql = 'Select * FROM questions INNER JOIN surveypackage ON questions.questionId = surveypackage.questionId
                WHERE surveypackage.surveyId = ' . $surveyId . ' ORDER BY surveypackage.ID';

        $result2 = mysql_query($sql,$database);

        if(!$result2)
        {
            echo 'no valid Survey-ID';
            return;
        }

        $zaehler = 0;

        while($row2 =  mysql_fetch_array($result2, MYSQL_ASSOC))
        {
            $cQuestion = new Question();

            $cQuestion->questionId = $row2['questionId'];
            $cQuestion->question = $row2['question'];

            $sql = 'Select SUM(chosen) AS gesamt FROM survey.answers WHERE answers.questionId = ' . $row2['questionId'];
            $resultChosen = mysql_query($sql,$database);
            $sumChosen = mysql_fetch_array($resultChosen,MYSQL_ASSOC);

            $cQuestion->asked = $sumChosen['gesamt'];

            $sql = 'Select answerId, answer, chosen FROM survey.answers WHERE survey.answers.questionId = ' . $row2['questionId'];
            $result3 = mysql_query($sql,$database);

            while($row3 = mysql_fetch_array($result3,MYSQL_ASSOC))
            {
                $average = $row3['chosen']/$sumChosen['gesamt']*100;
                $row3['average'] = $average;
                $cQuestion->answers[]=$row3;
            }

            $allresults[$zaehler++]=$cQuestion;

        }

        return $allresults;

    }

    public function newSurvey($survey)
    {
        $database = mysql_connect('mysql', 'root', 'test123') or die('no connection');
        mysql_select_db('survey', $database);

        foreach ($survey AS $newSurvey)
        {
            $sql = 'INSERT INTO survey.survey (surveyname) VALUES ("' . $newSurvey .'")';
            mysql_query($sql,$database);
            echo $newSurvey . 'wurde als neue Kategorie hinzugefügt!';
        }
    }

    public function newQuestionAdd($question)
    {
        $database = mysql_connect('mysql', 'root', 'test123') or die('no connection');
        mysql_select_db('survey', $database);

        $sql = 'SELECT MAX(survey.questions.questionId) AS maxId FROM survey.questions';
        $result = mysql_query($sql, $database);
        $maxId = mysql_fetch_array($result, MYSQL_ASSOC);

        foreach ($question AS $answer)
        {
            $sql = 'INSERT INTO survey.answers (questionId, answer) VALUES ("' . $maxId['maxId'] . '","' . $answer .'")';
            mysql_query($sql,$database);
        }

    }

    public function newQuestion($answer)
    {
        $database = mysql_connect('mysql', 'root', 'test123') or die('no connection');
        mysql_select_db('survey', $database);

        echo '<form action="/umfrage/neue_frage/hinzufuegen" method="post">';


        $i = 0;
        $array[]=0;
        foreach ($answer AS $number)
        {
            $array[$i++] = $number;
        }

        $sql = 'INSERT INTO survey.questions (question) VALUES ("' . $array[0] . '")';
        mysql_query($sql,$database);


        for($i=0;$i<$array[1];$i++)
        {
            $j = $i+1;
            echo '<input type="text" name="answerNumber' . $j . '">';
        }
        echo '<button type="submit">Absenden</button>
              </form>';
    }

    public function editSurvey($surveyId)
    {
        $database = mysql_connect('mysql', 'root', 'test123') or die('no connection');
        mysql_select_db('survey', $database);

        $sql = 'Select surveyname from survey.survey WHERE survey.survey.surveyId = ' . $surveyId;

        $result1 = mysql_query($sql,$database);

        if(!$result1)
        {
            echo 'no valid Survey-ID';
            return;
        }

        $subresults = array();
        $allresults = array();

        while($row =  mysql_fetch_array($result1, MYSQL_ASSOC)) {

            $category = new SurveyCategory($surveyId, $row['surveyname']);
        }

        $sql = 'Select * FROM questions INNER JOIN surveypackage ON questions.questionId = surveypackage.questionId
                WHERE surveypackage.surveyId = ' . $surveyId . ' ORDER BY surveypackage.ID';

        $result2 = mysql_query($sql,$database);

        if(!$result2)
        {
            echo 'no valid Survey-ID';
            return;
        }

        $zaehler = 0;

        while($row2 =  mysql_fetch_array($result2, MYSQL_ASSOC))
        {
            $sql = 'Select answerId, answer FROM survey.answers WHERE survey.answers.questionId = ' . $row2['questionId'];
            $result3 = mysql_query($sql,$database);

            $cQuestion = new Question();

            $cQuestion->questionId = $row2['questionId'];
            $cQuestion->question = $row2['question'];

            while($row3 = mysql_fetch_array($result3,MYSQL_ASSOC))
            {
                $cQuestion->answers[] = $row3;
            }

            $subresults[$zaehler++]=$cQuestion;
            $allresults['survey']=$category;
            $allresults['questions']=$subresults;
        }
        return $allresults;
    }

    public function confirmEditSurvey($array,$surveyId)
    {
        $database = mysql_connect('mysql', 'root', 'test123') or die('no connection');
        mysql_select_db('survey', $database);

        $sql = 'Select surveyname From survey.survey WHERE surveyId = ' . $surveyId;
        $result = mysql_query($sql,$database);

        $sql = 'Select * FROM questions INNER JOIN surveypackage ON questions.questionId = surveypackage.questionId
                WHERE surveypackage.surveyId = ' . $surveyId . ' ORDER BY surveypackage.ID';
        $result1= mysql_query($sql,$database);

        echo 'Änderungen:<br><br>';

        while($row = mysql_fetch_array($result,MYSQL_ASSOC))
        {
            echo $row['surveyname'] . ' => ' . $array['surveyname'] . '<br>';
            $sql = 'UPDATE survey.survey SET surveyname="' . $array['surveyname'] . '" WHERE surveyId=' . $surveyId;
            mysql_query($sql,$database);
        }

        while($row1 = mysql_fetch_array($result1,MYSQL_ASSOC))
        {
            echo $row1['question'] . ' => ' . $array['question' . $row1['questionId'] ] . '<br>';

            $sql = 'UPDATE questions SET question="' . $array['question' . $row1['questionId'] ] . '" WHERE questionId=' . $row1['questionId'];
            mysql_query($sql,$database);

            $sql = 'Select answerId, answer FROM survey.answers WHERE survey.answers.questionId = ' . $row1['questionId'];
            $result2 = mysql_query($sql,$database);

            while($row2 = mysql_fetch_array($result2,MYSQL_ASSOC))
            {
                echo $row2['answer'] . ' => ' . $array[$row2['answerId']] . '<br>';
                $sql = 'UPDATE answers SET answer="' . $array[$row2['answerId']] . '" WHERE answerId=' . $row2['answerId'];
                mysql_query($sql,$database);
                //echo $array[$row2['answerId']] . '<br>';
            }
        }
    }

    public function confirmAddAnswer($answer,$questionId)
    {
        $database = mysql_connect('mysql', 'root', 'test123') or die('no connection');
        mysql_select_db('survey', $database);

        $sql = 'INSERT INTO survey.answers (questionId,answer) VALUES ("' . $questionId .'","' . $answer['answer'] . '")';
        mysql_query($sql,$database);
        echo $answer['answer'] . ' als Antwort hinzugefügt!<br>';

    }
};



