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
  <li><a href="#model">Model</a></li>
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
<p>The example below is the near the actual configuration file for the <a href="http://megasnippets.com">megasnippets.com</a> website.</p>
<pre>
// notice : __DIR__ refers to this file directory

return array(
    'global' => array(
        'namespace' => 'MegaSnippets',
        'base_url' => 'http://megasnippets.com/',
        'website_name' => 'MegaSnippets',
        'contact_email' => 'webmaster@megasnippets.com',
        'pagination' => array(
            'items_per_page' => 40,
            'page_parameter' => 'page',
        ),
        'autostart_session' => false,
    ),
    'cache' => array(
        'feedCache' => array(
            'mode' => 'file',
            'path' => __DIR__ . '/../cachedir',
            'lifetime' => 3600
        ),
    ),
    'log' => array(
        'filelog' => array(
            'type' => 'file',
            'params' => array(
                'toplog' => true,
                'path' => __DIR__ . '/../logs/logfile.dat',
            ),
        ),
        'dblog' => array(
            'type' => 'database',
            'params' => array(
                'table' => 'cu_logs',
            ),
        ),
        'logs' => array(
            'type' => 'merge',
            'params' => array(
                'filelog',
                'dblog',
            )
        )
    ),
    'model' => array(
        'connection' => array(
            'driver' => 'MySql',
            'host' => '&lt;hostname&gt;',
            'dbname' => '&lt;databasename&gt;',
            'username' => '&lt;user&gt;',
            'password' => '&lt;pass&gt;',
        ),
        'entities' => include __DIR__ . '/entities.config.php', // See below this file section the entities.config.php sample
    ),
    'views' => array(
        'path' => __DIR__ . '/../views/',
        'map' => array(
            'Code' => array('file' => 'main/code.phtml', 'parent' => 'MainLayout'),
            'Language' => array('file' => 'main/language.phtml', 'parent' => 'SearchLayout', 'items_per_page' => 40),
            'Category' => array('file' => 'main/category.phtml', 'parent' => 'SearchLayout', 'items_per_page' => 40),
            'Search' => array('file' => 'main/search.phtml', 'parent' => 'SearchLayout', 'items_per_page' => 40),
            'SearchLayout' => array('file' => 'main/layouts/search.phtml', 'parent' => 'MainLayout'),
            'MainLayout' => 'main/layouts/main.phtml',
            'Home' => array('file' => 'main/index.phtml', 'parent' => 'MainLayout'),
            'Contact' => array('file' => 'main/contact.phtml', 'parent' => 'MainLayout'),
            'About' => array('file' => 'main/about.phtml', 'parent' => 'MainLayout'),
            'Submit' => array('file' => 'main/submit.phtml', 'parent' => 'MainLayout'),
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
            'LoginController',
            'SubmitController',
            'FeedController',
        ),
    ),
    'routes' => array(
        '/' => array('IndexController', 'Home'),
        '/source-codes/(:lang)/(:code)' => array('controller' => 'CodeController', 'view' => 'Code'),
        '/languages/(:lang)' => array('SearchController', 'Language'),
        '/languages/(:lang)/page-(:page)' => array('SearchController', 'Language'),
        '/about' => array('AboutController', 'About'),
        '/submit(/(:lang))' => array('SubmitController', 'Submit'),
        '/feed/(:feed)' => 'FeedController',
    ),
);
</pre>
<p>entities.config.php sample :</p>
<pre>

return array(
    'NamespaceTo\Category' => array(
        'table' => 'categories_table',
        'auto_increment_pk' => true,
        'primary_key_columns' => array('cat_id'),
        'map' => array(
            'category_id' => 'categoryId',
            'name' => 'name',
            'rewrited_name' => 'rewritedName',
        ),
    ),
    'NamespaceTo\Code' => array(
        'table' => 'codes_table',
        'auto_increment_pk' => true,
        'primary_key_columns' => array('c_id'),
        'map' => array(
            'code_id' => 'codeId',
            'definition' => 'definition',
            'rewrited_name' => 'rewritedName',
            'code' => 'code',
            'post_date' => 'postDate',
            'validated' => 'isValidated',
            'language_id' => 'languageId',
            'category_id' => 'categoryId',
            'last_modified' => 'lastModified',
            'password' => 'password',
        ),
    ),
    'NamespaceTo\Language' => array(
        'table' => 'languages_table',
        'auto_increment_pk' => true,
        'primary_key_columns' => array('lang_id'),
        'map' => array(
            'language_id' => 'languageId',
            'default_category_id' => 'defaultCategoryId',
            'name' => 'name',
            'rewrited_name' => 'rewritedName',
            'tags' => 'tags',
            'sh_class' => 'shClass',
            'extension' => 'extension',
        ),
    ),
);

</pre>

<h2><a name="model"></a>Model</h2>

<p>The model is the basis of all your application. This feature must be as simple as secured to allow you building a great architecture with a few risk.</p>
<p>The main object here is the DbEntity. All your model classes should inherits from this class to offer you all the Accelerator Framework interactions you can need in an MVC designed application.</p>

<p>Look at this sample class :</p>
<pre>

class Category extends DbEntity {

    protected $loadMode = parent::LOAD_MODE_CONFIG;
    public $categoryId;
    public $name;
    public $rewritedName;

    /**
     * Get a Category instance from a rewrited name.
     * 
     * @param string $rewritedName
     * @return NamespaceTo\Category
     */
    public static function getByRewrite($rewritedName) {
        return $rewritedName ?
                \Accelerator\Model\EntityManager::selectSingle(new Category(array(
                            'rewritedName' => $rewritedName))) :
                null;
    }

    public function __toString() {
        return $this->name;
    }

}

</pre>