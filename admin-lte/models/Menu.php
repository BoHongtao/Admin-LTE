<?php
namespace app\models;
use Yii;
class Menu extends Base
{
    public static function tableName()
    {
        return 'auth_menu';
    }
    public function rules(){
        return[
            [['name'],'required'],
            [['pid'],'integer'],
            [['name'], 'string', 'max' => 128],
            [['route'], 'string', 'max' => 128],
            [['act'], 'string', 'max' => 256],
            [['display','sort'],'integer'],
            [['data','icon','icon_disable','attr'],'string'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'pid' => '父级',
            'act' => '功能路由',
            'route'=>'菜单路由',
            'sort' => '排序',
            'data' => '描述',
            'display' => '是否显示',
            'icon' => '显示图标',
            'icon_disable' => '隐藏图标',
            'attr' => '当前控制器名称，添加active时使用',
        ];
    }

    //获取所有菜单列表
    public static function getMenuList(){
        $menu = Menu::find()->orderBy('sort DESC,id ASC')->asArray()->all();
        $menu = list_to_tree($menu,'id','pid');
        return $menu;
    }

    //左侧菜单
    public static function allMenuList($where = []){
        $menu = Menu::find()->orderBy('sort DESC,id ASC')->where($where)->asArray()->all();
        $menu = self::list_to_menu($menu,'id');
        return $menu;
    }

    public static function list_to_menu($list,$pk){
        if(is_array($list)) {
            // 创建基于主键的数组引用
            $refer = array();
            foreach ($list as $key => $data) {
                $refer[$data[$pk]] =& $list[$key];
            }
        }
        return $refer;
    }

    public static function getChildrenIds($id = 0,$self = false){
        $ids = Menu::find()->select("id")->where(["pid"=>$id])->asArray()->all();
        $ids = array_column($ids,"id");
        foreach ($ids as $cid){
            $ids = array_merge($ids,self::getChildrenIds($cid));
        }
        $self and $ids = array_merge([$id],$ids);
        return $ids;
    }
}