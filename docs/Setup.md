# Setup:

## Setup for Websites (using the Plugin) 
- Install the plugin like you would any other Composer Dependency (you will need access to the private repo for now)

### ⚠️ Warning
If your server is using Nginx instead of Apache you will need to prevent access to the consent log files located under [/wp-content/uploads/towa-gdpr/consents/](/wp-content/uploads/towa-gdpr/consents/)

## Setup for development (developing the plugin itself)
 1. Set up your WordPress version how you like. The only requirement is that it has ACF pro installed and activated.
 2. `cd` into the plugins folder.
 3. git clone the project. Name the folder `towa-gdpr-plugin`.
 4. Change `.env` path to where your installation url is (this is for browsersync only).
 5. Run `composer install` to install PHP dependencies.
 6. Run the frontend task you need:
	- `npm run watch` to use browsersync version.
	- `npm run prod` for production version.

