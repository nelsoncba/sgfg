<?php
$profile = app\models\Role::findOne(['id'=>Yii::$app->user->identity]);

$username = "";
$role = "";

if(isset(Yii::$app->user->identity->username))
    $username = Yii::$app->user->identity->username;
if(isset($profile->role))
    $role = $profile->role;
?>
<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/default-50x50.gif" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p>Usuario: <?= $username ?></p>
                <p>Perfil: <?= $role ?></p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <!-- search form -->
       <!-- <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>-->
        <!-- /.search form -->
        <?php
            $items = [];
            $role = 0;
            if(isset(Yii::$app->user->identity->role))
                $role = Yii::$app->user->identity->role;

            if($role==app\models\User::ROLE_ALMACEN){
                $items = [
                    ['label' => 'Compras', 'options' => ['class' => 'header']],
                    ['label' => 'Compra', 'icon' => 'shopping-bag', 'url' => ['/compra']],
                    ['label' => 'Almacen', 'options' => ['class' => 'header']],
                    ['label' => 'Almacen Producto', 'icon' => 'cubes', 'url' => ['/almacen-producto']],
                    ['label' => 'Producto', 'icon' => 'cube', 'url' => ['/producto']],
                    ['label' => 'Almacen Material', 'icon' => 'cubes', 'url' => ['/almacen-material']],
                    ['label' => 'Material', 'icon' => 'cube', 'url' => ['/material']]
                    ];
            }
            if($role==app\models\User::ROLE_ADMIN){
                $items = [['options' => ['class' => 'header']],
                    ['label' => 'Menu', 'options' => ['class' => 'header']],
                    ['label' => 'Administracion', 'options' => ['class' => 'header']],
                    ['label' => 'Cliente', 'icon' => 'handshake-o', 'url' => ['/cliente']],
                    ['label' => 'Proveedor', 'icon' => 'truck', 'url' => ['/proveedor']],
                    ['label' => 'Ventas', 'options' => ['class' => 'header']],
                    ['label' => 'Venta', 'icon' => 'shopping-cart', 'url' => ['/venta']],
                    ['label' => 'Pedido', 'icon' => 'file-text-o', 'url' => ['/pedido']],
                    ['label' => 'Compras', 'options' => ['class' => 'header']],
                    ['label' => 'Compra', 'icon' => 'shopping-bag', 'url' => ['/compra']],
                    ['label' => 'Almacen', 'options' => ['class' => 'header']],
                    ['label' => 'Almacen Producto', 'icon' => 'cubes', 'url' => ['/almacen-producto']],
                    ['label' => 'Producto', 'icon' => 'cube', 'url' => ['/producto']],
                    ['label' => 'Almacen Material', 'icon' => 'cubes', 'url' => ['/almacen-material']],
                    ['label' => 'Material', 'icon' => 'cube', 'url' => ['/material']]
                    ];
            }
        ?>

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu'],
                'items' => $items,
                    
                    // ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
                    // [
                    //     'label' => 'Same tools',
                    //     'icon' => 'share',
                    //     'url' => '#',
                    //     'items' => [
                    //         ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii'],],
                    //         ['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug'],],
                    //         [
                    //             'label' => 'Level One',
                    //             'icon' => 'circle-o',
                    //             'url' => '#',
                    //             'items' => [
                    //                 ['label' => 'Level Two', 'icon' => 'circle-o', 'url' => '#',],
                    //                 [
                    //                     'label' => 'Level Two',
                    //                     'icon' => 'circle-o',
                    //                     'url' => '#',
                    //                     'items' => [
                    //                         ['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
                    //                         ['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
                    //                     ],
                    //                 ],
                    //             ],
                    //         ],
                    //     ],
                    // ],
               
            ]
        ) ?>

    </section>

</aside>
