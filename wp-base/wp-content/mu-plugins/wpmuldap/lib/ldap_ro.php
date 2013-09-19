<?php
/* ldap_ro.class
 *
 * LDAP read only functionality class
 *
 * Member functions:
 * Authenticate
 * GetUserInfo
 * DoSearch
 * GetEmailList
 * GetDNParts
 *
 * AWY
 *
 * 03/12/03    1.3    Added GetEmailList, GetDNParts
 * 28/05/03    1.2    Added DoSearch
 * 08/01/03    1.1    Added GetUserInfo
 * 20/12/02    1.0    Created
 */

 require_once("ldap_core.php");
 require_once("defines.php");
 
class LDAP_ro extends LDAP {
	function Authenticate ($in_username, $in_passwd, &$user_data) {
		// First, connect to the LDAP server
		if(!$this->Dock()) {
			return LDAP_ERROR_CONNECTION;
		}
		
		// Set up the search stuff
		$attributes_to_get = array (get_site_option('ldapAttributeMail',LDAP_DEFAULT_ATTRIBUTE_MAIL),
					    get_site_option('ldapAttributeGivenname',LDAP_DEFAULT_ATTRIBUTE_GIVENNAME),
					    get_site_option('ldapAttributeSn',LDAP_DEFAULT_ATTRIBUTE_SN),
					    get_site_option('ldapAttributePhone',LDAP_DEFAULT_ATTRIBUTE_PHONE));

		if (get_site_option('ldapLinuxWindows'))
			$uid = get_site_option('ldapAttributeNixSearch',LDAP_DEFAULT_ATTRIBUTE_NIXSEARCH); //Linux
		else
			$uid = get_site_option('ldapAttributeWinSearch',LDAP_DEFAULT_ATTRIBUTE_WINSEARCH); //Windows

		$this->SetSearchCriteria ("($uid=$in_username)", $attributes_to_get);
		$this->Search();
		
		// Did we find the user?
		if ($this->info[0]["dn"] == "") {
			$this->Disconnect();
			return LDAP_ERROR_USER_NOT_FOUND;
		}
		
		// We always get back one more record than there really is
		$no_of_entries = (count ($this->info) - 1);
		
		// Authenticate again but this time as the user
		$this->SetAccessDetails ($this->info[0]["dn"], $in_passwd);

		if($this->Bind()) {
			// Return the user's data
			$user_data[LDAP_INDEX_DN] = $this->info[0]["dn"];
			$user_data[LDAP_INDEX_NAME] = $this->GetLDAPInfo (LDAP_INDEX_NAME);
			$user_data[LDAP_INDEX_NICKNAME] = $this->GetLDAPInfo (LDAP_INDEX_NICKNAME);
			$user_data[LDAP_INDEX_EMAIL] = $this->GetLDAPInfo (LDAP_INDEX_EMAIL);
			$user_data[LDAP_INDEX_GIVEN_NAME] = $this->GetLDAPInfo (LDAP_INDEX_GIVEN_NAME);
			$user_data[LDAP_INDEX_SURNAME] = $this->GetLDAPInfo (LDAP_INDEX_SURNAME);
			$user_data[LDAP_INDEX_PHONE] = $this->GetLDAPInfo (LDAP_INDEX_PHONE);
			$user_data[LDAP_INDEX_MEMBER] = $this->GetLDAPInfo (LDAP_INDEX_MEMBER);

			// If deny group set and user found, return
			$deny = $this->checkGroup($user_data[LDAP_INDEX_DN],wpmuLdapGroupsGet(array('siteoption' => 'ldapGroupDenyLogin')));
			if ($deny == LDAP_IN_GROUP) return LDAP_ERROR_DENIED_GROUP;

			// If allow group set and user found, 
			$allow = $this->checkGroup($user_data[LDAP_INDEX_DN],wpmuLdapGroupsGet(array('siteoption' => 'ldapGroupAllowLogin')));
			if ($allow == LDAP_IN_GROUP) return LDAP_OK; // found in group
			if ($allow == LDAP_ERROR_NOT_IN_GROUP) return LDAP_ERROR_ACCESS_GROUP; // not in group

			// Default Catch
			$return = LDAP_OK;
		} else {
			if ($this->GetErrorNumber() == 49) {
				$return = LDAP_ERROR_WRONG_PASSWORD;
			}
			else {
				$return = $this->GetErrorNumber();
			}
		}
		
		// Close the connection
		$this->Disconnect();
		
		return $return;
	}
	
	
	function GetUserInfo ($in_username, &$user_data) {
		// First, connect to the LDAP server
		$this->Dock();
		
		$attributes_to_get = array (get_site_option('ldapAttributeMail',LDAP_DEFAULT_ATTRIBUTE_MAIL),
					    get_site_option('ldapAttributeGivenname',LDAP_DEFAULT_ATTRIBUTE_GIVENNAME),
					    get_site_option('ldapAttributeSn',LDAP_DEFAULT_ATTRIBUTE_SN),
					    get_site_option('ldapAttributePhone',LDAP_DEFAULT_ATTRIBUTE_PHONE),
					    get_site_option('ldapAttributeHomedir',LDAP_DEFAULT_ATTRIBUTE_HOMEDIR),
					    get_site_option('ldapAttributeMember',LDAP_DEFAULT_ATTRIBUTE_MEMBER),
					    get_site_option('ldapAttributeMacaddress',LDAP_DEFAULT_ATTRIBUTE_MACADDRESS),
					    "dn");

		$this->SetSearchCriteria ("(cn=$in_username)", $attributes_to_get);
		$this->Search();
		
		// Did we find the user?
		if ($this->info[0]["dn"] == "") {
			$this->Disconnect();
			return LDAP_ERROR_USER_NOT_FOUND;
		}
		
		$user_data[LDAP_INDEX_EMAIL] = $this->GetLDAPInfo (LDAP_INDEX_EMAIL);
		$user_data[LDAP_INDEX_NAME] = $this->GetLDAPInfo (LDAP_INDEX_NAME);
		$user_data[LDAP_INDEX_NICKNAME] = $this->GetLDAPInfo (LDAP_INDEX_NICKNAME);
		$user_data[LDAP_INDEX_GIVEN_NAME] = $this->GetLDAPInfo (LDAP_INDEX_GIVEN_NAME);
		$user_data[LDAP_INDEX_SURNAME] = $this->GetLDAPInfo (LDAP_INDEX_SURNAME);
		$user_data[LDAP_INDEX_PHONE] = $this->GetLDAPInfo (LDAP_INDEX_PHONE);
		$user_data[LDAP_INDEX_HOMEDIR] = $this->GetLDAPInfo (LDAP_INDEX_HOMEDIR);
		$user_data[LDAP_INDEX_MEMBER] = $this->GetLDAPInfo (LDAP_INDEX_MEMBER);
		$user_data[LDAP_INDEX_MACADDRESS] = $this->GetLDAPInfo (LDAP_INDEX_MACADDRESS);
		$user_data[LDAP_INDEX_UNIQUE_MEMBER] = $this->GetLDAPInfo (LDAP_INDEX_UNIQUE_MEMBER);
		$user_data[LDAP_INDEX_DN] = $this->GetLDAPInfo (LDAP_INDEX_DN);
		
		$this->Disconnect();
		
		return LDAP_OK;
	}
	
	function DoSearch ($in_search_criteria, $in_attrs, &$data) {
		$this->Dock();
		$this->SetSearchCriteria ($in_search_criteria, $in_attrs);
		$this->Search();
		$this->Disconnect();
		$data = $this->info;
		
		return LDAP_OK;
	}

	function DoSearchUsername ($in_username, $attributes_to_get, &$data) {
		$this->Dock();
                if (get_site_option('ldapLinuxWindows')) 
                        $uid = get_site_option('ldapAttributeNixSearch',LDAP_DEFAULT_ATTRIBUTE_NIXSEARCH); //Linux
                else
                        $uid = get_site_option('ldapAttributeWinSearch',LDAP_DEFAULT_ATTRIBUTE_WINSEARCH); //Windows

                $this->SetSearchCriteria ("($uid=$in_username)", $attributes_to_get);
		$this->Search();
		$this->Disconnect();
		if ($this->info['count'] > 0) {
                        $data[LDAP_INDEX_DN] = $this->info[0]["dn"];
                        $data[LDAP_INDEX_NAME] = $this->GetLDAPInfo (LDAP_INDEX_NAME);
                        $data[LDAP_INDEX_NICKNAME] = $this->GetLDAPInfo (LDAP_INDEX_NICKNAME);
                        $data[LDAP_INDEX_EMAIL] = $this->GetLDAPInfo (LDAP_INDEX_EMAIL);
                        $data[LDAP_INDEX_GIVEN_NAME] = $this->GetLDAPInfo (LDAP_INDEX_GIVEN_NAME);
                        $data[LDAP_INDEX_SURNAME] = $this->GetLDAPInfo (LDAP_INDEX_SURNAME);
                        $data[LDAP_INDEX_PHONE] = $this->GetLDAPInfo (LDAP_INDEX_PHONE);

                        // If deny group set and user found, return
                        $deny = $this->checkGroup($user_data[LDAP_INDEX_DN],wpmuLdapGroupsGet(array('siteoption' => 'ldapGroupDenyLogin')));
                        if ($deny == LDAP_IN_GROUP) return LDAP_ERROR_DENIED_GROUP;

                        // If allow group set and user found,
                        $allow = $this->checkGroup($user_data[LDAP_INDEX_DN],wpmuLdapGroupsGet(array('siteoption' => 'ldapGroupAllowLogin')));
                        if ($allow == LDAP_IN_GROUP) return LDAP_OK; // found in group
                        if ($allow == LDAP_ERROR_NOT_IN_GROUP) return LDAP_ERROR_ACCESS_GROUP; // not in group

			// Default Catch
			return LDAP_OK;
		} else {
			$data = null;
		}
	}
	
	function GetEmailList ($in_email_list_name, &$emails, &$dns) {
		if ($in_email_list_name == "") return LDAP_ERROR_EMPTY_PARAM;
		
		$this->GetUserInfo ($in_email_list_name, $data);
		
		$no_of_members = count ($data[LDAP_INDEX_UNIQUE_MEMBER]);
		
		$non_empty_count = 0;
		for ($c=0; $c < $no_of_members; $c++) {
			// Get the user ID from the DN (cn= part)
			$parts = $this->GetDNParts ($data[LDAP_INDEX_UNIQUE_MEMBER][$c]);
			$parts = split ("=", $parts[0]);
			
			if ($parts[1] != "") {
				$this->GetUserInfo ($parts[1], $user_data);
				$emails[$c] = $user_data[LDAP_INDEX_EMAIL];
				$dns[$c] = $data[LDAP_INDEX_UNIQUE_MEMBER][$c];
				$non_empty_count++;
			}
		}
		
		return $non_empty_count;
	}
	
	function GetDNParts ($in_dn) {
		return ldap_explode_dn ($in_dn, 0);
	}

	// Test connection
	function testConnect () {
		return $this->Dock();
		if (!$this->Dock())
			return false;
		else
			return true;				
	}
}
