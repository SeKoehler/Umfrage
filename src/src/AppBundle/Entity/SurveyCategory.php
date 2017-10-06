<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="survey")
 */
class SurveyCategory
{

    public function __construct($surveyId, $surveyname)
    {
        $this->surveyId = $surveyId;
        $this->surveyname = $surveyname;
    }

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $surveyId;

    public function getsurveyId()
    {
        return $this->surveyId;
    }

    public function setsurveyId($surveyId)
    {
        $this->surveyId = $surveyId;
    }

    /**
     * @ORM\Column(type="string", length=200)
     */
    private $surveyname;


    public function getSurveyname()
    {
        return $this->surveyname;
    }


    public function setSurveyname($surveyname)
    {
        $this->surveyname = $surveyname;
    }



}