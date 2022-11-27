<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

// the name of the command is what users type after "php bin/console"
#[
    AsCommand(
        name: "make:test-for-routes",
        description: "Automated generation of PHP tests for routes.",
        hidden: false
    )
]
class MakeTestForRoutes extends Command
{
    public function __construct(private UrlGeneratorInterface $router)
    {
        parent::__construct();
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {
        $output->writeln(["Starting generation of tests."]);

        if (!file_exists("tests/Generated")) {
            mkdir("tests/Generated");
        }

        exec("php bin/console debug:router", $routesOutput);

        foreach ($routesOutput as $route) {
            $routeData = array_values(array_filter(explode(" ", $route)));
            if (empty($routeData)) {
                continue;
            }

            if (!$this->checkIfValidRoute($routeData)) {
                continue;
            }

            $method = $routeData[1];
            $path = $routeData[4];
            if (
                strpos($method, "GET") !== false ||
                strpos($method, "ANY") !== false
            ) {
                $className =
                    mb_convert_case($routeData[0], MB_CASE_TITLE, "UTF-8") .
                    "GetTest";

                $outputFile = "tests/Generated/" . $className . ".php";
                if (!file_exists($outputFile)) {
                    $stub = file_get_contents("stubs/phpunit-test.get.stub");
                    $stub = str_replace("{{ className }}", $className, $stub);
                    $stub = str_replace("{{ route }}", $path, $stub);
                    file_put_contents(
                        "tests/Generated/" . $className . ".php",
                        $stub
                    );
                }

                $this->generateCypressTest($path, $routeData[0]);
            }

            if (strpos($method, "POST") !== false) {
                $className =
                    mb_convert_case($routeData[0], MB_CASE_TITLE, "UTF-8") .
                    "PostTest";

                $outputFile = "tests/Generated/" . $className . ".php";
                if (!file_exists($outputFile)) {
                    $stub = file_get_contents("stubs/phpunit-test.post.stub");
                    $stub = str_replace("{{ className }}", $className, $stub);
                    $stub = str_replace("{{ route }}", $path, $stub);
                    file_put_contents($outputFile, $stub);
                }
            }
        }

        $output->writeln(["Tests generation finished."]);

        return Command::SUCCESS;
    }

    private function checkIfValidRoute(array $routeData): bool
    {
        if (empty($routeData)) {
            return false;
        }

        $invalidRoutes = [
            "_profiler",
            "_wdt",
            "_preview",
            "pentatrion",
            "Name",
            "-----------------------------",
        ];

        foreach ($invalidRoutes as $invalidRoute) {
            if (strpos($routeData[0], $invalidRoute) !== false) {
                return false;
            }
        }

        return true;
    }

    private function generateCypressTest(string $path, string $pathName): void
    {
        $className = mb_convert_case($pathName, MB_CASE_TITLE, "UTF-8");
        $outputFile = "cypress/e2e/" . $className . ".cy.js";
        if (!file_exists($outputFile)) {
            $stub = file_get_contents("stubs/cypress.test.stub");
            $stub = str_replace("{{ routeName }}", $path, $stub);
            $stub = str_replace(
                "{{ route }}",
                $this->router->generate(
                    $pathName,
                    [],
                    UrlGeneratorInterface::ABSOLUTE_URL
                ),
                $stub
            );
            file_put_contents($outputFile, $stub);
        }
    }
}
