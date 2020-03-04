# Setup:

## Setup for Websites (using Plugin) 
- Install the Plugin like you would any other composer dependency (you will need access to the private repo for now)

### ⚠️ Warning
if your server is using nginx instead of appache you will need to prevent access to the consent log files located under [/wp-content/uploads/towa-gdpr/consents/](/wp-content/uploads/towa-gdpr/consents/)

## Setup for development (developing the plugin itself)
 1. set up your Wordpress version how you like. The only requirement is that it has acf pro installed and activated.
 2. `cd` into the plugins folder
 3. git clone the project. name the folder `towa-gdpr-plugin`
 4. change `.env` path to where your installation url is (this is for browsersync only)
 5. run `composer install` to install php dependencies
 6. run the Frontend task you need:
	- `npm run watch` to use browsersync version
	- `npm run prod` for production version

