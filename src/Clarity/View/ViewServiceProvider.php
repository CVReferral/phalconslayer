<?php

/**
 * PhalconSlayer\Framework.
 *
 * @copyright 2015-2016 Daison Carino <daison12006013@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      http://docs.phalconslayer.com
 */

namespace Clarity\View;

use Phalcon\Events\Event;
use Phalcon\Events\Manager;
use Phalcon\Mvc\View\Engine\Php;
use Clarity\View\Volt\VoltAdapter;
use Clarity\View\Blade\BladeAdapter;
use Clarity\Support\Phalcon\Mvc\View;
use Clarity\Providers\ServiceProvider;

/**
 * The 'view' service provider.
 */
class ViewServiceProvider extends ServiceProvider
{
    /**
     * @var string
     */
    protected $alias = 'view';

    /**
     * @var bool
     */
    protected $shared = true;

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        $event_manager = new Manager;

        $event_manager->attach('view:afterRender',
            function (
                Event $event,
                View $dispatcher,
                $exception
            ) {
                $dispatcher->getDI()->get('flash')->session()->clear();
            }
        );

        di()->get('view')->setEventsManager($event_manager);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $view = new View;

        $view->setViewsDir(config()->path->views);

        $view->registerEngines([
            '.phtml'     => Php::class,
            '.volt'      => VoltAdapter::class,
            '.blade.php' => BladeAdapter::class,
        ]);

        return $view;
    }
}
