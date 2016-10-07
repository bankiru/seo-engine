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

## Configuration

## Integrations
