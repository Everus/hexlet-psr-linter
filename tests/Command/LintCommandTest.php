<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 11.08.16
 * Time: 9:57
 */

namespace HexletPSRLinter\Command;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;
use HexletPSRLinter\Application;

class LintCommandTest extends TestCase
{
    /**
     * @dataProvider fileProviders
     */
    public function testLint($params, $expected)
    {
        $application = new Application();
        $commandTester = new CommandTester($command = $application->get('lint'));
        $commandTester->execute($params);
        $result = $commandTester->getDisplay(true);
        $this->assertEquals($expected, $result);
    }

    public function fileProviders()
    {
        $fixturesPath = __DIR__.'/../Fixtures/';
        return [
            [
                array('file' => $fixturesPath.'LintTest1.php'),
                file_get_contents($fixturesPath.'LintTest1.txt')
            ],
            [
                array('file' => $fixturesPath.'LintTest2.php'),
                file_get_contents($fixturesPath.'LintTest2.txt')
            ],
            [
                array('file' => $fixturesPath.'LintTest3.php'),
                file_get_contents($fixturesPath.'LintTest3.txt')
            ],
            [
                array('file' => $fixturesPath.'LintTest4.php'),
                file_get_contents($fixturesPath.'LintTest4.txt')
            ],
            [
                array('file' => $fixturesPath.'LintTest5.php'),
                file_get_contents($fixturesPath.'LintTest5.txt')
            ],
            [
                array('file' => $fixturesPath.'TestDirectory'),
                file_get_contents($fixturesPath.'TestDirectory.txt')
            ],
            [
                array(),
                file_get_contents($fixturesPath.'TestHelp.txt')
            ],
        ];
    }
}
