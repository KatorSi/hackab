<?hh

namespace Test;
require_once "autoload.php";

class WorldBase extends Base {
  function world(string $prefix):string {
    return $prefix. " world";
  }
}

class Test extends WorldBase {
  function __construct() {
    echo "Hello, ";
    echo $this->world("big");
  }
}

new Test();
