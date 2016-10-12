<?php

/**
 * Created by PhpStorm.
 * User: Patrick
 * Date: 10/8/2016
 * Time: 1:44 PM
 */

$defaultTab = 1;

?>

<div class="container">

    <div id = "steps" class="panel panel-default">
        <div id = "tabs" class="row" style="margin-bottom: 20px">
            <div class = "col-md-12">
                <ul class="nav nav-tabs nav-justified">

                   <li role="presentation" class="tab_1 active">
                       <a href="#">Step 1 : Choose a time slot</a>
                   </li>

                   <li role="presentation" class="tab_2 disabled">
                       <a href="#">Step 2 : Provide your personal information</a>
                   </li>

                    <li role="presentation" class="tab_3 disabled">
                        <a href="#">Final Step : Email Confirmation</a>
                    </li>

                </ul>
            </div>
        </div>
        <div class="tab-content">
            <?php

            $tab = (isset($tab)) ? $tab : 'tab' . $defaultTab;

            ?>

            <?php
            /**
             * Created by PhpStorm.
             * User: Patrick
             * Date: 10/8/2016
             * Time: 3:41 PM
             */

            $stepNo = 1;

            ?>

            <script type = "text/javascript">

                $(document).ready(function() {
                    $("#proceed-to-step2").click(function() {
                        console.log("clicked");
                        var date_selected = $("input[name=optradio]:checked").val();
                        console.log(date_selected);
                        $("#text-date").text(date_selected);
                    });
                });

                $(function () { // put functions in respective buttons

                    $('.pager li.nextStep_<?php echo $stepNo ?>').on('click', function () { // for next step
                        if ($(this).hasClass('active'))
                            $(this).removeClass('active');

                        $("#tabs li.tab_<?php echo $stepNo ?>").removeClass('active');
                        $("#tabs li.tab_<?php echo $stepNo ?>").addClass('disabled');

                        $("#tabs li.tab_<?php echo $stepNo+1 ?>").addClass('active');
                    });

                });

            </script>
<!--Step 1-->
            <div id = "tab_1_<?php echo $stepNo ?>" class="tab-pane fade in <?php echo ($tab == $stepNo) ? 'active' : ''; ?>">

                <div class = "row">
                    <div class = "col-md-3 col-md-offset-1">
                        <div class = "panel panel-default">
                            <div class = "panel-body">
                                <div class="radio">
                                    <div class="radio" id="radio-date" name="form-date">
                                        <label><input type="radio" id="radio-today" name="optradio" value="<?=date("m-d-Y")?>"checked>Today (<?=date("m-d-Y")?>)</label>
                                        <label><input type="radio" id="radio-tomorrow" name="optradio" value="<?=date("m-d-Y", strtotime("tomorrow"))?>">Tomorrow (<?=date("m-d-Y", strtotime("tomorrow"))?>)</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class = "panel panel-default">
                            <div class = "panel-body">
                                Building:
                                <select class="form-control" name="form-building">
                                    <option selected disabled>Choose a building...</option>
                                    <?php foreach($buildings as $row):?>
                                        <option value="<?=$row->buildingid?>"><?=$row->name?></option>
                                    <?php endforeach;?>
                                </select>

                            </div>
                        </div>
                    </div>

                    <div class = "col-md-7">
                        <div class = "panel panel-default">
                            <div class = "panel-body">
                                SLOTS
                            </div>
                        </div>
                    </div>
                </div>

                <div class ="row">

                    <div class = "col-md-3 col-md-offset-8">
                        <ul class="pager">
                            <!--<li class="previous prevStep_</?php echo $stepNo ?>">
                                <a href="#tab_1_</?php echo $stepNo-1 ?>" data-toggle="tab"><span aria-hidden="true">&larr;</span> Go back to previous step</a>
                            </li>-->
                            <li class="nextStep_<?php echo $stepNo ?>">
                                <a href="#tab_1_<?php echo $stepNo+1 ?>" data-toggle="tab" id="proceed-to-step2">Proceed to next step <span aria-hidden="true">&rarr;</span></a>
                            </li>
                        </ul>
                    </div>

                </div>

            </div>
<!--End of Step 1-->

            <?php
            /**
             * Created by PhpStorm.
             * User: Patrick
             * Date: 10/8/2016
             * Time: 3:41 PM
             */

            $stepNo++;

            ?>

            <script type = "text/javascript">

                $(function () { // put functions in respective buttons

                    $('.pager li.nextStep_<?php echo $stepNo ?>').on('click', function () { // for next step
                        if ($(this).hasClass('active'))
                            $(this).toggleClass('active');

                        $("#tabs li.tab_<?php echo $stepNo ?>").removeClass('active');
                        $("#tabs li.tab_<?php echo $stepNo ?>").addClass('disabled');

                        $("#tabs li.tab_<?php echo $stepNo+1 ?>").addClass('active');
                    });

                    $('.pager li.prevStep_<?php echo $stepNo ?>').on('click', function () { // for next step
                        if ($(this).hasClass('active'))
                            $(this).toggleClass('active');

                        $("#tabs li.tab_<?php echo $stepNo ?>").removeClass('active');
                        $("#tabs li.tab_<?php echo $stepNo ?>").addClass('disabled');

                        $("#tabs li.tab_<?php echo $stepNo-1 ?>").addClass('active');
                    });

                });

            </script>

            <div id = "tab_1_<?php echo $stepNo ?>" class="tab-pane fade in <?php echo ($tab == $stepNo) ? 'active' : ''; ?>">

                <div class = "row">
                    <div class = "panel-body">
                        <div class = "col-md-3 col-md-offset-2">
                            <form>
                                <div class="form-group">
                                    <label for="idno">ID Number:</label>
                                    <input type="number" class="form-control" id="idno">
                                </div>

                                <div class="form-group">
                                    <label for="college">College:</label>
                                    <select class="form-control" name="form-college" id="select-college">
                                        <option selected disabled>Choose your college...</option>
                                        <?php foreach($colleges as $row):?>
                                            <option value="<?=$row->collegeid?>"><?=$row->name?></option>
                                        <?php endforeach;?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="type">Type:</label>
                                    <select class="form-control" name="form-type" id="select-type">
                                        <option selected disabled>Choose your type...</option>
                                        <?php foreach($types as $row):?>
                                            <option value="<?=$row->typeid?>"><?=$row->type?></option>
                                        <?php endforeach;?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="email">Email:</label>
                                    <input type="email" class="form-control" id="email">
                                </div>

                                <b>Date:</b> <span id="text-date"></span>
                                <br /><br />
                                <b>Time Slots:</b>
                                <div class = "row">
                                    <div class = "col-md-6">
                                        <div class="form-group">
                                            <label for="starttime">Start:</label>
                                            <input type="starttime" class="form-control" id="starttime">
                                        </div>
                                    </div>
                                    <div class = "col-md-6">
                                        <div class="form-group">
                                            <label for="endtime">End:</label>
                                            <input type="endtime" class="form-control" id="endtime">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class ="row">

                    <div class = "col-md-10 col-md-offset-1">
                        <ul class="pager">
                            <li class="previous prevStep_<?php echo $stepNo ?>">
                                <a href="#tab_1_<?php echo $stepNo-1 ?>" data-toggle="tab"><span aria-hidden="true">&larr;</span> Go back to previous step</a>
                            </li>
                            <li class="next nextStep_<?php echo $stepNo ?>">
                                <a href="#tab_1_<?php echo $stepNo+1 ?>" data-toggle="tab">Proceed to next step <span aria-hidden="true">&rarr;</span></a>
                            </li>
                        </ul>
                    </div>

                </div>

            </div>

            <?php
            /**
             * Created by PhpStorm.
             * User: Patrick
             * Date: 10/8/2016
             * Time: 3:41 PM
             */

            $stepNo++;

            ?>

            <div id = "tab_1_<?php echo $stepNo ?>" class="tab-pane fade in <?php echo ($tab == $stepNo) ? 'active' : ''; ?>">

                <div class = "row">
                    <div class = "col-md-10 col-md-offset-1">
                        <div class="panel-body">
                            STEP3
                        </div>
                    </div>
                </div>

                <!--<div class ="row">

                    <div class = "col-md-3 col-md-offset-8">
                        <ul class="pager">
                            <li class="previous"><a href="#"><span aria-hidden="true">&larr;</span> Older</a></li>
                            <li class="next"><a href="#">Proceed to next step <span aria-hidden="true">&rarr;</span></a></li>
                        </ul>
                    </div>

                </div>-->

            </div>


        </div> <!-- EOF -->

    </div>

</div>
