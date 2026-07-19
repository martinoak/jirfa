<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

/**
 * Zapíše nahrané obrázky do gitu a případně je odešle na server.
 *
 * Commity vznikají pod vlastní identitou (ve výchozím nastavení "Spock"),
 * aby šlo v historii snadno odlišit změny z administrace od ruční práce.
 */
class GitPublisher
{
    /**
     * @return array{ok: bool, message: string, output: string}
     */
    public function publish(string $summary): array
    {
        if (! config('git.enabled')) {
            return $this->fail('Automatické commity jsou vypnuté (GIT_AUTOMATION_ENABLED).');
        }

        $log = [];

        // Bez změn nemá smysl nic dělat.
        $status = $this->run(['git', 'status', '--porcelain', '--', ...config('git.paths')]);
        $log[] = $this->format($status);

        if (! $status['ok']) {
            return $this->fail('Nepodařilo se zjistit stav repozitáře.', implode("\n", $log));
        }

        if (trim($status['output']) === '') {
            return [
                'ok' => true,
                'message' => 'Žádné nové soubory k odeslání.',
                'output' => implode("\n", $log),
            ];
        }

        $add = $this->run(['git', 'add', '--', ...config('git.paths')]);
        $log[] = $this->format($add);

        if (! $add['ok']) {
            return $this->fail('Přidání souborů do gitu selhalo.', implode("\n", $log));
        }

        // Identita se předává jen pro tento příkaz (-c), takže se nemění
        // nastavení repozitáře ani počítače.
        $commit = $this->run([
            'git',
            '-c', 'user.name='.config('git.user.name'),
            '-c', 'user.email='.config('git.user.email'),
            'commit',
            '-m', $this->message($summary),
        ]);
        $log[] = $this->format($commit);

        if (! $commit['ok']) {
            return $this->fail('Commit selhal.', implode("\n", $log));
        }

        if (! config('git.push')) {
            return [
                'ok' => true,
                'message' => 'Změny byly commitnuty lokálně (odesílání je vypnuté).',
                'output' => implode("\n", $log),
            ];
        }

        $push = $this->run(['git', 'push', config('git.remote'), $this->branch()]);
        $log[] = $this->format($push);

        if (! $push['ok']) {
            return $this->fail(
                'Commit se vytvořil, ale odeslání na server selhalo. Zkontrolujte přístupové údaje serveru.',
                implode("\n", $log)
            );
        }

        return [
            'ok' => true,
            'message' => 'Změny byly odeslány na server.',
            'output' => implode("\n", $log),
        ];
    }

    protected function branch(): string
    {
        $result = $this->run(['git', 'rev-parse', '--abbrev-ref', 'HEAD']);

        return trim($result['output']) ?: 'master';
    }

    protected function message(string $summary): string
    {
        return trim($summary)."\n\nAutomatický commit z administrace.";
    }

    /**
     * Příkazy se spouštějí polem argumentů, takže se neinterpretuje shell
     * a do příkazu se nemůže propašovat nic z uživatelského vstupu.
     *
     * @param  array<int, string>  $command
     * @return array{ok: bool, command: string, output: string}
     */
    protected function run(array $command): array
    {
        $process = new Process($command, base_path());
        $process->setTimeout((float) config('git.timeout'));
        $process->run();

        $output = trim($process->getOutput()."\n".$process->getErrorOutput());

        if (! $process->isSuccessful()) {
            Log::warning('GitPublisher: příkaz selhal', [
                'command' => implode(' ', $command),
                'exit_code' => $process->getExitCode(),
                'output' => $output,
            ]);
        }

        return [
            'ok' => $process->isSuccessful(),
            'command' => implode(' ', $command),
            'output' => $output,
        ];
    }

    /**
     * @param  array{ok: bool, command: string, output: string}  $result
     */
    protected function format(array $result): string
    {
        return '$ '.$result['command']."\n".$result['output'];
    }

    /**
     * @return array{ok: bool, message: string, output: string}
     */
    protected function fail(string $message, string $output = ''): array
    {
        return ['ok' => false, 'message' => $message, 'output' => $output];
    }
}
