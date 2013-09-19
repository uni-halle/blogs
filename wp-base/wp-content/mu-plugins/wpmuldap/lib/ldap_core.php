<?php
require_once ("defines.php");

// Indices into the server information string
define ('SERVER_NAME', '0');
define ('SEARCH_BASE', '1');
define ('PRIV_DN',     '2');
define ('PRIV_PASSWD', '3');
define ('SERVER_PORT', '4');
define ('ENABLE_SSL',  '5');

class LDAP {
	var $server;
	var $connection_handle;
	var $error_no;
	var $error_txt;
	var $dn;
	var $dn_passwd;
	var $search_dn;
	var $search_string;
	var $attributes_to_get;
	var $search_result;
	var $info;               // 2D information array returned by ldap_search
	var $usessl;
	var $port;
	var $debug;
	
	function LDAP($options = array()) {
		$this->server = $options[SERVER_NAME];
		$this->search_dn = $options[SEARCH_BASE];
		$this->dn = $options[PRIV_DN];
		$this->dn_passwd = $options[PRIV_PASSWD];
		$this->usessl = $options[ENABLE_SSL];
		$this->port = $options[SERVER_PORT];
	}
	
	function Connect() {

		// have a look if SSL is enabled
		if ($this->usessl == 1)
			$proto = "ldaps://";
		else
			$proto = "ldap://";

		if ($this->debug) {
			#$this->connection_handle = ldap_connect ($proto.$this->server, $this->port);
			$this->connection_handle = ldap_connect ($proto.$this->server.':'.$this->port);
		} else {
			#$this->connection_handle = @ldap_connect ($proto.$this->server, $this->port);
			$this->connection_handle = @ldap_connect ($proto.$this->server.':'.$this->port);
		}
		
		// Error connecting?
		if (!$this->connection_handle) {
			$return = false;
			$this->LogError();
		}
		else {
			$return = true;
			ldap_set_option($this->connection_handle, LDAP_OPT_PROTOCOL_VERSION, 3);
			// Start TLS
			if ($this->usessl == 2) {
				if (!ldap_start_tls($this->connection_handle)) {
					$return = false;
					$this->LogError();
				}
			}
			if (!get_site_option('ldapLinuxWindows')) {
				//Windows Only
				ldap_set_option($this->connection_handle, LDAP_OPT_REFERRALS, 0);
			}
		}
		
		return $return;
	}
	
	function Bind() {
		$this->error_no = 0;
		$this->error_txt = "Success";
		$return = false;
		$this->dn_passwd = stripslashes($this->dn_passwd);
		if ($this->connection_handle) {
			if ($this->debug) {
				if (ldap_bind ($this->connection_handle, $this->dn, $this->dn_passwd)) {
					$return = true;
				}
			}
			else {
				if (@ldap_bind ($this->connection_handle, $this->dn, $this->dn_passwd))
				$return = true;
			}
		}
	
		if (!$return) {
			$this->error_no = @ldap_errno ($this->connection_handle);
			$this->LogError();
		}
	
		return $return;
	}
	
	function Dock() {
		// First, connect to the LDAP server
		$result = $this->Connect();
		if (!$result) {
			$this->LogError();
			return false;
		}
	
		// Now bind as the user with enough rights to browse the "cn" attribute
		if (!$this->Bind()) {
			$this->LogError();
			return false;
		}
	
		return true;
	}
	
	function Disconnect() {
		if ($this->connection_handle) {
			if ($this->debug) {
				ldap_close ($this->connection_handle);
			}
			else {
				@ldap_close ($this->connection_handle);
			}
	
			$this->connection_handle = 0;
		}
	}
	
	function GetErrorNumber() {
		return $this->error_no;
	}
	
	function GetErrorText() {
		return ldap_err2str ($this->error_no);
	}
	
	function SetAccessDetails ($dn, $passwd) {
		$this->dn = $dn;
		$this->dn_passwd = $passwd;
	}
	
	function SetSearchCriteria ($search, $attrs) {
		$this->search_string = $search;
		$this->attributes_to_get = $attrs;
	}
	
	function Search() {
		if ($this->connection_handle) {
			if ($this->debug) {
				$this->search_result = ldap_search ($this->connection_handle, $this->search_dn, $this->search_string, $this->attributes_to_get);
				$this->info = ldap_get_entries ($this->connection_handle, $this->search_result);
			}
			else {
				$this->search_result = @ldap_search ($this->connection_handle, $this->search_dn, $this->search_string, $this->attributes_to_get);
				$this->info = @ldap_get_entries ($this->connection_handle, $this->search_result);
			}
		}
	}
	
	function DebugOn() {
		$this->debug = true;
	}
	
	function DebugOff() {
		$this->debug = false;
	}
	
	function LogError() {
		$this->error_no = @ldap_errno ($this->connection_handle);
	}
	
	function GetLDAPInfo ($type) {
		$mail 		= get_site_option('ldapAttributeMail',LDAP_DEFAULT_ATTRIBUTE_MAIL);
		$nickname	= get_site_option('ldapAttributeNickname',LDAP_DEFAULT_ATTRIBUTE_NICKNAME);
		$givenname 	= get_site_option('ldapAttributeGivenname',LDAP_DEFAULT_ATTRIBUTE_GIVENNAME);
		$sn 		= get_site_option('ldapAttributeSn',LDAP_DEFAULT_ATTRIBUTE_SN);
		$phone 		= get_site_option('ldapAttributePhone',LDAP_DEFAULT_ATTRIBUTE_PHONE);
		$homedir 	= get_site_option('ldapAttributeHomedir',LDAP_DEFAULT_ATTRIBUTE_HOMEDIR);
		$member 	= get_site_option('ldapAttributeMember',LDAP_DEFAULT_ATTRIBUTE_MEMBER);
		$macaddress 	= get_site_option('ldapAttributeMacaddress',LDAP_DEFAULT_ATTRIBUTE_MACADDRESS);
		$dn 		= get_site_option('ldapAttributeDn',LDAP_DEFAULT_ATTRIBUTE_DN);

		if ($type == LDAP_INDEX_EMAIL) 			return $this->info[0][$mail][0];
		if ($type == LDAP_INDEX_NAME) 			return $this->info[0][$givenname][0]." ".$this->info[0][$sn][0];
		if ($type == LDAP_INDEX_GIVEN_NAME)		return $this->info[0][$givenname][0];
		if ($type == LDAP_INDEX_SURNAME) 		return $this->info[0][$sn][0];
		if ($type == LDAP_INDEX_PHONE) 			return $this->info[0][$phone];
		if ($type == LDAP_INDEX_HOMEDIR) 		return $this->info[0][$homedir][0];
		if ($type == LDAP_INDEX_MEMBER) 		return $this->info[0][$member];
		if ($type == LDAP_INDEX_MACADDRESS)		return $this->info[0][$macaddress];
		// When dealing with "uniqueMember", LDAP actually returns it as "member" - they're synonyms
		if ($type == LDAP_INDEX_UNIQUE_MEMBER) 		return $this->info[0][$member];
		if ($type == LDAP_INDEX_DN) 			return $this->info[0][$dn];
		if ($type == LDAP_INDEX_NICKNAME)		return empty($nickname) ? false : $this->info[0][$nickname][0];
	}

	function checkGroup($userDN,$groups){
		//Make sure we're connected - we're not when this is called from the admin side
		if (!$this->connection_handle) {
			$this->dock();
		}

		if (empty($groups)) return LDAP_GROUP_NOT_SET;
		
		// Get Groups
		$attributes_to_get = array(get_site_option('ldapAttributeDN',LDAP_DEFAULT_ATTRIBUTE_DN));
                if (get_site_option('ldapLinuxWindows')) {
			$search_filter = "(".get_site_option('ldapAttributeMemberNix',LDAP_DEFAULT_ATTRIBUTE_MEMBERNIX)."=$userDN)";
			$search_filter .= "(objectclass=".get_site_option('ldapAttributeGroupObjectclassNix',LDAP_DEFAULT_ATTRIBUTE_GROUP_OBJECTCLASSNIX).")";
                } else {
			$search_filter = "(".get_site_option('ldapAttributeMember',LDAP_DEFAULT_ATTRIBUTE_MEMBER)."=$userDN)";
			$search_filter .= "(objectclass=".get_site_option('ldapAttributeGroupObjectclass',LDAP_DEFAULT_ATTRIBUTE_GROUP_OBJECTCLASS).")";
		}
		$this->SetSearchCriteria("(&$search_filter)", $attributes_to_get);
                $this->Search();
		$results = ldap_get_entries($this->connection_handle, $this->search_result);

		// Check Groups
		$userGroups = array();
		for ($i = 0; $i < $results['count']; $i++) {
			$userGroups[$i] = strtolower($results[$i][get_site_option('ldapAttributeDN',LDAP_DEFAULT_ATTRIBUTE_DN)]);
			if (in_array($userGroups[$i],$groups)) return LDAP_IN_GROUP;
		}

		if ($this->checkGroupNested($groups,$userGroups)) {
			return LDAP_IN_GROUP;
		}

		// Check for nested groups
		return LDAP_ERROR_NOT_IN_GROUP;
	}

	/* Recursive function used to check nested groups */
	function checkGroupNested($reqgroups,$groups,$checkedgroups = array()) {
		if (!$groups) return false; //no more groups left to check

		#print "Checking Groups ".implode(",",$groups)." <br/>";

		$groupstocheck = array();
		foreach ($groups as $group) {
			// Get User Groups
	                $attributes_to_get = array(get_site_option('ldapAttributeDN',LDAP_DEFAULT_ATTRIBUTE_DN));
        	        $this->SetSearchCriteria("(&(".get_site_option('ldapAttributeMember',LDAP_DEFAULT_ATTRIBUTE_MEMBER)."=$group)(objectclass=".get_site_option('ldapAttributeGroupObjectclass',LDAP_DEFAULT_ATTRIBUTE_GROUP_OBJECTCLASS)."))", $attributes_to_get);
	                $this->Search();
			$results = ldap_get_entries($this->connection_handle, $this->search_result);
			$returnedgroups = array();
			for ($i = 0; $i < $results['count']; $i++) {
				array_push($returnedgroups,strtolower($results[$i][get_site_option('ldapAttributeDN',LDAP_DEFAULT_ATTRIBUTE_DN)]));
			}
		
			#print "Group $group is a member of: ".implode(",",$returnedgroups)."<br/>";

			foreach ($returnedgroups as $checkgroup) {
				if (in_array($checkgroup, $checkedgroups)) {
					continue;
				}

				#print "Checking membership for $checkgroup<br/>";

				if (in_array($checkgroup, $reqgroups)) {
					return true;
				} else {
					array_push($groupstocheck,$checkgroup);
				}
			}
		}
		$checkedgroups = array_unique(array_merge($groups,$checkedgroups));
		return $this->checkGroupNested($reqgroups,$groupstocheck,$checkedgroups);			
	}
}
