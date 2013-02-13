#!/bin/bash
##############################
#
# load-pear-modules.sh
#
#
#
###############################

function warning {

	echo ""
	echo "Warning"
	echo "======="
	
	echo "Some of these PEAR installs will fail as the"
	echo "required modules do not live in the default repository"
	echo "Simply cut and paste the suggested module location"
	echo "from the error message in the terminal and re run the script"
	echo ""
	
}

# first update PEAR
pear install PEAR	

pear install Archive_Tar
pear install Auth_SASL	
pear install Cache_Lite	
pear install Console_Getopt	
pear install DB	
pear install HTTP_OAuth	
pear install HTTP_Request2	
pear install Log	
pear install MDB2	
pear install Mail
pear install Net_SMTP	
pear install Net_Socket	
pear install Net_URL2	
pear install Services_Twitter	
pear install Structures_Graph	
pear install XML_Util


warning

