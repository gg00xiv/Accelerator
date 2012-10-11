<h1>PHP Accelerator Framework : PAF</h1>
===========

<p><strong>PHP Accelerator Framework speeds up your projects.</strong></p>

<h2>Main features</h2>

<ul>
<li>MVC based framework</li>
<li>Database entity framework included</li>
<li>Easy to learn</li>
<li>Easy to configure !</li>
<li>No 'magic' like in Zend Framework 1.xx</li>
</ul>

<h2>Summary</h2>
<ul>
  <li><a href="#get-started">Get started</a></li>
  <li><a href="#config">Config</a></li>
</ul>

<h2><a name="get-started"></a>Get started</h2>

<p>Your project architecture should looks like follow :</p>
<ul>
<li>models/
   <ul>
      <li>Account.php</li>
      <li>Code.php</li>
   </ul>
</li>
<li>views/
   <ul>
     <li>layouts/
        <ul>
         <li>mainlayout.phtml</li>
         <li>searchlayout.phtml</li>
        </ul>
     </li>
     <li>index.phtml</li>
     <li>search.phtml</li>
   </ul>
</li>
<li>controllers/
   <ul>
      <li>IndexController.php</li>
      <li>SearchController.php</li>
   </ul>
</li>
<li>src/ <em>(for all your project classes other than controllers or models)</em></li>
<li>config/
   <ul>
   <li>app.config.php</li>
   <li>entities.config.php</li>
   </ul>
</li>
<li>.htaccess</li>
<li>index.php</li>
</ul>

<h3>.htaccess</h3>
<pre>
SetEnv PHP_VER 5_4

Options -Indexes
 
RewriteEngine On

RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^.*$ index.php [NC,L]
</pre>
<h3>index.php</h3>

<pre>
<?php

require_once 'relative_path_to/Accelerator/Autoloader.php';
\Accelerator\Autoloader::register(array(
    'Accelerator' => 'relative_path_to_accelerator/Accelerator',
    'YourNamespace' => array(
        'relative_path_to_root/',
        'relatve_path_to_root/src/', // you can add more than two paths
    ),
));

\Accelerator\Application::instance()
        ->init(include '../config/app.config.php')
        ->dispatch();
?>
</pre>

<h2><a name="config"></a>Config</h2>

<p>The configuration file is the main feature of PAF : It have to be as simple as we would like.</p>
<p>The example below is the actual configuration file for the <a href="http://megasnippets.com">megasnippets.com</a> website.</p>
<pre>
<?php
// notice : __DIR__ refers to this file directory

return array(
    'global' => array(
        'namespace' => 'MegaSnippets',
        'base_url' => 'http://megasnippets.com/',
        'website_name' => 'MegaSnippets',
        'page_size' => 40,
        'page_parameter' => 'page',
    ),
    'model' => array(
        'connection' => array(
            'driver' => 'MySql',
            'host' => '&lt;hostname&gt;',
            'dbname' => '&lt;databasename&gt;',
            'username' => '&lt;user&gt;',
            'password' => '&lt;pass&gt;',
        ),
        'entities' => include __DIR__ . '/entities.config.php',
    ),
    'views' => array(
        'path' => __DIR__ . '/../views/',
        'map' => array(
            'Code' => array('file' => 'main/code.phtml', 'parent' => 'MainLayout'),
            'Language' => array('file' => 'main/language.phtml', 'parent' => 'SearchLayout', 'page_size' => 40),
            'Category' => array('file' => 'main/category.phtml', 'parent' => 'SearchLayout', 'page_size' => 40),
            'Search' => array('file' => 'main/search.phtml', 'parent' => 'SearchLayout', 'page_size' => 40),
            'SearchLayout' => array('file' => 'main/layouts/search.phtml', 'parent' => 'MainLayout'),
            'MainLayout' => 'main/layouts/main.phtml',
            'Home' => array('file' => 'main/index.phtml', 'parent' => 'MainLayout'),
            'Contact' => array('file' => 'main/contact.phtml', 'parent' => 'MainLayout'),
            'About' => array('file' => 'main/about.phtml', 'parent' => 'MainLayout'),
            'Admin' => array('file' => 'admin/index.phtml', 'parent' => 'AdminMainLayout'),
            'Login' => array('file' => 'main/login.phtml', 'parent' => 'MainLayout'),
            'AdminCode' => array('file' => 'admin/code.phtml', 'parent' => 'AdminMainLayout'),
            'AdminMainLayout' => 'admin/layouts/main.phtml',
        ),
    ),
    'controllers' => array(
        'namespace' => 'Controllers',
        'list' => array(
            'IndexController',
            'AboutController',
            'ContactController',
            'CodeController',
            'SearchController',
            'AboutController',
            'AdminIndexController',
            'AdminCodeController',
            'LoginController',
        ),
    ),
    'routes' => array(
        '/' => array('IndexController', 'Home'),
        '/source-codes/[:lang]/[:code]' => array('controller' => 'CodeController', 'view' => 'Code'),
        '/languages/[:lang]' => array('SearchController', 'Language'),
        '/languages/[:lang]/page-[:page]' => array('SearchController', 'Language'),
        '/languages/[:lang]/categories/[:cat]' => array('SearchController', 'Category'),
        '/languages/[:lang]/categories/[:cat]/page-[:page]' => array('SearchController', 'Category'),
        '/search.php?l=[:lang]&c=[:cat]&q=[:query]' => array('SearchController', 'Search'),
        '/search.php?l=[:lang]&c=[:cat]&q=[:query]&p=[:page]' => array('SearchController', 'Search'),
        '/about' => array('AboutController', 'About'),
        '/contact' => array('ContactController', 'Contact'),
        '/login' => array('LoginController', 'Login'),
        '/admin' => array('AdminIndexController', 'Admin'),
        '/admin/code/[:codeid]' => array('AdminCodeController', 'AdminCode'),
    ),
);
?>
</pre>