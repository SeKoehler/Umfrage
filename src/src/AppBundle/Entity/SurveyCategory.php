<?php
namespace AppBundle\Entity;

class SurveyCategory
{
    public function __construct($surveyId, $surveyname)
    {
        $this->surveyId = $surveyId;
        $this->surveyname = $surveyname;
    }

    private $surveyId;

    private $surveyname;

}