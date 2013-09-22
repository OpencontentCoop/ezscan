#!/usr/bin/env php
<?php
/**
 *
 * Return values:
 * - 0: OK
 * - 1: unknown command
 * - 2: unknown script
 *
 * Arguments:
 * - 
 */

// these environnement variables are set by the completion shell script
$ezpCompDir = getenv( 'EZPCOMP_EZ_DIR' );
$ezpCompIseZDir = getenv( 'EZPCOMP_IS_EZ_DIR' );
$ezpCompPwd = getenv( 'EZPCOMP_PWD' );

       echo "\n EZPCOMP_EZ_DIR: ";
       echo getenv( 'EZPCOMP_EZ_DIR' );
       echo "\n EZPCOMP_IS_EZ_DIR: ";
       echo getenv( 'EZPCOMP_IS_EZ_DIR' );
       echo "\n EZPCOMP_PWD:";
       echo getenv( 'EZPCOMP_PWD' );
       echo "\n";

// switch the working directory based on what the completion shell script has
if ( $ezpCompIseZDir == 1 && $ezpCompPwd != getcwd() )
{
    chdir( $ezpCompDir );
}

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
       echo "\n EZPCOMP_EZ_DIR: ";
       echo getenv( 'EZPCOMP_EZ_DIR' );
       echo "\n EZPCOMP_IS_EZ_DIR: ";
       echo getenv( 'EZPCOMP_IS_EZ_DIR' );
       echo "\n EZPCOMP_PWD";
       echo getenv( 'EZPCOMP_PWD' );
       echo "\n";
       return;
}

switch( $arguments[0] )
{

    case '_siteaccess_list':
        $siteaccessList = eZINI::instance()->variable( 'SiteAccessSettings', 'AvailableSiteAccessList' );
        complete( $siteaccessList );
        echo "\n";
        break;
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