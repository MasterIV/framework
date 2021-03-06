<?php


namespace Iv\Framework\Output;

use Iv\Framework\Files;
use Iv\Framework\Injection\Annotation\Component;

/** @Component() */
class TemplateFactory {
	private $loader;
	private $twig;

	/**
	 * TemplateFactory constructor.
	 */
	public function __construct() {
		$this->loader = new \Twig_Loader_Filesystem([
				Files::ROOT . 'assets/views',
				Files::ROOT . 'assets/tpl'
		]);

		$this->twig = new \Twig_Environment($this->loader, []);
		$this->twig->addFilter(new \Twig_SimpleFilter('ucfirst', 'ucfirst'));
		$this->twig->addFunction(new \Twig_SimpleFunction('path', function($location) {
			return defined('HTTP_ROOT') && $location[0] == '/' ? HTTP_ROOT.$location : $location;
		}));
	}

	public function addPath($path) {
		$this->loader->prependPath($path);
	}

	public function addGlobal($name, $value) {
		$this->twig->addGlobal($name, $value);
	}

	/**
	 * @param $name
	 * @return \Twig_TemplateWrapper
	 */
	public function template($name) {
		return $this->twig->load($name.'.twig');
	}

	public function view($name, $data = []) {
		return new TemplateView($this->template($name), $data);
	}
}