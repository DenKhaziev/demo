<?php


use App\Events\TicketCreated;

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

event(new TicketCreated([
    'id' => rand(1, 999),
    'subject' => '🚀 Запуск из reverb-test.php'
]));

echo "✅ Событие отправлено!";
