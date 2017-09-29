<?php

// src/AppBundle/Controller/SurveyController.php
namespace AppBundle\Controller;

use AppBundle\Survey;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SurveyController extends Controller
{
    /**
     * @Route("/umfrage")
     */
    public function surveyStartAction()
    {
        return $this->render('Survey/index.html.twig');
    }

    /**
     * @Route("/umfrage/start", name="choice_survey")
     */
    public function surveyChoiceAction()
    {
        $survey = new Survey();
        $survey->showAllSurvey();
        return $this->render('Survey/controlls.html.twig');
    }

    /**
     * @Route("/umfrage/{digit}", name="single_survey", requirements={"digit": "\d+"})
     */
    public function surveyQuestionAction($digit)
    {
        $survey = new Survey();
        $survey->surveyQuestions($digit);
        return $this->render('Survey/controlls2.html.twig', array('digit' => $digit));
    }

    /**
     * @Route("/umfrage/ende", name="endof_survey")
     */
    public function surveyEndAction(Request $request)
    {
        $survey = new Survey();
        $survey->surveyChoices($request->request->all());
        return $this->render('Survey/controlls.html.twig');
    }

    /**
     * @Route("/umfrage/{digit}/statistic", name="statistic_survey", requirements={"digit": "\d+"})
     */
    public function surveyStatisticAction($digit)
    {
        $survey = new Survey();
        $survey->surveyStatistics($digit);
        return $this->render('Survey/controlls2.html.twig', array('digit' => $digit));
    }

    /**
     * @Route("/umfrage/neue_kategorie", name="newcategory_survey")
     */
    public function surveyCategoryAction()
    {
        return $this->render('Survey/newcategory.html.twig');
    }

    /**
     * @Route("/umfrage/neue_kategorie/hinzufuegen", name="categoryadd_survey")
     */
    public function surveyCategoryAddAction(Request $request)
    {
        $survey = new Survey();
        $survey->newSurvey($request->request->all());
        return $this->render('Survey/controlls.html.twig');
    }

    /**
     * @Route("/umfrage/neue_frage", name="newquestion_survey")
     */
    public function surveyNewQuestionAction()
    {
        return $this->render('Survey/newquestion.html.twig');
    }

    /**
     * @Route("/umfrage/neue_frage/start", name="newquestionstart_survey")
     */
    public function surveyNewQuestionStartAction(Request $request)
    {
        $survey = new Survey();
        $survey->newQuestion($request->request->all());
        return $this->render('Survey/controlls.html.twig');
    }

    /**
     * @Route("/umfrage/neue_frage/hinzufuegen", name="questionadd_survey")
     */
    public function surveyNewQuestionAddAction(Request $request)
    {
        $survey = new Survey();
        $survey->newQuestionAdd($request->request->all());
        return $this->render('Survey/controlls.html.twig');
    }

    /**
     * @Route("/umfrage/{digit}/bearbeiten", name="edit_survey")
     */
    public function editSurveyAction($digit)
    {
        $survey = new Survey();
        $survey->editSurvey($digit);
        return $this->render('Survey/controlls.html.twig', array('digit' => $digit));
    }

    /**
     * @Route("/umfrage/{digit}/bearbeiten/confirm", name="confirmedit_survey", requirements={"digit": "\d+"})
     */
    public function confirmEditSurveyAction(Request $request, $digit)
    {
        $survey = new Survey();
        $survey->confirmEditSurvey($request->request->all(),$digit);
        return $this->render('Survey/controlls.html.twig', array('digit' => $digit));
    }
}
