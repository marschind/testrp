<?php
require("jwt.php");
require("errorCodes.php");
require("plunetapi.php");

// Check for authenticator
function auth() {
  global $_SETTINGS;
  $headers = apache_request_headers();
  $auth = $headers['Authorization'];
  if (!$auth) {
    throw new Exception("No Authorization header was supplied", ERR_MISSING_AUTHORIZATION);
  }
  if (strlen($auth) < 8 || substr($auth, 0, 7) != 'Bearer ') {
    throw new Exception("Authorization header schema should be \"bearer\"", ERR_MISSING_AUTHORIZATION);
  }
  $auth = JWT::decode(substr($auth, 7));
  if (!$auth) {
    throw new Exception("Authorization failed to decode web token", ERR_BAD_AUTHORIZATION);
  }
  // fetch user record
  $res = query("select * from users where id=:uid", array(':uid'=>$auth->sub));
  if (!$res[0]) {
    throw new Exception("Authorization failed - bad username and/or password", ERR_BAD_AUTHORIZATION);
  }
  $_SETTINGS['user'] = $res[0];
  $res = query("select * from revocations where user=:uid and iat=:iat", array(':uid'=>$_SETTINGS['user']['id'], ':iat'=>$auth->iat));
  if ($res[0]) {
    throw new Exception("Authorization failed - token has been revoked", ERR_BAD_AUTHORIZATION);
  }
}

function getDB() {
    global $_CONFIG;
    if ($_CONFIG['db']) {
      return($_CONFIG['db']);
    }
    $db_driver = $_CONFIG["database"]["driver"];
    $db_host = $_CONFIG["database"]["host"];
    $db_port = $_CONFIG["database"]["port"];
    $db_name = $_CONFIG["database"]["dbname"];
    $db_user = $_CONFIG["database"]["username"];
    $db_password = $_CONFIG["database"]["password"];
    $db = new PDO("{$db_driver}:host={$db_host};port={$db_port};dbname={$db_name}", $db_user, $db_password);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $_CONFIG['db'] = $db;
    return $db;
}
// Perform an SQL query
// use this function in the manner like this:
//
// query( "SELECT * FROM table WHERE foo=:foo AND bar=:bar", array(
//   ":foo" => 1,
//   ":bar" => "blah"
// ));
//
// This function uses PDO statement preparations, so it's safe
// return the array of query results
// if there's no result, then return null
function query($query_string, $params = array()) {
    $db = getDB();
    try {
        $query = $db->prepare($query_string);
        $query->execute($params);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        if (count($result) == 0) {
            return null;
        } else {
            return $result;
        }
    }
    catch(Exception $e) {
        print_r($e);
    }
}
function update($query_string, $params = array()) {
    $db = getDB();
    try {
        $query = $db->prepare($query_string);
        return $query->execute($params);
    }
    catch(Exception $e) {
        debuglog("sql update error: ".$e->getMessage());
        throw new Exception($e->getMessage(), ERR_DATABASE_FAILURE);
    }
}

function debuglog($s) {
  $fp = fopen("/tmp/acclaro.log", "a");
  fprintf($fp, "%s: %s\n", date('Y-m-d H:i:s'), $s);
  fclose($fp);
}

$_CONFIG = parse_ini_file("/home/acclaro/lib/config.txt", true);
$argc = sizeof($_REQUEST) - sizeof($_COOKIE);

?>
