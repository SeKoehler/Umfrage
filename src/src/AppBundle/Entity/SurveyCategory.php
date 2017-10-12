<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Survey
 *
 * @ORM\Table(name="surveycategory")
 * @ORM\Entity
 */
class SurveyCategory
{
    /**
     * @var integer
     *
     * @ORM\Column(name="surveyId", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $surveyId;

    /**
     * @return int
     */
    public function getSurveyid()
    {
        return $this->surveyId;
    }

    /**
     * @param int $surveyid
     */
    public function setSurveyid($surveyid)
    {
        $this->surveyId = $surveyid;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="surveyname", type="text", length=65535, nullable=false)
     */
    private $surveyname;

    /**
     * @return string
     */
    public function getSurveyname()
    {
        return $this->surveyname;
    }

    /**
     * @param string $surveyname
     */
    public function setSurveyname($surveyname)
    {
        $this->surveyname = $surveyname;
    }

    private $questions;

    /**
     * @return ArrayCollection
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * @param ArrayCollection $questions
     */
    public function setQuestions($questions)
    {
        $this->questions = $questions;
    }

    public function __construct()
    {
        $this->questions = new ArrayCollection();
    }


}

