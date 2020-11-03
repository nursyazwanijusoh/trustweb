<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BatchJobController extends Controller
{
  public function menu(){
    return view('admin.loadji');
  }



  public function findBand45(){

    // do the ldap things
    $errm = 'success';
    $errorcode = 200;

    $adminuser = config('custom.ldap.adminuser');
    $password = config('custom.ldap.adminpass');
    $hostnameSSL = config('custom.ldap.hostname');
    $udn= "cn=$adminuser, ou=serviceAccount, o=Telekom";
    $retdata = [];
    //	ldap_set_option(NULL, LDAP_OPT_DEBUG_LEVEL, 7);
    putenv('LDAPTLS_REQCERT=never');

    $con =  ldap_connect($hostnameSSL);
    if (is_resource($con)){
      if (ldap_set_option($con, LDAP_OPT_PROTOCOL_VERSION, 3)){
        ldap_set_option($con, LDAP_OPT_REFERRALS, 0);

        // try to bind / authenticate
        try{
        if (ldap_bind($con,$udn, $password)){

          // perform the search
          $ldres = ldap_search($con, 'ou=users,o=data', "ppjobgrade=4");
          $ldapdata = ldap_get_entries($con, $ldres);
          // dd($ldapdata);


          if($ldapdata['count'] > 0){
            unset($ldapdata['count']);

            foreach ($ldapdata as $key => $value) {
              array_push($retdata, [
                'no' => $value['cn']['0'],
                'name' => $value['fullname']['0'],
                'post' => $value['pppostdesc']['0'],
                'orgunit' => $value['pporgunitdesc']['0'],
                'grade' => 4
              ]);
            }

          }

          // perform the search
          $ldres = ldap_search($con, 'ou=users,o=data', "ppjobgrade=5");
          $ldapdata = ldap_get_entries($con, $ldres);
          // dd($ldapdata);


          if($ldapdata['count'] > 0){
            unset($ldapdata['count']);

            foreach ($ldapdata as $key => $value) {
              array_push($retdata, [
                'no' => $value['cn']['0'],
                'name' => $value['fullname']['0'],
                'post' => $value['pppostdesc']['0'],
                'orgunit' => $value['pporgunitdesc']['0'],
                'grade' => 5
              ]);
            }

          }

        } else {
          $errorcode = 403;
          $errm = 'Invalid admin credentials.';
        }} catch(Exception $e) {
          $errorcode = 500;
          $errm = $e->getMessage();
        }

      } else {
        $errorcode = 500;
        $errm = "TLS not supported. Unable to set LDAP protocol version to 3";
      }

      // clean up after done
      ldap_close($con);

    } else {
      $errorcode = 500;
      $errm = "Unable to connect to $hostnameSSL";
    }

    return $retdata;
  }


}
