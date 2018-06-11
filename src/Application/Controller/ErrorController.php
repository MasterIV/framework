<?php

namespace Iv\Framework\Application\Controller;
use Iv\Framework\Injection\Annotation\Component;
use Iv\Framework\Injection\Annotation\Inject;
use Iv\Framework\Output\TemplateFactory;
use Iv\Framework\Routing\UserException;

/**
 * Class ErrorController
 * @package Iv\Cms
 * @Component()
 */
class ErrorController {
	/** @var TemplateFactory */
	private $viewFactory;

	/**
	 * ErrorController constructor.
	 * @param $viewFactory
	 * @Inject({"@TemplateFactory"})
	 */
	public function __construct(TemplateFactory $viewFactory) {
		$this->viewFactory = $viewFactory;
	}

	public function notFound() {
		return $this->viewFactory->view('error', [
				'message' => 'The requested path was not found.',
				'details' => ''
		]);
	}

	public function forbidden() {
		return $this->viewFactory->view('error', [
				'message' => 'Access denied.',
				'details' => 'You don\'t have permission to access this page.'
		]);
	}

	public function serverError(\Exception $exception) {
		return $this->viewFactory->view('error', [
				'message' => $exception->getMessage(),
				'details' => $exception->getTraceAsString()
		]);
	}

	public function userError(UserException $exception) {
		return $this->viewFactory->view('error', [
				'message' => $exception->getMessage(),
				'details' => $exception->getTraceAsString()
		]);
	}
}