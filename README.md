# Homepage
My startpage that I will use to read the latest news delivered by my
favorite news sources from the Netherlands and other countries.

# Compatible RSS Feeds
- Artiesten Nieuws
- Dumpert
- GamersNET
- Geenstijl
- iDownload Blog
- Major Nelson
- Neowin
- NuTech
- RTL Nieuws

# Installation
Use the config.php.dist file in the app directory to create your config
file. You need to set wich of the above feeds need to be imported, and
you need to set a username and password for your homepage to open.
This is to prevent the "Has seen" from being set when other people visit
the url.

For the weather widgets to work, you need an OpenWeather API key that
can be set in the config file. 

# Set cronjob to update news
To make sure your homepage is being updated with the latest and all
news, make sure to enable a cronjob to open up /update/ every 10 minutes
or so. Depending on your liking.

# Mobile support
This homepage can also be used as a homepage for mobile phones. However
since the content of pages are displayed within an iframe, a few items
are hidden because of security restrictions that prevent cross-site
scripting and such.

# Ignore X-Frame headers
For Google Chrome users, it's "recommended" to install Ignore X-Frame
headers extension. This because one of the sites blocks the site from
being viewed within an iFrame. Using this extension you can overide the
restriction. However, there may be security concerns when enabling this
extension, so please use at your own risk!
