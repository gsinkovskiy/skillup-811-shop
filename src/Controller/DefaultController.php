<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController
{

	/**
	 * @Route("/", name="homepage")
	 */
	public function index()
	{
		return new Response('<h1>Hello, world!</h1>');
	}

	/**
	 * @Route("/about", name="about")
	 */
	public function about()
	{
		return new Response('<h1>About us</h1>');
	}

}
