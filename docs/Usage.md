# Usage
- After installing the Plugin go to the Backend Menu Page `Towa Gdpr` and setup all the fields in the Settings Group.
- The cookies / trackers are grouped to give users better understandig of what the trackers are for. 
- common examples of groups are: Marketing, Personalisation, Statistics
- Every cookie has javascript code that should be executed if the user consents to it. this could be tracker codes from Google Analytics or Facebook or whatever.
- Every cookie has optional links to your tracking providers data protection policies. If you use external services this is mendatory

## Hashing functionality
To guarantee users have to consent again if cookies change, a hashing functionality was added to the plugin if you need. To generate a new Hash you can click the `generate & update hash` functionality on the Settings page of the Plugin.

## No cookie pages
Define Pages where the cookie notice isn't shown and won't set the cookies.

## Consent Logging
- Settings made by Admin will be saved into Database Table `tableprefix_towa_gdpr_settings` 

