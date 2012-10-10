<h1>PHP Accelerator Framework : PAF</h1>
===========

<p><strong>PHP Accelerator Framework speeds up your projects.</strong></p>

<h2>Main features</h2>

- MVC based framework
- Database entity framework included
- Easy to learn

<h2>Get started</h2>

<p>Your project architecture should looks like follow :</p>
<ul>
<li>models</li>
<li>views</li>
<li>controllers</li>
<li>config
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