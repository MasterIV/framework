<?php


namespace Iv\Framework\Authentication\Controller;

use Iv\Framework\Authentication\AuthenticationService;
use Iv\Framework\Authentication\RegistrationException;
use Iv\Framework\Authentication\SessionAuthFilter;
use Iv\Framework\Output\Redirect;
use Iv\Framework\Output\TemplateFactory;

class AuthenticationController {
	/** @var TemplateFactory */
	private $viewFactory;
	/** @var AuthenticationService */
	private $service;
	/** @var SessionAuthFilter */
	private $filter;

	/**
	 * AuthenticationController constructor.
	 * @param TemplateFactory $viewFactory
	 * @param AuthenticationService $service
	 * @param SessionAuthFilter $filter
	 */
	public function __construct(TemplateFactory $viewFactory, AuthenticationService $service, SessionAuthFilter $filter) {
		$this->viewFactory = $viewFactory;
		$this->service = $service;
		$this->filter = $filter;
	}

	public function login($name, $pass, $relogin) {
		if($this->filter->login($name, $pass, $relogin))
			return new Redirect('/');

		return $this->viewFactory->view('login', [
				'title' => '[Page]',
				'name' => $name,
				'error' => empty($name) ? '' : 'Invalid credentials.',
		]);
	}

	public function register($name, $pass, $repeat, $email) {
		$errors = [];

		if(!empty($name))
			try {
				$this->service->register($name, $pass, $repeat, $email);
				$this->filter->login($name, $pass, true);
				return new Redirect('/');
			} catch( RegistrationException $e ) {
				$errors = $e->getErrors();
			}

		return $this->viewFactory->view('registration', [
				'title' => '[Page]',
				'name' => $name,
				'email' => $email,
				'errors' => $errors
		]);
	}
}