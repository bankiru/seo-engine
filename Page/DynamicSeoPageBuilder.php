<?php

namespace Bankiru\Seo\Page;

class DynamicSeoPageBuilder implements PageBuilder
{
    /** @var  array */
    protected $context = [];
    /** @var \Twig_Environment */
    protected $twig;
    /** @var  SeoPageBuilder */
    protected $builder;

    /**
     * DynamicSeoPageBuilder constructor.
     *
     * @param \Twig_Environment $twig
     * @param SeoPageBuilder    $builder
     */
    public function __construct(\Twig_Environment $twig = null, SeoPageBuilder $builder = null)
    {
        $this->twig    = $twig ?: new \Twig_Environment(new \Twig_Loader_Array([]));
        $this->builder = $builder ?: new SeoPageBuilder();
    }

    /**
     * @param array $context
     *
     * @return $this
     */
    public function setContext(array $context)
    {
        $this->context = $context;

        return $this;
    }

    /** {@inheritdoc} */
    public function getSeoPage()
    {
        $builder = new SeoPageBuilder();
        $this->build($builder);

        return $builder->getSeoPage();
    }

    /** {@inheritdoc} */
    public function setMeta(array $meta)
    {
        $this->builder->setMeta($meta);

        return $this;
    }

    /** {@inheritdoc} */
    public function setTitle($title)
    {
        $this->builder->setTitle($title);

        return $this;
    }

    /** {@inheritdoc} */
    public function setCanonicalLink($canonical = null)
    {
        $this->builder->setCanonicalLink($canonical);

        return $this;
    }

    /** {@inheritdoc} */
    public function setHtmlAttributes(array $htmlAttributes)
    {
        $this->builder->setHtmlAttributes($htmlAttributes);

        return $this;
    }

    /** {@inheritdoc} */
    public function setBreadcrumbs(array $breadcrumbs)
    {
        $this->builder->setBreadcrumbs($breadcrumbs);

        return $this;
    }

    public static function create()
    {
        return new static();
    }

    /**
     * @param PageBuilder $builder
     */
    protected function build(PageBuilder $builder)
    {
        $builder->setBreadcrumbs(array_map([$this, 'render'], $this->builder->getBreadcrumbs()));
        $builder->setCanonicalLink($this->render($this->builder->getCanonicalLink()));
        $builder->setTitle($this->render($this->builder->getTitle()));
        $builder->setHtmlAttributes(array_map([$this, 'render'], $this->builder->getHtmlAttributes()));
        $builder->setMeta(array_map([$this, 'renderMeta'], $this->builder->getMeta()));
    }

    protected function render($string)
    {
        return $this->twig->createTemplate((string)$string)->render($this->context);
    }

    protected function renderMeta(HtmlMetaInterface $meta)
    {
        if (null !== $meta->getName()) {
            return HtmlMeta::createNameMeta(
                $meta->getName(),
                $this->render($meta->getContent()),
                $meta->getAttributes()
            );
        }

        return HtmlMeta::createHttpEquivMeta(
            $meta->getHttpEquiv(),
            $this->render($meta->getContent()),
            $meta->getAttributes()
        );
    }
}
