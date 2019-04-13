# Corbomite Queue

<p><a href="https://travis-ci.org/buzzingpixel/corbomite-queue"><img src="https://travis-ci.org/buzzingpixel/corbomite-queue.svg?branch=master"></a></p>

Part of BuzzingPixel's Corbomite project.

This project provides a Queue for adding items to be run by the server in sequence.

## Usage

When you require this into a Corbomite project, the CLI commands and dependency injection config will automatically be set up.

### Installation

Corbomite Queue needs to add a couple of database tables in order to function. In order to do this, it needs to create some migrations which then need to be run. Run the create-migrations command, which will place migration files in your Corobomite project.

```bash
php app queue/create-migrations
```

After running that command, you'll need to run the migrations:

```bash
php app migrate/up
```

### Running the queue

In dev, you'll probably just want to run the queue manually. The command to do that is:

```bash
php app queue/run
```

That command runs the next item in your queue. In production environments, that command should be run every second. In Linux environments, you're encouraged to use a shell script and Supervisor to keep the process running. See the [Example Shell Script](queueRunnerExampleScript.sh`).

### Adding to the queue

The Queue API is provided to make things extremely easy to add items to the queue. Note that in the examples below, any class you specify to add the queue, Corbomite Queue will attempt to get it from the Dependency Injector first so you can use dependency injection if you want to.

```php
<?php
declare(strict_types=1);

use corbomite\di\Di;
use corbomite\queue\QueueApi;

/** @noinspection PhpUnhandledExceptionInspection */
$queueApi = Di::diContainer()->get(QueueApi::class);

$batchModel = $queueApi->makeActionQueueBatchModel([
    'name' => 'this_is_a_test',
    'title' => 'This is a Test',
    'items' => [
        $queueApi->makeActionQueueItemModel([
            'class' => \some\ClassThing::class,
        ]),
        $queueApi->makeActionQueueItemModel([
            'class' => \another\ClassThing::class,
        ]),
        $queueApi->makeActionQueueItemModel([
            'class' => \more\Classes::class,
            'method' => 'someMethod', // If method is not specified, --invoke is assumed
            // Provide anything you want in this array, your method will receive it as an argument
            'context' => [
                'stuff' => 'thing',
            ],
        ]),
    ],
]);

/** @noinspection PhpUnhandledExceptionInspection */
$queueApi->addToQueue($batchModel);
```

## License

Copyright 2019 BuzzingPixel, LLC

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at [http://www.apache.org/licenses/LICENSE-2.0](http://www.apache.org/licenses/LICENSE-2.0).

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
