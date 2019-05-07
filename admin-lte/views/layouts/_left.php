<?php

use yii\helpers\Url;

$controller = Yii::$app->controller->id;
$action = Yii::$app->controller->action->id;
$cations = $controller . '/' . $action;
?>
<!--左侧菜单-->
<?php
//菜单表中的权限
//配置缓存，过期时间为120s
$cache = Yii::$app->cache;
if(!$cache->exists('menu')){
    $menus = login();
    $cache->add('menu',$menus,10);
}else{
    $menus = $cache->get('menu');
}
?>
<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu" data-widget="tree">

            <?php foreach ($menus as $value) : ?>
                <?php if ($value['display'] == 2): ?>
                    <?php $result = explode(',', $value['attr']); ?>
                    <li <?php if (in_array($controller, $result)): ?>class="active treeview menu-open" <?php else: ?> class="treeview" <?php endif; ?>>
                        <a href="#" class="dropdown-toggle">
                            <i class="fa fa-dashboard"></i>
                            <span class="menu-text"> <?php echo $value['name'] ?> </span>
                        </a>
                        <?php if (isset($value['_child'])): ?>
                            <ul class="treeview-menu">
                                <?php foreach ($value['_child'] as $val) : ?>
                                    <?php if ($val['display'] == 2): ?>
                                        <li <?php if ($cations == $val['route']): ?>class="active"<?php endif; ?>>
                                            <a href="<?= Url::toRoute([$val['route']]) ?>"><?php echo $val['name'] ?></a>
                                        </li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>

        </ul>

    </section>
</aside>