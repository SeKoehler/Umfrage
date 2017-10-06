<?php

// src/AppBundle/Controller/SurveyController.php
namespace AppBundle\Controller;

use AppBundle\Entity\Answers;
use AppBundle\Entity\Question;
use AppBundle\Entity\Questions;
use AppBundle\Entity\SurveyCategory;
use AppBundle\Entity\Surveypackage;
use AppBundle\Survey;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;


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
        $repository = $this->getDoctrine()->getRepository(SurveyCategory::class);
        //$survey = new Survey();
        //$surveys= $survey->showAllSurvey();
        $surveys = $repository->findAll();

        return $this->render('Survey/showsurvey.html.twig', array('result' => $surveys));
    }

    /**
     * @Route("/umfrage/{digit}", name="single_survey", requirements={"digit": "\d+"})
     */
    public function surveyQuestionAction($digit)
    {
        //$survey = new Survey();
        //$questions= $survey->surveyQuestions($digit);
        $survey = $this->getDoctrine()->getRepository(\AppBundle\Entity\Survey::class)->find($digit);
        $questions = $this->getDoctrine()->getRepository(Questions::class)->findBysurveyId($digit);
        $answers = $this->getDoctrine()->getRepository(Answers::class)->findAll();

        $newsurvey = new Survey();
        $newsurvey->buildSurvey($survey,$questions,$answers);


        return $this->render('Survey/showquestions.html.twig', array('allresults' => $survey, 'digit' => $digit));
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
        /*$survey = new Survey();
        $statistics = $survey->surveyStatistics($digit);
        return $this->render('Survey/showstatistics.html.twig', array('allresults' => $statistics,'digit' => $digit));*/
        $survey = $this->getDoctrine()->getRepository(\AppBundle\Entity\Survey::class)->find($digit);
        $questions = $this->getDoctrine()->getRepository(Questions::class)->findBysurveyId($digit);
        $answers = $this->getDoctrine()->getRepository(Answers::class)->findAll();

        $newsurvey = new Survey();
        $newsurvey->buildSurvey($survey,$questions,$answers);


        return $this->render('Survey/showstatistics.html.twig', array('allresults' => $survey, 'digit' => $digit));
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
        /*$survey = new Survey();
        $edit = $survey->editSurvey($digit);
        return $this->render('Survey/editsurvey.html.twig', array('digit' => $digit, 'allresults' => $edit));*/

        $survey = $this->getDoctrine()->getRepository(\AppBundle\Entity\Survey::class)->find($digit);
        $questions = $this->getDoctrine()->getRepository(Questions::class)->findBysurveyId($digit);
        $answers = $this->getDoctrine()->getRepository(Answers::class)->findAll();

        $newsurvey = new Survey();
        $newsurvey->buildSurvey($survey,$questions,$answers);


        return $this->render('Survey/editsurvey.html.twig', array('allresults' => $survey, 'digit' => $digit));
    }

    /**
     * @Route("/umfrage/{digit}/bearbeiten/confirm", name="confirmedit_survey", requirements={"digit": "\d+"})
     */
    public function confirmEditSurveyAction(Request $request, $digit)
    {
        /*$survey = new Survey();
        $survey->confirmEditSurvey($request->request->all(),$digit);
        return $this->render('Survey/controlls.html.twig', array('digit' => $digit));*/
        $em = $this->getDoctrine()->getManager();
        $array = $request->request->all();
        $survey = $this->getDoctrine()->getRepository(\AppBundle\Entity\Survey::class)->find($digit);
        $questions = $this->getDoctrine()->getRepository(Questions::class)->findBysurveyId($digit);
        $answers = $this->getDoctrine()->getRepository(Answers::class)->findAll();

        $newsurvey = new Survey();
        $newsurvey->buildSurvey($survey,$questions,$answers);
        $surveynew = $newsurvey->newEditSurvey($survey,$array);
        $em->persist($survey);
        $em->flush();

        return $this->render('Survey/confirmeditsurvey.html.twig', array('oldresults' => $surveynew, 'newresults' => $survey, 'digit' => $digit));
    }

    /**
     * @Route("/umfrage/{digit}/bearbeiten/antwort_hinzufuegen/{digit2}", name="addanswer_survey", requirements={"digit": "\d+"})
     */
    public function addAnswerAction($digit2)
    {
        return $this->render('Survey/addanswer.html.twig', array('digit2' => $digit2));
    }

    /**
     * @Route("/umfrage/{digit}/bearbeiten/antwort_hinzufuegen/{digit2}/confirm", name="confirmaddanswer_survey", requirements={"digit": "\d+", "digit2": "\d+"})
     */
    public function confirmAddAnswerAction(Request $request,$digit, $digit2)
    {
        $survey = new Survey();
        $survey->confirmAddAnswer($request->request->all(),$digit2);
        return $this->render('Survey/controlls.html.twig');
    }

}
