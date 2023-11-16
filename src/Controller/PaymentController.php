<?php

namespace App\Controller;

use App\DTO\PriceRequestDTO;
use App\DTO\PurchaseRequestDTO;
use App\Entity\Coupon;
use App\Entity\Product;
use App\Repository\CouponRepository;
use App\Repository\ProductRepository;
use App\Service\PaymentService;
use App\Service\PriceHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PaymentController extends AbstractController
{
    public function __construct(
        private ProductRepository  $productRepository,
        private CouponRepository   $couponRepository,
        private PaymentService     $paymentService,
        private ValidatorInterface $validator,
        private PriceHelper        $priceService
    ) {}

    #[Route("/calculate-price", methods: ["GET"])]
    public function calculatePrice(Request $request)
    {
        $requestDTO = PriceRequestDTO::fromJSON($request->getContent());

        $errors = $this->validator->validate($requestDTO);
        if (count($errors) > 0) {
            return new JsonResponse(['error' => $errors->get(0)->getMessage()], 400);
        }

        /** @var Product $product */
        $product = $this->productRepository->find($requestDTO->getProductId());
        /** @var Coupon $coupon */
        $coupon = $this->couponRepository->findOneBy(['code' => $requestDTO->getCouponCode()]);

        $price = $this->priceService->calculateTotalPrice(
            $product->getPrice(),
            $requestDTO->getTaxNumber(),
            $coupon?->getType(),
            $coupon?->getDiscount()
        );

        return new JsonResponse(['result' => 'success', 'price' => $price]);
    }

    #[Route("/purchase", methods: ["POST"])]
    public function purchase(Request $request)
    {
        $requestDTO = PurchaseRequestDTO::fromJSON($request->getContent());

        $errors = $this->validator->validate($requestDTO);
        if (count($errors) > 0) {
            return new JsonResponse(['error' => $errors->get(0)->getMessage()], 400);
        }

        /** @var Product $product */
        $product = $this->productRepository->find($requestDTO->getProductId());
        /** @var Coupon $coupon */
        $coupon = $this->couponRepository->findOneBy(['code' => $requestDTO->getCouponCode()]);

        $price = $this->priceService->calculateTotalPrice(
            $product->getPrice(),
            $requestDTO->getTaxNumber(),
            $coupon?->getType(),
            $coupon?->getDiscount()
        );

        $result = $this->paymentService->makePayment($price, $requestDTO->getPaymentProcessor());

        return new JsonResponse(['result' => $result ? 'success' : 'fail']);
    }
}