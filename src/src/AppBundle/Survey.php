<?php
namespace AppBundle;

class Survey
{
    public function showAllSurvey()
    {
        $database = mysql_connect('mysql', 'root', 'test123') or die('no connection');
        mysql_select_db('survey', $database);

        $sql = 'Select * from survey';

        $result = mysql_query($sql,$database);

        if(!$result)
        {
            echo 'no valid result';
            return;
        }

        echo '<br><table>';
        echo '<tr>';
        echo '<th>ID</th>';
        echo '<th>Umfragebezeichnung</th>';
        echo '</tr>';

        while($row =  mysql_fetch_array($result, MYSQL_ASSOC))
        {

            echo '<tr>';
            echo '<td>' . $row['surveyId'] . '</td>';
            echo '<td><a href="/umfrage/' . $row['surveyId'] . '">' . $row['surveyname'] . '</a></td>';
            echo '</tr>';

        }
        echo '</table>';
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

        while($row =  mysql_fetch_array($result1, MYSQL_NUM)) {
            echo '<h1>' . $row[0] . '</h1>';
        }
        echo '<br><table>';

        $sql = 'Select * FROM questions INNER JOIN surveypackage ON questions.questionId = surveypackage.questionId
                WHERE surveypackage.surveyId = ' . $surveyId . ' ORDER BY surveypackage.ID';

        $result2 = mysql_query($sql,$database);

        if(!$result2)
        {
            echo 'no valid Survey-ID';
            return;
        }

        $zaehler = 1;

        echo '<form action="/umfrage/ende" method="post">';

        while($row =  mysql_fetch_array($result2, MYSQL_NUM))
        {
            $sql = 'Select answerId, answer FROM survey.answers WHERE survey.answers.questionId = ' . $row[0];
            $result3 = mysql_query($sql,$database);
            echo '<tr>';
            echo '<td>' . $zaehler++ . '. Frage: </td>';
            echo '<td>' . $row[1] . '</td>';
            echo '</tr>';

            while($row = mysql_fetch_array($result3,MYSQL_ASSOC))
            {
                echo '<tr>';
                echo '<td></td>';
                echo '<td><input type="radio" name="Antwort' . $zaehler . '" value="' . $row['answerId'] . '">' . $row['answer'] . '</td>';
                echo '</tr>';
            }
            echo '<tr>';
            echo '<td>'; echo '&nbsp;'; echo '</td>';
            echo '<td>'; echo '&nbsp;'; echo '</td>';
            echo '</tr>';
        }
        echo '</table>';
        echo '<button type="submit">Absenden</button>
                </form>';
        echo '<br>';
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


        $sql = 'Select surveyname from survey.survey WHERE survey.survey.surveyId = ' . $surveyId;

        $result1 = mysql_query($sql,$database);

        if(!$result1)
        {
            echo 'no valid Survey-ID';
            return;
        }

        while($row =  mysql_fetch_array($result1, MYSQL_NUM)) {
            echo '<h1>' . $row[0] . '</h1>';
        }
        echo '<br><table>';

        $sql = 'Select * FROM questions INNER JOIN surveypackage ON questions.questionId = surveypackage.questionId
                WHERE surveypackage.surveyId = ' . $surveyId . ' ORDER BY surveypackage.ID';

        $result2 = mysql_query($sql,$database);

        if(!$result2)
        {
            echo 'no valid Survey-ID';
            return;
        }

        $zaehler = 1;

        while($row =  mysql_fetch_array($result2, MYSQL_ASSOC))
        {
            $sql = 'Select SUM(chosen) AS gesamt FROM survey.answers WHERE answers.questionId = ' . $row['questionId'];
            $resultChosen = mysql_query($sql,$database);
            $sumChosen = mysql_fetch_array($resultChosen,MYSQL_ASSOC);

            $sql = 'Select answerId, answer, chosen FROM survey.answers WHERE survey.answers.questionId = ' . $row['questionId'];
            $result3 = mysql_query($sql,$database);

            echo '<tr>';
            echo '<td>' . $zaehler++ . '. Frage: </td>';
            echo '<td>' . $row['question'] . '</td>';
            echo '<td>Bereits ' . $sumChosen['gesamt'] . ' Leute zu dieser Umfrage befragt!</td>';
            echo '</tr>';

            while($row = mysql_fetch_array($result3,MYSQL_ASSOC))
            {
                $average = $row['chosen']/$sumChosen['gesamt']*100;
                echo '<tr>';
                echo '<td>'. $row['answer'] .'</td>';
                echo '<td>'. $row['chosen'] .'</td>';
                echo '<td>'. $average .' %</td>';
                echo '</tr>';
            }
            echo '<tr>';
            echo '<td>'; echo '&nbsp;'; echo '</td>';
            echo '<td>'; echo '&nbsp;'; echo '</td>';
            echo '</tr>';
        }
        echo '</table>';
    }

    public function newSurvey($survey)
    {
        $database = mysql_connect('mysql', 'root', 'test123') or die('no connection');
        mysql_select_db('survey', $database);

        foreach ($survey AS $newSurvey)
        {
            $sql = 'INSERT INTO survey.survey (surveyname) VALUES ("' . $newSurvey .'")';
            mysql_query($sql,$database);
            echo $sql;
            echo $newSurvey;
        }
    }

    public function newQuestionAdd($question)
    {
        $database = mysql_connect('mysql', 'root', 'test123') or die('no connection');
        mysql_select_db('survey', $database);

        $sql = 'SELECT MAX(survey.questions.questionId) AS maxId FROM survey.questions';
        $result = mysql_query($sql, $database);
        $maxId = mysql_fetch_array($result, MYSQL_ASSOC);
        echo $maxId['maxId'];

        foreach ($question AS $answer)
        {
            $sql = 'INSERT INTO survey.answers (questionId, answer) VALUES ("' . $maxId['maxId'] . '","' . $answer .'")';
            mysql_query($sql,$database);
            echo $sql;
            echo $answer;
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

        echo $array[1];

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

        echo '<form action="bearbeiten/confirm" method="post">';

        while($row =  mysql_fetch_array($result1, MYSQL_ASSOC)) {
            echo '<label>Umfragebezeichnung: </label>';
            echo '<input type="text" name="surveyname" value="' . $row['surveyname'] . '" size="30">';
            echo '</input><br><br>';
        }

        $sql = 'Select * FROM questions INNER JOIN surveypackage ON questions.questionId = surveypackage.questionId
                WHERE surveypackage.surveyId = ' . $surveyId . ' ORDER BY surveypackage.ID';

        $result2 = mysql_query($sql,$database);

        if(!$result2)
        {
            echo 'no valid Survey-ID';
            return;
        }

        $zaehler = 1;

        while($row =  mysql_fetch_array($result2, MYSQL_ASSOC))
        {
            $sql = 'Select answerId, answer FROM survey.answers WHERE survey.answers.questionId = ' . $row['questionId'];
            $result3 = mysql_query($sql,$database);

            echo '<label>' . $zaehler++ . '. Frage: </label>';
            echo '<input type="text" name="question' . $row['questionId'] . '" value="' . $row['question'] . '" size="30">';
            echo '<br>';
            echo '<br>';
            echo '<label>Antworten:</label><br>';

            while($row = mysql_fetch_array($result3,MYSQL_ASSOC))
            {
                echo '<input type="text" name="' . $row['answerId'] . '" value="' . $row['answer'] . '">';
                echo '<br>';
            }
            echo '<br>';

        }

        echo '<button type="submit">Absenden</button>';
        echo '</form>';
        echo '<br>';
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
        //echo $sql . '<br>';
        //echo $result;
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


        //echo $array['surveyname'] . '<br>';
        //echo $array['question' . "1" ] . '<br>';
        //var_dump($array);
    }
};



