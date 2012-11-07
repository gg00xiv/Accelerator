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
  <li><a href="#model">Model</a>
    <ul>
      <li><a href="#save">Saving</a></li>
      <li><a href="#delete">Deleting</a></li>
      <li><a href="#select">Selecting</a></li>
    </ul>
  </li>
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
<p><em>entities.config.php</em> sample :</p>
<pre>

return array(
    'NamespaceTo\Category' => array(
        'table' => 'categories_table',
        'auto_increment_pk' => true,
        'primary_key_columns' => array('cat_id'),
        'map' => array(
            'cat_id' => 'categoryId',
            'cat_name' => 'name',
            'rewrited_name' => 'rewritedName',
        ),
    ),
    'NamespaceTo\Code' => array(
        'table' => 'codes_table',
        'auto_increment_pk' => true,
        'primary_key_columns' => array('c_id'),
        'map' => array(
            'c_id' => 'codeId',
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
            'lang_id' => 'languageId',
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

<p>DbEntity abstract class implements some mehtod allowing you to save, delete or select
 from database where its connected.</p>

<h3><a name="save"></a>Saving</h3>

<p>Saving DbEntity is done using the <em>save</em> method. When save method is called, INSERT or UPDATE
sql statement is involved based on primary keys, if they are defined or not. Look at the example below :</p>

<pre>
$cat = new Category();
$cat->name = 'My category';

// This statement will insert a new category in database 
$cat->save();
// now $cat->categoryId has been filled with auto generated id as defined in config.

$cat->rewritedName = 'my_category';

// This statement will now update the previously inserted category
$cat->save();
</pre>

<h3><a name="delete"></a>Deleting</h3>

<p>Deleting entities is as simple as saving. Just call <em>delete</em> method of DbEntity.</p>

<pre>
// $cat variable is defined and has its primary key defined.
...
$cat->delete();
// now $cat->categoryId is set to null.
</pre>

<h3><a name="select"></a>Selecting</h3>

<p>There is multiple ways for selecting DbEntity. There are static methods from DbEntity and instance methods
allowing to use an DbEntity instance as selection filter.</p>
<p>Look at examples below :</p>

<h4>Static methods</h4>
<pre>
// execute a SELECT statement on categories_table where cat_name like '%my%'
// Category class is configured to be binded to categories_table database table and cat_name column to Category name field.
// Here, the value '%my%' use like operator because of '%' symbol only. If not '%' is present in value, = operator is used.
$cats = Category::select(array('name' => '%my%'));

// $cats is an instance of DbEntityCollection

// get the first record of DbEntityCollection
$cat = $cats->first();
// $cat is an instance of Category
</pre>

<pre>
// This statement throws a ModelException if more than one row is returned
$cat = Category::selectSingle(array('name' => '%my%'));

// $cat is an instance of Category
</pre>

<h4>Instance methods (filter mode)</h4>
<pre>

$catFilter = new Category();
$catFilter->name = '%my%';

$cats = $catFilter->filter();
// $cats is an instance of DbEntityCollection
</pre>

<pre>
$catFilter = new Category(array('name' => 'My Category'));

// This statement throws a ModelException if more than one row is returned
$cat = $catFilter->filterSingle();

// $cat is an instance of Category

</pre>