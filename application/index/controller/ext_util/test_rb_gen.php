<?php
require_once 'rbext.php';
R::setup("mysql:host=localhost;dbname=test", "root", "root");
$jsonstr = '{
  "paymentMethod": "CC",
  "priceAmount": "2.64",
  "priceCurrency": "EUR",
  "saleID": "18426319",
  "shopID": "115404",
  "type": "purchase",
  "signature": "ec0e2601184c1ca55376bcd95bcb0135a81c2c25"
}';
//$data = json_decode($jsonstr);
//$id= rbsave("abc", $data);

class MigrationLogger implements RedBeanPHP\Logger {

    private $file;

    public function __construct( $file ) {
        $this->file = $file;
    }

    public function log() {
        $query = func_get_arg(0);
        if (preg_match( '/^(CREATE|ALTER)/', $query )) {
            file_put_contents( $this->file, "{$query};\n",  FILE_APPEND );
        }
    }
}
R::freeze(FALSE);
$ml = new MigrationLogger( sprintf( 'test_rb_gen%s.sql', date('Y-m-d') ) );

R::getDatabaseAdapter()
    ->getDatabase()
    ->setLogger($ml)
    ->setEnableLogging(TRUE);

R::nuke();
$pages = R::dispense(array(
    '_type' => 'page',
    'title' => 'home',
    'ownPageList' => array(array(
        '_type' => 'page',
        'title' => 'shop',
        'ownPageList' => array(array(
            '_type' => 'page',
            'title' => 'wines',
            'ownPageList' => array(array(
                '_type' => 'page',
                'title' => 'whiskies',
            ))
        ))
    ))
));
R::store($pages);