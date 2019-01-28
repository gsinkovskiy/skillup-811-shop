<?php

namespace App\Controller;

use App\Entity\FeedbackRequest;
use App\Form\FeedbackRequestType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FeedbackController extends AbstractController
{
    /**
     * @Route("/feedback", name="feedback")
     */
    public function index(Request $request)
    {
        $feedbackRequest = new FeedbackRequest();
        $form = $this->createForm(FeedbackRequestType::class, $feedbackRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager(); // Получаем EntityManager для сохранения данных
            $manager->persist($feedbackRequest); // Помечаем новую сущность для сохранения в БД
            $manager->flush(); // "Сливаем" изменения в БД

            $this->addFlash('info', 'Спасибо за обращение, мы с Вами свяжемся.');

            return $this->redirectToRoute('feedback');
        }

        return $this->render('feedback/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
