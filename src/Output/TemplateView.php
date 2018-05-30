<?php


namespace Iv\Framework\Output;


use Iv\Framework\Routing\Response;

class TemplateView implements Response {
	/** @var array */
	private $data;
	/** @var \Twig_TemplateWrapper */
	private $template;

	/**
	 * TemplateView constructor.
	 * @param $data
	 * @param $template
	 */
	public function __construct(\Twig_TemplateWrapper $template, $data = []) {
		$this->data = $data;
		$this->template = $template;
	}

	public function send() {
		$this->template->display($this->data);
	}
}