# HiFile API

**HiFile file server API**

[![Latest Stable Version](https://poser.pugx.org/hiqdev/hifile-api/v/stable)](https://packagist.org/packages/hiqdev/hifile-api)
[![Total Downloads](https://poser.pugx.org/hiqdev/hifile-api/downloads)](https://packagist.org/packages/hiqdev/hifile-api)
[![Build Status](https://img.shields.io/travis/hiqdev/hifile-api.svg)](https://travis-ci.org/hiqdev/hifile-api)
[![Scrutinizer Code Coverage](https://img.shields.io/scrutinizer/coverage/g/hiqdev/hifile-api.svg)](https://scrutinizer-ci.com/g/hiqdev/hifile-api/)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/hiqdev/hifile-api.svg)](https://scrutinizer-ci.com/g/hiqdev/hifile-api/)
[![Dependency Status](https://www.versioneye.com/php/hiqdev:hifile-api/dev-master/badge.svg)](https://www.versioneye.com/php/hiqdev:hifile-api/dev-master)

## Installation

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

## License

This project is released under the terms of the BSD-3-Clause [license](LICENSE).
Read more [here](http://choosealicense.com/licenses/bsd-3-clause).

Copyright © 2018, HiQDev (http://hiqdev.com/)
