<?php

namespace App\Controller;

use App\Entity\Order;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PaymentsController extends AbstractController
{
    /**
     * @Route("/payments/make/{id}", name="payments_make_payment")
     */
    public function makePayment(Order $order, Request $request)
    {
        $wayforpay = $this->getGateway();
        $paymentFields = [
            'merchantTransactionSecureType' => 'AUTO',
            'merchantDomainName' => $request->getHost(),
            'orderReference' => $order->getId(),
            'orderDate' => $order->getCreatedAt()->getTimestamp(),
            'amount' => $order->getAmount() / 100,
            'currency' => 'UAH',
            'clientFirstName' => $order->getFirstName(),
            'clientLastName' => $order->getLastName(),
            'clientAddress' => $order->getAddress(),
            'clientEmail' => $order->getEmail(),
            'productName' => [],
            'productPrice' => [],
            'productCount' => [],
            'returnUrl' => $this->generateUrl('payments_done', ['id' => $order->getId()], UrlGeneratorInterface::ABSOLUTE_URL),
        ];

        foreach ($order->getItems() as $index => $orderItem) {
            $paymentFields['productName'][] = $orderItem->getProduct()->getName();
            $paymentFields['productPrice'][] = $orderItem->getPrice() / 100;
            $paymentFields['productCount'][] = $orderItem->getQuantity();
        }

        return $this->render('payments/make_payment.html.twig', [
            'order' => $order,
            'paymentForm' => $wayforpay->buildForm($paymentFields),
        ]);
    }

    /**
     * @Route("/payments/done/{id}", name="payments_done")
     */
    public function done(Order $order, Request $request)
    {
        $wayforpay = $this->getGateway();
        $result = $wayforpay->checkStatus([
            'orderReference' => (string) $order->getId(),
        ]);

        if ($result['transactionStatus'] == 'Approved') {
            $order->setIsPaid(true);

            return $this->render('payments/success.html.twig', [
                'order' => $order,
            ]);
        }

        return $this->render('payments/fail.html.twig', [
            'order' => $order,
            'reason' => $result['reason'],
        ]);
    }

    private function getGateway()
    {
        return new \WayForPay(getenv('WAYFORPAY_ACCOUNT'), getenv('WAYFORPAY_PASSWORD'));
    }

}
