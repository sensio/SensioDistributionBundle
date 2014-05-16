<?php

require_once dirname(__FILE__).'/SymfonyRequirements.php';

$symfonyRequirements = new SymfonyRequirements();

$iniPath = $symfonyRequirements->getPhpIniConfigPath();

$okMessage = '[OK] Your system is ready to execute Symfony2 projects!';
$errorMessage = '[ERROR] Your system is not ready to execute Symfony2 projects!';

$lineSize = strlen($errorMessage);

echo 'Symfony2 Requirements Checker'.PHP_EOL;
echo str_repeat('=', $lineSize);

echo_title('Looking for the INI configuration file used by PHP');

echo $iniPath ? $iniPath : 'WARNING: No configuration file (php.ini) used by PHP!';

echo_title('Checking mandatory requirements:');

$checkPassed = true;
$messages = array();
foreach ($symfonyRequirements->getRequirements() as $req) {
    /** @var $req Requirement */
    if ($helpText = getErrorMessage($req)) {
        echo 'E';
        $messages['error'][] = $helpText;
    } else {
        echo '.';
    }

    if (!$req->isFulfilled()) {
        $checkPassed = false;
    }
}

echo_title('Checking optional recommendations:');

foreach ($symfonyRequirements->getRecommendations() as $req) {
    if ($helpText = getErrorMessage($req)) {
        echo 'W';
        $messages['warning'][] = $helpText;
    } else {
        echo '.';
    }
}

if (empty($messages['error'])) {
    echo_result($okMessage, $lineSize);
}

if (!empty($messages['error'])) {
    echo_result($errorMessage, $lineSize);

    echo PHP_EOL.'Fix the following mandatory requirements'.PHP_EOL;
    echo str_repeat('-', $lineSize).PHP_EOL;

    foreach ($messages['error'] as $helpText) {
        echo '  * '.$helpText.PHP_EOL;
    }
}

if (!empty($messages['warning'])) {
    echo PHP_EOL.'(Optional) Fix the following recommendations'.PHP_EOL;
    echo str_repeat('-', $lineSize).PHP_EOL;

    foreach ($messages['warning'] as $helpText) {
        echo '  * '.$helpText.PHP_EOL;
    }
}

echo PHP_EOL.'Note  the command console could use a different php.ini file'.PHP_EOL;
echo '~~~~  than the one used with your web server. To be on the'.PHP_EOL;
echo '      safe side, please check the requirements from your web'.PHP_EOL;
echo '      server using the web/config.php script.'.PHP_EOL;

exit($checkPassed ? 0 : 1);

function getErrorMessage(Requirement $requirement)
{
    if ($requirement->isFulfilled()) {
        return;
    }

    return $requirement->getTestMessage().PHP_EOL.'    -> '.$requirement->getHelpText();
}

function echo_title($title)
{
    echo PHP_EOL.PHP_EOL.'> '.$title.PHP_EOL;
}

function echo_result($message, $lineSize)
{
    echo PHP_EOL.PHP_EOL;
    echo str_repeat('=', $lineSize).PHP_EOL;
    echo $message.PHP_EOL;
    echo str_repeat('=', $lineSize).PHP_EOL;
    sleep(1);
}
