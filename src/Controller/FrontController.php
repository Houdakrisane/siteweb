<?php

namespace App\Controller;

use App\Entity\Menu;
use App\Entity\Order;
use App\Form\OrderType;
use App\Repository\MenuRepository;
use App\Repository\OrderRepository;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    #[Route('/', name: 'app_front')]
    public function index(MenuRepository $menuRepository, CartService $cartService): Response
    {
        $cart = $cartService->getCart();

        return $this->render('menu/front.html.twig', [
            'menus' => $menuRepository->findAll(),
            'cart' => $cart,

        ]);
    }
    #[Route('/cart', name: 'app_front_cart')]
    public function cart(Request $request,MenuRepository $menuRepository, CartService $cartService,OrderRepository $orderRepository): Response
    {
        $cart = $cartService->getCart();
        $order = new Order();
        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $orderRepository->save($order, true);

            return $this->redirectToRoute('app_order_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('menu/cart.html.twig', [
            'menus' => $menuRepository->findAll(),
            'total' => $cartService->getTotalCartPrice(),
            'order' => $order,
            'form' => $form,
            'cart' => $cart,

        ]);
    }
    #[Route('/add-to-cart/{id}', name: 'add_to_cart',methods:"GET") ]
    public function add(MenuRepository $menuRepository, CartService $cartService,Menu $menu): Response
    {
        // Get the quantity from the request if necessary
        $quantity = 1;
        $cart = $cartService->getCart();

        // Add the menu item to the cart
        $cartService->addToCart($menu, $quantity);
        return $this->redirectToRoute('app_front',  [
            'menus' => $menuRepository->findAll(),

            'cart' => $cart,

        ]);


    }
    #[Route('/get-cart-data', name: 'get_cart_data',methods:"GET")]
    public function getCartData(CartService $cartService)
    {
        $cartData = $cartService->getCart();

        // You can format the cart data as needed before returning it
        // For example, you can calculate the total price, etc.

        return new JsonResponse($cartData);
    }

    #[Route('/get-cart-item-count', name: "get_cart_item_count",methods:"GET")]

    public function getCartItemCount(CartService $cartService): JsonResponse
    {
        $cartData = $cartService->getCart();
        $itemCount = count($cartData);

        return $this->json(['itemCount' => $itemCount]);
    }

    #[Route('/remove-from-cart/{id}', name: 'remove_from_cart', methods: ["POST"])]
    public function removeFromCart(CartService $cartService, Menu $menu)
    {
        $cartService->removeFromCart($menu);

        return new JsonResponse(['message' => 'Item removed from cart successfully']);
    }
    #[Route('/update-cart-item/{id}', name: 'update_cart_item', methods: ["POST"])]
    public function updateCartItem(Request $request, CartService $cartService, Menu $menu)
    {
        $quantity = $request->request->getInt('quantity', 1);
        $cartService->updateCartItemQuantity($menu, $quantity);

        return new JsonResponse(['message' => 'Cart item quantity updated successfully']);
    }
}
