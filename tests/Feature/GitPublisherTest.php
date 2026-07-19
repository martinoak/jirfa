<?php

namespace Tests\Feature;

use App\Services\GitPublisher;
use Symfony\Component\Process\Process;
use Tests\TestCase;

/**
 * Testy běží proti dočasnému repozitáři ve storage/framework/testing,
 * takže se nikdy nedotknou skutečné historie projektu ani vzdáleného serveru.
 */
class GitPublisherTest extends TestCase
{
    protected string $repo;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repo = storage_path('framework/testing/git-repo-'.uniqid());
        mkdir($this->repo.'/public/images', 0755, true);

        $this->git(['git', 'init', '-b', 'main']);
        $this->git(['git', 'config', 'user.email', 'test@example.com']);
        $this->git(['git', 'config', 'user.name', 'Test']);
        file_put_contents($this->repo.'/README.md', "test\n");
        $this->git(['git', 'add', '.']);
        $this->git(['git', 'commit', '-m', 'init']);

        // Publisher pracuje s base_path(), proto ho přesměrujeme na testovací repozitář.
        $this->app->setBasePath($this->repo);

        config()->set('git.enabled', true);
        config()->set('git.push', false);          // nikdy nikam neodesílat
        config()->set('git.paths', ['public/images']);
        config()->set('git.user.name', 'Spock');
        config()->set('git.user.email', 'spock@jirfa.cz');
    }

    protected function tearDown(): void
    {
        $this->deleteDirectory($this->repo);
        parent::tearDown();
    }

    /** @param array<int, string> $command */
    protected function git(array $command): string
    {
        $process = new Process($command, $this->repo);
        $process->run();

        return trim($process->getOutput());
    }

    protected function deleteDirectory(string $dir): void
    {
        if (! is_dir($dir)) {
            return;
        }

        // .git obsahuje soubory jen pro čtení, na Windows je nutné práva srovnat.
        $process = new Process(['cmd', '/c', 'rmdir', '/s', '/q', $dir]);
        $process->run();

        if (is_dir($dir)) {
            $items = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::CHILD_FIRST
            );
            foreach ($items as $item) {
                $item->isDir() ? @rmdir($item->getPathname()) : @unlink($item->getPathname());
            }
            @rmdir($dir);
        }
    }

    public function test_it_reports_when_there_is_nothing_to_commit(): void
    {
        $result = app(GitPublisher::class)->publish('Nic');

        $this->assertTrue($result['ok']);
        $this->assertStringContainsString('Žádné nové soubory', $result['message']);
    }

    public function test_it_commits_new_images_under_the_bot_identity(): void
    {
        file_put_contents($this->repo.'/public/images/novy.jpg', 'binary');

        $result = app(GitPublisher::class)->publish('Aktualizace obrázků z administrace');

        $this->assertTrue($result['ok'], $result['message']."\n".$result['output']);

        $author = $this->git(['git', 'log', '-1', '--pretty=%an <%ae>']);
        $this->assertSame('Spock <spock@jirfa.cz>', $author);

        $subject = $this->git(['git', 'log', '-1', '--pretty=%s']);
        $this->assertSame('Aktualizace obrázků z administrace', $subject);

        $files = $this->git(['git', 'show', '--name-only', '--pretty=format:', 'HEAD']);
        $this->assertStringContainsString('public/images/novy.jpg', $files);
    }

    public function test_it_does_not_change_the_repository_identity(): void
    {
        file_put_contents($this->repo.'/public/images/a.jpg', 'x');
        app(GitPublisher::class)->publish('Test');

        // -c předává identitu jen pro daný příkaz, konfigurace zůstává původní
        $this->assertSame('Test', $this->git(['git', 'config', 'user.name']));
        $this->assertSame('test@example.com', $this->git(['git', 'config', 'user.email']));
    }

    public function test_it_only_commits_configured_paths(): void
    {
        file_put_contents($this->repo.'/public/images/ok.jpg', 'x');
        file_put_contents($this->repo.'/tajne.txt', 'nemá se commitnout');

        $result = app(GitPublisher::class)->publish('Jen obrázky');
        $this->assertTrue($result['ok']);

        $files = $this->git(['git', 'show', '--name-only', '--pretty=format:', 'HEAD']);
        $this->assertStringContainsString('public/images/ok.jpg', $files);
        $this->assertStringNotContainsString('tajne.txt', $files);
    }

    public function test_it_can_be_disabled(): void
    {
        config()->set('git.enabled', false);
        file_put_contents($this->repo.'/public/images/a.jpg', 'x');

        $result = app(GitPublisher::class)->publish('Test');

        $this->assertFalse($result['ok']);
        $this->assertStringContainsString('vypnuté', $result['message']);
    }
}
