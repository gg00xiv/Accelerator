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

<pre>
<?php



?>
</pre>