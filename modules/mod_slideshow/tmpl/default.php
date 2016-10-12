<?php
$language = JFactory::getLanguage();
$language_tag = $language->getTag(); 
//echo $language_tag;
?>

<?php
$orginalData = array();
$list_ids = array();
//echo "<pre>"; var_dump($slideshows); die;
foreach($slideshows as $item) 
{ 
    $orginalData[$item->id] = array
    (
        'image' => $item->image,
        'm_image' => $item->m_image,
        'm2_image' => $item->m2_image,
        'm3_image' => $item->m3_image,
        'm4_image' => $item->m4_image,
        'm5_image' => $item->m5_image,
        'm6_image' => $item->m6_image,
        'm7_image' => $item->m7_image,
        'm8_image' => $item->m8_image,
        'm9_image' => $item->m9_image,
        'title' => $item->title,  
        'alt' => $item->alt,
        'description' => $item->description     
    );
    array_push($list_ids, $item->id);
}

if($slideshows){
	JLoader::import('helpers.blog',JPATH_BASE . '/components/com_blog');
	$data = BlogFrontendHelper::getTranslationForCurrentLanguage('slideshow','', implode(',', $list_ids ), array()); //array() $orginalData
}
//echo "<pre>"; var_dump($data); die;
?>

<div class="home-slide" id="home-slide">
	<div id="loading-status" style="text-align: center;">&nbsp;</div>
    <div id="slider-wrapper" style="display: none">
    <?php if($data): ?>
        <?php foreach ($data as $item): ?>
        <div id="slide-item">
        	<a href="<?php echo $item['alt']; ?>">
        		<img class="lazy-image landscape-320" src="<?php echo JURI::base(true) ?>/<?php echo $item['m_image'];?>" alt="<?php echo $item['title'];?>" />
        		<img class="lazy-image landscape-360" src="<?php echo JURI::base(true) ?>/<?php echo $item['m2_image'];?>" alt="<?php echo $item['title'];?>" />
                <img class="lazy-image landscape-768" src="<?php echo JURI::base(true) ?>/<?php echo $item['m3_image'];?>" alt="<?php echo $item['title'];?>" />
                <img class="lazy-image landscape-800" src="<?php echo JURI::base(true) ?>/<?php echo $item['m4_image'];?>" alt="<?php echo $item['title'];?>" />
                <img class="lazy-image landscape-980" src="<?php echo JURI::base(true) ?>/<?php echo $item['m5_image'];?>" alt="<?php echo $item['title'];?>" />
                <img class="lazy-image landscape-1280" src="<?php echo JURI::base(true) ?>/<?php echo $item['m6_image'];?>" alt="<?php echo $item['title'];?>" />
                <img class="lazy-image landscape-1920" src="<?php echo JURI::base(true) ?>/<?php echo $item['image'];?>" alt="<?php echo $item['title'];?>"/>
                <img class="lazy-image landscape-1024-768" src="<?php echo JURI::base(true) ?>/<?php echo $item['m7_image'];?>" alt="<?php echo $item['title'];?>"/>
                <img class="lazy-image landscape-1280-800" src="<?php echo JURI::base(true) ?>/<?php echo $item['m8_image'];?>" alt="<?php echo $item['title'];?>"/>

                <img class="lazy-image landscape-1680" src="<?php echo JURI::base(true) ?>/<?php echo $item['m9_image'];?>" alt="<?php echo $item['title'];?>"/>

        	</a>
        </div>
        <?php endforeach; else: ?>
        <img class="lazy-image landscape-img" src="<?php echo JURI::base(true) ?>/images/slidershow/no_image.png" alt="no-image" />
    <?php endif;?>
        
    </div>
</div>
<div class="pagination-slide"></div>


<?php $i = 0; foreach ($data as $item):  $i++;
    if ($i== 1) :
    $_SESSION['320'] = JURI::base(true).'/'.$item['m_image'];
    $_SESSION['360'] = JURI::base(true).'/'.$item['m2_image'];
    $_SESSION['768'] = JURI::base(true).'/'.$item['m3_image'];
    $_SESSION['800'] = JURI::base(true).'/'.$item['m4_image'];
    $_SESSION['980'] = JURI::base(true).'/'.$item['m5_image'];
    $_SESSION['1280'] = JURI::base(true).'/'.$item['m6_image'];
    $_SESSION['1920'] = JURI::base(true).'/'.$item['image'];
    $_SESSION['1024_768'] = JURI::base(true).'/'.$item['m7_image'];
    $_SESSION['1280_800'] = JURI::base(true).'/'.$item['m8_image'];
    $_SESSION['1680'] = JURI::base(true).'/'.$item['m9_image'];
    endif; endforeach;
?>

<script>
    jQuery(function($) {
        var sz320 = '<?php echo $_SESSION['320']; ?>';
        var sz360 = '<?php echo $_SESSION['360']; ?>';
        var sz768 = '<?php echo $_SESSION['768']; ?>';
        var sz800 = '<?php echo $_SESSION['800']; ?>';
        var sz980 = '<?php echo $_SESSION['980']; ?>';
        var sz1280 = '<?php echo $_SESSION['1280']; ?>';
        var sz1920 = '<?php echo $_SESSION['1920']; ?>';
        var sz1024_768 = '<?php echo $_SESSION['1024_768']; ?>';
        var sz1280_800 = '<?php echo $_SESSION['1280_800']; ?>';
        var sz1680 = '<?php echo $_SESSION['1680']; ?>';

        var height = $(window).height();
        var width = $(window).width();
        //console.log(width + ' ' + height);
        if (width <= 320) {
            $("#loading-status").css('background-image', 'url(' + sz320 + ')');
        } else
        if (width <= 360) {
            $("#loading-status").css('background-image', 'url(' + sz360 + ')');
        } else
        if (width <= 768) {
            if (height <= 360){ // too <=320
                $("#loading-status").css('background-image', 'url(' + sz1280 + ')');
            }
            else {
                $("#loading-status").css('background-image', 'url(' + sz768 + ')');
            }
        } else
        if (width <= 800) {
            $("#loading-status").css('background-image', 'url(' + sz1280_800 + ')');
        } else
        if (width <= 980) {
            $("#loading-status").css('background-image', 'url(' + sz980 + ')');
        } else
        if (width <= 1280) {
            if (height <= 600) {
                $("#loading-status").css('background-image', 'url(' + sz1280 + ')');
            } else if (height <= 768) {
                $("#loading-status").css('background-image', 'url(' + sz1024_768 + ')');
            } else if (height <= 800) {
                $("#loading-status").css('background-image', 'url(' + sz1280_800 + ')');

            } else if (height <= 980) {
                $("#loading-status").css('background-image', 'url(' + sz1280_800 + ')');
            } else {
                $("#loading-status").css('background-image', 'url(' + sz1280 + ')');
            }

        } else
        if (width <= 1680) {
            $("#loading-status").css('background-image', 'url(' + sz1680 + ')');
        } else
        {
            
        	var def =  '/images/slidershow/banner_on_load.jpg';
            $("#loading-status").css('background-image', 'url(' + def + ')'); 
        }

        //$("#loading-status").css('background-image', 'url(' + <?php echo JURI::base(true) ?>/<?php echo $item['image'];?> + ')');
    });
</script>