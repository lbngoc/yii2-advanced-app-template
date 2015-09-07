<?php
use yii\bootstrap\Nav;

?>
<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?= Yii::$app->user->displayName; ?></p>

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
        </form> -->
        <!-- /.search form -->

        <?php
        echo Nav::widget(
            [
                'encodeLabels' => false,
                'options' => ['class' => 'sidebar-menu'],
                'items' => [
                    // '<li class="header">Menu Yii2</li>',
                    ['label' => '<i class="fa fa-user"></i><span>Users</span>', 'url' => ['/user']],
                    ['label' => '<i class="fa fa-calendar-o"></i><span>Stakes</span>', 'url' => ['/stake']],
                    // [
                    //     'label' => '<i class="glyphicon glyphicon-lock"></i><span>Sing in</span>', //for basic
                    //     'url' => ['/site/login'],
                    //     'visible' =>Yii::$app->user->isGuest
                    // ],
                ],
            ]
        );
        ?>
    <?php
        $whitelist = array('127.0.0.1', "::1");
        if ( YII_DEBUG && in_array($_SERVER['REMOTE_ADDR'], $whitelist) ) :?>
        <ul class="sidebar-menu">
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-file-code-o"></i> <span>Development</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu menu-open" style="display: block">
                    <li><a href="<?= \yii\helpers\Url::to(['/admin']) ?>"><span class="fa fa-group"></span> Roles & Permissions</a>
                    </li>
                    <li><a href="<?= \yii\helpers\Url::to(['/gii']) ?>"><span class="fa fa-share"></span> Gii</a>
                    </li>
                    <li><a href="<?= \yii\helpers\Url::to(['/debug']) ?>"><span class="fa fa-dashboard"></span> Debug</a>
                    </li>
                    <!-- <li>
                        <a href="#"><i class="fa fa-circle-o"></i> Level One <i class="fa fa-angle-left pull-right"></i></a>
                        <ul class="treeview-menu">
                            <li><a href="#"><i class="fa fa-circle-o"></i> Level Two</a></li>
                            <li>
                                <a href="#">
                                    <i class="fa fa-circle-o"></i> Level Two <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
                                    <li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li> -->
                </ul>
            </li>
        </ul>
    <?php endif; ?>

    </section>

</aside>
