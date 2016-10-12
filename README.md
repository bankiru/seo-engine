[![Latest Stable Version](https://poser.pugx.org/bankiru/seo-engine/version)](https://packagist.org/packages/bankiru/seo-engine)
[![Total Downloads](https://poser.pugx.org/bankiru/seo-engine/downloads)](https://packagist.org/packages/bankiru/seo-engine)
[![Latest Unstable Version](https://poser.pugx.org/bankiru/seo-engine/v/unstable)](//packagist.org/packages/bankiru/seo-engine)
[![License](https://poser.pugx.org/bankiru/seo-engine/license)](https://packagist.org/packages/bankiru/seo-engine)

[![Build Status](https://travis-ci.org/bankiru/seo-engine.svg?branch=master)](https://travis-ci.org/bankiru/seo-engine)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/bankiru/seo-engine/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/bankiru/seo-engine/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/bankiru/seo-engine/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/bankiru/seo-engine/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/77fc0eb9-d2ad-48f1-842d-3d626f315024/mini.png)](https://insight.sensiolabs.com/projects/77fc0eb9-d2ad-48f1-842d-3d626f315024)

# Banki.ru SEO Engine

General purpose SEO library for standalone usage (Symfony DI included)

## Purpose

 * Processing general SEO data
 * SEO links generation
 * Sitemap generation
 
## Installation

```sh
composer require bankiru/seo-engine:~1.0
```

## Terminology

### Matching 

* **Destination** &mdash; is a route identifier and set of concrete entities indexed by placeholder codes
* Complete **TargetSpace** &mdash; is a set of all possible destinations for the given route identifier
* **Condition** &mdash; a binary predicate, can return **Destination** weight on successful match
* **TargetSpaceDefinition** &mdash; is a (sub)set of complete **TargetSpace**, defined by a set of **Conditions**

### Generation

* **Source** &mdash; is any countable and iterable source of entities, which could be filtered with **Condition**
* **Filler** &mdash; (in general) function that infers missing values into **Destination** 

## Usage

### Standalone

For general standalone usage you have to implement (or use out-of-the-box static collection implementations)
three services:

* **Destination** - An item to match by SEO engine
* **TargetDefinitionRepositoryInterface** - A source of **TargetSpaces** indexed by routes
* **PageRepositoryInterface** - Matcher of **SeoPageInterface** by matched **TargetSpace** and initial **Destination**

#### Generic flow

```php
// Instantiate TargetRepository
$targetRepository = new StaticTargetRepository();
// Fill it with $targetRepository->add($target);

// Instantiate PageRepository
$pageRepository = new StaticPageRepository();
// Fill page pairs with $pageRepository->add($target, $page);

// Instantiate target sorter
$sorter = new MatchScoreTargetSorter($targetRepository);
// Instantiate matcher

$matcher = new DestinationMatcher($sorter, $pageRepository);
// Create the destination to match
// The general approach is to hook into request processing and create it
// from incoming HTTP request

$destination = new Destination(
    '/blog/articles/123',
    [
        'page_id' => 123,
        'language' => 'en',
        'category' => 'programming'
    ]
);

// Obtain matching SEO page for destination. Or handle a matching exception

try {
  $page = $matcher->match($destination);
} catch (MatchingException $e) {
  // you probably also wan't to set proper headers here
  echo "Not found";
  exit();
}

// Do whatether you want to render $page as HTML response properly.
```

### Symfony integration

This library has built-in integration into symfony request processing flow and DI, so the 
kernel takes the most of the work above for you

```php

public function someAction(Request $request) {
    $destination = RequestDestinationFactory::createFromRequest($requset)
    $matcher = $container->get('bankiru.seo.matcher');
    
    try {
      $page = $matcher->match($destination);
    } catch (MatchingException $e) {
      throw $this->createNotFoundException();
    }
    
    return ['page'=>$page];
}
```

If you define `options: {seo: true}` for your route, then you can obtain SEO page immediately
with following signature

```php

public function someAction(SeoPageInterface $_seo_page)
{
   return ['page'=>$page];
}

```

This will throw an exception for you automatically.

## Configuration

### Routing

Configure route options like following

```yaml
my_route:
    resources: routes.yml
    options:
        seo: true
```

To enable listeners for this route

## Integrations

### Local static matching

To bootstrap data configuration there is a local implementation of 
necessary interface, which allows to start using the library immediately
pre-filling the repositories from init\config code. 

### Doctrine ORM matching

You can implement the necessary interfaces ontop of your entity repositories.
Make sure the entities implement required interfaces (target, condition, etc)

### Link generation

In order to use link generation ability, you have to define two
* Fill source registry with `SourceInterface` entities indexed by alias
* Create Link compiler which can forge a url using the route identifier from link and the destination items as `Sluggable`s
 
As a part of Symfony integration where is the `SymfonyRouterCompiler` 
which uses the `UrlGenerator` to compile the link reference

## Extensions

You can override, decorate and replace the following extension points to tune 
your SEO processing experience

### Matching

* `TargetRepositoryInterface` - finds the all matching targets for given route
* `TargetSorter` - chooses single target from all fetched above by matching with destination
* `PageRepositoryInterface` - finds the SEO page for target and destination

### Generation

* `DestinationNormalizer` - converts your entity into string for slug generation. `SluggableNormalizer` normalizer is the primary option for objects, `ScalarNormalizer` is used for all the scalars
* `DestinationCompiler` - forges your destination into the link (href, title and attributes). `SymfonyRouterCompiler` is the primary default option if symfony available
* `SourceInterface` - source of the data to create destinations. No default option, `CollectionSource` is the built-in one
* `SourceFiller` - extend missing entries in destination from source-generated values. Not required, thus no defaults

