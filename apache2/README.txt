Um Blogs beliebige Domaenen zuweisen zu koennen, werden diese per Proxy auf die Wordpress-MU-Domains gemappt. 

Da WP-MU immer absolute Links erzeugt, muessen auch Links transformiert werden. Dazu dient das Apache-Modul proxy_html.
Dieses liegt im Verzeichnis mod_proxy_html und kann mit apxc kompiliert werden (vgl. http://www.apachetutor.org/admin/reverseproxies).
Die Konfiguration liegt im Verzeichnis config.

