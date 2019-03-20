<?php
namespace app\components;

use Yii;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

class BreadcrumbsNew extends Breadcrumbs
{
    public function run()
    {
        if (empty($this->links)) {
            return;
        }
        $links = [];
        $links[] = "<i class=\"icon-home home-icon\"></i>";
        if ($this->homeLink === null) {
            $links[] = $this->renderItem([
                'label' => Yii::t('yii', 'Home'),
                'url' => Yii::$app->homeUrl,
            ], $this->itemTemplate);
        } elseif ($this->homeLink !== false) {
            $links[] = $this->renderItem($this->homeLink, $this->itemTemplate);
        }
        foreach ($this->links as $link) {
            if (!is_array($link)) {
                $link = ['label' => $link];
            }
            $links[] = $this->renderItem($link, isset($link['url']) ? $this->itemTemplate : $this->activeItemTemplate);
        }
        echo Html::tag($this->tag, implode('', $links), $this->options);
    }
}
?>