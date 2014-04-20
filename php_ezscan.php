#!/usr/bin/env php
<?php
/**
 *
 * 
 * Script creato da francesco su falsa riga di ezp per dare in pasto ad ezscan variabili eZ
 *
 *
 * Return values:
 * - 0: OK
 * - 1: unknown command
 * - 2: unknown script
 * 
 * 
 *
 * Arguments:
 * - 
 */

// check if we are on a ezpublish directory
if ( !file_exists( "lib/version.php" ) )
{
    echo "This script can only be executed from inside an eZ Publish directory\n";
    exit( 1 );
}

require 'autoload.php';

$input = new ezcConsoleInput();
try {
    $input->process();
} catch( ezcConsoleOptionException $e )
{
    die( $e->getMessage() );
}

$arguments = $input->getArguments();
if ( count( $arguments ) === 0 )
{
       echo "ezscan: show ezpublish ini values\n";
       echo "\n";
       echo "Usage: ";
       echo "ezscan _ini SECTION VALUE\n";
       echo "\n";
       echo "e.g.  ezscan _ini DatabaseSettings User\n";
       echo "      ezscan _ini SiteAccessSettings AvailableSiteAccessList\n";
       echo "\n";
       echo "shortcuts:\n";
       echo "      ezscan _siteaccess_list\n";
       echo "      ezscan _var_dir\n";
       echo "      ezscan _database\n";
       echo "      ezscan _database_server\n";
       echo "      ezscan _database_user\n";
       echo "\n";
       echo "Note: it must be executed inside an eZ documentroot (anywhere, but inside it)\n";
       echo "\n";
       return;
}

switch( $arguments[0] )
{

    # il seguente commentato in quanto concettualmente errato!!
    #case '_siteaccess_list':
    #    # attenzione, il seguente non va bene!
    #    # i siteaccess si ricavano dai file in settings/siteaccess. STOP
    #    $siteaccessList = eZINI::instance()->variable( 'SiteAccessSettings', 'AvailableSiteAccessList' );
    #    complete( $siteaccessList );
    #    echo "\n";
    #    break;
    case '_var_dir':
	    $var_Dir = eZINI::instance()->variable( 'FileSettings', 'VarDir' );
		echo $var_Dir;
		echo "\n";
		break;
    case '_database':
		$var_Dir = eZINI::instance()->variable( 'DatabaseSettings', 'Database' );
		echo $var_Dir;
		echo "\n";
		break;
    case '_database_server':
		$var_Dir = eZINI::instance()->variable( 'DatabaseSettings', 'Server' );
		echo $var_Dir;
		echo "\n";
		break;
    case '_database_user':
		$var_Dir = eZINI::instance()->variable( 'DatabaseSettings', 'User' );
		echo $var_Dir;
		echo "\n";
		break;
    // arguments list for a script
    case '_ini':
        if ( !isset( $arguments[1] ) )
            return 2;
        $output = eZINI::instance()->variable(  $arguments[1], $arguments[2] ); 
        if  (is_array($output) ) {
          complete( $output); 
        } else {
          echo $output;
        }
        echo "\n";
        break;
    //
    case '_ini_fetchFromFile':
        if ( !isset( $arguments[1] ) )
            return 2;
        $output = eZINI::instance($fileName = "site.ini.append.php",$rootDir = "settings/siteaccess/$arguments[3]")->variable(  $arguments[1], $arguments[2] );
        if  (is_array($output) ) {
          complete( $output);
        } else {
          echo $output;
        }
        echo "\n";
        break;
    // execute the script
    default:
      return 1;
}

/**
 * Formats and output a words list for completion
 * @param array $words
 */
function complete( array $words )
{
    echo implode( "\n", $words );
}


?>
