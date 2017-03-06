<?php

namespace Bankiru\Seo\Listener;

use Bankiru\Seo\Exception\MatchingException;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouterInterface;

final class SeoRequestListener
{
    /** @var RouterInterface */
    private $router;
    /**
     * @var SeoRequestInterface
     */
    private $seoRequest;

    /**
     * SeoRequestListener constructor.
     *
     * @param RouterInterface  $router
     * @param MasterSeoRequest $seoRequest
     */
    public function __construct(RouterInterface $router, SeoRequestInterface $seoRequest)
    {
        $this->router     = $router;
        $this->seoRequest = $seoRequest;
    }

    /**
     * @param GetResponseEvent $event
     *
     * @throws \UnexpectedValueException
     * @throws NotFoundHttpException
     * @throws \LogicException
     */
    public function onMasterRequest(KernelEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $request = $event->getRequest();

        $name  = $request->attributes->get('_route');
        $route = $this->router->getRouteCollection()->get($name);
        if (!$route) {
            return;
        }

        $seoOptions = $this->normalizeSeoOptions($route, $name);

        if (true !== $seoOptions['enabled']) {
            return;
        }

        $this->seoRequest->setDestination(
            RequestDestinationFactory::createFromRequest(
                $request,
                $seoOptions['destination']
            )
        );

        if (false === $seoOptions['match']) {
            return;
        }

        try {
            $page = $this->seoRequest->getPage();
        } catch (MatchingException $exception) {
            throw new NotFoundHttpException(null, $exception);
        }

        $request->attributes->set('_seo_page', $page);
    }

    /**
     * @param Route  $route
     * @param string $name
     *
     * @return array
     * @throws \UnexpectedValueException
     */
    private function normalizeSeoOptions(Route $route, $name)
    {
        $seoOptions = $route->getOption('seo');
        if (null === $seoOptions) {
            return ['enabled' => false, 'match' => false, 'destination' => []];
        }
        if (is_bool($seoOptions)) {
            return ['enabled' => $seoOptions, 'match' => true, 'destination' => []];
        }
        if (!is_array($seoOptions)) {
            throw new \UnexpectedValueException(
                sprintf('Seo options should be either boolean or array for route "%s"', $name)
            );
        }

        return array_replace_recursive(
            [
                'enabled'     => true,
                'match'       => true,
                'destination' => [],
            ],
            $seoOptions
        );
    }
}
