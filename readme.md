**Virtual host**

    <VirtualHost *:80>
	    ServerAdmin khanhkid@example.com
	    ServerName thesis.kid
	    ServerAlias www.thesis.kid
	    DocumentRoot /home/khanhkid/Desktop/website/website_thesis/public/
	<Directory "/home/khanhkid/Desktop/website/website_thesis/public/">
	   Require all granted
	   Order allow,deny
	   Allow from all
	   # New directive needed in Apache 2.4.3:
	</Directory>
	 ErrorLog ${APACHE_LOG_DIR}/error.log
	    CustomLog ${APACHE_LOG_DIR}/access.log combined
     </VirtualHost>

Create dir /public/userfiles/


Using python 2.7 & skicit learning to predict 
1. Precipitation
2. Temperature
