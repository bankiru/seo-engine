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
