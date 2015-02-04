# Morphdown v0.1

***A simple editor for Markdown documents with PHP at it's backend***

Mor**ph**down is a fork of [Morkdown](https://github.com/rvagg/morkdown): beautiful GitHub Flavoured Markdown editor. Morphdown renders [CommonMark Markdown](http://commonmark.org/) files with PHP.

Markdown content is parsed using [The PHP League CommonMark implementation](https://github.com/thephpleague/commonmark). Code syntax highlighting is not supported currently.

Morphdown is a **Google Chrome App** coupled to a PHP built-in server and uses [CodeMirror](http://codemirror.net) for the editor panel.

Morphdown will **automatically save** your document as you edit it.

## Installing & Using

You'll need Google Chrome of course, plus you'll a need `php-cli` interpreter on your system to start PHP web-server &mdash; which should be fine for Linux and Mac users. Getting it running on Windows might be a little tricky (but presumably not impossible!).

```
# clone project to your hard drive
mkdir -p ~/bin/morphdown-master
git clone https://github.com/e1himself/morphdown ~/bin/morphdown-master
cd ~/bin/morphdown-master

# install composer
curl -sS https://getcomposer.org/installer | php
php composer.phar install

# create a link to bin/morphdown somewhere in your PATH (/usr/bin will be fine)
ln bin/morphdown /usr/bin/morphdown
```

Once installed you can simply run the **`morphdown <path to file.md>`** command and you're away!

## Motivation

There are plenty of Markdown editors available on the web. Most of them are online. There some editors that work in a desktop environment and can edit local files. They're mostly not really hackable (i.e. one cannot easily adopt it for his needs). 

[Morkdown](https://github.com/rvagg/morkdown) is really beautiful exception of that: it uses javascript and is easily hackable. I (as a PHP developer) wanted to have the same editor but with PHP

## Under the hood

1. [Morkdown](https://github.com/rvagg/morkdown) frontend
2. [The PHP League CommonMark implementation](https://github.com/thephpleague/commonmark)
3. PHP Built-in web server
4. [Silex](https://github.com/silexphp/Silex) app as router
5. Starter script implemeted in [GO](https://golang.org/) 

## Roadmap

* Initial implementation: **done**
* Platform support: Ubuntu Linux: **done**
* Build and installation scripts
* Downloadable all-in-one package (phar archive or GO application) with external PHP
* Embedded PHP
* Platform support: MacOS, Windows

## FAQ

1. Why use GO for starter script?
   
   I tried to [use PHP to start a web server and a browser in parallel](https://gist.github.com/e1himself/49f03029a43bfd0b1b14). It is possible. But also it is needed to stop a server when browser window is closed. No luck here. I tried [pcntl](http://php.net/manual/ru/book.pcntl.php) extension functionality, [Spork](https://github.com/kriswallsmith/spork) wrapper implementation: nothing helped. After closing the browser, server continued to run. I'd be glad to see working solution if you know how to code it.

2. Why implement it with PHP at backend?

	Yes, PHP is not really suitable for this case, but I needed to guarantee that Markdown file will be rendered exactly the same way as my web-app will do that. Also I plan to extend Markdown format with my own functionality: it's better to do it once. In PHP in my case.
  
3. Why run it on desktop?

   To allow edit local files without messing around upload/download buttons, Dropboxes etc.


## Licence

MIT