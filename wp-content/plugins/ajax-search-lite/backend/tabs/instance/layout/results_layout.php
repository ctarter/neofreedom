<div class="item item-flex-nogrow item-flex-wrap">
    <?php
    $o = new wpdreamsCustomSelect("v_res_column_count", __('Number of result columns', 'ajax-search-lite'), array(
        'selects'=>array(
            array('option' => '1', 'value' => 1),
            array('option' => '2', 'value' => 2),
            array('option' => '3', 'value' => 3),
            array('option' => '4', 'value' => 4),
            array('option' => '5', 'value' => 5),
            array('option' => '6', 'value' => 6),
            array('option' => '7', 'value' => 7),
            array('option' => '8', 'value' => 8)
        ),
        'value'=>$sd['v_res_column_count']
    ));
    $params[$o->getName()] = $o->getData();

    $o = new wpdreamsTextSmall("v_res_column_min_width", __('Column minimum width (px)', 'ajax-search-lite'), array(
        'icon' => 'desktop',
        'value' => $sd['v_res_column_min_width']
    ));
    $params[$o->getName()] = $o->getData();
    $o = new wpdreamsTextSmall("v_res_column_min_width_tablet", '', array(
        'icon' => 'tablet',
        'value' => $sd['v_res_column_min_width_tablet']
    ));
    $params[$o->getName()] = $o->getData();
    $o = new wpdreamsTextSmall("v_res_column_min_width_phone", '', array(
        'icon' => 'phone',
        'value' => $sd['v_res_column_min_width_phone']
    ));
    $params[$o->getName()] = $o->getData();
    ?>
    <div class="descMsg item-flex-grow item-flex-100">
    <?php echo ' '. sprintf(
        __('Use with <a href="%s" target="_blank">CSS units</a> (like %s or %s or %s ..) Default: <strong>%s</strong>', 'ajax-search-lite'),
        'https://www.w3schools.com/cssref/css_units.asp', '200px', '30vw', '30%', '200px'
    ); ?>
    </div>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("description_context", __("Display the description context?", "ajax-search-lite"),
        $sd['description_context']);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg"><?php __("Will display the description from around the search phrase, not from the beginning.", "ajax-search-lite"); ?></p>
</div>
<div class="item"><?php
    $o = new wpdreamsTextSmall("v_res_max_height", __("Result box maximum height", "ajax-search-lite"), $sd['v_res_max_height']);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg"><?php echo __("If this value is reached, the scrollbar will definitely trigger. none or pixel units, like 800px. Default: none", "ajax-search-lite"); ?></p>
</div>
<div class="item"><?php
    $o = new wpdreamsTextSmall("itemscount", __("Results box viewport (in item numbers)", "ajax-search-lite"), $sd['itemscount'], array(array("func" => "ctype_digit", "op" => "eq", "val" => true)));
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg"><?php echo __("Used to calculate the box height. Result box height = (this option) x (average item height)", "ajax-search-lite"); ?></p>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("showmoreresults", __("Show 'More results..' text in the bottom of the search box?", "ajax-search-lite"), $sd['showmoreresults']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsText("showmoreresultstext", __("' Show more results..' text", "ajax-search-lite"), $sd['showmoreresultstext']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("showauthor", __("Show author in results?", "ajax-search-lite"), $sd['showauthor']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("showdate", __("Show date in results?", "ajax-search-lite"), $sd['showdate']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("showdescription", __("Show description in results?", "ajax-search-lite"), $sd['showdescription']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("description_context", __("Display the description context?", "ajax-search-lite"), $sd['description_context']);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">
        <?php echo __("Will display the description from around the search phrase, not from the beginning.", "ajax-search-lite"); ?>
    </p>
</div>
<div class="item">
    <?php
    $o = new wpdreamsTextSmall("descriptionlength", __("Description length", "ajax-search-lite"), $sd['descriptionlength']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>