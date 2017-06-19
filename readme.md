**Virtual host**

    <VirtualHost *:80>
		<Directory "E:/wamp/www/offshore/vessel">
			Require all granted
	        AllowOverride All
			Order Deny,Allow   
			Allow from all 
	    </Directory>
	    SetEnv FUEL_ENV "development"
	    DocumentRoot "E:/wamp/www/offshore/vessel/public/"
	    ServerName localsv.vessel.net	
	    ServerAlias localsv.vessel.net
	    ErrorLog "E:/wamp/logs/vessel.log"
	</VirtualHost>


**Setup connect database**

For developer: 
	/fuel/app/config/development/db.php

For production
	/fuel/app/config/production/db.php


----
**Code for UserPage**: /fuel/app/modules/frontend
	
- Route User: /fuel/app/modules/frontend/config/routes.php
- Template: /fuel/app/modules/frontend/views/template.php

**Code for AdminPage**: /fuel/app/modules/wsroot
	
- Route Admin: /fuel/app/modules/wsroot/config/routes.php
- Template: /fuel/app/modules/wsroot/views/template.php

**Model**:  /fuel/app/classes/model



----
**Database admin user**: /database_demo/admin_user.sql
For login admin page and manage user  

http://[domian]/wsroot/users/index