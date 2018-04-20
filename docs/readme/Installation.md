
Find your root project: `transmedia/file.api.screens.media` in my case.

Fetch the root project with git and install it with composer:

```sh
git clone git@git.hiqdev.com:transmedia/file.api.screens.media
cd file.api.screens.media
composer install
```

Setup environment variables: copy and tune `.env.example` file.
There aren't many options there, check and set all of them thouroughly.

```sh
cp .env.example .env
vim .env
```

After changing environment variables refresh config with:

```sh
composer dump
```
