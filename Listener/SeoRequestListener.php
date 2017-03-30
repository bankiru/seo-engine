<?php

namespace Bankiru\Seo\Listener;

use Bankiru\Seo\Exception\MatchingException;
use Symfony\Bundle\FrameworkBundle\Controller\RedirectController;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class SeoRequestListener
{
    /**
     * @var SeoRequestInterface
     */
    private $seoRequest;

    /**
     * SeoRequestListener constructor.
     *
     * @param SeoRequestInterface $seoRequest
     */
    public function __construct(SeoRequestInterface $seoRequest)
    {

        $this->seoRequest = $seoRequest;
    }

    /**
     * @param FilterControllerEvent $event
     *
     * @throws \UnexpectedValueException
     * @throws NotFoundHttpException
     * @throws \LogicException
     */
    public function onMasterRequest(FilterControllerEvent $event)
    {
        if (!$event->isMasterRequest()) {
            // do not resolve seo on subrequests
            return;
        }

        $controller = $event->getController();
        if (is_array($controller) && $controller[0] instanceof RedirectController) {
            // do not resolve seo on redirects
            return;
        }

        $request = $event->getRequest();

        $options    = $request->attributes->get('_seo_options', false);
        $seoOptions = $this->normalizeSeoOptions($options);
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
     * @param mixed $options
     *
     * @return array
     * @throws \UnexpectedValueException
     */
    private function normalizeSeoOptions($options)
    {
        if (null === $options) {
            return ['enabled' => false, 'match' => false, 'destination' => []];
        }
        if (is_bool($options)) {
            return ['enabled' => $options, 'match' => true, 'destination' => []];
        }
        if (!is_array($options)) {
            throw new \UnexpectedValueException('Seo options should be boolean, null or array');
        }

        return array_replace_recursive(
            [
                'enabled'     => true,
                'match'       => true,
                'destination' => [],
            ],
            $options
        );
    }
}
