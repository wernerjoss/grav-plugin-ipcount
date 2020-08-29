# IPcount Plugin

The **IPcount** Plugin is for [Grav CMS](http://github.com/getgrav/grav). It counts the visitors on your Website, excluding Robots/Scanners etc.
It is heavyly inspired by [Grav Plugin Iplocate](https://github.com/Perlkonig/grav-plugin-iplocate)

## Installation

Installing the IPcount plugin can be done in one of two ways. The GPM (Grav Package Manager) installation method enables you to quickly and easily install the plugin with a simple terminal command, while the manual method enables you to do so via a zip file.

### GPM Installation (Preferred, but currently not possible - see Manual Installation)

The simplest way to install this plugin is via the [Grav Package Manager (GPM)](http://learn.getgrav.org/advanced/grav-gpm) through your system's terminal (also called the command line).  From the root of your Grav install type:

    bin/gpm install ipcount

This will install the IPcount plugin into your `/user/plugins` directory within Grav. Its files can be found under `/your/site/grav/user/plugins/ipcount`.

### Manual Installation

To install this plugin, just download the zip version of this repository and unzip it under `/your/site/grav/user/plugins`. Then, rename the folder to `ipcount`. You can find these files on [GitHub](https://github.com/wernerjoss/grav-plugin-ipcount)

You should now have all the plugin files under

    /your/site/grav/user/plugins/ipcount

> NOTE: This plugin is a modular component for Grav which requires [Grav](http://github.com/getgrav/grav) and the [Error](https://github.com/getgrav/grav-plugin-error) and [Problems](https://github.com/getgrav/grav-plugin-problems) to operate.

## Configuration

Here's the default configuration. To override, first copy `ipcount.yaml` from the `plugins/ipcount` folder to your `config/plugins` folder.

```
enabled: true

```

  - `enabled` is used to enable/disable the plugin. There is no way to selectively enable this plugin. Either it is on or off.

## Usage

All you have to do is make sure the plugin is `enabled`.

## Twig Variables

Count data is stored in user://data/counter/counter.txt .
You can use this anywhere on your Website to display via inserting {{ counter() }} in the desired twig File (or page, if twig processing is enabled in frontmatter).
