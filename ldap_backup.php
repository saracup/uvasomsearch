<?php

    class Ldap {
        // CLASS PROPERTIES
        protected $_dn     = "";
        protected $_server = "";
		protected $_term   = ""; 
        protected $_type   = "";
        protected $_filter = "";

        // CLASS METHODS
        // Constructor
        public function __construct($type, $term) {
            $this->_type = $type;
            $this->_term = trim($term);

            switch ($type) {
                case 'name':
                    if (preg_match('/(\w+)\s(\w+)/',$term)) {
                        // single space separator = [givenname]&[sn]
                        $sString = preg_replace('/(\w+)\s(\w+)/', '(&(sn=$2)(|(givenname=*$1*)(edupersonnickname=*$1*)))', trim($term));
                        $this->_filter = strtolower($sString);
                    } elseif (preg_match('/(\w+),\s?(\w+)/',$term)) {
                        // single comma separator = [sn]&[givenname]
                        $sString = preg_replace('/(\w+),\s?(\w+)/', '(&(sn=$1)(|(givenname=*$2*)(edupersonnickname=*$2*)))', trim($term));
                        $this->_filter = strtolower($sString);
                    }  else {
                        // try to match up something with the common name
                        $this->_filter = "(cn=*" . trim($term) . "*)";
                    }
                    break;
                case 'alias':
                    // if there is a "@virginia.edu" domain, remove it, then add it later
                    $term = preg_replace('/(.*)@virginia.edu$/i', '$1', $term);
                    $this->_filter = "(mailalternateaddress=" . trim($term) . "@virginia.edu)";
                    break;
                case 'phone':
                    // accomodate all sorts of phone number formats
                    $term = preg_replace('/(\d{0,3})[- .]?(\d{4})$/', '$1-$2' , $term);
                    $this->_filter = "(telephonenumber=*" . trim($term) . ")";
                    break;
                default:
                    $this->_filter = "(uid=" . trim($term) . ")";
                    break;
            }

            $this->_dn     = "o=University of Virginia, c=US";
            $this->_server = "ldap.virginia.edu";
        }

        public function search() {
            // establish LDAP connection
            $ldapConnect = ldap_connect($this->_server) or die("Could not connect to LDAP server");
            // login anonymously
            ldap_bind($ldapConnect) or die("Could not bind to LDAP server");
            // search
            @$sr = ldap_search($ldapConnect, $this->_dn, $this->_filter);

            // if there are search results, proceed
            if ($sr) {
				ldap_sort($ldapConnect, $sr, 'givenname');
            	ldap_sort($ldapConnect, $sr, 'sn');
                $entries = ldap_get_entries($ldapConnect, $sr);
                return ($entries);
            } else {
                return (false);
            }
        }
    }

