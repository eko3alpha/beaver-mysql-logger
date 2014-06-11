<?php

namespace KZ\controller;
use KZ\app\interfaces\Registry;

/**
 * Class Front
 * @package KZ\controller
 */
class Front
{
	/**
	 * This is an Abstract Factory for Controllers instance.
	 *
	 * @var Kit
	 */
	protected $controllerKit;

	/**
	 * @var Registry
	 */
	protected $registry;

	/**
	 * @var Chain
	 */
	protected $controllerChain;

//отнаследовать от registry, сделать toString, toJson, менейдж headers.
	protected $response;

	/**
	 * @param Kit $controllerKit
	 * @param Registry $registry
	 */
	public function __construct(Kit $controllerKit, Registry $registry)
	{
		$this->controllerKit = $controllerKit;
		$this->registry = $registry;
	}

	/**
	 * Set up internal properties.
	 */
	public function init()
	{
		if ($this->controllerChain)
			return;

		$this->makeControllerChain();
	}

	/**
	 * Run controller chain.
	 */
	public function run()
	{
		$this->init();

		foreach ($this->controllerChain as $item)
			$item['instance']->{$item['action']}();
	}

	/**
	 * @return Chain
	 */
	public function getControllerChain()
	{
		return $this->controllerChain;
	}

	/**
	 * Add controller in controller chain after current controller.
	 *
	 * @param $controllerPath
	 * @param $controller
	 * @param $action
	 * @return $this
	 * @throws \RuntimeException
	 */
	public function forward($controllerPath, $controller, $action)
	{
		if (!$this->controllerChain)
			throw new \RuntimeException('You must call init to setup controllerChain before calling this method!');

		$controller = $this->makeController(
			$controllerPath, $controller, $action
		);

		$this->controllerChain->pushAfterCurrent($controller['instance'], $controller['action']);

		return $this;
	}

	/**
	 * @return Request
	 */
	public function getRequest()
	{
		return $this->registry->getRequest();
	}

	/**
	 * Make controller instance.
	 *
	 * @param $controllerPath
	 * @param $controller
	 * @param $action
	 * @return array
	 */
	public function makeController($controllerPath, $controller, $action)
	{
		$controller = $this->controllerKit->makeController(
			$controllerPath,
			$controller,
			$action
		);

		return [
			'instance' => $controller,
			'action' => $action
		];
	}

	/**
	 * Make controller chain by request.
	 */
	protected function makeControllerChain()
	{
		$controller = $this->makeController(
			$this->getRequest()->getControllerPath(),
			$this->getRequest()->getController(),
			$this->getRequest()->getAction()
		);

		$this->controllerChain = $this->registry->getKit()->makeControllerChain();
		$this->controllerChain->push($controller['instance'], $controller['action']);
	}
} 