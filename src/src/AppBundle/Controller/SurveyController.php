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
     * @Route("/umfrage",
     *      name="index_survey")
     */
    public function surveyStartAction()
    {
        return $this->render('Survey/index.html.twig');
    }

    /**
     * @Route("/umfrage/start",
     *      name="choice_survey")
     */
    public function surveyChoiceAction()
    {
        $repository = $this->getDoctrine()->getRepository(\AppBundle\Entity\Survey::class);
        $surveys = $repository->findAll();

        return $this->render('Survey/showsurvey.html.twig', array('result' => $surveys));
    }

    /**
     * @Route("/umfrage/{digit}",
     *      name="single_survey",
     *      requirements={"digit": "\d+"})
     */
    public function surveyQuestionAction($digit)
    {
        $survey = $this->getDoctrine()->getRepository(\AppBundle\Entity\Survey::class)->find($digit);
        $questions = $this->getDoctrine()->getRepository(Questions::class)->findBysurveyId($digit);
        $answers = $this->getDoctrine()->getRepository(Answers::class)->findAll();

        $newsurvey = new Survey();
        $newsurvey->buildSurvey($survey,$questions,$answers);


        return $this->render('Survey/showquestions.html.twig', array('allresults' => $survey, 'digit' => $digit));
    }

    /**
     * @Route("/umfrage/ende",
     *      name="endof_survey")
     */
    public function surveyEndAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $choices = $request->request->all();

        foreach ($choices as $choice)
        {
            $mychoice = $this->getDoctrine()->getRepository(Answers::class)->find($choice);
            $mychoice->setChosen($mychoice->getChosen()+1);
            $em->persist($mychoice);

        }
        $em->flush();
        return $this->render('Survey/controlls.html.twig');
    }

    /**
     * @Route("/umfrage/{digit}/statistic",
     *      name="statistic_survey",
     *      requirements={"digit": "\d+"})
     */
    public function surveyStatisticAction($digit)
    {
        $survey = $this->getDoctrine()->getRepository(\AppBundle\Entity\Survey::class)->find($digit);
        $questions = $this->getDoctrine()->getRepository(Questions::class)->findBysurveyId($digit);
        $answers = $this->getDoctrine()->getRepository(Answers::class)->findAll();

        $newsurvey = new Survey();
        $newsurvey->buildSurvey($survey,$questions,$answers);


        return $this->render('Survey/showstatistics.html.twig', array('allresults' => $survey, 'digit' => $digit));
    }

    /**
     * @Route("/umfrage/neue_kategorie",
     *      name="newcategory_survey")
     */
    public function surveyCategoryAction()
    {
        return $this->render('Survey/newcategory.html.twig');
    }

    /**
     * @Route("/umfrage/neue_kategorie/hinzufuegen",
     *      name="categoryadd_survey")
     */
    public function surveyCategoryAddAction(Request $request)
    {
        $array = $request->request->all();

        $newsurvey = new \AppBundle\Entity\Survey();
        $newsurvey->setSurveyname($array['surveyname']);
        $em = $this->getDoctrine()->getManager();
        $em->persist($newsurvey);
        $em->flush();

        return $this->render('Survey/confirmaddnewsurvey.html.twig', array('surveyname' => $array['surveyname']));
    }

    /**
     * @Route("/umfrage/{digit}/bearbeiten/neue_frage/",
     *      name="newquestion_survey",
     *      requirements={"digit": "\d+"})
     */
    public function surveyNewQuestionAction($digit)
    {
        return $this->render('Survey/newquestion.html.twig', array('digit' => $digit));
    }

    /**
     * @Route("/umfrage/{digit}/bearbeiten/neue_frage/start",
     *      name="newquestionstart_survey",
     *      requirements={"digit": "\d+"})
     */
    public function surveyNewQuestionStartAction(Request $request, $digit)
    {
        $array = $request->request->all();

        $answernumber = $array['answerNumber'];
        return $this->render('Survey/newquestionanswers.html.twig', array('answernumber' => $answernumber, 'digit' => $digit, 'question' => $array['question']));
    }

    /**
     * @Route("/umfrage/{digit}/bearbeiten/neue_frage/hinzufuegen/{digit2}",
     *      name="newquestionadd_survey",
     *      requirements={"digit": "\d+", "digit2": "\d+"})
     */
    public function surveyNewQuestionAddAction(Request $request, $digit, $digit2)
    {
        $em = $this->getDoctrine()->getManager();
        $survey = $this->getDoctrine()->getRepository(\AppBundle\Entity\Survey::class)->find($digit);
        $array = $request->request->all();
        $newsurvey = new Survey();
        $newquestion = $newsurvey->addNewQuestion($array,$survey,$digit2);

        $answers = $newquestion->getAnswers();

        foreach ($answers as $answer)
        {
            $em->persist($answer);
        }

        $em->persist($newquestion);
        $em->flush();

        return $this->render('Survey/confirmaddnewquestion.html.twig');
    }

    /**
     * @Route("/umfrage/{digit}/bearbeiten",
     *      name="edit_survey",
     *      requirements={"digit": "\d+"})
     */
    public function editSurveyAction($digit)
    {
        $survey = $this->getDoctrine()->getRepository(\AppBundle\Entity\Survey::class)->find($digit);
        $questions = $this->getDoctrine()->getRepository(Questions::class)->findBysurveyId($digit);
        $answers = $this->getDoctrine()->getRepository(Answers::class)->findAll();

        $newsurvey = new Survey();
        $newsurvey->buildSurvey($survey,$questions,$answers);

        return $this->render('Survey/editsurvey.html.twig', array('allresults' => $survey, 'digit' => $digit));
    }

    /**
     * @Route("/umfrage/{digit}/bearbeiten/confirm",
     *      name="confirmedit_survey",
     *      requirements={"digit": "\d+"})
     */
    public function confirmEditSurveyAction(Request $request, $digit)
    {
        $em = $this->getDoctrine()->getManager();
        $survey = $this->getDoctrine()->getRepository(\AppBundle\Entity\Survey::class)->find($digit);
        $questions = $this->getDoctrine()->getRepository(Questions::class)->findBysurveyId($digit);
        $answers = $this->getDoctrine()->getRepository(Answers::class)->findAll();

        $newsurvey = new Survey();
        $newsurvey->buildSurvey($survey,$questions,$answers);
        $surveynew = $newsurvey->newEditSurvey($survey,$request->request->all());
        $em->persist($survey);
        $em->flush();

        return $this->render('Survey/confirmeditsurvey.html.twig', array('oldresults' => $surveynew, 'newresults' => $survey, 'digit' => $digit));
    }

    /**
     * @Route("/umfrage/{digit}/bearbeiten/antwort_hinzufuegen/{digit2}",
     *      name="addanswer_survey",
     *      requirements={"digit": "\d+", "digit2": "\d+"})
     */
    public function addAnswerAction($digit2)
    {
        return $this->render('Survey/addanswer.html.twig', array('digit2' => $digit2));
    }

    /**
     * @Route("/umfrage/{digit}/bearbeiten/antwort_hinzufuegen/{digit2}/confirm",
     *      name="confirmaddanswer_survey",
     *      requirements={"digit": "\d+", "digit2": "\d+"})
     */
    public function confirmAddAnswerAction(Request $request, $digit2)
    {
        $survey = new Survey();
        $question = $this->getDoctrine()->getRepository(Questions::class)->findOneByquestionId($digit2);
        $answer = $survey->addNewAnswer($request->request->all(),$question,1);
        $em = $this->getDoctrine()->getManager();
        $em->persist($answer);
        $em->flush();


        return $this->render('Survey/confirmaddanswer.html.twig');
    }

}
