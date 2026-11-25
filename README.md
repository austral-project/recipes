# Austral recipes

Symfony Flex recipes of the [Austral](https://github.com/austral-project) bundles: on `composer require`,
Flex registers the bundle and copies its configuration and the entities of the application into the project.

Every bundle works without its recipe: a recipe only copies what the project owns (entities, configuration to
adapt, routes, environment variables). The default values stay in the bundles.

## Use the recipes in a project

Add the endpoint to the `composer.json` of the project:

```json
"extra": {
    "symfony": {
        "endpoint": [
            "https://api.github.com/repos/austral-project/recipes/contents/index.json?ref=flex/master",
            "flex://defaults"
        ]
    }
}
```

Then `composer require austral/security-bundle`. For a bundle already installed:
`composer recipes:install austral/security-bundle --force`, and `composer recipes:update` after a change of its
recipe.

## Recipes

| Package | Copies |
|---|---|
| `austral/entity-file-bundle` | `config/packages/austral_entity_file.yaml`, `config/routes/austral_entity_file.yaml` |
| `austral/http-bundle` | `config/packages/austral_http.yaml` |
| `austral/cache-bundle` | `config/packages/austral_cache.yaml` |
| `austral/elastic-search-bundle` | `config/packages/austral_elastic_search.yaml`, `ELASTICSEARCH_URL` in `.env` |
| `austral/security-bundle` | `config/packages/austral_security.yaml`, the entities `User`, `Role`, `Group` |

The bundles that have nothing to copy (tools, entity, entity-translate, form, list, filter) have no recipe: Flex
registers a Symfony bundle without a recipe by itself, and recipes-checker refuses a recipe that only does that.

## Structure

```
austral/
  security-bundle/
    4.0/
      manifest.json         # configurators: bundles, copy-from-recipe, env...
      post-install.txt      # message displayed after the installation
      config/               # copied into config/
      src/                  # copied into src/
```

A file of the recipe is not copied when the project already has it.

## Publish

The `master` branch holds the sources; the `flex/master` branch holds the files read by Flex (`index.json` and one
JSON file per recipe), generated with [recipes-checker](https://github.com/symfony-tools/recipes-checker):

```bash
git ls-tree HEAD */*/* | /path/to/recipes-checker/run generate:flex-endpoint austral-project/recipes master flex/master ./output/
```

Then commit the content of `./output/` on `flex/master`. Nobody edits `flex/master` by hand.

## Versions

One branch holds every version: the version of a recipe is a folder (`4.0/`, then `4.1/` when the files to copy
change). Flex uses the folder of the highest version lower than or equal to the installed version of the
bundle.
