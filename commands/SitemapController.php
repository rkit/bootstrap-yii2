<?php

namespace app\commands;

use yii\console\{Controller, ExitCode};
use yii2tech\sitemap\File;
use yii\helpers\Console;

/**
 * Command for create sitemap
 */
class SitemapController extends Controller
{
    private $sitemap;

    public function init()
    {
        $this->sitemap = new File();
    }

    public function actionCreate(): int
    {
        // writeItems…

        $this->sitemap->close();

        $this->stdout("Done!\n", Console::FG_GREEN);
        return ExitCode::OK;
    }

    private function writeItems(string $modelClass, $cb)
    {
        $items = $modelClass::find()->active()->asArray()->all();
        foreach ($items as $item) {
            $options = [];

            if (isset($item['date_update'])) {
                $options['lastModified'] = substr($item['date_update'], 0, 10);
            }

            $this->sitemap->writeUrl($cb($item), $options);
        }
    }
}
