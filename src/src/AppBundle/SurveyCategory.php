<?php
namespace AppBundle;

class SurveyCategory
{
    public function __construct($surveyId, $surveyname)
    {
        $this->surveyId = $surveyId;
        $this->surveyname = $surveyname;
    }

    public $surveyId = 0;

    public $surveyname = "";

}