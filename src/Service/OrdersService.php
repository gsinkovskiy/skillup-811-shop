<?php

namespace App\Service;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Product;
use App\Entity\User;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;

class OrdersService
{

    private $request;

    private $orderRepository;

    private $entityManager;

    private $twig;

    private $mailer;

    private $parameters;

    public function __construct(
        RequestStack $requestStack,
        OrderRepository $orderRepository,
        EntityManagerInterface $entityManager,
        Environment $twig,
        \Swift_Mailer $mailer,
        ParameterBagInterface $parameters
    ) {
        $this->request = $requestStack->getCurrentRequest();
        $this->orderRepository = $orderRepository;
        $this->entityManager = $entityManager;
        $this->twig = $twig;
        $this->mailer = $mailer;
        $this->parameters = $parameters;
    }

    public function addToCart(Product $product): Order
    {
        $order = $this->getOrderFromRequest();
        $items = $order->getItems();

        if ($items[$product->getId()]) {
            $items[$product->getId()]->addQuantity(1);
        } else {
            $item = new OrderItem();
            $item->setProduct($product);
            $item->setQuantity(1);
            $order->addItem($item);
            $this->entityManager->persist($item);
        }

        $this->entityManager->flush();

        return $order;
    }

    public function getOrderFromRequest()
    {
        $order = null;
        $orderId = $this->request->cookies->get('orderId');

        if ($orderId) {
            $order = $this->orderRepository->find($orderId);
        }

        if (!$order) {
            $order = new Order();
            $this->entityManager->persist($order);
        }

        return $order;
    }

    public function removeItemFromCart(OrderItem $item)
    {
        $cart = $this->getOrderFromRequest();
        $order = $item->getOrder();

        if ($cart !== $order) {
            return;
        }

        $order->removeItem($item);
        $this->entityManager->remove($item);
        $this->entityManager->flush();
    }

    public function setItemQuantity(OrderItem $item, $quantity)
    {
        $cart = $this->getOrderFromRequest();
        $order = $item->getOrder();

        if ($cart !== $order) {
            return;
        }

        if ($quantity < 1) {
            return;
        }

        $item->setQuantity($quantity);
        $this->entityManager->flush();
    }

    public function prepareOrder(?User $user)
    {
        $order = $this->getOrderFromRequest();

        if ($user) {
            $order->setFirstName($user->getFirstName());
            $order->setLastName($user->getLastName());
            $order->setEmail($user->getEmail());
            $order->setPhone($user->getPhone());
            $order->setAddress($user->getAddress());
        }

        return $order;
    }

    public function sendOrder(Order $order)
    {
        $order->setStatus(Order::STATUS_ORDERED);
        $this->entityManager->persist($order);
        $this->entityManager->flush();
        $this->sendEmail($this->parameters->get('adminEmail'), 'orders/mail/newOrderForAdmin.html.twig', ['order' => $order]);
    }

    private function sendEmail(string $to, string $templateName, array $context)
    {
        $template = $this->twig->load($templateName);
        $subject = $template->renderBlock('subject', $context);
        $body = $template->renderBlock('body', $context);

        $message = new \Swift_Message();
        $message->setSubject($subject);
        $message->setBody($body, 'text/html');
        $message->setTo($to);
        $message->setFrom($this->parameters->get('fromEmail'), $this->parameters->get('fromName'));
        $this->mailer->send($message);
    }

}
