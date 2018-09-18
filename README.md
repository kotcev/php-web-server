**Basic PHP Web Server**

**Required dependencies:**
- PHP >=7.2.0
- ZTS enabled
- php-pthreads ext
- Under Unix environment

**Installation**
- `composer install`

**Additional info**

Unfortunately the php pThreads extension does not work well with resources (the sockets are resources in php), 
so it may broke the zend heap after the
first request, in this case if you want to try out the server without multi-threading just set the blocking flag of the 
server object as TRUE to stop using threads. 