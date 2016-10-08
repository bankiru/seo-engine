<?php

namespace Bankiru\Seo\Tests\Bundle;

use Bankiru\Seo\Entity\TargetDefinition;
use Bankiru\Seo\Exception\MatchingException;
use Bankiru\Seo\Integration\Local\StaticPageRepository;
use Bankiru\Seo\Integration\Local\StaticTargetRepository;
use Bankiru\Seo\Page\SeoPageBuilder;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SeoPageTest extends WebTestCase
{
    protected static function getKernelClass()
    {
        return SeoKernel::class;
    }

    public function getRoutes()
    {
        return [
            'event match'    => ['event_controller_match', 'test/match', 'Title'],
            'event no match' => ['event_controller_match', 'test/no_match', false],
            'event no seo'   => ['event_controller_match', 'test/no_seo', 'not_found'],
        ];
    }

    /**
     * @dataProvider getRoutes
     *
     * @param string       $route
     * @param string       $path
     * @param string|false $response
     */
    public function testControllerEvents($route, $path, $response)
    {
        $client    = static::createClient();
        $container = static::$kernel->getContainer();

        $target = new TargetDefinition($route);
        $page   = (new SeoPageBuilder())->setTitle('Title')->getSeoPage();

        $targetRepo = new StaticTargetRepository();
        $pageRepo   = new StaticPageRepository();
        $container->set('bankiru.seo.target_repository', $targetRepo);
        $container->set('bankiru.seo.page_repository', $pageRepo);

        $targetRepo->add($target);
        $pageRepo->add($target, $page);

        try {
            $client->request('GET', $path);
            $serverResponse = $client->getResponse()->getContent();

            if (false === $response) {
                self::fail(sprintf('Should fail with match exception. Received "%s"', $serverResponse));
            }

            self::assertSame($response, $serverResponse);
        } catch (NotFoundHttpException $exception) {
            if (false !== $response) {
                throw $exception;
            }
        }
    }

    protected function setUp()
    {
        self::bootKernel();
    }
}
