<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
use Drupal\Core\DrupalKernel;
use Symfony\Component\HttpFoundation\Request;
use Michelf\MarkdownExtra;

$autoloader = require_once 'autoload.php';

$kernel = new DrupalKernel('prod', $autoloader);

$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();

$kernel->terminate($request, $response);

echo "\n";

$query = \Drupal::entityQuery('node');
$nids = $query->execute();
$departments = \Drupal::entityTypeManager()->getStorage('node')->loadMultiple($nids);

foreach ($departments as $d) {

    $body = $d->get('body');
    #$value   = MarkdownExtra::defaultTransform($body->value);
    #$summary = $body->summary;
    #$format  = 'basic_html';
    #echo "$value\n $format\n";

    if ($body->format === 'markdown') {

        $d->body->setValue([
            'value'   => MarkdownExtra::defaultTransform($body->value),
            'summary' => $body->summary,
            'format'  => 'basic_html'
        ]);
        $d->save();
    }
}
