<?php

$directoryOfVueComponents = "assets/components";
$testsDirectory = "vitest";

// create directory for vitest test cases if folder does not exist yet
if (!file_exists($testsDirectory)) {
    mkdir($testsDirectory, 0755);
}

function iterateDirectory($directory)
{
    global $directoryOfVueComponents;
    global $testsDirectory;

    foreach (new DirectoryIterator($directory) as $fileInfo) {
        if ($fileInfo->isDot()) {
            continue;
        }

        if ($fileInfo->isDir()) {
            iterateDirectory($fileInfo->getFileInfo());
            continue;
        }

        if ($fileInfo->getFileInfo()->getExtension() !== "vue") {
            continue;
        }

        $testToCreate =
            str_replace(
                $directoryOfVueComponents,
                "",
                $fileInfo->getFileInfo()
            ) . ".test.ts";
        $testToCreate = array_values(
            array_filter(explode(DIRECTORY_SEPARATOR, $testToCreate))
        );
        $testToCreate =
            $testsDirectory . DIRECTORY_SEPARATOR . implode("-", $testToCreate);

        if (!file_exists($testToCreate)) {
            $componentName = str_replace(
                "." . $fileInfo->getExtension(),
                "",
                $fileInfo->getFilename()
            );
            $testStub = file_get_contents("stubs/vitest.stub");
            $testStub = str_replace(
                "{{ ComponentName }}",
                $componentName,
                $testStub
            );
            $testStub = str_replace(
                "{{ ComponentPath }}",
                "./" . $fileInfo->getFileInfo(),
                $testStub
            );

            file_put_contents($testToCreate, $testStub);
        }
    }
}

iterateDirectory($directoryOfVueComponents);
