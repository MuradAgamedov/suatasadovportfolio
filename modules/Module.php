<?php

namespace modules;

use Craft;
use craft\elements\Entry;
use yii\base\Event;
use yii\base\Module as BaseModule;
use craft\web\View;

class Module extends BaseModule
{
    public function init()
    {
        parent::init();

        // ✅ ƏSAS SƏTİR – Craft-a modules alias-ını tanıtdırırıq
        Craft::setAlias('@modules', __DIR__);

        // Twig qlobal "dict" dəyişəni əlavə olunur
        Event::on(
            View::class,
            View::EVENT_BEFORE_RENDER_TEMPLATE,
            function () {
                $entries = Entry::find()
                    ->section('dictionary')
                    ->site(Craft::$app->sites->currentSite->handle)
                    ->all();

                $dict = [];
                foreach ($entries as $entry) {
                    $dict[$entry->keyword] = $entry->value;
                }

                Craft::$app->view->registerTwigExtension(
                    new class($dict) extends \Twig\Extension\AbstractExtension implements \Twig\Extension\GlobalsInterface {
                        private $dict;
                        public function __construct($dict)
                        {
                            $this->dict = $dict;
                        }
                        public function getGlobals(): array
                        {
                            return ['dict' => $this->dict];
                        }
                    }
                );
            }
        );
    }
}
