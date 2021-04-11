# IPcount Plugin

The **IPcount** Plugin is for [Grav CMS](http://github.com/getgrav/grav). It counts the visitors on your Website, excluding Robots/Scanners etc.
It is inspired by [Grav Plugin Iplocate](https://github.com/Perlkonig/grav-plugin-iplocate)

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

> NOTE: This plugin requires [Grav](http://github.com/getgrav/grav) and the [Error](https://github.com/getgrav/grav-plugin-error) and [Problems](https://github.com/getgrav/grav-plugin-problems) to operate.

## Configuration

Here's the default configuration. To override, first copy `ipcount.yaml` from the `plugins/ipcount` folder to your `config/plugins` folder.

```
enabled: true
```

`enabled` is used to enable/disable the plugin. There is no way to selectively enable this plugin. Either it is on or off.

## Usage

All you have to do is make sure the plugin is `enabled`.

## Twig Variables

Count data is stored in user://data/counter/counter.txt  - this is true up to Version 1.1.0.  
From Version 1.2.1, Data File is user://data/counter/counter.json, so, if you are updating from V 1.1.x to 1.2.x, be sure to just copy your old count data from counter.txt to counter.json ! - a sample counter.json looks like this:
```
{"count":123456}

```
so all you have to do is copy the correct number from counter.txt to counter.json :-)  

You can use the shortcode anywhere on your Website to display cumulated count datas via inserting {{ counter() }} in the desired twig File (or page, if twig processing is enabled in frontmatter).

## Data Visualisation:  
Now that dayly count data is also stored, there should be the possibility to show this in a handy graphical representation, and here it is:  
The Plugin comes with all you need for a Bar Chart that shows the dayly count data in a diagram.  
All you need to do is copy the provided template visitors.html.twig from the plugin's templates Directory to the templates Directory of your Theme.  
Then you can create a page where the Diagram will be shown. Just be sure to use the Template visitors for the page, and use the following code in the page Frontmatter:
```
title: Stats (or anything else you like...)
datafile: counter.json
cache_enable: false
process:
    markdown: true
    twig: true
never_cache_twig: true

```
![](https://github.com/wernerjoss/grav-plugin-ipcount/blob/master/ksnip_20210130-213851.png)

## Additional Notes:
As of V 1.3.5, a Utility Script CountRotate.php is added in Folder cron which can be used to keep the Count Data File at a reasonable small size, without losing old Data - see comments in the File for Usage.

## Upgrade Notice:
If you are upgrading from a Version <= 1.3.1, be sure to also update (= copy from Plugin templates Directory to your theme templates Directory) the file visitors.html.twig in your theme folder !

