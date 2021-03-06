<?php

class JWT {

  function encode($uid) {
    global $_CONFIG;
    $key = $_CONFIG['apiauthsecret'];
    $iat = time();
    $data = [ 'iat'=>$iat, 'sub'=>$uid ];
    $header = static::urlsafeB64Encode(json_encode(['typ'=>'JWT','alg'=>'HS256']));
    $payload = static::urlsafeB64Encode(json_encode($data));
    $sig = static::urlsafeB64Encode(hash_hmac('SHA256', $header . '.' . $payload, $key, true));
    return($header . '.' . $payload . '.' . $sig);
  }

  function encodepayload($data) {
    global $_CONFIG;
    $key = $_CONFIG['apiauthsecret'];
    $iat = time();
    $header = static::urlsafeB64Encode(json_encode(['typ'=>'JWT','alg'=>'HS256']));
    $payload = static::urlsafeB64Encode(json_encode($data));
    $sig = static::urlsafeB64Encode(hash_hmac('SHA256', $header . '.' . $payload, $key, true));
    return($header . '.' . $payload . '.' . $sig);
  }

  function decode($token) {
    global $_CONFIG;
    $key = $_CONFIG['apiauthsecret'];
    $ra = explode('.', $token);
    $sig = static::urlsafeB64Encode(hash_hmac('SHA256', $ra[0] . '.' . $ra[1], $key, true));
    if ($sig != $ra[2]) {
      return(false);
    }
    $header = json_decode(static::urlsafeB64Decode($ra[0]));
    if ($header->typ != 'JWT' || $header->alg != 'HS256') {
      return(false);
    }
    $payload = json_decode(static::urlsafeB64Decode($ra[1]));
    return($payload);
  }

  public static function urlsafeB64Encode($input) {
    return(str_replace('=', '', strtr(base64_encode($input), '+/', '-_')));
  }
  public static function urlsafeB64Decode($input) {
    $remainder = strlen($input) % 4;
    if ($remainder) {
      $padlen = 4 - $remainder;
      $input .= str_repeat('=', $padlen);
    }
    return(base64_decode(strtr($input, '-_', '+/')));
  }
}

//$j = new JWT();
//print($j->encode('qwerty'));

?>
