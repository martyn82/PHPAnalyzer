PHPAnalyzer
===========

[![Build Status](https://travis-ci.org/martyn82/PHPAnalyzer.png?branch=master)](https://travis-ci.org/martyn82/PHPAnalyzer)

Dependencies
------------

[PHP 5.4+](http://www.php.net)

[Apache web server](http://www.apache.org)

[Composer dependency manager](http://getcomposer.org)

[Nikic PHP-Parser](https://github.com/nikic/PHP-Parser)

Installation
------------

Installing the tool is simple:

1. Clone the repository.
2. Run ```$ composer install``` in the root.

You can setup an Apache virtual host to enable the web interface. I will add a sample configuration file for you to use.

How to use
----------

Command-line interface
The executable ```analyze``` is the one you need to analyze a PHP program.

You can specify what and how to analyze in a configuration INI file:
```
[project]
key = demo
name = Demo Project
path = /var/www/demo/library

[analysis]
extensions = php

[system]
memory = 200M
```

The ```project``` section defines meta information about the project to analyze. Define a key, a display name and a root path.
Under ```analysis``` you must specify the file extensions of the files to analyze. Currently, only 'php' is supported.
```system``` defines some system-related settings. Memory is the one that is in use at the moment. If you run into out-of-memory issues while analyzing a project, you should increase this.
Supported formats: \d+(M|G) ; e.g., 512M or 2G.

Example usage:

```$ ./analyze -c config.ini```

You can use the '-h' option for a list of available arguments.

What it does
------------

The analyzer tool will analyze your PHP program by extracting these code facts:
* Volume facts: total lines, total lines of code, total lines of comments, total blank lines.
* Entity facts: total number of files, packages, classes, and methods.
* Unit facts: unit sizes (i.e., lines per method).
* Complexity measures: cyclomatic complexity per method.
* Code clones: the number of clones, total lines of duplications.

It then analyzes these facts:
* Partitions your methods into risk levels of complexity: low, moderate, high, or very high.
* What percentage of your code is complex.
* Partitions your methods into size categories: small, medium, large, very large.
* What percentage of your code is large.
* What percentage of your code is duplicated.

Why is this important?
----------------------

It is generally believed that the larger programs become, the more difficult it is to maintain. If you can manage to keep
your code clear by dividing it up into modules and separate methods, then maintaining the program easier than when you
end up in a big ball of mud.

If you keep your methods small and not too complex, then maintaining your code is easier. If, on the other hand, your
code has mostly very large methods, very complex methods, and a lot of duplications, then you will have a hard time
maintaining it with more errors as a result.

This tool aims to give you insight in the maintainability of your program. You will be able to see which parts of your
program needs more attention or even need refactoring to keep it simple.
