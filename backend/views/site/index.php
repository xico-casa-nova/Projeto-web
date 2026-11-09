<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Dashboard';
?>
<div class="site-index">

    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3>150</h3>
                    <p>New Orders</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag fa fa-shopping-cart"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>53<sup style="font-size: 20px">%</sup></h3>
                    <p>Bounce Rate</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars fa fa-bar-chart"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3>44</h3>
                    <p>User Registrations</p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add fa fa-user-plus"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-red">
                <div class="inner">
                    <h3>65</h3>
                    <p>Unique Visitors</p>
                </div>
                <div class="icon">
                    <i class="ion ion-pie-graph fa fa-pie-chart"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
    </div>
    <!-- /.row -->

    <!-- Main row -->
    <div class="row">
        <!-- Left col -->
        <section class="col-lg-7 connectedSortable">
            <!-- Custom tabs (Charts with tabs)-->
            <div class="nav-tabs-custom">
                <!-- Tabs within a box -->
                <ul class="nav nav-tabs pull-right">
                    <li class="active"><a href="#revenue-chart" data-toggle="tab">Area</a></li>
                    <li><a href="#sales-chart" data-toggle="tab">Donut</a></li>
                    <li class="pull-left header"><i class="fa fa-inbox"></i> Sales</li>
                </ul>
                <div class="tab-content no-padding">
                    <!-- Morris chart - Sales -->
                    <div class="chart tab-pane active" id="revenue-chart" style="position: relative; height: 300px; padding: 20px;">
                        <p class="text-center text-muted" style="margin-top: 100px;">
                            <i class="fa fa-line-chart fa-3x"></i><br>
                            Area Chart Placeholder
                        </p>
                    </div>
                    <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 300px; padding: 20px;">
                        <p class="text-center text-muted" style="margin-top: 100px;">
                            <i class="fa fa-pie-chart fa-3x"></i><br>
                            Donut Chart Placeholder
                        </p>
                    </div>
                </div>
            </div>
            <!-- /.nav-tabs-custom -->

            <!-- TO DO List -->
            <div class="box box-primary">
                <div class="box-header">
                    <i class="ion ion-clipboard fa fa-clipboard"></i>
                    <h3 class="box-title">To Do List</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <ul class="todo-list">
                        <li>
                            <span class="handle"><i class="fa fa-ellipsis-v"></i> <i class="fa fa-ellipsis-v"></i></span>
                            <span class="text">Design a nice theme</span>
                            <small class="label label-danger"><i class="fa fa-clock-o"></i> 2 mins</small>
                        </li>
                        <li>
                            <span class="handle"><i class="fa fa-ellipsis-v"></i> <i class="fa fa-ellipsis-v"></i></span>
                            <span class="text">Make the theme responsive</span>
                            <small class="label label-info"><i class="fa fa-clock-o"></i> 4 hours</small>
                        </li>
                        <li>
                            <span class="handle"><i class="fa fa-ellipsis-v"></i> <i class="fa fa-ellipsis-v"></i></span>
                            <span class="text">Let theme shine like a star</span>
                            <small class="label label-warning"><i class="fa fa-clock-o"></i> 1 day</small>
                        </li>
                        <li>
                            <span class="handle"><i class="fa fa-ellipsis-v"></i> <i class="fa fa-ellipsis-v"></i></span>
                            <span class="text">Check messages and notifications</span>
                            <small class="label label-success"><i class="fa fa-clock-o"></i> 3 days</small>
                        </li>
                    </ul>
                </div>
                <!-- /.box-body -->
                <div class="box-footer clearfix no-border">
                    <button type="button" class="btn btn-default pull-right"><i class="fa fa-plus"></i> Add item</button>
                </div>
            </div>
            <!-- /.box -->
        </section>
        <!-- /.Left col -->

        <!-- right col -->
        <section class="col-lg-5 connectedSortable">

            <!-- Solid sales graph box -->
            <div class="box box-solid bg-teal-gradient">
                <div class="box-header">
                    <i class="fa fa-th"></i>
                    <h3 class="box-title">Sales Graph</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn bg-teal btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button type="button" class="btn bg-teal btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body border-radius-none" style="padding: 30px; text-align: center;">
                    <i class="fa fa-bar-chart fa-4x"></i>
                    <p>Graph Visualizer</p>
                </div>
            </div>
            <!-- /.box -->

            <!-- Calendar -->
            <div class="box box-solid bg-green-gradient">
                <div class="box-header">
                    <i class="fa fa-calendar"></i>
                    <h3 class="box-title">Calendar</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn bg-green btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button type="button" class="btn bg-green btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding" style="padding: 20px; text-align: center;">
                    <h4><?= date('l, d F Y') ?></h4>
                    <p class="text-muted" style="color: #e0f2e9;">AdminLTE Calendar Widget</p>
                </div>
            </div>
            <!-- /.box -->

        </section>
        <!-- right col -->
    </div>
    <!-- /.row (main row) -->

</div>
