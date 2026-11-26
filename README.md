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
| `austral/admin-bundle` | `config/packages/austral_admin.yaml`, `config/routes/austral_admin.yaml` (firewall to add by hand) |
| `austral/entity-file-bundle` | `config/packages/austral_entity_file.yaml`, `config/routes/austral_entity_file.yaml` |
| `austral/http-bundle` | `config/packages/austral_http.yaml` |
| `austral/cache-bundle` | `config/packages/austral_cache.yaml` |
| `austral/elastic-search-bundle` | `config/packages/austral_elastic_search.yaml`, `ELASTICSEARCH_URL` in `.env` |
| `austral/security-bundle` | `config/packages/austral_security.yaml`, the entities `User`, `Role`, `Group` |

The firewalls stay in `config/packages/security.yaml`: Symfony refuses new firewalls in a second file, so
the recipes of security-bundle and admin-bundle display the firewall to add after the installation.

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

The `master` branch holds the sources; the `flex/master` branch holds the files read by Flex (`index.json`, one
JSON file per recipe and `archived/`), generated with [recipes-checker](https://github.com/symfony-tools/recipes-checker).
Nobody edits `flex/master` by hand.

Once, install recipes-checker in `/tmp/recipes-checker`:

```bash
git clone https://github.com/symfony-tools/recipes-checker /tmp/recipes-checker
composer install --working-dir=/tmp/recipes-checker --no-dev
```

After a push on `master`, from the clone of this repository (on `master`, nothing to commit):

```bash
# 1. open flex/master in a separate folder (the clone stays on master)
git fetch origin
git worktree add /tmp/austral-flex flex/master

# 2. generate into it: the previous files of archived/ are kept, "composer recipes:update" needs them
git ls-tree HEAD */*/* | /tmp/recipes-checker/run generate:flex-endpoint austral-project/recipes master flex/master /tmp/austral-flex/

# 3. check, commit and push flex/master
cd /tmp/austral-flex
git status    # index.json, the JSON file of the changed recipes, new files in archived/
git add -A && git commit -m "chore: generate the Flex endpoint" && git push origin flex/master

# 4. remove the folder
cd - && git worktree remove /tmp/austral-flex
```

Without PHP, the installation and step 2 in Docker (clone first: Docker creates an empty folder owned by root for
a missing volume):

```bash
git clone https://github.com/symfony-tools/recipes-checker /tmp/recipes-checker
docker run --rm -u "$(id -u):$(id -g)" -e HOME=/tmp -v /tmp/recipes-checker:/checker \
  composer:2 install --working-dir=/checker --no-dev

docker run --rm -u "$(id -u):$(id -g)" -e HOME=/tmp \
  -v "$PWD":/recipes -v /tmp/recipes-checker:/checker -v /tmp/austral-flex:/out \
  -w /recipes --entrypoint sh composer:2 -c '
    git config --global --add safe.directory /recipes &&
    git ls-tree HEAD */*/* | php /checker/run generate:flex-endpoint austral-project/recipes master flex/master /out/'
```

Check in a project: `composer recipes austral/<bundle>`.

## Versions

One branch holds every version: the version of a recipe is a folder (`4.0/`, then `4.1/` when the files to copy
change). Flex uses the folder of the highest version lower than or equal to the installed version of the
bundle.
